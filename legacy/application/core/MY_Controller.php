<?php
/**
 * Customized controller for REST API
 * 
 * @license    http://opensource.org/licenses/MIT MIT
 * @since     0.4.3
 */

/**
 * This class specializes the CodeIgniter controller by adding
 * Everything needed for a REST API
 * CORS is supported (preflight requests, verbs, etc.).
 */
class MY_RestController extends CI_Controller
{

    /**
     * OAuth2 server used by all methods in order to determine if the user is connected
     * @var OAuth2\Server Authentication server 
     */
    protected $server;

    /**
     * Is the API enabled or not
     * @var bool
     */
    protected $isApiEnabled = false;

    /**
     * Default constructor
     * Check user credentials
     */
    public function __construct()
    {
        parent::__construct();
        $this->isApiEnabled = filter_var($this->config->item('api_enabled'), FILTER_VALIDATE_BOOLEAN, ['' => FILTER_NULL_ON_FAILURE]);
        header("Access-Control-Allow-Origin: {$this->config->item('api_access_control_allow_origin')}");
        header("Access-Control-Allow-Methods: {$this->config->item('api_access_control_allow_methods')}");
        header("Access-Control-Allow-Headers: {$this->config->item('api_access_control_allow_headers')}");
        header("Access-Control-Max-Age: {$this->config->item('api_access_control_max_age')}");

        if (!$this->isApiEnabled) {
            $this->output->set_status_header(503, 'Service Unavailable');
            die();
        }

        OAuth2\Autoloader::register();
        $storage = new OAuth2\Storage\Pdo($this->db->conn_id);
        $this->server = new OAuth2\Server($storage);
        $this->server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
        $this->server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
    }

    /**
     * Check if the input string contains a date
     *
     * @param string $date Date tobe validated
     * @param string $format Optional Date format, 'Y-m-d' by default
     * @return boolean TRUE if the string is a date,false otherwise
     */
    protected function validateDate(string $date, string $format = 'Y-m-d'): bool
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}
