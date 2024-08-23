<?php
/**
 * This controller allows a manager to list and manage telework requests submitted to him
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class allows a manager to list and manage telework requests submitted to him.
 * Since 0.3.0, we expose the list of collaborators and allow a manager to access to some reports:
 *  - presence report of an employee.
 *  - counters of an employee (telework balance).
 *  - Yearly calendar of an employee.
 * But those reports are not served by this controller (either HR or Calendar controller).
 */
class TeleworkRequests extends CI_Controller {

    /**
     * Default constructor
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function __construct() {
        parent::__construct();
        setUserContext($this);
        $this->load->model('teleworks_model');
        $this->lang->load('teleworkrequests', $this->language);
        $this->lang->load('global', $this->language);
    }

    /**
     * Display the list of all floating requests submitted to you
     * Status is submitted or accepted/rejected depending on the filter parameter.
     * @param string $name Filter the list of submitted telework requests (all or requested)
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function index($filter = 'requested') {
        $this->auth->checkIfOperationIsAllowed('list_telework_requests');
        $data = getUserContext($this);
        $this->lang->load('datatable', $this->language);
        $this->load->helper('form');
        $data['filter'] = $filter;
        $data['title'] = lang('teleworkrequests_index_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_telework_validation');
        ($filter == 'all')? $showAll = TRUE : $showAll = FALSE;
        if ($this->config->item('enable_teleworks_history') == TRUE){
            $data['teleworkrequests'] = $this->teleworks_model->getTeleworksRequestedToManagerWithHistory($this->session->userdata('id'), $showAll, 'Floating');
        }else{
            $data['teleworkrequests'] = $this->teleworks_model->getTeleworksRequestedToManager($this->session->userdata('id'), $showAll, 'Floating');
        }
        $this->load->model('telework_campaign_model');
        $data['campaigns'] = $this->telework_campaign_model->getTeleworkCampaigns();
        $data['showAll'] = $showAll;
        $data['flash_partial_view'] = $this->load->view('templates/flash', $data, TRUE);
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('teleworkrequests/index', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Display the list of all telework requests that have been submitted to you by an employee
     * @param int $id unique identifier of the employee
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function teleworks($filter = 'requested', $id) {
        $this->load->helper('form');
        $this->load->model('users_model');
        $employee = $this->users_model->getUsers($id);
        if (($this->user_id != $employee['manager']) && ($this->is_hr === FALSE)) {
            log_message('error', 'User #' . $this->user_id . ' illegally tried to access to teleworkrequests/teleworks  #' . $id);
            $this->session->set_flashdata('msg', lang('teleworkrequests_summary_flash_msg_forbidden'));
            redirect('teleworks');
        } else {
            $data = getUserContext($this);
            $this->lang->load('teleworks', $this->language);
            $data['name'] = $this->users_model->getName($id);
            // Check if exists
            if ($data['name'] == "") {
                redirect('notfound');
            }
            $this->lang->load('datatable', $this->language);
            
            $data['title'] = lang('campaign_teleworks_index_html_title');
            $data['user_id'] = $id;
            $data['filter'] = $filter;
            $this->load->model('teleworks_model');
            $this->load->model('telework_campaign_model');
            $data['campaigns'] = $this->telework_campaign_model->getTeleworkCampaigns();
            ($filter == 'all') ? $showAll = TRUE : $showAll = FALSE;
            if ($this->config->item('enable_teleworks_history') == TRUE) {
                $data['teleworks'] = $this->teleworks_model->getTeleworksRequestedToManagerWithHistory($this->user_id, $showAll, NULL, $id);
            } else {
                $data['teleworks'] = $this->teleworks_model->getTeleworksRequestedToManager($this->user_id, $showAll, NULL, $id);
            }
            $data['flash_partial_view'] = $this->load->view('templates/flash', $data, TRUE);
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('teleworkrequests/teleworks', $data);
            $this->load->view('templates/footer');
        }
    }
    
    /**
     * Display the list of all campaign telework requests that have been submitted to you by an employee
     * @param int $id unique identifier of the employee
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function campaignteleworks($filter = 'requested', $id) {
        $this->load->helper('form');
        $this->load->model('users_model');
        $employee = $this->users_model->getUsers($id);
        if (($this->user_id != $employee['manager']) && ($this->is_hr === FALSE)) {
            log_message('error', 'User #' . $this->user_id . ' illegally tried to access to teleworkrequests/campaignteleworks  #' . $id);
            $this->session->set_flashdata('msg', lang('teleworkrequests_summary_flash_msg_forbidden'));
            redirect('teleworks');
        } else {
            $data = getUserContext($this);
            $this->lang->load('teleworks', $this->language);
            $data['name'] = $this->users_model->getName($id);
            // Check if exists
            if ($data['name'] == "") {
                redirect('notfound');
            }
            $this->lang->load('datatable', $this->language);

            $data['title'] = lang('campaign_teleworks_index_html_title');
            $data['user_id'] = $id;
            $data['filter'] = $filter;
            $this->load->model('teleworks_model');
            $this->load->model('telework_campaign_model');
            $data['campaigns'] = $this->telework_campaign_model->getTeleworkCampaigns();
            ($filter == 'all') ? $showAll = TRUE : $showAll = FALSE;
            if ($this->config->item('enable_teleworks_history') == TRUE) {
                $data['teleworks'] = $this->teleworks_model->getTeleworksRequestedToManagerWithHistory($this->user_id, $showAll, 'Campaign', $id);
            } else {
                $data['teleworks'] = $this->teleworks_model->getTeleworksRequestedToManager($this->user_id, $showAll, 'Campaign', $id);
            }
            $data['flash_partial_view'] = $this->load->view('templates/flash', $data, TRUE);
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('teleworkrequests/campaignteleworks', $data);
            $this->load->view('templates/footer');
        }
    }

    /**
     * Accept a telework request
     * @param int $id telework request identifier
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function accept($id) {
        $this->auth->checkIfOperationIsAllowed('accept_telework_requests');
        $this->load->model('users_model');
        $this->load->model('delegations_model');
        $telework = $this->teleworks_model->getTeleworks($id);
        if (empty($telework)) {
            redirect('notfound');
        }
        $employee = $this->users_model->getUsers($telework['employee']);
        $is_delegate = $this->delegations_model->isDelegateOfManager($this->user_id, $employee['manager']);
        if (($this->user_id == $employee['manager']) || ($this->is_hr)  || ($is_delegate)) {
            $this->teleworks_model->switchStatus($id, LMS_ACCEPTED);
            $this->sendMail($id, LMS_REQUESTED_ACCEPTED);
            $this->session->set_flashdata('msg', lang('teleworkrequests_accept_flash_msg_success'));
            if (isset($_GET['source'])) {
                redirect($_GET['source']);
            } else {
                redirect('teleworkrequests');
            }
        } else {
            log_message('error', 'User #' . $this->user_id . ' illegally tried to accept telework #' . $id);
            $this->session->set_flashdata('msg', lang('teleworkrequests_accept_flash_msg_error'));
            redirect('teleworks');
        }
    }
    
    /**
     * Accept all campaign telework requests
     * @param int $employeeId identifier of the employee
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function acceptAll($employeeId) {
        $this->auth->checkIfOperationIsAllowed('accept_all_telework_requests');
        $this->load->model('users_model');
        $this->load->model('delegations_model');
        $teleworks = $this->teleworks_model->getRequestedCampaignTeleworksOfEmployee($employeeId);
        if (empty($teleworks)) {
            redirect('notfound');
        }
        $employee = $this->users_model->getUsers($employeeId);
        $is_delegate = $this->delegations_model->isDelegateOfManager($this->user_id, $employee['manager']);
        if (($this->user_id == $employee['manager']) || ($this->is_hr)  || ($is_delegate)) {
            for ($i = 0; $i < count($teleworks); $i ++) {
                $this->teleworks_model->switchStatus($teleworks[$i]['id'], LMS_ACCEPTED);
            }
            if (isset($_GET['source'])) {
                redirect($_GET['source']);
            } else {
                redirect('teleworkrequests');
            }
        } else {
            log_message('error', 'User #' . $this->user_id . ' illegally tried to accept all campaign telework requests');
            $this->session->set_flashdata('msg', lang('teleworkrequests_accept_flash_msg_error'));
            redirect('teleworks');
        }
    }
    
    /**
     * Reject a telework request
     * @param int $id telework request identifier
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function reject($id) {
        $this->auth->checkIfOperationIsAllowed('reject_telework_requests');
        $this->load->model('users_model');
        $this->load->model('delegations_model');
        $telework = $this->teleworks_model->getTeleworks($id);
        if (empty($telework)) {
            redirect('notfound');
        }
        $employee = $this->users_model->getUsers($telework['employee']);
        $is_delegate = $this->delegations_model->isDelegateOfManager($this->user_id, $employee['manager']);
        if (($this->user_id == $employee['manager']) || ($this->is_hr)  || ($is_delegate)) {
            if(isset($_POST['comment'])){
              $this->teleworks_model->switchStatusAndComment($id, LMS_REJECTED, $_POST['comment']);
            } else {
              $this->teleworks_model->switchStatus($id, LMS_REJECTED);
            }
            $this->sendMail($id, LMS_REQUESTED_REJECTED);
            $this->session->set_flashdata('msg',  lang('teleworkrequests_reject_flash_msg_success'));
            if (isset($_GET['source'])) {
                redirect($_GET['source']);
            } else {
                redirect('teleworkrequests');
            }
        } else {
            log_message('error', 'User #' . $this->user_id . ' illegally tried to reject telework #' . $id);
            $this->session->set_flashdata('msg', lang('teleworkrequests_reject_flash_msg_error'));
            redirect('teleworks');
        }
    }
    
    /**
     * Reject all campaign telework requests
     * @param int $employeeId identifier of the employee
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function rejectAll($employeeId) {
        $this->auth->checkIfOperationIsAllowed('reject_all_telework_requests');
        $this->load->model('users_model');
        $this->load->model('delegations_model');
        $teleworks = $this->teleworks_model->getRequestedCampaignTeleworksOfEmployee($employeeId);
        if (empty($teleworks)) {
            redirect('notfound');
        }
        $employee = $this->users_model->getUsers($employeeId);
        $is_delegate = $this->delegations_model->isDelegateOfManager($this->user_id, $employee['manager']);
        if (($this->user_id == $employee['manager']) || ($this->is_hr)  || ($is_delegate)) {
            for ($i = 0; $i < count($teleworks); $i ++) {
                $this->teleworks_model->switchStatus($teleworks[$i]['id'], LMS_REJECTED);
            }
            $this->session->set_flashdata('msg',  lang('teleworkrequests_reject_flash_msg_success'));
            if (isset($_GET['source'])) {
                redirect($_GET['source']);
            } else {
                redirect('teleworkrequests');
            }
        } else {
            log_message('error', 'User #' . $this->user_id . ' illegally tried to reject all campaign telework requests');
            $this->session->set_flashdata('msg', lang('teleworkrequests_reject_flash_msg_error'));
            redirect('teleworks');
        }
    }

    /**
     * Accept the cancellation of a telework request
     * @param int $id telework request identifier
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function acceptCancellation($id) {
        $this->auth->checkIfOperationIsAllowed('accept_telework_requests');
        $this->load->model('users_model');
        $this->load->model('delegations_model');
        $telework = $this->teleworks_model->getTeleworks($id);
        if (empty($telework)) {
            redirect('notfound');
        }
        $employee = $this->users_model->getUsers($telework['employee']);
        $is_delegate = $this->delegations_model->isDelegateOfManager($this->user_id, $employee['manager']);
        if (($this->user_id == $employee['manager']) || ($this->is_hr)  || ($is_delegate)) {
            $this->teleworks_model->switchStatus($id, LMS_CANCELED);
            $this->sendMail($id, LMS_CANCELLATION_CANCELED);
            $this->session->set_flashdata('msg', lang('teleworkrequests_cancellation_accept_flash_msg_success'));
            if (isset($_GET['source'])) {
                redirect($_GET['source']);
            } else {
                redirect('teleworkrequests');
            }
        } else {
            log_message('error', 'User #' . $this->user_id . ' illegally tried to accept the cancellation of telework #' . $id);
            $this->session->set_flashdata('msg', lang('teleworkrequests_cancellation_accept_flash_msg_error'));
            redirect('teleworks');
        }
    }

    /**
     * Reject the cancellation of a telework request
     * @param int $id telework request identifier
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function rejectCancellation($id) {
        $this->auth->checkIfOperationIsAllowed('reject_telework_requests');
        $this->load->model('users_model');
        $this->load->model('delegations_model');
        $telework = $this->teleworks_model->getTeleworks($id);
        if (empty($telework)) {
            redirect('notfound');
        }
        $employee = $this->users_model->getUsers($telework['employee']);
        $is_delegate = $this->delegations_model->isDelegateOfManager($this->user_id, $employee['manager']);
        if (($this->user_id == $employee['manager']) || ($this->is_hr)  || ($is_delegate)) {
            //$this->teleworks_model->switchStatus($id, LMS_ACCEPTED);
            if(isset($_POST['comment'])){
              $this->teleworks_model->switchStatusAndComment($id, LMS_ACCEPTED, $_POST['comment']);
            } else {
              $this->teleworks_model->switchStatus($id, LMS_ACCEPTED);
            }
            $this->sendMail($id, LMS_CANCELLATION_REQUESTED);
            $this->session->set_flashdata('msg', lang('teleworkrequests_cancellation_reject_flash_msg_success'));
            if (isset($_GET['source'])) {
                redirect($_GET['source']);
            } else {
                redirect('teleworkrequests');
            }
        } else {
            log_message('error', 'User #' . $this->user_id . ' illegally tried to accept the cancellation of telework #' . $id);
            $this->session->set_flashdata('msg', lang('teleworkrequests_cancellation_reject_flash_msg_error'));
            redirect('teleworks');
        }
    }

    /**
     * Display the list of delegations
     * @param int $id Identifier of the manager (from HR/Employee) or 0 if self
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function delegations($id = 0) {
        if ($id == 0) $id = $this->user_id;
        //Self modification or by HR
        if (($this->user_id == $id) || ($this->is_hr)) {
            $data = getUserContext($this);
            $this->lang->load('datatable', $this->language);
            $data['title'] = lang('teleworkrequests_delegations_title');
            $data['help'] = $this->help->create_help_link('global_link_doc_page_delegations');
            $this->load->model('users_model');
            $data['name'] = $this->users_model->getName($id);
            $data['id'] = $id;
            $this->load->model('delegations_model');
            $data['delegations'] = $this->delegations_model->listDelegationsForManager($id);
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('teleworkrequests/delegations', $data);
            $this->load->view('templates/footer');
        } else {
            log_message('error', 'User #' . $this->user_id . ' illegally tried to access to list_delegations');
            $this->session->set_flashdata('msg', sprintf(lang('global_msg_error_forbidden'), 'list_delegations'));
            redirect('teleworks');
        }
    }

    /**
     * Ajax endpoint : Delete a delegation for a manager
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function deleteDelegations() {
        $manager = $this->input->post('manager_id', TRUE);
        $delegation = $this->input->post('delegation_id', TRUE);
        if (($this->user_id != $manager) && ($this->is_hr == FALSE)) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            if (isset($manager) && isset($delegation)) {
                $this->output->set_content_type('text/plain');
                $this->load->model('delegations_model');
                $this->delegations_model->deleteDelegation($delegation);
                $this->output->set_output($delegation);
            } else {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
            }
        }
    }

    /**
     * Ajax endpoint : Add a delegation for a manager
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function addDelegations() {
        $manager = $this->input->post('manager_id', TRUE);
        $delegate = $this->input->post('delegate_id', TRUE);
        if (($this->user_id != $manager) && ($this->is_hr === FALSE)) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            if (isset($manager) && isset($delegate)) {
                $this->output->set_content_type('text/plain');
                $this->load->model('delegations_model');
                if (!$this->delegations_model->isDelegateOfManager($delegate, $manager)) {
                    $id = $this->delegations_model->addDelegate($manager, $delegate);
                    $this->output->set_output($id);
                } else {
                    $this->output->set_output('null');
                }
            } else {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
            }
        }
    }

    /**
     * Create a telework request in behalf of a collaborator
     * @param int $id Identifier of the employee
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function createtelework($id) {
        $this->lang->load('hr', $this->language);
        $this->load->model('users_model');
        $employee = $this->users_model->getUsers($id);
        if (($this->user_id != $employee['manager']) && ($this->is_hr === FALSE)) {
            log_message('error', 'User #' . $this->user_id . ' illegally tried to access to collaborators/telework/create  #' . $id);
            $this->session->set_flashdata('msg', lang('teleworkrequests_summary_flash_msg_forbidden'));
            redirect('teleworks');
        } else {
            $data = getUserContext($this);
            $this->load->helper('form');
            $this->load->library('form_validation');
            $data['title'] = lang('hr_teleworks_create_title');
            $data['help'] = $this->help->create_help_link('global_link_doc_page_request_telework');
            $data['form_action'] = 'teleworkrequests/createtelework/' . $id;
            $data['source'] = 'requests/collaborators';
            $data['employee'] = $id;

            $this->form_validation->set_rules('startdate', lang('hr_teleworks_create_field_start'), 'required|strip_tags');
            $this->form_validation->set_rules('startdatetype', 'Start Date type', 'required|strip_tags');
            $this->form_validation->set_rules('enddate', lang('teleworks_create_field_end'), 'required|strip_tags');
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
                $this->teleworks_model->setTeleworks($id);       //We don't use the return value
                $this->session->set_flashdata('msg', lang('hr_teleworks_create_flash_msg_success'));
                //No mail is sent, because the manager would set the telework status to accepted
                redirect('requests/collaborators');
            }
        }
    }
    
    /**
     * Create telework requests for a campaign in behalf of a collaborator
     * @param int $id Identifier of the employee
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function createcampaigntelework($id) {
        $this->lang->load('hr', $this->language);
        $this->load->model('users_model');
        $this->lang->load('calendar_lang', $this->language);
        $employee = $this->users_model->getUsers($id);
        if (($this->user_id != $employee['manager']) && ($this->is_hr === FALSE)) {
            log_message('error', 'User #' . $this->user_id . ' illegally tried to access to collaborators/telework/create  #' . $id);
            $this->session->set_flashdata('msg', lang('teleworkrequests_summary_flash_msg_forbidden'));
            redirect('teleworks');
        } else {
            $data = getUserContext($this);
            $this->load->helper('form');
            $this->load->library('form_validation');
            $data['title'] = lang('hr_teleworks_create_title');
            $data['help'] = $this->help->create_help_link('global_link_doc_page_request_telework');
            $data['form_action'] = 'teleworkrequests/createforcampaign/' . $id;
            $data['source'] = 'requests/collaborators';
            $data['employee'] = $id;
            
            $this->form_validation->set_rules('campaign', lang('hr_teleworks_create_field_campaign'), 'required|strip_tags');
            $this->form_validation->set_rules('startdate', lang('hr_teleworks_create_field_start'), 'strip_tags');
            $this->form_validation->set_rules('enddate', lang('hr_teleworks_create_field_end'), 'strip_tags');
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
                $this->teleworks_model->setTeleworksForCampaign($id);       //We don't use the return value
                $this->session->set_flashdata('msg', lang('hr_teleworks_create_flash_msg_success'));
                //No mail is sent, because the manager would set the telework status to accepted
                redirect('requests/collaborators');
            }
        }
    }

    /**
     * Send a telework request email to the employee that requested the telework.
     * @param int $id Telework request identifier
     * @param int $transition Transition in the workflow of telework request
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    private function sendMail($id, $transition)
    {
        $this->load->model('users_model');
        $this->load->model('organization_model');
        $telework = $this->teleworks_model->getTeleworks($id);
        $employee = $this->users_model->getUsers($telework['employee']);
        $supervisor = $this->organization_model->getSupervisor($employee['organization']);

        //Send an e-mail to the employee
        $this->load->library('email');
        $this->load->library('polyglot');
        $usr_lang = $this->polyglot->code2language($employee['language']);

        //We need to instance an different object as the languages of connected user may differ from the UI lang
        $lang_mail = new CI_Lang();
        $lang_mail->load('email', $usr_lang);
        $lang_mail->load('global', $usr_lang);

        $date = new DateTime($telework['startdate']);
        $startdate = $date->format($lang_mail->line('global_date_format'));
        $date = new DateTime($telework['enddate']);
        $enddate = $date->format($lang_mail->line('global_date_format'));

        switch ($transition) {
            case LMS_REQUESTED_ACCEPTED:
                $title = $lang_mail->line('email_telework_request_validation_title');
                $subject = $lang_mail->line('email_telework_request_accept_subject');
                break;
            case LMS_REQUESTED_REJECTED:
                $title = $lang_mail->line('email_telework_request_validation_title');
                $subject = $lang_mail->line('email_telework_request_reject_subject');
                break;
            case LMS_CANCELLATION_REQUESTED:
                $title = $lang_mail->line('email_telework_request_cancellation_title');
                $subject = $lang_mail->line('email_telework_cancel_reject_subject');
                break;
            case LMS_CANCELLATION_CANCELED:
                $title = $lang_mail->line('email_telework_request_cancellation_title');
                $subject = $lang_mail->line('email_telework_cancel_accept_subject');
                break;
        }
        $comments=$telework['comments'];
        $comment = '';
        if(!empty($comments)){
          $comments=json_decode($comments);
          foreach ($comments->comments as $comments_item) {
            if($comments_item->type =="comment"){
              $comment = $comments_item->value;
            }
          }
        }

        $data = array(
            'Title' => $title,
            'Firstname' => $employee['firstname'],
            'Lastname' => $employee['lastname'],
            'StartDate' => $startdate,
            'EndDate' => $enddate,
            'StartDateType' => $lang_mail->line($telework['startdatetype']),
            'EndDateType' => $lang_mail->line($telework['enddatetype']),
            'Cause' => $telework['cause'],
            'Comments' => $comment
        );
        $this->load->library('parser');
        switch ($transition) {
            case LMS_REQUESTED_ACCEPTED:
                $message = $this->parser->parse('emails/' . $employee['language'] . '/telework_request_accepted', $data, TRUE);
                break;
            case LMS_REQUESTED_REJECTED:
                $message = $this->parser->parse('emails/' . $employee['language'] . '/telework_request_rejected', $data, TRUE);
                break;
            case LMS_CANCELLATION_REQUESTED:
                $message = $this->parser->parse('emails/' . $employee['language'] . '/telework_cancel_rejected', $data, TRUE);
                $supervisor = NULL; //No need to warn the supervisor as nothing changes
                break;
            case LMS_CANCELLATION_CANCELED:
                $message = $this->parser->parse('emails/' . $employee['language'] . '/telework_cancel_accepted', $data, TRUE);
                break;
        }
        sendMailByWrapper($this, $subject, $message, $employee['email'], is_null($supervisor)?NULL:$supervisor->email);
    }

    /**
     * Export the list of all floating telework requests (sent to the connected user) into an Excel file
     * @param string $filter Filter the list of submitted telework requests (all or requested)
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function export($filter = 'requested') {
        $data['filter'] = $filter;
        $this->load->view('teleworkrequests/export', $data);
    }
    
    /**
     * Export the list of all campaign telework requests (sent to the connected user) into an Excel file
     * @param string $filter Filter the list of submitted telework requests (all or requested)
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function exportforcampaign($filter = 'requested', $id) {
        $data['filter'] = $filter;
        $data['user_id'] = $id;
        $this->load->view('teleworkrequests/exportforcampaign', $data);
    }
    
    /**
     * Export the list of all telework requests (sent to the connected user) into an Excel file
     * @param string $filter Filter the list of submitted telework requests (all or requested)
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function exportall($filter = 'requested', $id) {
        $data['filter'] = $filter;
        $data['user_id'] = $id;
        $this->load->view('teleworkrequests/exportall', $data);
    }
}
