<?php
/**
 * This controller is the entry point for the REST API
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.3.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * This class implements a HTTP API served through an OAuth2 server.
 * In order to use it, you need to insert an OAuth2 client into the database, for example :
 * INSERT INTO oauth_clients (client_id, client_secret, redirect_uri) VALUES ("testclient", "testpass", "http://fake/");
 * where "testclient" and "testpass" are respectively the login and password.
 * Examples are provided into tests/rest folder.
 */
class Api extends CI_Controller
{

    /**
     * OAuth2 server used by all methods in order to determine if the user is connected
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
    }

    /**
     * Generate a documentation of the library on the fly
     * The doc is compliant with OpenAPI 3.0
     */
    public function doc(): void
    {
        if (!defined('API_HOST')) {
            define('API_HOST', base_url());
        }
        if (!defined('TOKEN_URL')) {
            define('TOKEN_URL', base_url('api/token'));
        }

        $openapi = (new \OpenApi\Generator)->generate([FCPATH . 'application/api/']);
        $this->output
            ->set_content_type('Content-Type: application/json')
            ->set_output($openapi->toJson());
    }

    /**
     * Get a OAuth2 token
     */
    public function token(): void
    {
        $this->server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
    }

    /**
     * Get all contracts
     * @param int $contractId Id of the contract
     */
    public function contracts(int $contractId = 0): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            $this->load->model('contracts_model');
            $result = $this->contracts_model->getContracts($contractId);
            if (empty($result)) {
                $this->output->set_header("HTTP/1.1 404 Not Found");
                return;
            }
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($result));
        }
    }

    /**
     * Get the list of users with all their attributes
     * Requires scope users (see tests/rest/api3.php)
     * Not documented with OpenAPI, might be deprecated in a near future
     * @since 0.6.0
     */
    public function usersExt(): void
    {
        $request = OAuth2\Request::createFromGlobals();
        $response = new OAuth2\Response();
        $scopeRequired = 'users';
        if (!$this->server->verifyResourceRequest($request, $response, $scopeRequired)) {
            $response->send();
        } else {
            $this->load->model('users_model');
            $result = $this->users_model->getUsers();
            header("Content-Type: application/json");
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($result));
        }
    }

    /**
     * Check if the input string contains a date
     *
     * @param string $date Date tobe validated
     * @param string $format Optional Date format, 'Y-m-d' by default
     * @return boolean TRUE if the string is a date,false otherwise
     */
    private function validateDate(string $date, string $format = 'Y-m-d'): bool
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

}
