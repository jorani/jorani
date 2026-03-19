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
 * Swagger Documentation is built from sources in application/api/
 * @property CI_Config $config
 * @property CI_Lang $lang
 * @property CI_Loader $load
 * @property CI_Input $input
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
     * @throws RuntimeException if the response is not an OAuth2\Response
     */
    public function token(): void
    {
        $response = $this->server->handleTokenRequest(OAuth2\Request::createFromGlobals());
        if ($response instanceof OAuth2\Response) {
            $response->send();
            return;
        }
        throw new RuntimeException('Unexpected OAuth2 response type.');
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
     * @param int $contractId Unique identifier of an contract
     */
    public function entitleddayscontract(int $contractId): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            $this->load->model('entitleddays_model');
            $result = $this->entitleddays_model->getEntitledDaysForContract($contractId);
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
     * Add entitled days to a given contract
     * @param int $contractId Unique identifier of an contract
     */
    public function addentitleddayscontract(int $contractId): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            //Check first if the contract exists
            $this->load->model('contracts_model');
            $contract = $this->contracts_model->getContracts($contractId);
            if (empty($contract)) {
                $this->output->set_header("HTTP/1.1 404 Not Found");
                return;
            }
            //Add the entitlement
            $this->load->model('entitleddays_model');
            $startdate = $this->input->post('startdate');
            $enddate = $this->input->post('enddate');
            $days = $this->input->post('days');
            $type = $this->input->post('type');
            $description = $this->input->post('description');
            $result = $this->entitleddays_model->addEntitledDaysToContract($contractId, $startdate, $enddate, $days, $type, $description);
            if (empty($result)) {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
            } else {
                echo json_encode($result);
            }
        }
    }

    /**
     * Get the list of entitled days for a given employee
     * @param int $employeeId Unique identifier of an employee
     */
    public function entitleddaysemployee(int $employeeId): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            //Check first if the employee exists
            $this->load->model('users_model');
            $employee = $this->users_model->getUsers($employeeId);
            if (empty($employee)) {
                $this->output->set_header("HTTP/1.1 404 Not Found");
                return;
            }
            //Return the list of entitlements
            $this->load->model('entitleddays_model');
            $result = $this->entitleddays_model->getEntitledDaysForEmployee($employeeId);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($result));
        }
    }

    /**
     * Add entitled days to a given employee
     * @param int $employeeId Unique identifier of an employee
     */
    public function addentitleddaysemployee(int $employeeId): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            //Check first if the employee exists
            $this->load->model('users_model');
            $employee = $this->users_model->getUsers($employeeId);
            if (empty($employee)) {
                $this->output->set_header("HTTP/1.1 404 Not Found");
                return;
            }
            //Try to add the entitlement
            $this->load->model('entitleddays_model');
            $startdate = $this->input->post('startdate');
            $enddate = $this->input->post('enddate');
            $days = $this->input->post('days');
            $type = $this->input->post('type');
            $description = $this->input->post('description');
            $result = $this->entitleddays_model->addEntitledDaysToEmployee($employeeId, $startdate, $enddate, $days, $type, $description);
            if (empty($result)) {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($result));
            }
        }
    }

    /**
     * Get the leaves counter of a given employee
     * @param int $employeeId Unique identifier of an employee
     * @param int|string|null $refTmp tmp of the Date of reference (or current date if NULL)
     */
    public function leavessummary(int $employeeId, int|string|null $refTmp = NULL): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            //Check first if the employee exists
            $this->load->model('users_model');
            $employee = $this->users_model->getUsers($employeeId);
            if (empty($employee)) {
                $this->output->set_header("HTTP/1.1 404 Not Found");
                return;
            }
            //Compute the summary based on the current date or the one given as parameter
            $this->load->model('leaves_model');
            $refDate = $refTmp;
            if ($refTmp != NULL) {
                if (strpos((string) $refTmp, '-') === false) { //If we passed a timestamp
                    $refDate = date("Y-m-d", (int) $refTmp);
                }
            } else {
                $refDate = date("Y-m-d");
            }
            if (!$this->validateDate($refDate)) {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
                return;
            }

            $result = $this->leaves_model->getLeaveBalanceForEmployee($employeeId, $refDate);
            if (empty($result)) {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($result));
            }
        }
    }

    /**
     * Get all the leaves requests
     * @param int|string $startDate tmp or string (YYYY-MM-DD) of the Start Date
     * @param int|string $endDate tmp or string (YYYY-MM-DD) of the End Date
     */
    public function leavesInRange(int|string $startDate, int|string $endDate): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            //Convert the input timestamp if needed
            if (strpos($startDate, '-') === false) { //If we passed a timestamp
                $startDate = date("Y-m-d", (int) $startDate);
            }
            if (strpos($endDate, '-') === false) { //If we passed a timestamp
                $endDate = date("Y-m-d", (int) $endDate);
            }
            //Validate the input
            if (!$this->validateDate($startDate)) {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
                return;
            }
            if (!$this->validateDate($endDate)) {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
                return;
            }
            //Get the list of leave requests
            $this->load->model('leaves_model');
            $result = $this->leaves_model->all($startDate, $endDate);
            if (empty($result)) {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($result));
            }
        }
    }

    /**
     * Get the list of leave types (useful to get the labels into a cache)
     */
    public function leavetypes(): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            $this->load->model('types_model');
            $result = $this->types_model->getTypes();
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($result));
        }
    }

    /**
     * Accept a leave request
     * @param int $leaveId identifier of the leave request to accept
     * @since 0.4.4
     */
    public function acceptleave(int $leaveId): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            //Check first if the leave exists
            $this->load->model('leaves_model');
            $leave = $this->leaves_model->getLeaves($leaveId);
            if (empty($leave)) {
                $this->output->set_header("HTTP/1.1 404 Not Found");
                return;
            }
            $this->leaves_model->switchStatus($leaveId, LMS_ACCEPTED);
        }
    }

    /**
     * Reject a leave request
     * @param int $leaveId identifier of leave request to reject
     * @since 0.4.4
     */
    public function rejectleave(int $leaveId): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            //Check first if the leave exists
            $this->load->model('leaves_model');
            $leave = $this->leaves_model->getLeaves($leaveId);
            if (empty($leave)) {
                $this->output->set_header("HTTP/1.1 404 Not Found");
                return;
            }
            $this->leaves_model->switchStatus($leaveId, LMS_REJECTED);
        }
    }

    /**
     * Get the list of positions (useful to get the labels into a cache)
     */
    public function positions(): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            $this->load->model('positions_model');
            $result = $this->positions_model->getPositions();
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($result));
        }
    }

    /**
     * Get the department details of a given employee
     * @param int $employeeId Identifier of an employee (attached to an entity)
     */
    public function userdepartment(int $employeeId): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            //Check first if the employee exists
            $this->load->model('users_model');
            $employee = $this->users_model->getUsers($employeeId);
            if (empty($employee)) {
                $this->output->set_header("HTTP/1.1 404 Not Found");
                return;
            }

            $this->load->model('organization_model');
            $result = $this->organization_model->getDepartment($employeeId);
            if (empty($result)) {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($result));
            }
        }
    }

    /**
     * Get the list of users or a specific user. 
     * The password, picture, and random_hash fields are removed from the result set
     * @param int $id Unique identifier of a user
     */
    public function users(int $id = 0): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            $this->load->model('users_model');
            $result = $this->users_model->getUsers($id);
            if ($id === 0) {
                foreach ($result as $k1 => $q) {
                    foreach ($q as $k2 => $r) {
                        if ($k2 == 'password') {
                            unset($result[$k1][$k2]);
                        }
                        if ($k2 == 'random_hash') {
                            unset($result[$k1][$k2]);
                        }
                        if ($k2 == 'picture') {
                            unset($result[$k1][$k2]);
                        }
                    }
                }
            } else {
                if (is_null($result)) {
                    $this->output->set_header("HTTP/1.1 404 Not Found");
                    return;
                }
                unset($result['password']);
                unset($result['random_hash']);
                unset($result['picture']);
            }
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($result));
        }
    }

    /**
     * Get the list of leaves for a given employee
     * @param int $employeeId Unique identifier of an employee
     */
    public function userleaves(int $employeeId): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            //Check first if the employee exists
            $this->load->model('users_model');
            $employee = $this->users_model->getUsers($employeeId);
            if (empty($employee)) {
                $this->output->set_header("HTTP/1.1 404 Not Found");
                return;
            }

            $this->load->model('leaves_model');
            $result = $this->leaves_model->getLeavesOfEmployee($employeeId);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($result));
        }
    }

    /**
     * Get the list of extra for a given employee
     * @param int $employeeId Unique identifier of an employee
     */
    public function userextras(int $employeeId): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            //Check first if the employee exists
            $this->load->model('users_model');
            $employee = $this->users_model->getUsers($employeeId);
            if (empty($employee)) {
                $this->output->set_header("HTTP/1.1 404 Not Found");
                return;
            }

            $this->load->model('overtime_model');
            $result = $this->overtime_model->getExtrasOfEmployee($employeeId);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($result));
        }
    }

    /**
     * Get the monthly presence stats for a given employee
     * @param int $employeeId Unique identifier of an employee
     * @param int $month Month number [1-12]
     * @param int $year Year number (XXXX)
     * @since 0.4.0
     */
    public function monthlypresence(int $employeeId, int $month, int $year): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            $this->load->model('users_model');
            $employee = $this->users_model->getUsers($employeeId);
            if (empty($employee)) {
                $this->output->set_header("HTTP/1.1 404 Not Found");
                return;
            }
            if (!isset($employee['contract'])) {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
            } else {
                $this->load->model('leaves_model');
                $this->load->model('dayoffs_model');
                $start = sprintf('%d-%02d-01', $year, $month);
                $lastDay = date("t", strtotime($start));    //last day of selected month
                $end = sprintf('%d-%02d-%02d', $year, $month, $lastDay);
                $result = new stdClass();
                $linear = $this->leaves_model->linear($employeeId, $month, $year, FALSE, FALSE, TRUE, FALSE);
                $result->leaves = $this->leaves_model->monthlyLeavesDuration($linear);
                $result->dayoffs = $this->dayoffs_model->lengthDaysOffBetweenDates($employee['contract'], $start, $end);
                $result->total = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                $result->start = $start;
                $result->end = $end;
                $result->open = $result->total - $result->dayoffs;
                $result->work = $result->open - $result->leaves;
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($result));
            }
        }
    }

    /**
     * Delete a user from the database
     * This is not recommended. Consider moving it into an archive entity of your organization
     * @param int $userId Unique identifier of an employee
     * @since 0.4.0
     */
    public function deleteuser(int $userId): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            $this->load->model('users_model');
            $user = $this->users_model->getUsers($userId);
            if (is_null($user)) {
                $this->output->set_header("HTTP/1.1 404 Not Found");
            } else {
                $this->users_model->deleteUser($userId);
            }
        }
    }

    /**
     * Update a user
     * Updated fields are passed by POST parameters or in the input stream for PATCH
     * Note that for PATCH method, you need to send a compliant content type (multipart/x-www-form-urlencoded)
     * @param int $userId Unique identifier of an employee
     * @since 0.4.0
     */
    public function updateuser(int $userId): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            $this->load->model('users_model');
            //Find out if the user exists
            $user = $this->users_model->getUsers($userId);
            if (is_null($user)) {
                $this->output->set_header("HTTP/1.1 404 Not Found");
                return;
            }

            $data = array();
            if ($this->input->server('REQUEST_METHOD') === 'PATCH') {
                if (!empty($this->input->input_stream('firstname'))) {
                    $data['firstname'] = $this->input->input_stream('firstname');
                }
                if (!empty($this->input->input_stream('lastname'))) {
                    $data['lastname'] = $this->input->input_stream('lastname');
                }
                if (!empty($this->input->input_stream('login'))) {
                    $data['login'] = $this->input->input_stream('login');
                }
                if (!empty($this->input->input_stream('email'))) {
                    $data['email'] = $this->input->input_stream('email');
                }
                if (!empty($this->input->input_stream('password'))) {
                    $data['password'] = $this->input->input_stream('password');
                }
                if (!empty($this->input->input_stream('role'))) {
                    $data['role'] = $this->input->input_stream('role');
                }
                if (!empty($this->input->input_stream('manager'))) {
                    $data['manager'] = $this->input->input_stream('manager');
                }
                if (!empty($this->input->input_stream('organization'))) {
                    $data['organization'] = $this->input->input_stream('organization');
                }
                if (!empty($this->input->input_stream('contract'))) {
                    $data['contract'] = $this->input->input_stream('contract');
                }
                if (!empty($this->input->input_stream('position'))) {
                    $data['position'] = $this->input->input_stream('position');
                }
                if (!empty($this->input->input_stream('datehired'))) {
                    $data['datehired'] = $this->input->input_stream('datehired');
                }
                if (!empty($this->input->input_stream('identifier'))) {
                    $data['identifier'] = $this->input->input_stream('identifier');
                }
                if (!empty($this->input->input_stream('language'))) {
                    $data['language'] = $this->input->input_stream('language');
                }
                if (!empty($this->input->input_stream('timezone'))) {
                    $data['timezone'] = $this->input->input_stream('timezone');
                }
                if (!empty($this->input->input_stream('ldap_path'))) {
                    $data['ldap_path'] = $this->input->input_stream('ldap_path');
                }
                if (!empty($this->input->input_stream('country'))) {
                    $data['country'] = $this->input->input_stream('country');
                }
                if (!empty($this->input->input_stream('calendar'))) {
                    $data['calendar'] = $this->input->input_stream('calendar');
                }
                if (!empty($this->input->input_stream('active'))) {
                    $data['active'] = $this->input->input_stream('active');
                }
                if (!empty($this->input->input_stream('calendar'))) {
                    $data['calendar'] = $this->input->input_stream('calendar');
                }
                if (!empty($this->input->input_stream('user_properties'))) {
                    $data['user_properties'] = $this->input->input_stream('user_properties');
                }
                if (!empty($this->input->input_stream('picture'))) {
                    $data['picture'] = $this->input->input_stream('picture');
                }
            } else {
                if (!empty($this->input->post('firstname'))) {
                    $data['firstname'] = $this->input->post('firstname');
                }
                if (!empty($this->input->post('lastname'))) {
                    $data['lastname'] = $this->input->post('lastname');
                }
                if (!empty($this->input->post('login'))) {
                    $data['login'] = $this->input->post('login');
                }
                if (!empty($this->input->post('email'))) {
                    $data['email'] = $this->input->post('email');
                }
                if (!empty($this->input->post('password'))) {
                    $data['password'] = $this->input->post('password');
                }
                if (!empty($this->input->post('role'))) {
                    $data['role'] = $this->input->post('role');
                }
                if (!empty($this->input->post('manager'))) {
                    $data['manager'] = $this->input->post('manager');
                }
                if (!empty($this->input->post('organization'))) {
                    $data['organization'] = $this->input->post('organization');
                }
                if (!empty($this->input->post('contract'))) {
                    $data['contract'] = $this->input->post('contract');
                }
                if (!empty($this->input->post('position'))) {
                    $data['position'] = $this->input->post('position');
                }
                if (!empty($this->input->post('datehired'))) {
                    $data['datehired'] = $this->input->post('datehired');
                }
                if (!empty($this->input->post('identifier'))) {
                    $data['identifier'] = $this->input->post('identifier');
                }
                if (!empty($this->input->post('language'))) {
                    $data['language'] = $this->input->post('language');
                }
                if (!empty($this->input->post('timezone'))) {
                    $data['timezone'] = $this->input->post('timezone');
                }
                if (!empty($this->input->post('ldap_path'))) {
                    $data['ldap_path'] = $this->input->post('ldap_path');
                }
                if (!empty($this->input->post('country'))) {
                    $data['country'] = $this->input->post('country');
                }
                if (!empty($this->input->post('calendar'))) {
                    $data['calendar'] = $this->input->post('calendar');
                }
                if (!empty($this->input->post('active'))) {
                    $data['active'] = $this->input->post('active');
                }
                if (!empty($this->input->post('calendar'))) {
                    $data['calendar'] = $this->input->post('calendar');
                }
                if (!empty($this->input->post('user_properties'))) {
                    $data['user_properties'] = $this->input->post('user_properties');
                }
                if (!empty($this->input->post('picture'))) {
                    $data['picture'] = $this->input->post('picture');
                }
            }
            if (empty($data)) {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
                return;
            }
            $this->users_model->updateUserByApi($userId, $data);
        }
    }

    /**
     * Create an employee (fields are passed by POST parameters)
     * Returns the new inserted id
     * @param bool $sendEmail Send an Email to the new employee (FALSE by default)
     * @since 0.4.0
     */
    public function createuser(bool $sendEmail = FALSE): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            $sendEmail = filter_var($sendEmail, FILTER_VALIDATE_BOOLEAN);

            $this->load->model('users_model');
            $firstname = $this->input->post('firstname');
            $lastname = $this->input->post('lastname');
            $login = $this->input->post('login');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $role = $this->input->post('role');
            $manager = $this->input->post('manager');
            $organization = $this->input->post('organization');
            $contract = $this->input->post('contract');
            $position = $this->input->post('position');
            $datehired = $this->input->post('datehired');
            $identifier = $this->input->post('identifier');
            $language = $this->input->post('language');
            $timezone = $this->input->post('timezone');
            $ldap_path = $this->input->post('ldap_path');
            $active = $this->input->post('active');
            $country = $this->input->post('country');   //Not used
            $calendar = $this->input->post('calendar'); //Not used
            $userProperties = $this->input->post('user_properties');
            $picture = $this->input->post('picture');

            //Set default values
            $this->load->library('polyglot');
            if (empty($language)) {
                $language = $this->polyglot->language2code($this->config->item('language'));
            }

            //Generate a random password if the field is empty
            if (empty($password)) {
                $password = $this->users_model->randomPassword(8);
            }

            //If not specified, the user is a regular employee
            if (empty($role)) {
                $role = 2;
            }

            //Check mandatory fields
            if (empty($firstname) || empty($lastname) || empty($login) || empty($email)) {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
                log_message('error', 'HTTP API/Create user: Mandatory fields are missing.');
            } else {
                if ($this->users_model->isLoginAvailable($login)) {
                    $result = $this->users_model->insertUserByApi(
                        $firstname,
                        $lastname,
                        $login,
                        $email,
                        $password,
                        $role,
                        $manager,
                        $organization,
                        $contract,
                        $position,
                        $datehired,
                        $identifier,
                        $language,
                        $timezone,
                        $ldap_path,
                        TRUE,
                        $country,
                        $calendar
                    );

                    if ($sendEmail == TRUE) {
                        //Send an e-mail to the user so as to inform that its account has been created
                        $this->load->library('email');
                        $userLang = $this->polyglot->code2language($language);
                        $this->lang->load('users', $userLang);
                        $this->lang->load('email', $userLang);

                        $this->load->library('parser');
                        $data = array(
                            'Title' => lang('email_user_create_title'),
                            'BaseURL' => base_url(),
                            'Firstname' => $firstname,
                            'Lastname' => $lastname,
                            'Login' => $login,
                            'Password' => $password
                        );
                        $message = $this->parser->parse('emails/' . $language . '/new_user', $data, TRUE);
                        $this->email->set_encoding('quoted-printable');

                        if (($this->config->item('from_mail') !== NULL) && ($this->config->item('from_name') !== NULL)) {
                            $this->email->from($this->config->item('from_mail'), $this->config->item('from_name'));
                        } else {
                            $this->email->from('do.not@reply.me', 'LMS');
                        }
                        $this->email->to($email);
                        if (($this->config->item('subject_prefix')) !== NULL) {
                            $subject = $this->config->item('subject_prefix');
                        } else {
                            $subject = '[Jorani] ';
                        }
                        $this->email->subject($subject . lang('email_user_create_subject'));
                        $this->email->message($message);
                        $this->email->send();
                    }
                    echo json_encode($result);
                } else {
                    $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
                    log_message('error', 'HTTP API/Create user: This login is not available.');
                }
            }
        }
    }

    /**
     * Create a leave request (fields are passed by POST parameters).
     * This function doesn't send e-mails and it is used for imposed leaves
     * Returns the new inserted id.
     * @since 0.4.0
     */
    public function createleave(): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            $startdate = $this->input->post('startdate');
            $enddate = $this->input->post('enddate');
            $status = $this->input->post('status');
            $employee = $this->input->post('employee');
            $cause = $this->input->post('cause');
            $startdatetype = $this->input->post('startdatetype');
            $enddatetype = $this->input->post('enddatetype');
            $duration = $this->input->post('duration');
            $type = $this->input->post('type');
            $comments = $this->input->post('comments');
            $document = $this->input->post('document');

            //Check mandatory fields
            if (
                empty($startdate) || empty($enddate) || empty($status) || empty($employee)
                || empty($startdatetype) || empty($enddatetype) || empty($duration) || empty($type)
            ) {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
                log_message('error', 'Mandatory fields are missing.');
            } else {
                //Convert the input timestamp if needed
                if (strpos($startdate, '-') === false) { //If we passed a timestamp
                    $startdate = date("Y-m-d", $startdate);
                }
                if (strpos($enddate, '-') === false) { //If we passed a timestamp
                    $enddate = date("Y-m-d", $enddate);
                }
                //Validate the input
                if (!$this->validateDate($startdate)) {
                    $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
                    return;
                }
                if (!$this->validateDate($enddate)) {
                    $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
                    return;
                }

                //Find out if the user exists
                $this->load->model('users_model');
                $user = $this->users_model->getUsers($employee);
                if (is_null($user)) {
                    $this->output->set_header("HTTP/1.1 404 Not Found");
                    return;
                }

                //Find out if the type exists
                $this->load->model('types_model');
                $typeCheck = $this->types_model->getTypes($type);
                if (is_null($typeCheck)) {
                    $this->output->set_header("HTTP/1.1 404 Not Found");
                    return;
                }

                $this->load->model('leaves_model');
                $result = $this->leaves_model->createLeaveByApi(
                    $startdate,
                    $enddate,
                    $status,
                    $employee,
                    $cause,
                    $startdatetype,
                    $enddatetype,
                    $duration,
                    $type,
                    $comments,
                    $document
                );
                echo json_encode($result);
            }
        }
    }

    /**
     * Get the list of employees attached to an entity
     * @param int $entityId Identifier of the entity
     * @param bool $children If TRUE, we include sub-entities, FALSE otherwise
     * @since 0.4.3
     */
    public function getListOfEmployeesInEntity(int $entityId, bool $children): void
    {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            $this->load->model('organization_model');

            //Find out if the entity exists
            $entity = $this->organization_model->getName($entityId);
            if (empty($entity)) {
                $this->output->set_header("HTTP/1.1 404 Not Found");
                return;
            }

            $children = filter_var($children, FILTER_VALIDATE_BOOLEAN);
            $result = $this->organization_model->allEmployees($entityId, $children);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($result));
        }
    }
}
