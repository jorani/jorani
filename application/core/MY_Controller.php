<?php
/**
 * Customized controller for REST API
 * @copyright Copyright (c) 2014-2023 Benjamin BALET
 * @license   http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link      https://github.com/bbalet/jorani
 * @since     0.4.3
 */

/**
 * This class specializes the CodeIgniter controller by adding
 * Everything needed for REST (Authentication, content negotiation, etc.)
 * CORS is supported (preflight requests, verbs, etc.).
 */
class MY_RestController extends CI_Controller {
    /**
     * @var stdClass Properties of the connected user 
     */
    protected $user;
    
    /**
     * @var string Requested language (english language name)
     */
    protected $language;

    /**
     * Default constructor
     * Check user credentials
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct()
    {
        parent::__construct();
        log_message('debug', 'Current URI = ' . $this->uri->uri_string());
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Accept-Language");
        if ($this->input->method(FALSE) != 'OPTIONS') {
            if (empty($this->input->server('PHP_AUTH_USER'))) {
                log_message('error', 'Authenticate: PHP_AUTH_USER is missing (webserver misconfiguration or BasicAuth wasn\'t sent)');
                $this->notAuthenticated();
            } else {
                $this->load->model('users_model');
                $username = $this->input->server('PHP_AUTH_USER');
                $password = $this->input->server('PHP_AUTH_PW');
                $user = NULL;
                if ($this->config->item('ldap_enabled') === TRUE) {
                    if ($password != "") { //Bind to MS-AD with blank password might return OK
                        $ldap = ldap_connect($this->config->item('ldap_host'), $this->config->item('ldap_port'));
                        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
                        set_error_handler(function() { /* ignore errors */
                        });

                        $basedn = "";
                        if (($this->config->item('ldap_search_enabled')) === TRUE) {
                            $bind = ldap_bind($ldap, $this->config->item('ldap_search_user'), $this->config->item('ldap_search_password'));
                            $resultSet = ldap_search($ldap, $this->config->item('ldap_basedn'), sprintf($this->config->item('ldap_search_pattern'), $username));
                            $userEntry = ldap_first_entry($ldap, $resultSet);
                            $basedn = ldap_get_dn($ldap, $userEntry);
                        } else {
                            //Priority is given to the base DN defined into the database, then try with the template
                            $basedn = $this->users_model->getBaseDN($username);
                            if ($basedn == "") {//can return NULL
                                $basedn = sprintf($this->config->item('ldap_basedn'), $username);
                            }
                        }

                        $bind = ldap_bind($ldap, $basedn, $password);
                        restore_error_handler();
                        if ($bind) {
                            $user = $this->users_model->checkCredentialsForREST($username, "ldap");
                        } else {
                            //Attempt to login the user with the password stored into DB, provided this password is not emptye
                            if ($password != "") {
                                $user = $this->users_model->checkCredentialsForREST($username, "internal", $password);
                            }
                        }
                        ldap_close($ldap);
                    } else {
                        log_message('debug', 'Authenticate: Empty password provided for LDAP Backend');
                    }
                } else {
                    $user = $this->users_model->checkCredentialsForREST($username, "internal", $password);
                }

                if (is_null($user)) {
                    $this->notAuthenticated();
                } else {
                    $this->user = clone $user;
                    log_message('debug', 'Authenticate: Welcome dear user #' . $user->id);
                    $this->load->library('polyglot');
                    if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                        log_message('debug', 'Client sent us acceptable language codes: ' . $_SERVER['HTTP_ACCEPT_LANGUAGE']);
                        $availableLanguages = explode(",", $this->config->item('languages'));
                        log_message('debug', 'Jorani currently support one of these lang codes: ' . $this->config->item('languages'));
                        
                        $possibleLanguage = $this->preferedLanguages($availableLanguages, $_SERVER['HTTP_ACCEPT_LANGUAGE']);
                        $langCode = $this->polyglot->language2code($this->config->item('language'));
                        if (count($possibleLanguage) > 0) {
                            if (!in_array($langCode, $availableLanguages)) {
                                log_message('debug', 'Sorry, language code of client is not supported: ' . $langCode);
                            } else {
                                $langCode = key($possibleLanguage);
                            }
                        }
                        $this->language = $this->polyglot->code2language($langCode);
                    } else {
                        log_message('debug', 'Client did not send its favorite languages.');
                        $this->language = $this->config->item('language');
                    }
                    log_message('debug', 'We\'ll use ' . $this->language);

                    //Decode JSON into POST array
                    $mediaType = $this->input->get_request_header('Content-Type', TRUE);
                    log_message('debug', 'Media Type = ' . $mediaType);
                    if (strpos($mediaType, 'application/json') !== false) {
                        log_message('debug', 'Decode input JSON into POST array');
                        $_POST = json_decode(file_get_contents('php://input'), true);
                    }
                }
            }
        }
    }

    /**
     * Get an associative array of the preferred languages 
     * Languages are sorted out by their preference score
     * 
     * @param array $availableLanguages list of languages supported by Jorani
     * @param string $httpAcceptLanguage HTTP Request Header (accept-language)
     * @return array associative array langCode/Score (eg. [en] => 0.8, [es] => 0.4)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    private function preferedLanguages($availableLanguages, $httpAcceptLanguage) {
        $availableLanguages = array_flip($availableLanguages);
        $langs = array();
        preg_match_all('~([\w-]+)(?:[^,\d]+([\d.]+))?~', strtolower($httpAcceptLanguage), $matches, PREG_SET_ORDER);
        foreach($matches as $match) {
    
            list($a, $b) = explode('-', $match[1]) + array('', '');
            $value = isset($match[2]) ? (float) $match[2] : 1.0;
    
            if(isset($availableLanguages[$match[1]])) {
                $langs[$match[1]] = $value;
                continue;
            }
    
            if(isset($availableLanguages[$a])) {
                $langs[$a] = $value - 0.1;
            }
    
        }
        arsort($langs);
        return $langs;
    }

    /**
     * Pre-flight check for CORS requests
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function options() {
        log_message('debug', '__options');
    }

    /**
     * Terminate lifecycle of the web request if the user can't be authenticated
     */
    protected function notAuthenticated() {
        log_message('error', ' /!\ notAuthenticated: Send back HTTP 401 Error');
        http_response_code(401);
        header('WWW-Authenticate: Basic realm="Jorani Rest API"');
        die();
    }

    /**
     * Terminate lifecycle of the web request if the user doesn't have enough privileges
     */
    protected function forbidden() {
        log_message('error', ' /!\ forbidden: Send back HTTP 403 Error');
        http_response_code(403);
        die();
    }

    /**
     * Terminate lifecycle of the web request if the object was not found
     */
    protected function notFound() {
        log_message('error', ' /!\ notFound: The object was not found');
        http_response_code(404);
        die();
    }

    /**
     * Terminate lifecycle of the web request if the parameters are invalid
     */
    protected function badRequest() {
        log_message('error', ' /!\ badRequest: Invalid input');
        http_response_code(400);
        die();
    }
}
