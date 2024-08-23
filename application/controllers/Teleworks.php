<?php
/**
 * This controller contains the actions allowing an employee to list and manage its telework requests
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

//We can define custom triggers before saving the telework request into the database
require_once FCPATH . "local/triggers/telework.php";

/**
 * This class allows an employee to list and manage its telework requests
 * Since 0.4.3 a trigger is called at the creation, if the function triggerCreateTeleworkRequest is defined
 * see content of /local/triggers/telework.php
 */
class Teleworks extends CI_Controller {

    /**
     * Default constructor
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function __construct() {
        parent::__construct();
        setUserContext($this);
        $this->load->model('teleworks_model');
        $this->lang->load('teleworks', $this->language);
        $this->lang->load('global', $this->language);        
    }

    /**
     * Display the list of the telework requests of the connected user
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function index() {
        $this->auth->checkIfOperationIsAllowed('list_teleworks');
        $data = getUserContext($this);
        $this->lang->load('datatable', $this->language);
        if ($this->config->item('enable_teleworks_history') == TRUE){
          $data['teleworks'] = $this->teleworks_model->getTeleworksOfEmployeeWithHistory($this->session->userdata('id'));
        } else {
          $data['teleworks'] = $this->teleworks_model->getTeleworksOfEmployee($this->session->userdata('id'));
        }
        $data['title'] = lang('teleworks_index_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_telework_requests_list');
        $data['flash_partial_view'] = $this->load->view('templates/flash', $data, TRUE);
        $this->load->model('telework_campaign_model');
        $data['campaigns'] = $this->telework_campaign_model->getTeleworkCampaigns();
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('teleworks/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Display the history of changes of a telework request
     * @param int $id Identifier of the telework request
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function history($id) {
        $this->auth->checkIfOperationIsAllowed('list_teleworks');
        $data = getUserContext($this);
        $this->lang->load('datatable', $this->language);
        $data['telework'] = $this->teleworks_model->getTeleworks($id);
        $this->load->model('telework_history_model');
        $data['events'] = $this->telework_history_model->getTeleworkRequestsHistory($id);
        $this->load->view('teleworks/history', $data);
    }

    /**
     * Display the details of teleworks taken/entitled for the connected user
     * @param string $refDate Date (e.g. 2011-10-05)
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function counters($refDate = NULL) {
        $this->auth->checkIfOperationIsAllowed('counters_teleworks');
        $data = getUserContext($this);
        $this->lang->load('datatable', $this->language);
        if ($refDate != NULL) {
            $data['isDefault'] = 0;
        } else {
            $refDate = date("Y-m-d");
            $data['isDefault'] = 1;
        }
        $data['refDate'] = $refDate;
        $data['summary'] = $this->teleworks_model->getTeleworkCountForEmployee($this->user_id, $refDate);

        if (!is_null($data['summary'])) {
            $data['title'] = lang('teleworks_summary_title');
            $data['help'] = $this->help->create_help_link('global_link_doc_page_my_summary');
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('teleworks/counters', $data);
            $this->load->view('templates/footer');
        } else {
            $this->session->set_flashdata('msg', lang('teleworks_summary_flash_msg_error'));
            redirect('teleworks');
        }
    }

    /**
     * Display a telework request
     * @param string $source Page source (teleworks, requests) (self, manager)
     * @param int $id identifier of the telework request
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function view($source, $id) {
        $this->auth->checkIfOperationIsAllowed('view_teleworks');
        $this->load->model('users_model');
        $this->load->model('status_model');
        $this->load->helper('form');
        $data = getUserContext($this);
        $data['telework'] = $this->teleworks_model->getTeleworkWithComments($id);
        if (empty($data['telework'])) {
            redirect('notfound');
        }
        //If the user is not its not HR, not manager and not the creator of the telework
        //the employee can't see it, redirect to LR list
        if ($data['telework']['employee'] != $this->user_id) {
            if ((!$this->is_hr)) {
                $this->load->model('users_model');
                $employee = $this->users_model->getUsers($data['telework']['employee']);
                if ($employee['manager'] != $this->user_id) {
                    $this->load->model('delegations_model');
                    if (!$this->delegations_model->isDelegateOfManager($this->user_id, $employee['manager'])) {
                        log_message('error', 'User #' . $this->user_id . ' illegally tried to view telework #' . $id);
                        redirect('teleworks');
                    }
                }
            } //Admin
        } //Current employee
        $data['source'] = $source;
        //overwrite source (for taking into account the tabular calendar)
        if ($this->input->get('source') != NULL) {
            $data['source'] = urldecode($this->input->get('source'));
        }

        $data['title'] = lang('teleworks_view_html_title');
        if ($source == 'teleworkrequests') {
            if (empty($employee)) {
                $this->load->model('users_model');
                $data['name'] = $this->users_model->getName($data['telework']['employee']);
            } else {
                $data['name'] = $employee['firstname'] . ' ' . $employee['lastname'];
            }
        } else {
            $data['name'] = '';
        }
        if (isset($data["telework"]["comments"])){
          $last_comment = new stdClass();;
          foreach ($data["telework"]["comments"]->comments as $comments_item) {
            if($comments_item->type == "comment"){
              $comments_item->author = $this->users_model->getName($comments_item->author);
              $comments_item->in = "in";
              $last_comment->in="";
              $last_comment=$comments_item;
            } else if($comments_item->type == "change"){
              $comments_item->status = $this->status_model->getName($comments_item->status_number);
            }
          }
        }
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('teleworks/view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Create a new comment or append a comment to the comments
     * on a telework request
     * @param int $id Id of the telework request
     * @param string $source Page where we redirect after posting
     * @author Emilien NICOLAS <milihhard1996@gmail.com>
     */
    public function createComment($id, $source = "teleworks/teleworks"){
      $this->auth->checkIfOperationIsAllowed('view_teleworks');
      $data = getUserContext($this);
      $oldComment = $this->teleworks_model->getCommentsTelework($id);
      $newComment = new stdClass;
      $newComment->type = "comment";
      $newComment->author = $this->session->userdata('id');
      $newComment->value = $this->input->post('comment');
      $newComment->date = date("Y-n-j");
      if ($oldComment != NULL){
        array_push($oldComment->comments, $newComment);
      }else {
        $oldComment = new stdClass;
        $oldComment->comments = array($newComment);
      }
      $json = json_encode($oldComment);
      $this->teleworks_model->addComments($id, $json);
      if(isset($_GET['source'])){
        $source = $_GET['source'];
      }
      redirect("/$source/$id");
    }

    /**
     * Create a telework request
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function create() {
        $this->auth->checkIfOperationIsAllowed('create_teleworks');
        $data = getUserContext($this);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['title'] = lang('teleworks_create_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_request_telework');

        $this->form_validation->set_rules('startdate', lang('teleworks_create_field_start'), 'required|strip_tags');
        $this->form_validation->set_rules('startdatetype', 'Start Date type', 'required|strip_tags');
        $this->form_validation->set_rules('enddate', lang('teleworks_create_field_end'), 'required|strip_tags');
        $this->form_validation->set_rules('enddatetype', 'End Date type', 'required|strip_tags');
        $this->form_validation->set_rules('duration', lang('teleworks_create_field_duration'), 'required|strip_tags');
        $this->form_validation->set_rules('cause', lang('teleworks_create_field_cause'), 'strip_tags');
        $this->form_validation->set_rules('status', lang('teleworks_create_field_status'), 'required|strip_tags');

        if ($this->form_validation->run() === FALSE) {
            $this->load->model('contracts_model');
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('teleworks/create');
            $this->load->view('templates/footer');
        } else {
          //Prevent thugs to auto validate their telework requests
          if (!$this->is_hr && !$this->is_admin) {
            if ($this->input->post('status') > LMS_REQUESTED) {
                log_message('error', 'User #' . $this->session->userdata('id') . 
                    ' tried to submit a LR with an wrong status = ' . $this->input->post('status'));
                $_POST['status'] = LMS_REQUESTED;
            }
          }  

          if (function_exists('triggerCreateTeleworkRequest')) {
              triggerCreateTeleworkRequest($this);
          }
          $telework_id = $this->teleworks_model->setTeleworks($this->session->userdata('id'));
          $this->session->set_flashdata('msg', lang('teleworks_create_flash_msg_success'));

          //If the status is requested, send an email to the manager
          if ($this->input->post('status') == LMS_REQUESTED) {
              $this->sendMailOnTeleworkRequestCreation($telework_id);
          }
          if (isset($_GET['source'])) {
              redirect($_GET['source']);
          } else {
              redirect('teleworks');
          }
        }
    }
    
    /**
     * Create a telework request for a campaign
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function createForCampaign()
    {   
        $this->auth->checkIfOperationIsAllowed('create_campaign_teleworks');
        $data = getUserContext($this);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->lang->load('calendar_lang', $this->language);        
        $data['title'] = lang('teleworks_create_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_request_telework');
        
        $this->form_validation->set_rules('campaign', lang('teleworks_create_field_campaign'), 'required|strip_tags');
        $this->form_validation->set_rules('recurrence', lang('teleworks_create_field_recurrence'), 'required|strip_tags');
        $this->form_validation->set_rules('day', lang('teleworks_create_field_day'), 'required|strip_tags');        
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->model('telework_campaign_model');
            $data['campaigns'] = $this->telework_campaign_model->getActiveCampaigns();
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('teleworks/createforcampaign');
            $this->load->view('templates/footer');
        } else {
            //Prevent thugs to auto validate their telework requests
            if (!$this->is_hr && !$this->is_admin) {
                if ($this->input->post('status') > LMS_REQUESTED) {
                    log_message('error', 'User #' . $this->session->userdata('id') .
                        ' tried to submit a LR with an wrong status = ' . $this->input->post('status'));
                    $_POST['status'] = LMS_REQUESTED;
                }
            }
            
            if (function_exists('triggerCreateTeleworkRequest')) {
                triggerCreateTeleworkRequest($this);
            }
            $telework_ids = $this->teleworks_model->setTeleworksForCampaign($this->session->userdata('id'));
            $this->session->set_flashdata('msg', lang('teleworks_create_flash_msg_success'));
            
            //If the status is requested, send an email to the manager
            if ($this->input->post('status') == LMS_REQUESTED  && count($telework_ids) > 0) {
                $this->sendMailOnCampaignTeleworkRequestCreation($telework_ids, $this->session->userdata('id'));
            }
            if (isset($_GET['source'])) {
                redirect($_GET['source']);
            } else {
                redirect('teleworks');
            }
        }
    }

    /**
     * Edit a telework request
     * @param int $id Identifier of the telework request
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function edit($id) {
        $this->auth->checkIfOperationIsAllowed('edit_teleworks');
        $this->load->model('users_model');
        $this->load->model('status_model');
        $data = getUserContext($this);
        $data['telework'] = $this->teleworks_model->getTeleworkWithComments($id);
        //Check if exists
        if (empty($data['telework'])) {
            redirect('notfound');
        }
        //If the user is not its own manager and if the telework is
        //already requested, the employee can't modify it
        if (!$this->is_hr) {
            if (($this->session->userdata('manager') != $this->user_id) &&
                    $data['telework']['status'] != LMS_PLANNED) {
                if ($this->config->item('edit_rejected_requests') == FALSE ||
                    $data['telework']['status'] != LMS_REJECTED) {//Configuration switch that allows editing the rejected telework requests
                    log_message('error', 'User #' . $this->user_id . ' illegally tried to edit telework #' . $id);
                    $this->session->set_flashdata('msg', lang('teleworks_edit_flash_msg_error'));
                    redirect('teleworks');
                 }
            }
        } //Admin

        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('startdate', lang('teleworks_edit_field_start'), 'required|strip_tags');
        $this->form_validation->set_rules('startdatetype', 'Start Date type', 'required|strip_tags');
        $this->form_validation->set_rules('enddate', lang('teleworks_edit_field_end'), 'required|strip_tags');
        $this->form_validation->set_rules('enddatetype', 'End Date type', 'required|strip_tags');
        $this->form_validation->set_rules('duration', lang('teleworks_edit_field_duration'), 'required|strip_tags');
        $this->form_validation->set_rules('cause', lang('teleworks_edit_field_cause'), 'strip_tags');
        $this->form_validation->set_rules('status', lang('teleworks_edit_field_status'), 'required|strip_tags');

        if ($this->form_validation->run() === FALSE) {
            $data['title'] = lang('teleworks_edit_html_title');
            $data['help'] = $this->help->create_help_link('global_link_doc_page_request_telework');
            $data['id'] = $id;
            $this->load->model('contracts_model');
            $this->load->model('users_model');
            $data['name'] = $this->users_model->getName($data['telework']['employee']);
            if (isset($data["telework"]["comments"])){
              $last_comment = new stdClass();;
              foreach ($data["telework"]["comments"]->comments as $comments_item) {
                if($comments_item->type == "comment"){
                  $comments_item->author = $this->users_model->getName($comments_item->author);
                  $comments_item->in = "in";
                  $last_comment->in="";
                  $last_comment=$comments_item;
                } else if($comments_item->type == "change"){
                  $comments_item->status = $this->status_model->getName($comments_item->status_number);
                }
              }
            }
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('teleworks/edit', $data);
            $this->load->view('templates/footer');
        } else {
          //Prevent thugs to auto validate their telework requests
          if (!$this->is_hr && !$this->is_admin) {
            if ($this->input->post('status') == LMS_ACCEPTED) {
                log_message('error', 'User #' . $this->session->userdata('id') . 
                    ' tried to submit a LR with an wrong status = ' . $this->input->post('status'));
                $_POST['status'] = LMS_REQUESTED;
            }
            if ($this->input->post('status') == LMS_CANCELED) {
                log_message('error', 'User #' . $this->session->userdata('id') . 
                    ' tried to submit a LR with an wrong status = ' . $this->input->post('status'));
                $_POST['status'] = LMS_CANCELLATION;
            }
          }

            //Users must use an existing telework type, otherwise
            $this->load->model('contracts_model');

            $this->teleworks_model->updateTeleworks($id);       //We don't use the return value
            $this->session->set_flashdata('msg', lang('teleworks_edit_flash_msg_success'));
            //If the status is requested or cancellation, send an email to the manager
            if ($this->input->post('status') == LMS_REQUESTED) {
                $this->sendMailOnTeleworkRequestCreation($id);
            }
            if ($this->input->post('status') == LMS_CANCELLATION) {
                $this->sendMailOnTeleworkRequestCreation($id);
            }
            if (isset($_GET['source'])) {
                redirect($_GET['source']);
            } else {
                redirect('teleworks');
            }
        }
    }

    /**
     * change a the status of a planned request to  requested
     * @param int $id id of the telework
     * @author Emilien NICOLAS <milihhard1996@gmail.com>
     */
     public function requestTelework($id){
       $telework = $this->teleworks_model->getTeleworks($id);
       if (empty($telework)) {
           redirect('notfound');
       } else {
           //Only the connected user can reject its own requests
           if ($this->user_id != $telework['employee']){
               $this->session->set_flashdata('msg', lang('teleworks_cancellation_flash_msg_error'));
               redirect('teleworks');
           }
           //We can cancel a telework request only with a status 'Accepted'
           if ($telework['status'] == LMS_PLANNED) {
               $this->teleworks_model->switchStatus($id, LMS_REQUESTED);
               $this->sendMailOnTeleworkRequestCreation($id);
               $this->session->set_flashdata('msg', lang('teleworks_cancellation_flash_msg_success'));
               redirect('teleworks');
           } else {
               $this->session->set_flashdata('msg', lang('teleworks_cancellation_flash_msg_error'));
               redirect('teleworks');
           }
       }
     }

    /**
     * Send an email reminder (so as to remind to the manager that he
     * must either accept/reject a request or a cancellation)
     * @param int $id Identifier of the telework request
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function reminder($id) {
        $this->auth->checkIfOperationIsAllowed('create_teleworks');
        $data = getUserContext($this);
        $telework = $this->teleworks_model->getTeleworks($id);
        switch($telework['status']) {
            case LMS_REQUESTED: //Requested
                $this->sendMailOnTeleworkRequestCreation($id, TRUE);
                break;
            case LMS_CANCELLATION: //Cancellation
                $this->sendMailOnTeleworkRequestCancellation($id, TRUE);
                break;
        }
        $this->session->set_flashdata('msg', lang('teleworks_reminder_flash_msg_success'));
        if (isset($_GET['source'])) {
            redirect($_GET['source']);
        } else {
            redirect('teleworks');
        }
    }

    /**
     * Send a telework request creation email to the manager of the connected employee
     * @param int $id Telework request identifier
     * @param int $reminder In case where the employee wants to send a reminder
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    private function sendMailOnTeleworkRequestCreation($id, $reminder=FALSE) {
        $this->load->model('users_model');
        $this->load->model('delegations_model');
        //We load everything from DB as the LR can be edited from HR/Employees
        $telework = $this->teleworks_model->getTeleworks($id);
        $user = $this->users_model->getUsers($telework['employee']);
        $manager = $this->users_model->getUsers($user['manager']);
        if (empty($manager['email'])) {
            $this->session->set_flashdata('msg', lang('teleworks_create_flash_msg_error'));
        } else {
            //Send an e-mail to the manager
            $this->load->library('email');
            $this->load->library('polyglot');
            $usr_lang = $this->polyglot->code2language($manager['language']);

            //We need to instance an different object as the languages of connected user may differ from the UI lang
            $lang_mail = new CI_Lang();
            $lang_mail->load('email', $usr_lang);
            $lang_mail->load('global', $usr_lang);

            if ($reminder) {
                $this->sendGenericMail($telework, $user, $manager, $lang_mail,
                    $lang_mail->line('email_telework_request_reminder') . ' ' .
                    $lang_mail->line('email_telework_request_creation_title'),
                    $lang_mail->line('email_telework_request_reminder') . ' ' .
                    $lang_mail->line('email_telework_request_creation_subject'),
                    'telework_request');
            } else {
                $this->sendGenericMail($telework, $user, $manager, $lang_mail,
                    $lang_mail->line('email_telework_request_creation_title'),
                    $lang_mail->line('email_telework_request_creation_subject'),
                    'telework_request');
            }
        }
    }
    
    /**
     * Send a telework request creation email for a campaign to the manager of the connected employee
     * @param int $reminder In case where the employee wants to send a reminder
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    private function sendMailOnCampaignTeleworkRequestCreation($ids, $employee) {
        $this->load->model('users_model');
        $this->load->model('delegations_model');
        //We load everything from DB as the LR can be edited from HR/Employees
        
        $teleworks = array();
        for ($i = 0; $i < count($ids); $i ++) {
            $teleworks[] = $this->teleworks_model->getTeleworks($ids[$i]);
        }
        $user = $this->users_model->getUsers($employee);
        $manager = $this->users_model->getUsers($user['manager']);
        if (empty($manager['email'])) {
            $this->session->set_flashdata('msg', lang('teleworks_create_flash_msg_error'));
        } else {
            //Send an e-mail to the manager
            $this->load->library('email');
            $this->load->library('polyglot');
            $usr_lang = $this->polyglot->code2language($manager['language']);
            
            //We need to instance an different object as the languages of connected user may differ from the UI lang
            $lang_mail = new CI_Lang();
            $lang_mail->load('email', $usr_lang);
            $lang_mail->load('global', $usr_lang);

            $this->sendMailForCampaign($teleworks, $user, $manager, $lang_mail,
                $lang_mail->line('email_campaign_telework_request_creation_title'),
                $lang_mail->line('email_campaign_telework_request_creation_subject'),
                'campaign_telework_request');
        }
    }    

    /**
     * Send a notification to the manager of the connected employee when the
     * telework request has been canceled by its collaborator.
     * @param int $id Telework request identifier
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    private function sendMailOnTeleworkRequestCanceled($id) {
        $this->load->model('users_model');
        $this->load->model('types_model');
        $this->load->model('delegations_model');
        //We load everything from DB as the LR can be edited from HR/Employees
        $telework = $this->teleworks_model->getTeleworks($id);
        $user = $this->users_model->getUsers($telework['employee']);
        $manager = $this->users_model->getUsers($user['manager']);
        if (empty($manager['email'])) {
            //TODO: create specific error message when the employee has no manager
            $this->session->set_flashdata('msg', lang('teleworks_cancel_flash_msg_error'));
        } else {
            //Send an e-mail to the manager
            $this->load->library('email');
            $this->load->library('polyglot');
            $usr_lang = $this->polyglot->code2language($manager['language']);

            //We need to instance an different object as the languages of connected user may differ from the UI lang
            $lang_mail = new CI_Lang();
            $lang_mail->load('email', $usr_lang);
            $lang_mail->load('global', $usr_lang);

            $this->sendGenericMail($telework, $user, $manager, $lang_mail,
                $lang_mail->line('email_telework_request_cancellation_title'),
                $lang_mail->line('email_telework_request_cancellation_subject'),
                'telework_cancelled');
        }
    }

    /**
     * Send a telework request cancellation email to the manager of the connected employee
     * @param int $id Telework request identifier
     * @param int $reminder In case where the employee wants to send a reminder
     * @author Guillaume Blaquiere <guillaume.blaquiere@gmail.com>
     */
    private function sendMailOnTeleworkRequestCancellation($id, $reminder=FALSE) {
        $this->load->model('users_model');
        $this->load->model('types_model');
        $this->load->model('delegations_model');
        //We load everything from DB as the LR can be edited from HR/Employees
        $telework = $this->teleworks_model->getTeleworks($id);
        $user = $this->users_model->getUsers($telework['employee']);
        $manager = $this->users_model->getUsers($user['manager']);
        if (empty($manager['email'])) {
            $this->session->set_flashdata('msg', lang('teleworks_cancel_flash_msg_error'));
        } else {
            //Send an e-mail to the manager
            $this->load->library('email');
            $this->load->library('polyglot');
            $usr_lang = $this->polyglot->code2language($manager['language']);

            //We need to instance an different object as the languages of connected user may differ from the UI lang
            $lang_mail = new CI_Lang();
            $lang_mail->load('email', $usr_lang);
            $lang_mail->load('global', $usr_lang);

            if ($reminder) {
                $this->sendGenericMail($telework, $user, $manager, $lang_mail,
                    $lang_mail->line('email_telework_request_reminder') . ' ' .
                    $lang_mail->line('email_telework_request_cancellation_title'),
                    $lang_mail->line('email_telework_request_reminder') . ' ' .
                    $lang_mail->line('email_telework_request_cancellation_subject'),
                    'telework_request');
            } else {
                $this->sendGenericMail($telework, $user, $manager, $lang_mail,
                    $lang_mail->line('email_telework_request_cancellation_title'),
                    $lang_mail->line('email_telework_request_cancellation_subject'),
                    'telework_cancel');
            }
        }
    }

    /**
     * Send a generic email from the collaborator to the manager (delegate in copy) when a telework request is created or cancelled
     * @param $telework Telework request
     * @param $user Connected employee
     * @param $manager Manger of connected employee
     * @param $lang_mail Email language library
     * @param $title Email Title
     * @param $detailledSubject Email detailled Subject
     * @param $emailModel template email to use
     * @author Guillaume Blaquiere <guillaume.blaquiere@gmail.com>
     *
     */
    private function sendGenericMail($telework, $user, $manager, $lang_mail, $title, $detailledSubject, $emailModel) {

        $date = new DateTime($telework['startdate']);
        $startdate = $date->format($lang_mail->line('global_date_format'));
        $date = new DateTime($telework['enddate']);
        $enddate = $date->format($lang_mail->line('global_date_format'));

        $comments=$telework['comments'];
        $comment = '';
        if(!empty($comments)){
          $comments=json_decode($comments);
          foreach ($comments->comments as $comments_item) {
            if($comments_item->type == "comment"){
              $comment = $comments_item->value;
            }
          }
        }
        log_message('info', "comment : " . $comment);
        $this->load->library('parser');
        $data = array(
            'Title' => $title,
            'Firstname' => $user['firstname'],
            'Lastname' => $user['lastname'],
            'StartDate' => $startdate,
            'EndDate' => $enddate,
            'StartDateType' => $lang_mail->line($telework['startdatetype']),
            'EndDateType' => $lang_mail->line($telework['enddatetype']),
            //'Type' => $this->types_model->getName($telework['type']),
            'Duration' => $telework['duration'],
            'Reason' => $telework['cause'],
            'BaseUrl' => $this->config->base_url(),
            'TeleworkId' => $telework['id'],
            'UserId' => $this->user_id,
            'Comments' => $comment
        );
        $message = $this->parser->parse('emails/' . $manager['language'] . '/'.$emailModel, $data, TRUE);

        $to = $manager['email'];
        $subject = $detailledSubject . ' ' . $user['firstname'] . ' ' . $user['lastname'];
        //Copy to the delegates, if any
        $cc = NULL;
        $delegates = $this->delegations_model->listMailsOfDelegates($manager['id']);
        if ($delegates != '') {
            $cc = $delegates;
        }

        sendMailByWrapper($this, $subject, $message, $to, $cc);
    }

    /**
     * Delete a telework request
     * @param int $id identifier of the telework request
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function delete($id) {
        $can_delete = FALSE;
        //Test if the telework request exists
        $teleworks = $this->teleworks_model->getTeleworks($id);
        if (empty($teleworks)) {
            redirect('notfound');
        } else {
            if ($this->is_hr) {
                $can_delete = TRUE;
            } else {
                if (($teleworks['status'] == LMS_PLANNED) &&
                        $teleworks['employee'] == $this->user_id) {
                    $can_delete = TRUE;
                }
                if ($this->config->item('delete_rejected_requests') == TRUE ||
                    $teleworks['status'] == LMS_REJECTED) {
                    $can_delete = TRUE;
                }
            }
            if ($can_delete === TRUE) {
                $this->teleworks_model->deleteTelework($id);
            } else {
                $this->session->set_flashdata('msg', lang('teleworks_delete_flash_msg_error'));
                if (isset($_GET['source'])) {
                    redirect($_GET['source']);
                } else {
                    redirect('teleworks');
                }
            }
        }
        $this->session->set_flashdata('msg', lang('teleworks_delete_flash_msg_success'));
        if (isset($_GET['source'])) {
            redirect($_GET['source']);
        } else {
            redirect('teleworks');
        }
    }

    /**
     * Ask for the cancellation of a telework request. Extend the workflow with
     * cancellation and canceled steps.
     * Change of behavior (compared to prior versions):
     *  - Manager and HR do not cancel telework requests, they reject them.
     *  - Only the connected user can reject its own requests.
     *  - If the cancellation request is accepted, it goes on accepted
     * @param int $id identifier of the telework request
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function cancellation($id) {
        //Test if the telework request exists
        $telework = $this->teleworks_model->getTeleworks($id);
        if (empty($telework)) {
            redirect('notfound');
        } else {
            //Only the connected user can reject its own requests
            if ($this->user_id != $telework['employee']){
                $this->session->set_flashdata('msg', lang('teleworks_cancellation_flash_msg_error'));
                redirect('teleworks');
            }
            //We can cancel a telework request only with a status 'Accepted'
            if ($telework['status'] == LMS_ACCEPTED) {
                $this->teleworks_model->switchStatus($id, LMS_CANCELLATION);
                $this->sendMailOnTeleworkRequestCancellation($id);
                $this->session->set_flashdata('msg', lang('teleworks_cancellation_flash_msg_success'));
                redirect('teleworks');
            } else {
                $this->session->set_flashdata('msg', lang('teleworks_cancellation_flash_msg_error'));
                redirect('teleworks');
            }
        }
    }

    /**
     * Allows the employee to cancel a requested telework request.
     * Only the connected user can reject its own requests.
     * Send a notification to the line manager.
     * Next status is 'Canceled'
     * @param int $id identifier of the telework request
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function cancel($id) {
        //Test if the telework request exists
        $telework = $this->teleworks_model->getTeleworks($id);
        if (empty($telework)) {
            redirect('notfound');
        } else {
            //Only the connected user can reject its own requests
            if ($this->user_id != $telework['employee']){
                $this->session->set_flashdata('msg', lang('teleworks_cancellation_flash_msg_error'));
                redirect('teleworks');
            }
            //We can cancel a telework request only with a status 'Requested'
            if ($telework['status'] == LMS_REQUESTED) {
                $this->teleworks_model->switchStatus($id, LMS_CANCELED);
                $this->sendMailOnTeleworkRequestCanceled($id);
                $this->session->set_flashdata('msg', lang('teleworkrequests_cancellation_accept_flash_msg_success'));
                redirect('teleworks');
            } else {
                $this->session->set_flashdata('msg', lang('teleworks_cancellation_flash_msg_error'));
                redirect('teleworks');
            }
        }
    }

    /**
     * Export the list of all teleworks into an Excel file
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function export() {
        $this->load->view('teleworks/export');
    }

    /**
     * Ajax endpoint : Send a list of fullcalendar events
     * @param int $entity_id Entity identifier
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function organization($entity_id) {
        header("Content-Type: application/json");
        $start = $this->input->get('start', TRUE);
        $end = $this->input->get('end', TRUE);
        $children = filter_var($this->input->get('children', TRUE), FILTER_VALIDATE_BOOLEAN);
        $statuses = $this->input->get('statuses');
        echo $this->teleworks_model->department($entity_id, $start, $end, $children, $statuses);
    }

    /**
     * Ajax endpoint : Send a list of fullcalendar events
     * @param int $list_id List identifier
     * @author Emilien NICOLAS <milihhard1996@gmail.com>
     */
    public function listEvents($list_id){
      header("Content-Type: application/json");
      $start = $this->input->get('start', TRUE);
      $end = $this->input->get('end', TRUE);
      $statuses = $this->input->get('statuses');
      echo $this->teleworks_model->getListRequest($list_id, $start, $end, $statuses);
    }

    /**
     * Ajax endpoint. Result varies according to input :
     *  - difference between the entitled and the taken days
     *  - try to calculate the duration of the telework
     *  - try to detect overlapping telework requests
     *  If the user is linked to a contract, returns end date of the yearly telework period or NULL
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function validate() {
        header("Content-Type: application/json");
        $id = $this->input->post('id', TRUE);
        // The above parameters could cause an SQL injection vulnerability due to the non standard
        // SQL query in teleworks_model::detectOverlappingTeleworks
        // SQL query in teleworks_model::detectOverlappingLeavesForTelework
        $date = $this->input->post('startdate', TRUE);
        $start = DateTime::createFromFormat('Y-m-d', $date);
        $startdate = ($start && $start->format('Y-m-d') === $date) ? $date : '1970-01-01';
        $date = $this->input->post('enddate', TRUE);
        $end = DateTime::createFromFormat('Y-m-d', $date);
        $enddate = ($end && $end->format('Y-m-d') === $date) ? $date : '1970-01-01';
        $startdatetype = $this->input->post('startdatetype', TRUE); // Mandatory field checked by frontend
        $enddatetype = $this->input->post('enddatetype', TRUE); // Mandatory field checked by frontend
        $telework_id = $this->input->post('telework_id', TRUE);
        $teleworkValidator = new stdClass();
        $deductDayOff = FALSE;
        $teleworkValidator->errors = 0;
        $this->load->model('users_model');
        $employee = $this->users_model->getUsers($id);
        if (isset($id) && isset($startdate) && isset($enddate)) {
            if (isset($telework_id)) {
                $teleworkValidator->overlap = $this->teleworks_model->detectOverlappingTeleworks($id, $startdate, $enddate, $startdatetype, $enddatetype, $telework_id);
            } else {
                $teleworkValidator->overlap = $this->teleworks_model->detectOverlappingTeleworks($id, $startdate, $enddate, $startdatetype, $enddatetype);
            }
            $teleworkValidator->overlapleaves = $this->teleworks_model->detectOverlappingLeavesForTelework($id, $startdate, $enddate, $startdatetype, $enddatetype);
            $teleworkValidator->overlaptimeorganisations = $this->teleworks_model->detectOverlappingTimeOrganisations($id, $startdate, $enddate, $startdatetype, $enddatetype);
            if ($teleworkValidator->overlap)
                $teleworkValidator->errors = $teleworkValidator->errors + 1;
            if ($teleworkValidator->overlapleaves)
                $teleworkValidator->errors = $teleworkValidator->errors + 1;
            if ($teleworkValidator->overlaptimeorganisations)
                $teleworkValidator->errors = $teleworkValidator->errors + 1;
        }

        // Returns end date of the yearly telework period or NULL if the user is not linked to a contract
        $this->load->model('contracts_model');
        $startentdate = NULL;
        $endentdate = NULL;
        $hasContract = $this->contracts_model->getBoundaries($id, $startentdate, $endentdate);
        $teleworkValidator->PeriodStartDate = $startentdate;
        $teleworkValidator->PeriodEndDate = $endentdate;
        $teleworkValidator->hasContract = $hasContract;

        // Add non working days between the two dates (including their type: morning, afternoon and all day)
        if (isset($id) && ($startdate != '') && ($enddate != '') && $hasContract === TRUE) {
            $this->load->model('dayoffs_model');
            $teleworkValidator->listDaysOff = $this->dayoffs_model->listOfDaysOffBetweenDates($id, $startdate, $enddate);
            // Sum non-working days and overlapping with day off detection
            $result = $this->teleworks_model->actualLengthAndDaysOff($id, $startdate, $enddate, $startdatetype, $enddatetype, $teleworkValidator->listDaysOff, $deductDayOff);
            $teleworkValidator->overlapDayOff = $result['overlapping'];
            $teleworkValidator->lengthDaysOff = $result['daysoff'];
            $teleworkValidator->length = $result['length'];
        }
        // If the user has no contract, simply compute a date difference between start and end dates
        if (isset($id) && isset($startdate) && isset($enddate) && $hasContract === FALSE) {
            $teleworkValidator->length = $this->teleworks_model->length($id, $startdate, $enddate, $startdatetype, $enddatetype);
        }

        $this->load->model('telework_rule_model');
        $this->load->model('telework_campaign_model');
        $this->load->model('time_organisation_model');

        // Check the dates of telework requests that correspond to the valid campaigns
        $campaigndates = $this->telework_campaign_model->getValidCampaignDates();
        $count = 0;
        foreach ($campaigndates as $campaigndate) {
            if ($startdate >= $campaigndate->startdate && $enddate <= $campaigndate->enddate)
                $count ++;
        }
        if ($count > 0)
            $teleworkValidator->forcampaigndates = TRUE;
        else {
            $teleworkValidator->forcampaigndates = FALSE;
            $teleworkValidator->errors = $teleworkValidator->errors + 1;
        }

        $teleworkrule = $this->telework_rule_model->getTeleworkRuleForEmployee($id);
        $timeorganisation = $this->time_organisation_model->getTimeOrganisationForEmployee($id);

        if ($teleworkrule) {
            // Check the deadline for a telework request
            $deadline = $teleworkrule['delay'];
            $now = new DateTime('now');
            $delay = $now->diff($start)->days;
            $teleworkValidator->delay = $delay;
            if (($delay < $deadline && $now->format('W') == $start->format('W')) || $startdate < $now->format('Y-m-d')) {
                $teleworkValidator->deadlinerespected = FALSE;                
                if (! $this->is_admin && ! $this->is_hr && $this->user_id != $employee['manager'])
                    $teleworkValidator->errors = $teleworkValidator->errors + 1;
            } else
                $teleworkValidator->deadlinerespected = TRUE;

            // Detect if the telework request exceeds the number of days allowed
            $weeklyAllowedTelework = $teleworkrule['limit'];
            if ($timeorganisation) {
                if ($timeorganisation['recurrence'] == 'Even' || $timeorganisation['recurrence'] == 'Odd') {
                    if (($timeorganisation['recurrence'] == 'Even' && $start->format('W') % 2 == 0) || ($timeorganisation['recurrence'] == 'Odd' && $start->format('W') % 2 != 0))
                        $weeklyAllowedTelework = $teleworkrule['limit'] - $timeorganisation['duration'];
                    else
                        $weeklyAllowedTelework = $teleworkrule['limit'];
                } else
                    $weeklyAllowedTelework = $teleworkrule['limit'] - $timeorganisation['duration'];
            }
            if (isset($telework_id)) {
                $limitExceeding = $this->teleworks_model->detectLimitExceeding($id, $startdate, $telework_id) + $teleworkValidator->length;
            } else {
                $limitExceeding = $this->teleworks_model->detectLimitExceeding($id, $startdate) + $teleworkValidator->length;
            }
            if ($limitExceeding > $weeklyAllowedTelework) {
                $teleworkValidator->limitExceeding = TRUE;
                $teleworkValidator->errors = $teleworkValidator->errors + 1;
            } else
                $teleworkValidator->limitExceeding = FALSE;
            $teleworkValidator->weeklyAllowedTelework = $weeklyAllowedTelework;
            $teleworkValidator->weeklyTeleworkDetected = $this->teleworks_model->detectLimitExceeding($id, $startdate);
        }
        
        $teleworkValidator->halfday = FALSE;
        $teleworkValidator->fractionalpart = 0;
        if (isset($teleworkValidator->length) && strpos($teleworkValidator->length, '.') !== false)
            $teleworkValidator->fractionalpart = explode('.', $teleworkValidator->length)[1];
        // Check the time organisation information and the rights for half-day telework request
        if ($timeorganisation && ($timeorganisation['daytype'] == 'Morning' || $timeorganisation['daytype'] == 'Afternoon')) {
            if (($timeorganisation['daytype'] == 'Afternoon' && $timeorganisation['day'] == $end->format('l') && $timeorganisation['daytype'] != $enddatetype && (($timeorganisation['recurrence'] == 'Odd' && $end->format('W') % 2 != 0) || ($timeorganisation['recurrence'] == 'Even' && $end->format('W') % 2 == 0) || $timeorganisation['recurrence'] == 'All')) || ($timeorganisation['daytype'] == 'Morning' && $timeorganisation['day'] == $start->format('l') && $timeorganisation['daytype'] != $startdatetype && (($timeorganisation['recurrence'] == 'Odd' && $start->format('W') % 2 != 0) || ($timeorganisation['recurrence'] == 'Even' && $start->format('W') % 2 == 0) || $timeorganisation['recurrence'] == 'All')))
                $teleworkValidator->halfday = TRUE;
        }

        if ($teleworkValidator->fractionalpart > 0 && ! $teleworkValidator->halfday && ! $this->is_admin && ! $this->is_hr && $this->user_id != $employee['manager'])
            $teleworkValidator->errors = $teleworkValidator->errors + 1;

        // Repeat start and end dates of the telework request
        $teleworkValidator->RequestStartDate = $startdate;
        $teleworkValidator->RequestEndDate = $enddate;

        echo json_encode($teleworkValidator);
    }
    
    
    /**
     * Send a generic email from the collaborator to the manager (delegate in copy) when a telework request is created or cancelled
     * @param $teleworks Campaign telework requests
     * @param $user Connected employee
     * @param $manager Manger of connected employee
     * @param $lang_mail Email language library
     * @param $title Email Title
     * @param $detailledSubject Email detailled Subject
     * @param $emailModel template email to use
     * @author Guillaume Blaquiere <guillaume.blaquiere@gmail.com>
     *
     */
    private function sendMailForCampaign($teleworks, $user, $manager, $lang_mail, $title, $detailledSubject, $emailModel) {
        $dates = null;
        for ($i = 0; $i < count($teleworks); $i ++) {
            $date = new DateTime($teleworks[$i]['startdate']);
            $dates .= lang($date->format("l")) . ' ' . $date->format($lang_mail->line('global_date_format')) . '<br />';
        }

        $this->load->library('parser');
        $data = array(
            'Title' => $title,
            'Firstname' => $user['firstname'],
            'Lastname' => $user['lastname'],
            'BaseUrl' => $this->config->base_url(),            
            'UserId' => $this->user_id,
            'Dates' => $dates
        );
        $message = $this->parser->parse('emails/' . $manager['language'] . '/'.$emailModel, $data, TRUE);
        
        $to = $manager['email'];
        $subject = $detailledSubject . ' ' . $user['firstname'] . ' ' . $user['lastname'];
        //Copy to the delegates, if any
        $cc = NULL;
        $delegates = $this->delegations_model->listMailsOfDelegates($manager['id']);
        if ($delegates != '') {
            $cc = $delegates;
        }
        
        sendMailByWrapper($this, $subject, $message, $to, $cc);
    }
}
