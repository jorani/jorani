<?php
/**
 * This controller serves the ajax endpoints that manages entitled days
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class serves the ajax endpoints that manages entitled days.
 * Entitled days are a kind of leave credit given at a contract (many employees) or at employee level.
 */
class Entitleddays extends CI_Controller {
   
    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {
        parent::__construct();
        setUserContext($this);
        $this->load->model('entitleddays_model');
        $this->lang->load('entitleddays', $this->language);
    }

    /**
     * Display an ajax-based form that list entitled days of a user
     * and allow updating the list by adding or removing one item
     * @param int $id User identifier
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function user($id) {
        $this->auth->checkIfOperationIsAllowed('entitleddays_user');
        $data = getUserContext($this);
        $this->lang->load('datatable', $this->language);
        $data['id'] = $id;
        $data['entitleddays'] = $this->entitleddays_model->getEntitledDaysForEmployee($id);
        $this->load->model('types_model');
        $data['types'] = $this->types_model->getTypes();
        $this->load->model('users_model');
        $user = $this->users_model->getUsers($id);
        $data['employee_name'] = $user['firstname'] . ' ' . $user['lastname'];
        
        if (!empty($user['contract'])) {
            $this->load->model('contracts_model');
            $contract = $this->contracts_model->getContracts($user['contract']);
            $data['contract_name'] = $contract['name'];
            $data['contract_start_month'] = intval(substr($contract['startentdate'], 0, 2));
            $data['contract_start_day'] = intval(substr($contract['startentdate'], 3));
            $data['contract_end_month'] = intval(substr($contract['endentdate'], 0, 2));
            $data['contract_end_day'] = intval(substr($contract['endentdate'], 3));
        } else {
            $data['contract_name'] = '';
        }
        
        $data['title'] = lang('entitleddays_user_index_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_entitleddays_employee');
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('entitleddays/user', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Display an ajax-based form that list entitled days of a contract
     * and allow updating the list by adding or removing one item
     * @param int $id contract identifier
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function contract($id) {
        $this->auth->checkIfOperationIsAllowed('entitleddays_contract');
        $data = getUserContext($this);
        $this->lang->load('datatable', $this->language);
        $data['id'] = $id;
        $data['entitleddays'] = $this->entitleddays_model->getEntitledDaysForContract($id);
        $this->load->model('types_model');
        $data['types'] = $this->types_model->getTypes();
        $this->load->model('contracts_model');
        $contract = $this->contracts_model->getContracts($id);
        $data['contract_name'] = $contract['name'];
        $data['contract_start_month'] = intval(substr($contract['startentdate'], 0, 2));
        $data['contract_start_day'] = intval(substr($contract['startentdate'], 3));
        $data['contract_end_month'] = intval(substr($contract['endentdate'], 0, 2));
        $data['contract_end_day'] = intval(substr($contract['endentdate'], 3));
        
        $data['title'] = lang('entitleddays_contract_index_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_entitleddays_contract');
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('entitleddays/contract', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Ajax endpoint : delete an entitled days credit (to an employee)
     * and returns the number of rows affected
     * @param int $id entitled days credit identifier
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function userdelete($id) {
        $this->auth->checkIfOperationIsAllowed('entitleddays_user_delete');
        $this->output->set_content_type('text/plain');
        echo $this->entitleddays_model->deleteEntitledDays($id);
    }
    
    /**
     * Ajax endpoint : delete an entitled days credit (to a contract)
     * and returns the number of rows affected
     * @param int $id entitled days credit identifier
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function contractdelete($id) {
        $this->auth->checkIfOperationIsAllowed('entitleddays_contract_delete');
        $this->output->set_content_type('text/plain');
        echo $this->entitleddays_model->deleteEntitledDays($id);
    }
    
    /**
     * Ajax endpoint : insert into the list of entitled days for a given user
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function ajax_user() {
        if ($this->auth->isAllowed('entitleddays_user') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $user_id = $this->input->post('user_id', TRUE);
            $startdate = $this->input->post('startdate', TRUE);
            $enddate = $this->input->post('enddate', TRUE);
            $days = $this->input->post('days', TRUE);
            $type = $this->input->post('type', TRUE);
            $description = sanitize($this->input->post('description', TRUE));
            if (isset($startdate) && isset($enddate) && isset($days) && isset($type) && isset($user_id)) {
                $this->output->set_content_type('text/plain');
                $id = $this->entitleddays_model->addEntitledDaysToEmployee($user_id, $startdate, $enddate, $days, $type, $description);
                echo $id;
            } else {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
            }
        }
    }
    
    /**
     * Ajax endpoint : insert into the list of entitled days for a given contract
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function ajax_contract() {
        if ($this->auth->isAllowed('entitleddays_user') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $contract_id = $this->input->post('contract_id', TRUE);
            $startdate = $this->input->post('startdate', TRUE);
            $enddate = $this->input->post('enddate', TRUE);
            $days = $this->input->post('days', TRUE);
            $type = $this->input->post('type', TRUE);
            $description = sanitize($this->input->post('description', TRUE));
            if (isset($startdate) && isset($enddate) && isset($days) && isset($type) && isset($contract_id)) {
                $this->output->set_content_type('text/plain');
                $id = $this->entitleddays_model->addEntitledDaysToContract($contract_id, $startdate, $enddate, $days, $type, $description);
                echo $id;
            } else {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
            }
        }
    }
    
    /**
     * Ajax endpoint : Update an entitled days row 
     * on a contract of an employee (as the both are stored into the same table)
     * id : row identifier into the database
     * operation : "increase" or "decrease" by 1 (the number can be negative).
     *                  "credit" modify the value of the credit
     *                  "update" update all the value of the credit line
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function ajax_update() {
        if ($this->auth->isAllowed('entitleddays_user') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $id = $this->input->post('id', TRUE);
            $operation = $this->input->post('operation', TRUE);   
            if (isset($id) && isset($operation)) {
                $this->output->set_content_type('text/plain');
                $days = $this->input->post('days', TRUE);
                switch ($operation) {
                    case  "increase":
                        $id = $this->entitleddays_model->increase($id, $days);
                        break;
                    case "decrease":
                        $id = $this->entitleddays_model->decrease($id, $days);
                        break;
                    case "credit":
                        $id = $this->entitleddays_model->updateNbOfDaysOfEntitledDaysRecord($id, $days);
                        break;
                    case "update":
                        $startdate = $this->input->post('startdate', TRUE);
                        $enddate = $this->input->post('enddate', TRUE);
                        $type = $this->input->post('type', TRUE);
                        $description = sanitize($this->input->post('description', TRUE));
                        $id = $this->entitleddays_model->updateEntitledDays($id, $startdate, $enddate, $days, $type, $description);
                        break;
                    default:
                        $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
                }
                echo $id;
            } else {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
            }
        }
    }

}
