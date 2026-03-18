<?php
/**
 * This controller helps a 3rd application to use Jorani as an Identity
 * Provider (SSO scenario) using OAuth2 protocol as explained into the
 * docuementation of PHP OAuth2 server:
 * http://bshaffer.github.io/oauth2-server-php-docs/cookbook/
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @since      0.6.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * This class implements a OAuth2 Authorization mechanism for a 3rd application
 */
class Authorization extends CI_Controller
{

    /**
     * OAuth2 server used by all methods in order to determine 
     * if the user is connected
     * @var OAuth2\Server Authentication server 
     */
    protected $server;

    /**
     * Default constructor
     * Initializing of OAuth2 server
     */
    public function __construct()
    {
        parent::__construct();
        OAuth2\Autoloader::register();
        $storage = new OAuth2\Storage\Pdo($this->db->conn_id);
        $this->server = new OAuth2\Server($storage);
        $this->server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
        $this->server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));

        if ($this->session->userdata('language') === FALSE) {
            $availableLanguages = explode(",", $this->config->item('languages'));
            $this->load->library('polyglot');
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
     * OAuth2 authorize endpoint 
     */
    public function authorize()
    {
        $request = OAuth2\Request::createFromGlobals();
        $response = new OAuth2\Response();

        // validate the authorize request
        if (!$this->server->validateAuthorizeRequest($request, $response)) {
            $response->send();
            die;
        }

        //OAuth2 payload
        $state = $this->input->get('state');
        $responseType = $this->input->get('response_type');
        $redirectUri = $this->input->get_post('redirect_uri');
        $clientId = $this->input->get_post('client_id');
        $data['state'] = $state;
        $data['responseType'] = $responseType;
        $data['redirectUri'] = $redirectUri;
        $data['clientId'] = $clientId;

        //Display simple login form if the user is not logged-in
        if (!$this->session->userdata('logged_in')) {
            $data['title'] = lang('session_login_title');
            if (empty($_POST)) {
                $this->load->library('form_validation');
                $data['language'] = $this->session->userdata('language');
                $data['language_code'] = $this->session->userdata('language_code');
                $this->load->view('session/login_simple', $data);
            }
        }

        if ($this->session->userdata('logged_in')) {
            $userId = $this->session->userdata('id');
            $this->load->model('oauthclients_model');

            //Test if we received a form authorizing the application
            if (empty($_POST)) {
                // Does the application was already authorized by user
                if ($this->oauthclients_model->isOAuthAppAllowed($clientId, $userId)) {
                    //redirect with authorization code if the user has authorized your client
                    $this->server->handleAuthorizeRequest($request, $response, TRUE, $userId);
                    $response->send();
                } else {
                    // display an authorization form
                    $data['title'] = sprintf(lang('oauth2_authorize_question'), $clientId);
                    $data['language'] = $this->session->userdata('language');
                    $data['language_code'] = $this->session->userdata('language_code');
                    //Load the icon of the OAuth2 3rd application or a default icon
                    $iconPath = FCPATH . 'local/images/' . $clientId . '.png';
                    if (file_exists($iconPath)) {
                        $data['iconPath'] = base_url() . 'local/images/' . $clientId . '.png';
                    } else {
                        $data['iconPath'] = base_url() . 'assets/images/application.png';
                    }
                    $this->load->view('templates/header', $data);
                    $this->load->view('session/authorize', $data);
                }
            } else {
                //redirect with authorization code if the user has authorized your client
                $is_authorized = ($_POST['authorized'] === 'yes');
                if ($is_authorized) {
                    $this->oauthclients_model->allowOAuthApp($clientId, $userId);
                }
                $this->server->handleAuthorizeRequest($request, $response, $is_authorized, $userId);
                $response->send();
            }
        }
    }

    /**
     * Get the details of the connected user
     */
    public function userinfo()
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            $token = $this->server->getAccessTokenData(OAuth2\Request::createFromGlobals());
            $this->load->model('users_model');
            $result = $this->users_model->getUsers($token['user_id']);
            unset($result['password']);
            header("Content-Type: application/json");
            echo json_encode($result);
        }
    }

    /**
     * Handle the Simplified login form for OAuth authorization
     */
    public function login()
    {
        //Decrypt password
        $password = $this->input->post('password');
        $this->load->model('users_model');
        $loggedin = $this->users_model->checkCredentials($this->input->post('login'), $password);
        if ($loggedin == FALSE) {
            log_message('error', '{controllers/session/login} Invalid login id or password for user=' . $this->input->post('login'));
            if ($this->users_model->isActive($this->input->post('login'))) {
                $this->session->set_flashdata('msg', lang('session_login_flash_bad_credentials'));
            } else {
                $this->session->set_flashdata('msg', lang('session_login_flash_account_disabled'));
            }
        } else {
            $this->load->model('sessions_model');
            $this->sessions_model->purgeOldData();
        }

        //Redirect to the OAtuh2 endpoint whatever the outcome
        $state = $this->input->get_post('state');
        $responseType = $this->input->get_post('response_type');
        $redirectUri = $this->input->get_post('redirect_uri');
        $clientId = $this->input->get_post('client_id');
        $params = "state=$state&response_type=$responseType&redirect_uri=$redirectUri&client_id=$clientId";
        redirect("api/authorization/authorize?$params");
    }
}
