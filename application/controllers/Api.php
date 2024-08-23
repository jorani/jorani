<?php
/**
 * This controller is the entry point for the REST API
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.3.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }
define("API_HOST", dirname((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"));
define("TOKEN_URL", API_HOST . '/token');

/**
 * @OA\Server(url=API_HOST)
 * @OA\Info(
 *     title="Jorani HTTP API",
 *     description="Leave & Overtime Management System",
 *     version="1.1"
 * )
 *
 * @OA\SecurityScheme(
 *     type="oauth2",
 *     name="jorani_auth",
 *     securityScheme="jorani_auth",
 *     @OA\Flow(
 *         flow="clientCredentials",
 *         tokenUrl=TOKEN_URL,
 *         refreshUrl=TOKEN_URL,
 *         scopes={
 *             "users": "Access to users' information",
 *         }
 *     )
 * )
 *
 * This class implements a HTTP API served through an OAuth2 server.
 * In order to use it, you need to insert an OAuth2 client into the database, for example :
 * INSERT INTO oauth_clients (client_id, client_secret, redirect_uri) VALUES ("testclient", "testpass", "http://fake/");
 * where "testclient" and "testpass" are respectively the login and password.
 * Examples are provided into tests/rest folder.
 */
class Api extends CI_Controller {
    
    /**
     * OAuth2 server used by all methods in order to determine if the user is connected
     * @var OAuth2\Server Authentication server 
     */
    protected $server; 
    
    /**
     * Default constructor
     * Initializing of OAuth2 server
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {
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
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function doc() {
        $openapi = \OpenApi\Generator::scan([__FILE__]);
        $this->output
            ->set_content_type('Content-Type: application/x-yaml')
            ->set_output($openapi->toJson());
    }

    /**
     * Get a OAuth2 token
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function token() {
        $this->server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
    }

    /**
     * @OA\Get(
     *     path="/contracts/",
     *     description="Get the list of contracts",
     *     @OA\Response(
     *         response=200,
     *         description="List of contracts",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Contract")
     *         ),
     *     ),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     *
     * @OA\Get(
     *     path="/contracts/{contract_id}",
     *     description="Get a specific contract",
     *     @OA\Parameter(
     *        name="contract_id",
     *        description="Identifier of the contract",
     *        in="path",
     *        required=false,
     *        @OA\Schema(
     *           type="integer",
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contract",
     *         @OA\JsonContent(ref="#/components/schemas/Contract"),
     *     ),
     *     @OA\Response(response="404", description="Contract not found"),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     * @param int $contractId Unique identifier of a contract
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function contracts($contractId = 0) {
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
     * @OA\Get(
     *     path="/entitleddayscontract/{contract_id}",
     *     description="Get the list of entitled days for a given contract",
     *     @OA\Parameter(
     *        name="contract_id",
     *        description="Identifier of the contract",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *           type="array",
     *           type="integer",
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of entitled days",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Entitledday")
     *         ),
     *     ),
     *     @OA\Response(response="404", description="Contract not found"),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     * @param int $contractId Unique identifier of an contract
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function entitleddayscontract($contractId) {
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
     * @OA\Post(
     *     path="/addentitleddayscontract/{contract_id}",
     *     description="Add or remove entitlement on a contract",
     *     @OA\Parameter(
     *        name="contract_id",
     *        description="Identifier of the contract",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *           type="integer",
     *        )
     *     ),
     *     @OA\RequestBody(
     *        description="Entitlement to be added to a contract",
     *        @OA\MediaType(mediaType="multipart/form-data",
     *           @OA\Schema(
     *              @OA\Property(property="startdate", type="string", format="date", description="Left boundary of the credit validity (YYYY-MM-DD)"),
     *              @OA\Property(property="enddate", type="string", format="date", description="Right boundary of the credit validity. Duration cannot exceed one year (YYYY-MM-DD)"),
     *              @OA\Property(property="type", type="integer", description="Leave type"),
     *              @OA\Property(property="days", type="number", description="Number of days (can be negative so as to deduct/adjust entitlement)"),
     *              @OA\Property(property="description", type="string", description="Description of a credit / debit (entitlement / adjustment)"),
     *           )
     *       )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The entitlement was added on the contract",
     *         @OA\JsonContent(
     *            @OA\Schema(
     *               @OA\Property(property="id", type="integer", description="Unique identifier of an entitlement")
     *            )
     *         ),
     *     ),
     *     @OA\Response(response="404", description="Contract not found"),
     *     @OA\Response(response="422", description="Invalid parameters"),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     * Add entitled days to a given contract
     * @param int $contractId Unique identifier of an contract
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function addentitleddayscontract($contractId) {
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
     * @OA\Get(
     *     path="/entitleddaysemployee/{employee_id}",
     *     description="Get the list of entitled days for a given employee",
     *     @OA\Parameter(
     *        name="employee_id",
     *        description="Identifier of the employee",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *           type="integer",
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of entitled days",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Entitledday")
     *         ),
     *     ),
     *     @OA\Response(response="404", description="Employee not found"),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     * Get the list of entitled days for a given employee
     * @param int $employeeId Unique identifier of an employee
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function entitleddaysemployee($employeeId) {
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
     * @OA\Post(
     *     path="/addentitleddaysemployee/{employee_id}",
     *     description="Give entitlement to an employee",
     *     @OA\Parameter(
     *        name="employee_id",
     *        description="Identifier of the employee",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *           type="integer",
     *        )
     *     ),
     *     @OA\RequestBody(
     *        description="Entitlement to be added to an employee",
     *        @OA\MediaType(mediaType="multipart/form-data",
     *           @OA\Schema(
     *              @OA\Property(property="startdate", type="string", format="date", description="Left boundary of the credit validity (YYYY-MM-DD)"),
     *              @OA\Property(property="enddate", type="string", format="date", description="Right boundary of the credit validity. Duration cannot exceed one year (YYYY-MM-DD)"),
     *              @OA\Property(property="type", type="integer", description="Leave type"),
     *              @OA\Property(property="days", type="number", description="Number of days (can be negative so as to deduct/adjust entitlement)"),
     *              @OA\Property(property="description", type="string", description="Description of a credit / debit (entitlement / adjustment)"),
     *           )
     *       )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The entitlement was added on the employee",
     *         @OA\JsonContent(
     *            @OA\Schema(
     *               @OA\Property(property="id", type="integer", description="Unique identifier of an entitlement")
     *            )
     *         ),
     *     ),
     *     @OA\Response(response="404", description="Employee not found"),
     *     @OA\Response(response="422", description="Invalid parameters"),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     * Add entitled days to a given employee
     * @param int $id Unique identifier of an employee
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function addentitleddaysemployee($employeeId) {
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
     * @OA\Get(
     *     path="/leavessummary/{employee_id}",
     *     @OA\Parameter(
     *        name="employee_id",
     *        description="Identifier of the employee",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *           type="integer",
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Get the leaves counter of a given employee",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/LeavesSummary")
     *         ),
     *     ),
     *     @OA\Response(response="404", description="Employee not found"),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     *
     * 
     * @OA\Get(
     *     path="/leavessummary/{employee_id}/{refTmp}",
     *     description="Get the leaves counter of a given employee",
     *     @OA\Parameter(
     *        name="employee_id",
     *        description="Identifier of the employee",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *           type="integer",
     *        )
     *     ),
     *     @OA\Parameter(
     *        name="refTmp",
     *        description="Reference date (YYYY-MM-DD or Unix timestamp)",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *           type="string",
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Leaves counter",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/LeavesSummary")
     *         ),
     *     ),
     *     @OA\Response(response="404", description="Employee not found"),
     *     @OA\Response(response="422", description="Invalid parameters"),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     * Get the leaves counter of a given employee
     * @param int $employeeId Unique identifier of an employee
     * @param string $refTmp tmp of the Date of reference (or current date if NULL)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function leavessummary($employeeId, $refTmp = NULL) {
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
                if (strpos($refTmp, '-') === false) { //If we passed a timestamp
                    $refDate = date("Y-m-d", $refTmp);
                }
            } else {
                $refDate = date("Y-m-d");
            }
            if (!$this->validateDate($refDate)) {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
                return;
            }

            $result = $this->leaves_model->getLeaveBalanceForEmployee($employeeId, FALSE, $refDate);
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
     * @OA\Get(
     *     path="/leaves/{start_date}/{end_date}",
     *     description="Get all the leave requests stored into the database",
     *     @OA\Parameter(
     *        name="start_date",
     *        description="Start date (YYYY-MM-DD or Unix timestamp)",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *           type="string", format="date",
     *        )
     *     ),
     *     @OA\Parameter(
     *        name="end_date",
     *        description="End date (YYYY-MM-DD or Unix timestamp)",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *           type="string", format="date",
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of leave requests",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Leave")
     *         ),
     *     ),
     *     @OA\Response(response="422", description="Invalid parameters"),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     *  Get all the leaves requests
     * @param string $startDate tmp or string (YYYY-MM-DD) of the Start Date
     * @param string $endDate tmp or string (YYYY-MM-DD) of the End Date
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function leavesInRange($startDate, $endDate) {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            //Convert the input timestamp if needed
            if (strpos($startDate, '-') === false) { //If we passed a timestamp
                $startDate = date("Y-m-d", $startDate);
            }
            if (strpos($endDate, '-') === false) { //If we passed a timestamp
                $endDate = date("Y-m-d", $endDate);
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
     * @OA\Get(
     *     path="/leavetypes/",
     *     description="Get the list of leave types",
     *     @OA\Response(
     *         response=200,
     *         description="List of leave types",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/LeaveType")
     *         ),
     *     ),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     * Get the list of leave types (useful to get the labels into a cache)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function leavetypes() {
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
     * @OA\Get(
     *     path="/acceptleave/{leave_id}",
     *     description="Accept a leave request",
     *     @OA\Parameter(
     *        name="leave_id",
     *        description="Identifier of the leave request",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *           type="integer",
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Leave request accepted",
     *     ),
     *     @OA\Response(response="404", description="Leave request not found"),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     * Accept a leave request
     * @param int $leaveId identifier of the leave request to accept
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     * @since 0.4.4
     */
    public function acceptleave($leaveId) {
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
     * @OA\Get(
     *     path="/rejectleave/{leave_id}",
     *     description="Reject a leave request",
     *     @OA\Parameter(
     *        name="leave_id",
     *        description="Identifier of the leave request",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *           type="integer",
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Leave request rejected",
     *     ),
     *     @OA\Response(response="404", description="Leave request not found"),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     * Reject a leave request
     * @param int $leaveId identifier of leave request to reject
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     * @since 0.4.4
     */
    public function rejectleave($leaveId) {
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
     * @OA\Get(
     *     path="/positions/",
     *     description="Get the list of positions",
     *     @OA\Response(
     *         response=200,
     *         description="List of positions",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Position")
     *         ),
     *     ),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     * Get the list of positions (useful to get the labels into a cache)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function positions() {
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
     * @OA\Get(
     *     path="/userdepartment/{employee_id}",
     *     description="Get the department details of a given employee",
     *     @OA\Parameter(
     *        name="employee_id",
     *        description="Identifier of the employee",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *           type="integer",
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Department of the employee",
     *         @OA\JsonContent(ref="#/components/schemas/Department")
     *     ),
     *     @OA\Response(response="404", description="Employee not found"),
     *     @OA\Response(response="422", description="Invalid parameters"),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     * Get the department details of a given employee
     * @param int $employeeId Identifier of an employee (attached to an entity)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function userdepartment($employeeId) {
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
     * @OA\Get(
     *     path="/users/",
     *     description="Get the list of users",
     *     @OA\Response(
     *         response=200,
     *         description="List of users",
     *         @OA\JsonContent(
     *             @OA\Items(ref="#/components/schemas/User")
     *         ),
     *     ),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     */

    /**
     * @OA\Get(
     *     path="/users/{user_id}",
     *     description="Get a specific user",
     *     @OA\Parameter(
     *        name="user_id",
     *        description="Identifier of the user",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *           type="integer",
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User",
     *         @OA\JsonContent(ref="#/components/schemas/User"),
     *     ),
     *     @OA\Response(response="404", description="User not found"),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     * Get the list of users or a specific user. 
     * The password, picture, and random_hash fields are removed from the result set
     * @param int $id Unique identifier of a user
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function users($id = 0) {
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
        } else {
            $this->load->model('users_model');
            $result = $this->users_model->getUsers($id);
            if ($id === 0) {
                foreach($result as $k1=>$q) {
                  foreach($q as $k2=>$r) {
                    if($k2 == 'password') {
                      unset($result[$k1][$k2]);
                    }
                    if($k2 == 'random_hash') {
                        unset($result[$k1][$k2]);
                    }
                    if($k2 == 'picture') {
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
     * @OA\Get(
     *     path="/userleaves/{employee_id}",
     *     description="Get all the leave requests of an employee",
     *     @OA\Parameter(
     *        name="employee_id",
     *        description="Identifier of the employee",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *           type="integer",
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of leave requests",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Leave")
     *         ),
     *     ),
     *     @OA\Response(response="404", description="Employee not found"),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     * Get the list of leaves for a given employee
     * @param int $employeeId Unique identifier of an employee
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function userleaves($employeeId) {
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
     * @OA\Get(
     *     path="/userextras/{employee_id}",
     *     description="Get all the overtime requests of an employee",
     *     @OA\Parameter(
     *        name="employee_id",
     *        description="Identifier of the employee",
     *        in="path",
     *        required=false,
     *        @OA\Schema(
     *           type="integer",
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of overtime requests",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Overtime")
     *         ),
     *     ),
     *     @OA\Response(response="404", description="Employee not found"),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     *  Get the list of extra for a given employee
     * @param int $employeeId Unique identifier of an employee
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function userextras($employeeId) {
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
     * @OA\Get(
     *     path="/monthlypresence/{employee_id}/{month}/{year}",
     *     description="Get the monthly presence report for a given employee",
     *     @OA\Parameter(
     *        name="employee_id",
     *        description="Identifier of the employee",
     *        in="path",
     *        required=false,
     *        @OA\Schema(
     *           type="integer",
     *        )
     *     ),
     *     @OA\Parameter(
     *        name="month",
     *        description="Month number (1-12)",
     *        in="path",
     *        required=false,
     *        @OA\Schema(
     *           type="integer",
     *        )
     *     ),
     *     @OA\Parameter(
     *        name="year",
     *        description="Full Year number (e.g. 2019)",
     *        in="path",
     *        required=false,
     *        @OA\Schema(
     *           type="integer",
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Monthly presence report",
     *         @OA\JsonContent(ref="#/components/schemas/MonthlyPresence"),
     *     ),
     *     @OA\Response(response="404", description="Employee not found"),
     *     @OA\Response(response="422", description="No Contract is attached to employee"),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     * Get the monthly presence stats for a given employee
     * @param int $employeeId Unique identifier of an employee
     * @param int $month Month number [1-12]
     * @param int $year Year number (XXXX)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     * @since 0.4.0
     */
    public function monthlypresence($employeeId, $month, $year) {
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
     * @OA\Delete(
     *     path="/users/{user_id}",
     *     description="Delete a user",
     *     @OA\Parameter(
     *        name="user_id",
     *        description="Identifier of the user",
     *        in="path",
     *        required=false,
     *        @OA\Schema(
     *           type="integer",
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User is deleted",
     *     ),
     *     @OA\Response(response="404", description="User not found"),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     * Delete a user from the database
     * This is not recommended. Consider moving it into an archive entity of your organization
     * @param int $userId Unique identifier of an employee
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     * @since 0.4.0
     */
    public function deleteuser($userId) {
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
     * @OA\Patch(
     *     path="/users/{user_id}",
     *     description="Update a user",
     *     @OA\Parameter(
     *        name="user_id",
     *        description="Identifier of the user",
     *        in="path",
     *        required=false,
     *        @OA\Schema(
     *           type="integer",
     *        )
     *     ),
     *     @OA\RequestBody(
     *        description="Properties of user",
     *        @OA\MediaType(mediaType="multipart/x-www-form-urlencoded",
     *           @OA\Schema(
     *              @OA\Property(property="firstname", type="string", description="First name"),
     *              @OA\Property(property="lastname", type="string", description="Last name"),
     *              @OA\Property(property="login", type="string", description="Identfier used to login (can be an email address)"),
     *              @OA\Property(property="email", type="string", description="Email address"),
     *              @OA\Property(property="role", type="integer", description="Role of the employee (binary mask). See table roles."),
     *              @OA\Property(property="manager", type="integer", description="Employee validating the requests of the employee"),
     *              @OA\Property(property="country", type="integer", description="Country code (for later use)"),
     *              @OA\Property(property="organization", type="integer", description="Entity where the employee has a position"),
     *              @OA\Property(property="contract", type="integer", description="Contract of the employee"),
     *              @OA\Property(property="position", type="integer", description="Position of the employee"),
     *              @OA\Property(property="datehired", type="string", description="Date hired / Started"),
     *              @OA\Property(property="identifier", type="string", description="Internal/company identifier"),
     *              @OA\Property(property="language", type="string", description="Language ISO code"),
     *              @OA\Property(property="ldap_path", type="string", description="LDAP Path for complex authentication schemes"),
     *              @OA\Property(property="active", type="boolean", description="Is user active"),
     *              @OA\Property(property="timezone", type="string", description="Timezone of user"),
     *              @OA\Property(property="calendar", type="string", description="External Calendar address"),
     *              @OA\Property(property="user_properties", type="string", description="External Calendar address"),
     *              @OA\Property(property="picture", type="string", description="Profile picture (Base64)"),
     *           )
     *       )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User is updated",
     *     ),
     *     @OA\Response(response="404", description="User not found"),
     *     @OA\Response(response="422", description="Nothing to update or wrong media type"),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     * Update a user
     * Updated fields are passed by POST parameters or in the input stream for PATCH
     * Note that for PATCH method, you need to send a compliant content type (multipart/x-www-form-urlencoded)
     * @param int $userId Unique identifier of an employee
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     * @since 0.4.0
     */
    public function updateuser($userId) {
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
     * @OA\Post(
     *     path="/users/{send_email}",
     *     description="Create a new user. Return the ID of the new user.",
     *     @OA\Parameter(
     *        name="send_email",
     *        description="Send an email to the new user (TRUE/FALSE)",
     *        in="path",
     *        required=false,
     *        @OA\Schema(
     *           type="boolean",
     *        )
     *     ),
     *     @OA\RequestBody(
     *        description="Properties of user",
     *        @OA\MediaType(mediaType="multipart/form-data",
     *           @OA\Schema(
     *              @OA\Property(property="firstname", type="string", description="First name"),
     *              @OA\Property(property="lastname", type="string", description="Last name"),
     *              @OA\Property(property="login", type="string", description="Identfier used to login (can be an email address)"),
     *              @OA\Property(property="email", type="string", description="Email address"),
     *              @OA\Property(property="role", type="integer", description="Role of the employee (binary mask). See table roles."),
     *              @OA\Property(property="manager", type="integer", description="Employee validating the requests of the employee"),
     *              @OA\Property(property="country", type="integer", description="Country code (for later use)"),
     *              @OA\Property(property="organization", type="integer", description="Entity where the employee has a position"),
     *              @OA\Property(property="contract", type="integer", description="Contract of the employee"),
     *              @OA\Property(property="position", type="integer", description="Position of the employee"),
     *              @OA\Property(property="datehired", type="string", description="Date hired / Started"),
     *              @OA\Property(property="identifier", type="string", description="Internal/company identifier"),
     *              @OA\Property(property="language", type="string", description="Language ISO code"),
     *              @OA\Property(property="ldap_path", type="string", description="LDAP Path for complex authentication schemes"),
     *              @OA\Property(property="active", type="boolean", description="Is user active"),
     *              @OA\Property(property="timezone", type="string", description="Timezone of user"),
     *              @OA\Property(property="calendar", type="string", description="External Calendar address"),
     *              @OA\Property(property="user_properties", type="string", description="External Calendar address"),
     *              @OA\Property(property="picture", type="string", description="Profile picture (Base64)"),
     *           )
     *       )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User is created",
     *     ),
     *     @OA\Response(response="422", description="Missing mandatory fields or login is not available"),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     * Create an employee (fields are passed by POST parameters)
     * Returns the new inserted id
     * @param bool $sendEmail Send an Email to the new employee (FALSE by default)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     * @since 0.4.0
     */
    public function createuser($sendEmail = FALSE) {
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
                    $result = $this->users_model->insertUserByApi($firstname, $lastname, $login, $email, $password, $role,
                        $manager, $organization, $contract, $position, $datehired, $identifier, $language, $timezone,
                        $ldap_path, TRUE, $country, $calendar);
                    
                    if($sendEmail == TRUE) {
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

                        if (($this->config->item('from_mail') !== NULL) && ($this->config->item('from_name') !== NULL) ) {
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
     * @OA\Post(
     *     path="/leaves/",
     *     description="Create a new leave request. Return the ID of the new user.",
     *     @OA\RequestBody(
     *        description="Leave Request",
     *        @OA\MediaType(mediaType="multipart/form-data",
     *           @OA\Schema(
     *              @OA\Property(property="startdate", type="string", format="date", description="Start date of the leave request"),
     *              @OA\Property(property="enddate", type="string", format="date", description="End date of the leave request"),
     *              @OA\Property(property="status", type="integer", description="Identifier of the status of the leave request (Requested, Accepted, etc.). See status table."),
     *              @OA\Property(property="employee", type="integer", description="Employee requesting the leave request"),
     *              @OA\Property(property="cause", type="string", description="Reason of the leave request"),
     *              @OA\Property(property="startdatetype", type="string", pattern="^(Morning|Afternoon)$", description="Morning/Afternoon"),
     *              @OA\Property(property="enddatetype", type="string", pattern="^(Morning|Afternoon)$", description="Morning/Afternoon"),
     *              @OA\Property(property="duration", type="integer", description="Length of the leave request"),
     *              @OA\Property(property="type", type="integer", description="Identifier of the type of the leave request (Paid, Sick, etc.). See type table.'"),
     *              @OA\Property(property="comments", type="string", description="Comments on leave request (JSON)"),
     *              @OA\Property(property="document", type="string", description="Optional supporting document"),
     *           )
     *       )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Leave is created",
     *     ),
     *     @OA\Response(response="422", description="Missing mandatory fields"),
     *     @OA\Response(response="404", description="Employee or leave type not found"),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     * Create a leave request (fields are passed by POST parameters).
     * This function doesn't send e-mails and it is used for imposed leaves
     * Returns the new inserted id.
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     * @since 0.4.0
     */
    public function createleave() {
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
            if (empty($startdate) || empty($enddate) || empty($status) || empty($employee) 
                    || empty($startdatetype) || empty($enddatetype) || empty($duration) || empty($type)) {
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
                $result = $this->leaves_model->createLeaveByApi($startdate, $enddate, $status, $employee, $cause,
                            $startdatetype, $enddatetype, $duration, $type, $comments, $document);
                echo json_encode($result);
            }
        }
    }
    
    /**
     * @OA\Get(
     *     path="/getListOfEmployeesInEntity/{entity_id}/{include_children}",
     *     description="Get the list of employees attached to an entity",
     *     @OA\Parameter(
     *        name="entity_id",
     *        description="Identifier of the entity (e.g. department)",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *           type="integer",
     *        )
     *     ),
     *     @OA\Parameter(
     *        name="include_children",
     *        description="Include children entities",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *           type="boolean",
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of users attached to the entity",
     *         @OA\JsonContent(
     *            type="array",
     *            @OA\Items(ref="#/components/schemas/User")
     *         ),
     *     ),
     *     @OA\Response(response="404", description="Entity not found"),
     *     @OA\Response(response="401", description="Unauthorized or not authenticated"),
     *     security={
     *         {"jorani_auth": {}}
     *     }
     * )
     * Get the list of employees attached to an entity
     * @param int $entityId Identifier of the entity
     * @param bool $children If TRUE, we include sub-entities, FALSE otherwise
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     * @since 0.4.3
     */
    public function getListOfEmployeesInEntity($entityId, $children) {
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

    /**
     * Get the list of users with all their attributes
     * Requires scope users (see tests/rest/api3.php)
     * Not documented with OpenAPI, might be deprecated in a near future
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     * @since 0.6.0
     */
    public function usersExt() {
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
    private function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    
}


/**
 * @OA\Schema(
 *   schema="Contract",
 *   description="A contract groups employees having the same days off and entitlement rules",
 *   @OA\Property(property="id", type="integer", description="Unique identifier of a contract"),
 *   @OA\Property(property="name", type="string", description="Name of the contract"),
 *   @OA\Property(property="startentdate", type="string", pattern="^\d{2}/\d{2}$", description="Day and month numbers of the left boundary"),
 *   @OA\Property(property="endentdate", type="string", pattern="^\d{2}/\d{2}$", description="Day and month numbers of the right boundary"),
 *   @OA\Property(property="weekly_duration", type="string", description="Approximate duration of work per week (in minutes)"),
 *   @OA\Property(property="daily_duration", type="string", description="Approximate duration of work per day and (in minutes)"),
 *   @OA\Property(property="default_leave_type", type="string", description="default leave type for the contract (overwrite default type set in config file)."),
 * )
 */
class Contract {}

/**
 * @OA\Schema(
 *   schema="Entitledday",
 *   description="Add or substract entitlement on employees or contracts (can be the result of an OT)",
 *   @OA\Property(property="id", type="integer", description="Unique identifier of an entitlement"),
 *   @OA\Property(property="contract", type="integer", description="If entitlement is credited to a contract, Id of contract"),
 *   @OA\Property(property="employee", type="integer", description="If entitlement is credited to an employee, Id of employee"),
 *   @OA\Property(property="overtime", type="integer", description="Optional Link to an overtime request, if the credit is due to an OT"),
 *   @OA\Property(property="startdate", type="string", format="date", description="Left boundary of the credit validity (YYYY-MM-DD)"),
 *   @OA\Property(property="enddate", type="string", format="date", description="Right boundary of the credit validity. Duration cannot exceed one year (YYYY-MM-DD)"),
 *   @OA\Property(property="type", type="integer", description="Leave type"),
 *   @OA\Property(property="days", type="number", description="Number of days (can be negative so as to deduct/adjust entitlement)"),
 *   @OA\Property(property="description", type="string", description="Description of a credit / debit (entitlement / adjustment)"),
 * )
 */
class Entitledday {}

/**
 * @OA\Schema(
 *   schema="LeavesSummary",
 *   description="Leaves counter of a given employee",
 *   type="array",
 *   @OA\Items(
 *      @OA\Property(property="type", type="string", description="Leave type"),
 *      @OA\Property(
 *         property="counters",
 *         type="array",
 *         @OA\Items(
 *            @OA\Property(property="entitled", type="number", description="Ent"),
 *            @OA\Property(property="taken", type="number", description="Taken"),
 *            @OA\Property(property="left", type="string", description="Taken"),
 *            @OA\Property(property="misc", type="string", description="Misc")
 *         )
 *      )
 *   )
 * )
 */
class LeavesSummary {}

/**
 * @OA\Schema(
 *   schema="Leave",
 *   description="Leave request",
 *   @OA\Property(property="id", type="integer", description="Unique identifier of a leave request"),
 *   @OA\Property(property="startdate", type="string", format="date", description="Start date of the leave request"),
 *   @OA\Property(property="enddate", type="string", format="date", description="End date of the leave request"),
 *   @OA\Property(property="status", type="integer", description="Identifier of the status of the leave request (Requested, Accepted, etc.). See status table."),
 *   @OA\Property(property="employee", type="integer", description="Employee requesting the leave request"),
 *   @OA\Property(property="cause", type="string", description="Reason of the leave request"),
 *   @OA\Property(property="startdatetype", type="string", description="Morning/Afternoon"),
 *   @OA\Property(property="enddatetype", type="string", description="Morning/Afternoon"),
 *   @OA\Property(property="duration", type="integer", description="Length of the leave request"),
 *   @OA\Property(property="type", type="integer", description="Identifier of the type of the leave request (Paid, Sick, etc.). See type table.'"),
 *   @OA\Property(
 *      property="comments",
 *      description="Comments on leave request (JSON)",
 *      type="array",
 *      @OA\Items(
 *         @OA\Property(property="type", type="string", description="Type of comment (change or comment)"),
 *         @OA\Property(property="status_number", type="number", description="If comment of type change, new status id"),
 *         @OA\Property(property="date", format="date", type="string", description="Date of the comment"),
 *         @OA\Property(property="author", type="string", description="Identifier of the employee commenting the request"),
 *         @OA\Property(property="value", type="string", description="If comment of type comment, the content of comment")
 *      )
 *   ),
 *   @OA\Property(property="document", type="string", description="Optional supporting document"),
 * )
 */
class Leave {}

/**
 * @OA\Schema(
 *   schema="Position",
 *   description="Job Position",
 *   @OA\Property(property="id", type="integer", description="Unique identifier of the position"),
 *   @OA\Property(property="name", type="string", description="Name of the position"),
 *   @OA\Property(property="description", type="string", description="Description of the position"),
 * )
 */
class Position {}

/**
 * @OA\Schema(
 *   schema="LeaveType",
 *   description="Leave type",
 *   @OA\Property(property="id", type="integer", description="Unique identifier of the type"),
 *   @OA\Property(property="name", type="string", description="Name of the leave type"),
 *   @OA\Property(property="acronym", type="string", description="Acronym of the leave type"),
 *   @OA\Property(property="deduct_days_off", type="boolean", description="Deduct days off when computing the balance of the leave type"),
 * )
 */
class LeaveType {}

/**
 * @OA\Schema(
 *   schema="Department",
 *   description="Department in the Organization",
 *   @OA\Property(property="id", type="integer", description="Unique identifier of the department"),
 *   @OA\Property(property="name", type="string", description="Name of the department"),
 *   @OA\Property(property="parent_id", type="integer", description="Parent department (or -1 if root)"),
 *   @OA\Property(property="supervisor", type="integer", description="This user will receive a copy of accepted and rejected leave requests"),
 * )
 */
class Department {}

/**
 * @OA\Schema(
 *   schema="User",
 *   description="User/Employee",
 *   @OA\Property(property="id", type="integer", description="Unique identifier of the user"),
 *   @OA\Property(property="firstname", type="string", description="First name"),
 *   @OA\Property(property="lastname", type="string", description="Last name"),
 *   @OA\Property(property="login", type="string", description="Identfier used to login (can be an email address)"),
 *   @OA\Property(property="email", type="string", description="Email address"),
 *   @OA\Property(property="role", type="integer", description="Role of the employee (binary mask). See table roles."),
 *   @OA\Property(property="manager", type="integer", description="Employee validating the requests of the employee"),
 *   @OA\Property(property="country", type="integer", description="Country code (for later use)"),
 *   @OA\Property(property="organization", type="integer", description="Entity where the employee has a position"),
 *   @OA\Property(property="contract", type="integer", description="Contract of the employee"),
 *   @OA\Property(property="position", type="integer", description="Position of the employee"),
 *   @OA\Property(property="datehired", type="string", description="Date hired / Started"),
 *   @OA\Property(property="identifier", type="string", description="Internal/company identifier"),
 *   @OA\Property(property="language", type="string", description="Language ISO code"),
 *   @OA\Property(property="ldap_path", type="string", description="LDAP Path for complex authentication schemes"),
 *   @OA\Property(property="active", type="boolean", description="Is user active"),
 *   @OA\Property(property="timezone", type="string", description="Timezone of user"),
 *   @OA\Property(property="calendar", type="string", description="External Calendar address"),
 *   @OA\Property(property="user_properties", type="string", description="External Calendar address"),
 * )
 */
class User {}

/**
 * @OA\Schema(
 *   schema="Overtime",
 *   description="Overtime worked (extra time request)",
 *   @OA\Property(property="id", type="integer", description="Unique identifier of the overtime request"),
 *   @OA\Property(property="employee", type="integer", description="Employee requesting the OT"),
 *   @OA\Property(property="date", format="date", type="string", description="Date when the OT was done (YYYY-MM-DD)"),
 *   @OA\Property(property="duration", type="number", description="Duration of the OT"),
 *   @OA\Property(property="cause", type="string", description="Reason why the OT was done"),
 *   @OA\Property(property="status", type="integer", description="Status of OT (Planned, Requested, Accepted, Rejected)"),
 * )
 */
class Overtime {}

/**
 * @OA\Schema(
 *   schema="MonthlyPresence",
 *   description="Overtime worked (extra time request)",
 *   @OA\Property(property="leaves", type="number", description="Number of leave taken"),
 *   @OA\Property(property="dayoffs", type="number", description="Number of non working days"),
 *   @OA\Property(property="total", type="number", description="Total number of days in the month"),
 *   @OA\Property(property="start", type="string", description="First day of the month (YYYY-MM-DD)"),
 *   @OA\Property(property="end", type="string", description="Last day of the month (YYYY-MM-DD)"),
 *   @OA\Property(property="open", type="number", description="Number of opened days (Total - Days off)"),
 *   @OA\Property(property="work", type="number", description="Number of worked days (Total - Days off - Leaves)"),
 * )
 */
class MonthlyPresence {}
