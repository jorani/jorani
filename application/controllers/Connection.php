<?php
/**
 * This controller manages the connection to the application
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class manages the connection to the application
 * CodeIgniter uses a cookie to store session's details.
 * Login page uses RSA so as to encrypt the user's password.
 */
class Connection extends CI_Controller {

    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {
        parent::__construct();
        $this->load->library('polyglot');
        if ($this->session->userdata('language') == NULL) {
            $availableLanguages = explode(",", $this->config->item('languages'));
            $languageCode = $this->polyglot->language2code($this->config->item('language'));
            if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                if (in_array($_SERVER['HTTP_ACCEPT_LANGUAGE'], $availableLanguages)) {
                    $languageCode = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
                }
            }
            $this->session->set_userdata('language_code', $languageCode);
            $this->session->set_userdata('language', $this->polyglot->code2language($languageCode));
        }
        $this->lang->load('session', $this->session->userdata('language'));
        $this->lang->load('global', $this->session->userdata('language'));
    }
    
    /**
     * Generate a random string by using openssl, dev/urandom or random
     * @param int $length optional length of the string
     * @return string random string
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    private function generateRandomString($length = 10) {
        if(function_exists('openssl_random_pseudo_bytes')) {
          $rnd = openssl_random_pseudo_bytes($length, $strong);
          if ($strong === TRUE)
            return base64_encode($rnd);
        }
        $sha =''; $rnd ='';
        if (file_exists('/dev/urandom')) {
          $fp = fopen('/dev/urandom', 'rb');
          if ($fp) {
              if (function_exists('stream_set_read_buffer')) {
                  stream_set_read_buffer($fp, 0);
              }
              $sha = fread($fp, $length);
              fclose($fp);
          }
        }
        for ($i=0; $i<$length; $i++) {
          $sha  = hash('sha256',$sha.mt_rand());
          $char = mt_rand(0,62);
          $rnd .= chr(hexdec($sha[$char].$sha[$char+1]));
        }
        return base64_encode($rnd);
    }
    
    /**
     * Login form
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function login() {
        //The login form is not used with SAML2 authentication mode
        if ($this->config->item('saml_enabled') === TRUE) {
            redirect('api/sso');
        }
        //If we are already connected (login bookmarked), then redirect to home
        if ($this->session->userdata('logged_in') === TRUE) {
            redirect('home');
        }
        
        $data['title'] = lang('session_login_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_login');
        $this->load->helper('form');
        $this->load->library('form_validation');
        //Note that we don't receive the password as a clear string
        $this->form_validation->set_rules('login', lang('session_login_field_login'), 'required');

        $data['last_page'] = $this->session->userdata('last_page');
        if ($this->form_validation->run() === FALSE) {
            $data['public_key'] = file_get_contents('./assets/keys/public.pem', TRUE);
            $data['salt'] = $this->generateRandomString(rand(5, 20));
            $data['language'] = $this->session->userdata('language');
            $data['language_code'] = $this->session->userdata('language_code');
            $this->session->set_userdata('salt', $data['salt']);
            $data['flash_partial_view'] = $this->load->view('templates/flash', $data, TRUE);
            $this->load->view('templates/header', $data);
            $this->load->view('session/login', $data);
            $this->load->view('templates/footer');
        } else {
            $this->load->model('users_model');
            //Set language
            $this->session->set_userdata('language_code', $this->input->post('language'));
            $this->session->set_userdata('language', $this->polyglot->code2language($this->input->post('language')));
            
            //Decipher the password value (RSA encoded -> base64 -> decode -> decrypt) and remove the salt!
            $password = '';
            if (function_exists('openssl_pkey_get_private')) {
                $privateKey = openssl_pkey_get_private(file_get_contents('./assets/keys/private.pem', TRUE));
                openssl_private_decrypt(base64_decode($this->input->post('CipheredValue')), $password, $privateKey, OPENSSL_PKCS1_OAEP_PADDING);
                while ($msg = openssl_error_string()) {
                    log_message('error', 'openssl error message=' . $msg);
                }
            } else {
                $rsa = new phpseclib\Crypt\RSA();
                $privateKey = file_get_contents('./assets/keys/private.pem', TRUE);
                $rsa->setEncryptionMode(phpseclib\Crypt\RSA::ENCRYPTION_OAEP);
                $rsa->loadKey($privateKey, phpseclib\Crypt\RSA::PRIVATE_FORMAT_PKCS1);
                $password = $rsa->decrypt(base64_decode($this->input->post('CipheredValue')));
            }
            //Remove the salt
            $len_salt = strlen($this->session->userdata('salt')) * (-1);
            $password = substr($password, 0, $len_salt);
            
            $loggedin = FALSE;
            if ($this->config->item('ldap_enabled') === TRUE) {
                if ($password != "") { //Bind to MS-AD with blank password might return OK
                    $ldap = ldap_connect($this->config->item('ldap_host'), $this->config->item('ldap_port'));
                    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
                    set_error_handler(function() { /* ignore errors */
                    });

                    $basedn = "";
                    if (($this->config->item('ldap_search_enabled')) === TRUE) {
                        $bind = ldap_bind($ldap, $this->config->item('ldap_search_user'), $this->config->item('ldap_search_password'));
                        $resultSet = ldap_search($ldap, $this->config->item('ldap_basedn'), sprintf($this->config->item('ldap_search_pattern'), $this->input->post('login')));
                        $userEntry = ldap_first_entry($ldap, $resultSet);
                        $basedn = ldap_get_dn($ldap, $userEntry);
                    } else {
                        //Priority is given to the base DN defined into the database, then try with the template
                        $basedn = $this->users_model->getBaseDN($this->input->post('login'));
                        if ($basedn == "") {//can return NULL
                            $basedn = sprintf($this->config->item('ldap_basedn'), $this->input->post('login'));
                        }
                    }

                    $bind = ldap_bind($ldap, $basedn, $password);
                    restore_error_handler();
                    if ($bind) {
                        $loggedin = $this->users_model->checkCredentialsLDAP($this->input->post('login'));
                    } else {
                        //Attempt to login the user with the password stored into DB, provided this password is not emptye
                        if ($password != "") {
                            $loggedin = $this->users_model->checkCredentials($this->input->post('login'), $password);
                        }
                    }
                    ldap_close($ldap);
                }
            } else {
                $loggedin = $this->users_model->checkCredentials($this->input->post('login'), $password);
            }

            if ($loggedin == FALSE) {
                log_message('error', '{controllers/session/login} Invalid login id or password for user=' . $this->input->post('login'));
                if ($this->users_model->isActive($this->input->post('login'))) {
                    $this->session->set_flashdata('msg', lang('session_login_flash_bad_credentials'));
                } else {
                    $this->session->set_flashdata('msg', lang('session_login_flash_account_disabled'));
                }
                redirect('session/login');
            } else {
                $this->redirectToLastPage();
            }
        }
    }

    /**
     * Logout the user and destroy the session data
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function logout() {
        $this->session->sess_destroy();
        redirect('session/login');
    }

    /**
     * Change the language and redirect to last page (i.e. page that submit the language form)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function language() {
        $this->load->helper('form');

        //Prevent transversal path attack and the selection of an unavailable language
        $languages = explode(",", $this->config->item('languages'));
        $language = $this->input->get_post('language', true);
        if (in_array($language, $languages)) {
            $this->session->set_userdata('language_code', $language);
            $this->session->set_userdata('language', $this->polyglot->code2language($language));
        }
        if ($this->input->post('last_page') == FALSE) {
            $this->redirectToLastPage();
        } else {
            $this->redirectToLastPage($this->input->post('last_page'));
        }
    }
    
    /**
     * If the user has a target page (e.g. link in an e-mail), redirect to this destination
     * @param string $page Force the redirection to a given page
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    private function redirectToLastPage($page = "") {
        if ($page!=="") {
            redirect($page);
        } else {
            if ($this->session->userdata('last_page') != '') {
                if (strpos($this->session->userdata('last_page'), 'index.php', strlen($this->session->userdata('last_page')) - strlen('index.php'))) {
                    $this->session->set_userdata('last_page', base_url() . 'home');
                }
                if ($this->session->userdata('last_page_params') == '') {
                    redirect($this->session->userdata('last_page'));
                } else {
                    redirect($this->session->userdata('last_page') . '?' . $this->session->userdata('last_page_params'));
                }
            } else {
                redirect('home');
            }
        }
    }
    
    /**
     * Ajax : Send the password by e-mail to a user requesting it
     * POST: string login Login of the user
     * RETURN: UNKNOWN if the login was not found, OK otherwise
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function forgetpassword() {
        $this->output->set_content_type('text/plain');
        $login = $this->input->post('login');
        $this->load->model('users_model');
        $user = $this->users_model->getUserByLogin($login);
        if (is_null($user)) {
            $this->output->set_output('UNKNOWN');
        } else {
            //We need to instance an different object as the languages of connected user may differ from the UI lang
            $lang_mail = new CI_Lang();
            $usr_lang = $this->polyglot->code2language($user->language);
            $lang_mail->load('email', $usr_lang);
            $lang_mail->load('global', $usr_lang);
            //Generate random password and store its hash into db
            $password = $this->users_model->resetClearPassword($user->id);
            //Prepare the e-mail content by parsing a view
            $this->load->library('parser');
            $data = array(
                'Title' => $lang_mail->line('email_password_forgotten_title'),
                'BaseURL' => base_url(),
                'Firstname' => $user->firstname,
                'Lastname' => $user->lastname,
                'Login' => $user->login,
                'Password' => $password
            );
            $message = $this->parser->parse('emails/' . $user->language . '/password_forgotten', $data, TRUE);
            //Send the e-mail
            sendMailByWrapper($this,
                    $lang_mail->line('email_password_forgotten_subject'),
                    $message,
                    $user->email);
            //Tell to the frontend that we've found the login and sent the email
            $this->output->set_output('OK');
        }
    }
    
    /**
     * Try to authenticate the user using one of the OAuth2 providers
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function loginOAuth2() {
        $oauth2Enabled = $this->config->item('oauth2_enabled');
        $oauth2Provider = $this->config->item('oauth2_provider');
        $oauth2ClientId = $this->config->item('oauth2_client_id');
        $oauth2ClientSecret = $this->config->item('oauth2_client_secret');
        if ($oauth2Enabled === FALSE) {
            $this->output->set_output('ERROR: OAuth2 is disabled');
            return;
        }
        $authCode = $this->input->post('auth_code');
        
        if (!is_null($authCode)) {
            $this->load->model('users_model');
            switch ($oauth2Provider) {
                case 'google':
                    $provider = new League\OAuth2\Client\Provider\Google(Array(
                        'clientId' => $oauth2ClientId,
                        'clientSecret' => $oauth2ClientSecret,
                        'redirectUri' => 'postmessage',
                        'accessType' => 'offline',
                    ));
                    $token = $provider->getAccessToken('authorization_code', Array('code' => $authCode));
                    try {
                        //We try to get the e-mail address from the Google+ API
                        $ownerDetails = $provider->getResourceOwner($token);
                        $email = $ownerDetails->getEmail();
                        //If we find the e-mail address into the database, we're good
                        $loggedin = $this->users_model->checkCredentialsEmail($email);
                        if ($loggedin === TRUE) {
                            $this->output->set_output('OK');
                        } else {
                            $this->output->set_output(lang('session_login_flash_bad_credentials'));
                        }
                    } catch (Exception $e) {
                        $this->output->set_output('ERROR: ' . $e->getMessage());
                    }
                    break;
                default:
                    $this->output->set_output('ERROR: unsupported OAuth2 provider');
            }
        } else {
            $this->output->set_output('ERROR: Invalid OAuth2 token');
        }
    }

    /**
     * Returns the metadata needed for SAML2 Authentication
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function metadata() {
        require_once APPPATH . 'config/saml.php';
        $settings = new OneLogin\Saml2\Settings($samlSettings, true);
        $metadata = $settings->getSPMetadata();
        $errors = $settings->validateMetadata($metadata);
        if (empty($errors)) {
            $this->output->set_content_type('text/xml');
            $this->output->set_output($metadata);
        } else {
            throw new OneLogin\Saml2\Error(
                'Invalid SP metadata: '.implode(', ', $errors),
                OneLogin\Saml2\Error::METADATA_SP_INVALID
            );
        }
    }
    
    /**
     * SAML2 SSO endpoint that starts the login via SSO
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function sso() {
        require_once APPPATH . 'config/saml.php';
        $auth = new OneLogin\Saml2\Auth($samlSettings);
        $auth->login();
    }
    
    /**
     * SAML2 Logout endpoint that perfom the logout
     * This feature is not supported by all IdP (eg. Google)
     * That why a message might appear to explain that you are not logged from the IdP
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function slo() {
        require_once APPPATH . 'config/saml.php';
        $auth = new OneLogin\Saml2\Auth($samlSettings);
        if ($samlSettings['idp']['singleLogoutService']['url'] === '') {
            $data['title'] = lang('session_login_title');
            $data['language'] = $this->session->userdata('language');
            $data['language_code'] = $this->session->userdata('language_code');
            $this->load->view('templates/header', $data);
            $this->load->view('session/noslo', $data);
            $this->load->view('templates/footer');
        } else {
            $returnTo = null;
            $paramters = array();
            $nameId = null;
            $sessionIndex = null;
            if ($this->session->userdata("samlNameId") !== FALSE) {
                $nameId = $this->session->userdata("samlNameId");
            }
            if ($this->session->userdata("samlSessionIndex") !== FALSE) {
                $sessionIndex = $this->session->userdata("samlSessionIndex");
            }
            $auth->logout($returnTo, $paramters, $nameId, $sessionIndex);
            $this->session->sess_destroy();
            redirect('api/sso');
        }
    }
    
    /**
     * SAML2 sls endpoint
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function sls() {
        require_once APPPATH . 'config/saml.php';
        $auth = new OneLogin\Saml2\Auth($samlSettings);
        if (isset($this->session) && ($this->session->userdata("LogoutRequestID") !== FALSE)) {
            $requestID = $this->session->userdata("LogoutRequestID");
        } else {
            $requestID = null;
        }

        $auth->processSLO(false, $requestID);
        $errors = $auth->getErrors();
        if (!empty($errors)) {
            log_message('error', '{controllers/session/sls} SSO Errors=' . implode(', ', $errors));
        }
        $this->session->sess_destroy();
        redirect('api/sso');
    }

    /**
     * SAML2 acs endpoint. Called by the IdP to perform the connection
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function acs() {
        require_once APPPATH . 'config/saml.php';
        $auth = new OneLogin\Saml2\Auth($samlSettings);
        if (isset($this->session) && ($this->session->userdata("AuthNRequestID") !== FALSE)) {
            $requestID = $this->session->userdata("AuthNRequestID");
        } else {
            $requestID = null;
        }

        $auth->processResponse($requestID);
        $errors = $auth->getErrors();
        if (!empty($errors)) {
            log_message('error', '{controllers/session/acs} SSO Errors=' . implode(', ', $errors));
        }

        $loggedin = FALSE;
        if ($auth->isAuthenticated()) {
            $this->session->set_userdata("samlUserdata", $auth->getAttributes());
            $this->session->set_userdata("samlNameId", $auth->getNameId());
            $this->session->set_userdata("samlSessionIndex", $auth->getSessionIndex());
            $this->session->unset_userdata(array('AuthNRequestID'));

            //If we find the e-mail address into the database, we're good
            $this->load->model('users_model');
            $loggedin = $this->users_model->checkCredentialsEmail($auth->getNameId());
            if ($loggedin === TRUE) {
                $this->redirectToLastPage();
            }
            
            if ($loggedin === FALSE) {
                $data['title'] = lang('session_login_title');
                $data['help'] = $this->help->create_help_link('global_link_doc_page_login');
                $data['language'] = $this->session->userdata('language');
                $data['language_code'] = $this->session->userdata('language_code');
                $data['message'] = lang('session_login_flash_account_disabled');
                $this->load->view('templates/header', $data);
                $this->load->view('session/failure', $data);
                $this->load->view('templates/footer');
            }
        }
    }

}
