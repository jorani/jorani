<?php
/**
 * This controller manages the connection to the application
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.1.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Token\AccessToken;

/**
 * This class manages the connection to the application
 * CodeIgniter uses a cookie to store session's details.
 * @property CI_Config $config
 * @property CI_Lang $lang
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Output $output
 * @property Contracts_model $contracts_model
 * @property Dayoffs_model $dayoffs_model
 * @property Entitleddays_model $entitleddays_model
 * @property Leaves_model $leaves_model
 * @property OAuthClients_model $oauthclients_model
 * @property Organization_model $organization_model
 * @property Positions_model $positions_model
 * @property Overtime_model $overtime_model
 * @property Types_model $types_model
 * @property Users_model $users_model
 */
class Connection extends CI_Controller
{

    /**
     * Default constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('polyglot');
        if ($this->session->userdata('language') == NULL) {
            $availableLanguages = explode(",", $this->config->item('languages'));
            $languageCode = $this->polyglot->language2code($this->config->item('language'));
            if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
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
     * Login form
     */
    public function login(): void
    {
        //The login form is not used with SAML2 authentication mode
        $samlEnabled = filter_var($this->config->item('saml_enabled'), FILTER_VALIDATE_BOOLEAN, ['' => FILTER_NULL_ON_FAILURE]);
        if ($samlEnabled === TRUE) {
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
        $this->form_validation->set_rules('login', lang('session_login_field_login'), 'required');

        $data['last_page'] = $this->session->userdata('last_page');
        if ($this->form_validation->run() === FALSE) {
            $data['language'] = $this->session->userdata('language');
            $data['language_code'] = $this->session->userdata('language_code');
            $data['flash_partial_view'] = $this->load->view('templates/flash', $data, TRUE);
            $this->load->view('templates/header', $data);
            $this->load->view('session/login', $data);
            $this->load->view('templates/footer');
        } else {
            $this->load->model('users_model');
            //Set language
            $languages = explode(",", $this->config->item('languages'));
            $language = $this->input->get_post('language', true);
            if (in_array($language, $languages)) {
                $this->session->set_userdata('language_code', $language);
                $this->session->set_userdata('language', $this->polyglot->code2language($language));
            } else {
                $this->session->set_userdata('language_code', 'en');
                $this->session->set_userdata('language', $this->polyglot->code2language('en'));
            }

            $password = $this->input->post('password');
            $loggedin = FALSE;
            $ldapEnabled = filter_var($this->config->item('ldap_enabled'), FILTER_VALIDATE_BOOLEAN, ['' => FILTER_NULL_ON_FAILURE]);
            if ($ldapEnabled === TRUE) {
                if ($password != "") { //Bind to MS-AD with blank password might return OK
                    $ldapUri = sprintf('ldap://%s:%d', $this->config->item('ldap_host'), $this->config->item('ldap_port'));
                    $ldap = ldap_connect($ldapUri);
                    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
                    set_error_handler(static function (int $_errno, string $_errstr, string $_errfile, int $_errline): bool {
                        return true; /* ignore errors */
                    });
                    $basedn = "";
                    $ldapSearchUser = filter_var($this->config->item('ldap_search_user'), FILTER_VALIDATE_BOOLEAN, ['' => FILTER_NULL_ON_FAILURE]);
                    if ($ldapSearchUser === TRUE) {
                        $bind = ldap_bind($ldap, $this->config->item('ldap_search_user'), $this->config->item('ldap_search_password'));
                        $resultSet = ldap_search($ldap, $this->config->item('ldap_basedn'), sprintf($this->config->item('ldap_search_pattern'), $this->input->post('login')));
                        $userEntry = ldap_first_entry($ldap, $resultSet);
                        $basedn = ldap_get_dn($ldap, $userEntry);
                    } else {
                        //Priority is given to the base DN defined into the database, then try with the template
                        $basedn = $this->users_model->getBaseDN($this->input->post('login'));
                        if ($basedn == "") {
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
                $this->load->model('sessions_model');
                $this->sessions_model->purgeOldData();
                $this->redirectToLastPage();
            }
        }
    }

    /**
     * Logout the user and destroy the session data
     */
    public function logout(): void
    {
        $this->session->sess_destroy();
        redirect('session/login');
    }

    /**
     * Change the language and redirect to last page (i.e. page that submit the language form)
     */
    public function language(): void
    {
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
     */
    private function redirectToLastPage($page = ""): void
    {
        if ($page !== "") {
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
     * Try to authenticate the user using one of the OAuth2 providers
     */
    public function loginOAuth2(): void
    {
        $oauth2Enabled = filter_var($this->config->item('oauth2_enabled'), FILTER_VALIDATE_BOOLEAN, ['' => FILTER_NULL_ON_FAILURE]);
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
            switch (strtolower($oauth2Provider)) {
                case 'google':
                    $provider = new Google([
                        'clientId' => $oauth2ClientId,
                        'clientSecret' => $oauth2ClientSecret,
                        'redirectUri' => 'postmessage',
                        'accessType' => 'offline',
                    ]);
                    /** @var AccessToken $token */
                    $token = $provider->getAccessToken('authorization_code', ['code' => $authCode]);
                    try {
                        /** @var \League\OAuth2\Client\Provider\GoogleUser $ownerDetails */
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
                    return;
            }
        } else {
            $this->output->set_output('ERROR: Invalid OAuth2 token');
            return;
        }
    }

    /**
     * Returns the metadata needed for SAML2 Authentication
     */
    public function metadata(): void
    {
        /** @var array<string, mixed> $samlSettings */
        $samlSettings = []; // From config/saml.php (to avoid PHPStan error)
        require_once APPPATH . 'config/saml.php';
        $settings = new OneLogin\Saml2\Settings($samlSettings, true);
        $metadata = $settings->getSPMetadata();
        $errors = $settings->validateMetadata($metadata);
        if (empty($errors)) {
            $this->output->set_content_type('text/xml');
            $this->output->set_output($metadata);
        } else {
            throw new OneLogin\Saml2\Error(
                'Invalid SP metadata: ' . implode(', ', $errors),
                OneLogin\Saml2\Error::METADATA_SP_INVALID
            );
        }
    }

    /**
     * SAML2 SSO endpoint that starts the login via SSO
     */
    public function sso(): void
    {
        /** @var array<string, mixed> $samlSettings */
        $samlSettings = []; // From config/saml.php (to avoid PHPStan error)
        require_once APPPATH . 'config/saml.php';
        $auth = new OneLogin\Saml2\Auth($samlSettings);
        $auth->login();
    }

    /**
     * SAML2 Logout endpoint that perfom the logout
     * This feature is not supported by all IdP (eg. Google)
     * That why a message might appear to explain that you are not logged from the IdP
     */
    public function slo(): void
    {
        /** @var array<string, mixed> $samlSettings */
        $samlSettings = []; // From config/saml.php (to avoid PHPStan error)
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
            $paramters = [];
            $nameId = null;
            $sessionIndex = null;
            if ($this->session->userdata("samlNameId") !== FALSE) {
                $nameId = $this->session->userdata("samlNameId");
            }
            if ($this->session->userdata("samlSessionIndex") !== FALSE) {
                $sessionIndex = $this->session->userdata("samlSessionIndex");
            }
            $this->session->sess_destroy();
            $auth->logout($returnTo, $paramters, $nameId, $sessionIndex, false);
            redirect('api/sso'); // @phpstan-ignore deadCode.unreachable (False positive)
        }
    }

    /**
     * SAML2 sls endpoint
     */
    public function sls(): void
    {
        /** @var array<string, mixed> $samlSettings */
        $samlSettings = []; // From config/saml.php (to avoid PHPStan error)
        require_once APPPATH . 'config/saml.php';
        $auth = new OneLogin\Saml2\Auth($samlSettings);
        if ($this->session->userdata("LogoutRequestID") !== FALSE) {
            $requestID = $this->session->userdata("LogoutRequestID");
        } else {
            $requestID = null;
        }
        $this->session->sess_destroy();
        $auth->processSLO(false, $requestID, false, null, false);
        redirect('api/sso'); // @phpstan-ignore deadCode.unreachable (False positive)
    }

    /**
     * SAML2 acs endpoint. Called by the IdP to perform the connection
     */
    public function acs(): void
    {
        /** @var array<string, mixed> $samlSettings */
        $samlSettings = []; // From config/saml.php (to avoid PHPStan error)
        require_once APPPATH . 'config/saml.php';
        $auth = new OneLogin\Saml2\Auth($samlSettings);
        if ($this->session->userdata("AuthNRequestID") !== FALSE) {
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
                $this->load->model('sessions_model');
                $this->sessions_model->purgeOldData();
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
