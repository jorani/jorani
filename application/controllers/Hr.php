<?php
/**
 * This controller serves all the actions performed by human resources department
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class serves all the actions performed by human resources department.
 * There is a distinction with Admin controller which contain technical actions on users.
 * HR controller deals with employees.
 */
class Hr extends CI_Controller {

    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {
        parent::__construct();
        setUserContext($this);
        $this->load->model('leaves_model');
        $this->lang->load('hr', $this->language);
        $this->lang->load('global', $this->language);
    }

    /**
     * Display the list of all employees
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function employees() {
        $this->auth->checkIfOperationIsAllowed('list_employees');
        $data = getUserContext($this);
        $this->lang->load('datatable', $this->language);
        $this->lang->load('entitleddays', $this->language);
        $this->lang->load('leaves', $this->language);
        $data['title'] = lang('hr_employees_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_list_employees');
        $this->load->model('contracts_model');
        $data['contracts'] = $this->contracts_model->getContracts();
        $this->load->model('types_model');
        $data['types'] = $this->types_model->getTypes();
        $data['flash_partial_view'] = $this->load->view('templates/flash', $data, TRUE);
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('hr/employees', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Returns the list of the employees attached to an entity
     * Prints the table content in a JSON format expected by jQuery Datatable
     * @param int $id optional id of the entity, all entities if 0
     * @param bool $children TRUE : include sub entities, FALSE otherwise
     * @param string $filterActive "all"; "active" (only), or "inactive" (only)
     * @param string $criterion1 "lesser" or "greater" (optional)
     * @param string $date1 Date Hired (optional)
     * @param string $criterion2 "lesser" or "greater" (optional)
     * @param string $date2 Date Hired (optional)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function employeesOfEntity($id = 0, $children = TRUE, $filterActive = "all",
            $criterion1 = NULL, $date1 = NULL, $criterion2 = NULL, $date2 = NULL) {
        if ($this->auth->isAllowed('list_employees') == FALSE) {
            return $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $children = filter_var($children, FILTER_VALIDATE_BOOLEAN);
            $this->load->model('users_model');
            $employees = $this->users_model->employeesOfEntity($id, $children, $filterActive,
                    $criterion1, $date1, $criterion2, $date2);

            //Prepare an object that will be encoded in JSON
            $msg = new \stdClass();
            $msg->draw = 1;
            $msg->recordsTotal = count($employees);
            $msg->recordsFiltered = count($employees);
            $msg->data = array();

            foreach ($employees as $employee) {
                $date = new DateTime(is_null($employee->datehired)?"":$employee->datehired);
                $tmpDate = $date->getTimestamp();
                $displayDate = $date->format(lang('global_date_format'));

                $row = new \stdClass();
                $row->DT_RowId = $employee->id;
                $row->id = $employee->id;
                $row->firstname = $employee->firstname;
                $row->lastname = $employee->lastname;
                $row->email = $employee->email;
                $row->entity = $employee->entity;
                $row->identifier = $employee->identifier;
                $row->contract = $employee->contract;
                $row->datehired = new \stdClass();
                $row->datehired->display = $displayDate;
                $row->datehired->timestamp = $tmpDate;
                $row->position = $employee->position;
                $row->manager_name = $employee->manager_name;
                $msg->data[] = $row;
            }
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($msg));
        }
    }

    /**
     * Ajax endpoint: edit the manager for a list of employees
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function editManager() {
        header("Content-Type: application/json");
        if ($this->auth->isAllowed('list_employees') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $managerId = $this->input->post('manager', TRUE);
            $employees = $this->input->post('employees', TRUE);
            $objectEmployees = json_decode($employees);
            $this->load->model('users_model');
            $result = $this->users_model->updateManagerForUserList($managerId, $objectEmployees);
            echo $result;
        }
    }

    /**
     * Ajax endpoint: edit the entity for a list of employees
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function editEntity() {
        header("Content-Type: application/json");
        if ($this->auth->isAllowed('list_employees') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $entityId = $this->input->post('entity', TRUE);
            $employees = $this->input->post('employees', TRUE);
            $objectEmployees = json_decode($employees);
            $this->load->model('users_model');
            $result = $this->users_model->updateEntityForUserList($entityId, $objectEmployees);
            echo $result;
        }
    }

    /**
     * Ajax endpoint: edit the contract for a list of employees
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function editContract() {
        header("Content-Type: application/json");
        if ($this->auth->isAllowed('list_employees') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $contractId = $this->input->post('contract', TRUE);
            $employees = $this->input->post('employees', TRUE);
            $objectEmployees = json_decode($employees);
            $this->load->model('users_model');
            $result = $this->users_model->updateContractForUserList($contractId, $objectEmployees);
            echo $result;
        }
    }

    /**
     * Ajax endpoint: create a leave request for a list of employees
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function createLeaveRequest() {
        header("Content-Type: application/json");
        if ($this->auth->isAllowed('list_employees') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $type = $this->input->post('type', TRUE);
            $duration = $this->input->post('duration', TRUE);
            $startdate = $this->input->post('startdate', TRUE);
            $enddate = $this->input->post('enddate', TRUE);
            $startdatetype = $this->input->post('startdatetype', TRUE);
            $enddatetype = $this->input->post('enddatetype', TRUE);
            $cause = $this->input->post('cause', TRUE);
            $status = $this->input->post('status', TRUE);
            $employees = $this->input->post('employees', TRUE);
            $objectEmployees = json_decode($employees);
            $this->load->model('leaves_model');
            $result = $this->leaves_model->createRequestForUserList($type, $duration,
                    $startdate, $enddate, $startdatetype, $enddatetype, $cause, $status,
                    $objectEmployees);
            echo $result;
        }
    }

    /**
     * Ajax endpoint : insert into the list of entitled days for a list of employees
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function editEntitlements() {
        if ($this->auth->isAllowed('entitleddays_user') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $employees = $this->input->post('employees', TRUE);
            $startdate = $this->input->post('startdate', TRUE);
            $enddate = $this->input->post('enddate', TRUE);
            $days = $this->input->post('days', TRUE);
            $type = $this->input->post('type', TRUE);
            $description = sanitize($this->input->post('description', TRUE));
            if (isset($startdate) && isset($enddate) && isset($days) && isset($type) && isset($employees)) {
                $this->load->model('entitleddays_model');
                $objectEmployees = json_decode($employees);
                foreach ($objectEmployees as $user_id) {
                    $id = $this->entitleddays_model->addEntitledDaysToEmployee((int) $user_id, $startdate, $enddate, $days, $type, $description);
                    echo $id . ',';
                }
            } else {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
            }
        }
    }

    /**
     * Display the list of leaves for a given employee
     * @param int $id employee id
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function leaves($id) {
        $this->auth->checkIfOperationIsAllowed('list_employees');
        $data = getUserContext($this);
        $this->lang->load('leaves', $this->language);
        $this->load->model('users_model');
        $data['name'] = $this->users_model->getName($id);
        //Check if exists
        if ($data['name'] == "") {
            redirect('notfound');
        }
        $this->lang->load('datatable', $this->language);
        $data['title'] = lang('hr_leaves_title');
        $data['user_id'] = $id;
        $this->load->model('leaves_model');
        $this->load->model('types_model');
        $data['types'] = $this->types_model->getTypes();
        $data['leaves'] = $this->leaves_model->getLeavesOfEmployee($id);
        if ($this->config->item('enable_history') === TRUE) {
            $this->load->model('history_model');
            $data['deletedLeaves'] = $this->history_model->getDeletedLeaveRequests($id);
        }
        $data['flash_partial_view'] = $this->load->view('templates/flash', $data, TRUE);
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('hr/leaves', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Display the list of overtime requests for a given employee
     * @param int $id employee id
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function overtime($id) {
        $this->auth->checkIfOperationIsAllowed('list_employees');
        $data = getUserContext($this);
        $this->load->model('users_model');
        $data['name'] = $this->users_model->getName($id);
        //Check if exists
        if ($data['name'] == "") {
            redirect('notfound');
        }
        $this->lang->load('datatable', $this->language);
        $data['title'] = lang('hr_overtime_title');
        $data['user_id'] = $id;
        $this->load->model('overtime_model');
        $data['extras'] = $this->overtime_model->getExtrasOfEmployee($id);
        $data['flash_partial_view'] = $this->load->view('templates/flash', $data, TRUE);
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('hr/overtime', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Display the list of teleworks for a given employee
     * @param int $id employee id
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function teleworks($id) {																										 
        $this->auth->checkIfOperationIsAllowed('list_employees');
        $data = getUserContext($this);							  
        $this->lang->load('teleworks', $this->language);
        $this->load->model('users_model');
        $data['name'] = $this->users_model->getName($id);
        //Check if exists
        if ($data['name'] == "") {
            redirect('notfound');
        }
        $this->lang->load('datatable', $this->language);
															   
        $data['title'] = lang('hr_teleworks_title');
        $data['user_id'] = $id;
        $this->load->model('teleworks_model');
        $this->load->model('telework_campaign_model');
        $data['campaigns'] = $this->telework_campaign_model->getTeleworkCampaigns();
        $data['types'] = array(
            'Campaign',
            'Floating'
        );
        $data['teleworks'] = $this->teleworks_model->getTeleworksOfEmployee($id);
        if ($this->config->item('enable_teleworks_history') === TRUE) {
            $this->load->model('telework_history_model');
            $data['deletedTeleworks'] = $this->telework_history_model->getDeletedTeleworkRequests($id);
        }
        $data['flash_partial_view'] = $this->load->view('templates/flash', $data, TRUE);
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('hr/teleworks', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Display the details of leaves taken/entitled for a given employee
     * @param string $source page calling the report (employees, collaborators)
     * @param int $id Identifier of the employee
     * @param string $refTmp Timestamp (reference date)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function counters($source, $id, $refTmp = NULL) {
        if ($source == 'collaborators') { $this->auth->checkIfOperationIsAllowed('list_collaborators'); }
        if ($source == 'employees') { $this->auth->checkIfOperationIsAllowed('list_employees'); }
        $data = getUserContext($this);
        if ($source == 'collaborators') { $data['source'] = 'collaborators'; }
        if ($source == 'employees') { $data['source'] = 'employees'; }
        $this->lang->load('entitleddays', $this->language);
        $this->lang->load('datatable', $this->language);
        $refDate = date("Y-m-d");
        if ($refTmp != NULL) {
            $refDate = date("Y-m-d", $refTmp);
            $data['isDefault'] = 0;
        } else {
            $data['isDefault'] = 1;
        }

        $data['refDate'] = $refDate;
        $data['summary'] = $this->leaves_model->getLeaveBalanceForEmployee($id, FALSE, $refDate);
        if (!is_null($data['summary'])) {
            $this->load->model('entitleddays_model');
            $this->load->model('users_model');
            $user = $this->users_model->getUsers($id);
            $data['employee_name'] = $user['firstname'] . ' ' . $user['lastname'];
            $this->load->model('contracts_model');
            $contract = $this->contracts_model->getContracts($user['contract']);
            $data['contract_name'] = $contract['name'];
            $data['contract_start'] = $contract['startentdate'];
            $data['contract_end'] = $contract['endentdate'];
            $data['employee_id'] = $id;
            $data['contract_id'] = $user['contract'];
            $data['entitleddayscontract'] = $this->entitleddays_model->getEntitledDaysForContract($user['contract']);
            $data['entitleddaysemployee'] = $this->entitleddays_model->getEntitledDaysForEmployee($id);
            $data['title'] = lang('hr_summary_title');
            $data['help'] = $this->help->create_help_link('global_link_doc_page_leave_balance_employee');
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('hr/counters', $data);
            $this->load->view('templates/footer');
        } else {
            $this->session->set_flashdata('msg', lang('hr_summary_flash_msg_error'));
            redirect('hr/employees');
        }
    }

    /**
     * Create a leave request in behalf of an employee
     * @param int $id Identifier of the employee
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function createleave($id) {
        $this->auth->checkIfOperationIsAllowed('list_employees');
        $data = getUserContext($this);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['title'] = lang('hr_leaves_create_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_request_leave');
        $data['form_action'] = 'hr/leaves/create/' . $id;
        $data['source'] = 'hr/employees';
        $data['employee'] = $id;

        $this->form_validation->set_rules('startdate', lang('hr_leaves_create_field_start'), 'required|strip_tags');
        $this->form_validation->set_rules('startdatetype', 'Start Date type', 'required|strip_tags');
        $this->form_validation->set_rules('enddate', lang('hr_leaves_create_field_end'), 'required|strip_tags');
        $this->form_validation->set_rules('enddatetype', 'End Date type', 'required|strip_tags');
        $this->form_validation->set_rules('duration', lang('hr_leaves_create_field_duration'), 'required|strip_tags');
        $this->form_validation->set_rules('type', lang('hr_leaves_create_field_type'), 'required|strip_tags');
        $this->form_validation->set_rules('cause', lang('hr_leaves_create_field_cause'), 'strip_tags');
        $this->form_validation->set_rules('status', lang('hr_leaves_create_field_status'), 'required|strip_tags');

        if ($this->form_validation->run() === FALSE) {
            $this->load->model('contracts_model');
            $leaveTypesDetails = $this->contracts_model->getLeaveTypesDetailsOTypesForUser($id);
            $data['defaultType'] = $leaveTypesDetails->defaultType;
            $data['credit'] = $leaveTypesDetails->credit;
            $data['types'] = $leaveTypesDetails->types;
            $this->load->model('users_model');
            $data['name'] = $this->users_model->getName($id);
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('hr/createleave');
            $this->load->view('templates/footer');
        } else {
            $this->leaves_model->setLeaves($id);   //Return not used
            $this->session->set_flashdata('msg', lang('hr_leaves_create_flash_msg_success'));
            //No mail is sent, because the HR Officer would set the leave status to accepted
            redirect('hr/employees');
        }
    }
    
    /**
     * Create a telework request in behalf of an employee
     * @param int $id Identifier of the employee
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function createtelework($id) {
        $this->auth->checkIfOperationIsAllowed('list_employees');
        $data = getUserContext($this);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['title'] = lang('hr_teleworks_create_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_request_telework');
        $data['form_action'] = 'hr/teleworks/create/' . $id;
        $data['source'] = 'hr/employees';
        $data['employee'] = $id;
        
        $this->form_validation->set_rules('startdate', lang('hr_teleworks_create_field_start'), 'required|strip_tags');
        $this->form_validation->set_rules('startdatetype', 'Start Date type', 'required|strip_tags');
        $this->form_validation->set_rules('enddate', lang('hr_teleworks_create_field_end'), 'required|strip_tags');
        $this->form_validation->set_rules('enddatetype', 'End Date type', 'required|strip_tags');
        $this->form_validation->set_rules('duration', lang('hr_teleworks_create_field_duration'), 'required|strip_tags');
        $this->form_validation->set_rules('cause', lang('hr_teleworks_create_field_cause'), 'strip_tags');
        $this->form_validation->set_rules('status', lang('hr_teleworks_create_field_status'), 'required|strip_tags');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->model('users_model');
            $data['name'] = $this->users_model->getName($id);
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('hr/createtelework');
            $this->load->view('templates/footer');
        } else {
        	$this->load->model('teleworks_model');
            $this->teleworks_model->setTeleworks($id);   //Return not used
            $this->session->set_flashdata('msg', lang('hr_teleworks_create_flash_msg_success'));
            //No mail is sent, because the HR Officer would set the leave status to accepted
            redirect('hr/employees');
        }
    }
    
    /**
     * Create telework requests for a campaign in behalf of an employee
     * @param int $id Identifier of the employee
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function createcampaigntelework($id) {
        $this->auth->checkIfOperationIsAllowed('list_employees');
        $data = getUserContext($this);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->lang->load('calendar_lang', $this->language);
        $data['title'] = lang('hr_teleworks_create_campaign_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_request_telework');
        $data['form_action'] = 'hr/campaignteleworks/create/' . $id;
        $data['source'] = 'hr/employees';
        $data['employee'] = $id;
        
        $this->form_validation->set_rules('campaign', lang('hr_teleworks_create_field_campaign'), 'required|strip_tags');
        $this->form_validation->set_rules('recurrence', lang('teleworks_create_field_recurrence'), 'required|strip_tags');
        $this->form_validation->set_rules('day', lang('hr_teleworks_create_field_day'), 'required|strip_tags');
        $this->form_validation->set_rules('status', lang('hr_teleworks_create_field_status'), 'required|strip_tags');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->model('telework_campaign_model');            
            $this->load->model('users_model');
            $data['campaigns'] = $this->telework_campaign_model->getActiveCampaigns();
            $data['name'] = $this->users_model->getName($id);
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('hr/createforcampaign');
            $this->load->view('templates/footer');
        } else {
        	$this->load->model('teleworks_model');
            $this->teleworks_model->setTeleworksForCampaign($id);   //Return not used
            $this->session->set_flashdata('msg', lang('hr_teleworks_create_flash_msg_success'));
            //No mail is sent, because the HR Officer would set the leave status to accepted
            redirect('hr/employees');
        }
    }

    /**
     * Display presence details for a given employee
     * @param string $source page calling the report (employees, collaborators)
     * @param int $id employee id
     * @param int $month Month number or 0 for last month (default)
     * @param int $year Year number or 0 for current year (default)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function presence($source, $id, $month=0, $year=0) {
        if ($source == 'collaborators') { $this->auth->checkIfOperationIsAllowed('list_collaborators'); }
        if ($source == 'employees') { $this->auth->checkIfOperationIsAllowed('list_employees'); }
        $data = getUserContext($this);
        if ($source == 'collaborators') { $data['source'] = 'collaborators'; }
        if ($source == 'employees') { $data['source'] = 'employees'; }
        $this->lang->load('datatable', $this->language);
        $this->lang->load('calendar', $this->language);
        $data['title'] = lang('hr_presence_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_presence_report');

        $data['user_id'] = $id;
        $this->load->model('leaves_model');
        $this->load->model('users_model');
        $this->load->model('dayoffs_model');
        $this->load->model('contracts_model');

        //Details about the employee
        $employee = $this->users_model->getUsers($id);
        if (($this->user_id != $employee['manager']) && ($this->is_hr === FALSE)) {
            log_message('error', 'User #' . $this->user_id . ' illegally tried to access to hr/presence  #' . $id);
            $this->session->set_flashdata('msg', sprintf(lang('global_msg_error_forbidden'), 'hr/presence'));
            redirect('leaves');
        }
        $data['employee_name'] =  $employee['firstname'] . ' ' . $employee['lastname'];
        $contract = $this->contracts_model->getContracts($employee['contract']);
        if (!empty($contract)) {
            $data['contract_id'] = $contract['id'];
            $data['contract_name'] = $contract['name'];
        } else {
            $data['contract_id'] = '';
            $data['contract_name'] = '';
        }

        //Compute facts about dates and the selected month
        if ($month == 0) $month = date('m', strtotime('last month'));
        if ($year == 0) $year = date('Y', strtotime('last month'));
        $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $start = sprintf('%d-%02d-01', $year, $month);
        $lastDay = date("t", strtotime($start));    //last day of selected month
        $end = sprintf('%d-%02d-%02d', $year, $month, $lastDay);
        //Number of non working days during the selected month
        $non_working_days = $this->dayoffs_model->lengthDaysOffBetweenDates($employee['contract'], $start, $end);
        $opened_days = $total_days - $non_working_days;
        $data['month'] = $month;
        $data['month_name'] = date('F', strtotime($start));
        $data['year'] = $year;
        $data['default_date'] = $start;
        $data['total_days'] = $total_days;
        $data['opened_days'] = $opened_days;
        $data['non_working_days'] = $non_working_days;

        //tabular view of the leaves
        $data['linear'] = $this->leaves_model->linear($id, $month, $year, FALSE, FALSE, TRUE, FALSE, FALSE, FALSE);
        if ($this->config->item('disable_telework') === FALSE)
            $data['linear'] = $this->leaves_model->removeOverlappingTeleworks($data['linear']);
        $data['leave_duration'] = $this->leaves_model->monthlyLeavesDuration($data['linear']);
        $data['work_duration'] = $opened_days - $data['leave_duration'];
        $data['leaves_detail'] = $this->leaves_model->monthlyLeavesByType($data['linear']);
        if ($this->config->item('disable_time_organisation') === FALSE && $this->config->item('hide_time_orgnisation_in_cals') === FALSE) {
            $this->load->model('time_organisation_model');
            $timeorganisationdates = $this->time_organisation_model->getTimeOrganisationDatesToCalendar($id, $month, $year);
            $this->leaves_model->addTimeOrganisationDatesToCalendar($data['linear'], $timeorganisationdates, $id);
        }

        //List of accepted leave requests
        $data['leaves'] = $this->leaves_model->getAcceptedLeavesBetweenDates($id, $start, $end);

        //Leave balance of the employee
        $data['employee_id'] = $id;
        $refDate = new DateTime($end);
        $data['refDate'] = $refDate->format(lang('global_date_format'));
        $data['summary'] = $this->leaves_model->getLeaveBalanceForEmployee($id, FALSE, $end);

        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('hr/presence', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Export the list of all leave requests of an employee into an Excel file
     * @param int $id employee id
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function exportLeaves($id) {
        $this->load->model('leaves_model');
        $this->load->model('users_model');
        $data['id'] = $id;
        $this->load->view('hr/export_leaves', $data);
    }

    /**
     * Export the list of all overtime requests of an employee into an Excel file
     * @param int $id employee id
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function exportOvertime($id) {
        $this->load->model('overtime_model');
        $this->load->model('users_model');
        $data['id'] = $id;
        $this->load->view('hr/export_overtime', $data);
    }
    
    /**
     * Export the list of all telework requests of an employee into an Excel file
     * @param int $id employee id
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function exportTeleworks($id) {
        $this->load->model('teleworks_model');
        $this->load->model('users_model');
        $data['id'] = $id;
        $this->load->view('hr/export_teleworks', $data);
    }

    /**
     * Export the list of all employees into an Excel file
     * @param int $id optional id of the entity, all entities if 0
     * @param bool $children TRUE : include sub entities, FALSE otherwise
     * @param string $filterActive "all"; "active" (only), or "inactive" (only)
     * @param string $criterion1 "lesser" or "greater" (optional)
     * @param string $date1 Date Hired (optional)
     * @param string $criterion2 "lesser" or "greater" (optional)
     * @param string $date2 Date Hired (optional)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function exportEmployees($id = 0, $children = TRUE, $filterActive = "all",
                            $criterion1 = NULL, $date1 = NULL, $criterion2 = NULL, $date2 = NULL) {
        $this->load->model('users_model');
        $data['id'] = $id;
        $data['children'] = filter_var($children, FILTER_VALIDATE_BOOLEAN);
        $data['filterActive'] = $filterActive;
        $data['criterion1'] = $criterion1;
        $data['date1'] = $date1;
        $data['criterion2'] = $criterion2;
        $data['date2'] = $date2;
        $this->load->view('hr/export_employees', $data);
    }

    /**
     * Action: export the presence details for a given employee
     * @param string $source page calling the report (employees, collaborators)
     * @param int $id employee id
     * @param int $month Month number or 0 for last month (default)
     * @param int $year Year number or 0 for current year (default)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function exportPresence($source,$id, $month=0, $year=0) {
        if ($source == 'collaborators') { $this->auth->checkIfOperationIsAllowed('list_collaborators'); }
        if ($source == 'employees') { $this->auth->checkIfOperationIsAllowed('list_employees'); }
        setUserContext($this);
        $this->lang->load('calendar', $this->language);
        $this->load->model('leaves_model');
        $this->load->model('teleworks_model');
        $this->load->model('time_organisation_model');
        $this->load->model('users_model');
        $this->load->model('dayoffs_model');
        $this->load->model('contracts_model');

        $employee = $this->users_model->getUsers($id);
        if (($this->user_id != $employee['manager']) && ($this->is_hr === FALSE)) {
            log_message('error', 'User #' . $this->user_id . ' illegally tried to access to hr/presence  #' . $id);
            $this->session->set_flashdata('msg', sprintf(lang('global_msg_error_forbidden'), 'hr/presence'));
            redirect('leaves');
        }

        $data['employee'] = $employee;
        $data['month'] = $month;
        $data['year'] = $year;
        $data['id'] = $id;
        $this->load->view('hr/export_presence', $data);
    }
}
