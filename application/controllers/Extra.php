<?php
/**
 * This controller contains the actions allowing an employee to list and manage its overtime requests
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

//We can define custom triggers before saving the extra request into the database
require_once FCPATH . "local/triggers/extra.php";

/**
 * This class allows an employee to list and manage its overtime requests
 * Since 0.4.3 a trigger is called at the creation, if the function triggerCreateExtraRequest is defined
 * see content of /local/triggers/extra.php
 */
class Extra extends CI_Controller {

    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {
        parent::__construct();
        setUserContext($this);
        $this->load->model('overtime_model');
        $this->lang->load('extra', $this->language);
        $this->lang->load('global', $this->language);
    }

    /**
     * Display the list of the overtime requests by the connected employee
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function index() {
        $this->auth->checkIfOperationIsAllowed('list_extra');
        $data = getUserContext($this);
        $this->lang->load('datatable', $this->language);
        $data['extras'] = $this->overtime_model->getExtrasOfEmployee($this->user_id);
        $data['title'] = lang('extra_index_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_extra_list');
        $data['flash_partial_view'] = $this->load->view('templates/flash', $data, TRUE);
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('extra/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Display an overtime request
     * @param string $source Page source (extra, overtime) (self, manager)
     * @param int $id identifier of the overtime request
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function view($source, $id) {
        $this->auth->checkIfOperationIsAllowed('view_extra');
        $data = getUserContext($this);
        $data['extra'] = $this->overtime_model->getExtras($id);
        if (empty($data['extra'])) {
            redirect('notfound');
        }

        //If the user is not its not HR, not manager and not the creator of the overtime
        //the employee can't see it, redirect to LR list
        if ($data['extra']['employee'] != $this->user_id) {
            if ((!$this->is_hr)) {
                $this->load->model('users_model');
                $employee = $this->users_model->getUsers($data['extra']['employee']);
                if ($employee['manager'] != $this->user_id) {
                    $this->load->model('delegations_model');
                    if (!$this->delegations_model->isDelegateOfManager($this->user_id, $employee['manager'])) {
                        log_message('error', 'User #' . $this->user_id . ' illegally tried to view overtime #' . $id);
                        redirect('extra');
                    }
                }
            } //Admin
        } //Current employee

        $data['title'] = lang('extra_view_hmtl_title');
        $data['source'] = $source;
        if ($source != 'extra') {
            if (empty($employee)) {
                $this->load->model('users_model');
                $data['name'] = $this->users_model->getName($data['extra']['employee']);
            } else {
                $data['name'] = $employee['firstname'] . ' ' . $employee['lastname'];
            }
        } else {
            $data['name'] = '';
        }
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('extra/view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Create an overtime request
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function create() {
        $this->auth->checkIfOperationIsAllowed('create_extra');
        $data = getUserContext($this);
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('date', lang('extra_create_field_date'), 'required|strip_tags');
        $this->form_validation->set_rules('duration', lang('extra_create_field_duration'), 'required|strip_tags');
        $this->form_validation->set_rules('cause', lang('extra_create_field_cause'), 'required|strip_tags');
        $this->form_validation->set_rules('status', lang('extra_create_field_status'), 'required|strip_tags');

        if ($this->form_validation->run() === FALSE) {
            $data['title'] = lang('extra_create_title');
            $data['help'] = $this->help->create_help_link('global_link_doc_page_create_overtime');
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('extra/create');
            $this->load->view('templates/footer');
        } else {
          //Prevent thugs to auto validate their extra requests
          if (!$this->is_hr && !$this->is_admin) {
            if ($this->input->post('status') > LMS_REQUESTED) {
              $_POST['status'] = LMS_REQUESTED;
            }
          }
          if (function_exists('triggerCreateExtraRequest')) {
              triggerCreateExtraRequest($this);
          }
          $extra_id = $this->overtime_model->setExtra();
          $this->session->set_flashdata('msg', lang('extra_create_msg_success'));
          //If the status is requested, send an email to the manager
          if ($this->input->post('status') == LMS_REQUESTED) {
              $this->sendMail($extra_id);
          }
          if (isset($_GET['source'])) {
              redirect($_GET['source']);
          } else {
              redirect('extra');
          }
        }
    }

    /**
     * Edit an overtime request
     * @param int $id identifier of the overtime request
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function edit($id) {
        $this->auth->checkIfOperationIsAllowed('edit_extra');
        $data = getUserContext($this);
        $data['extra'] = $this->overtime_model->getExtras($id);
        //Check if exists
        if (empty($data['extra'])) {
            redirect('notfound');
        }
        //If the user is not its own manager and if the overtime is
        //already requested, the employee can't modify it
        if (!$this->is_hr) {
            if (($this->session->userdata('manager') != $this->user_id) &&
                    $data['extra']['status'] != 1) {
                log_message('error', 'User #' . $this->user_id . ' illegally tried to edit overtime request #' . $id);
                $this->session->set_flashdata('msg', lang('extra_edit_msg_error'));
                redirect('extra');
            }
        } //Admin

        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('date', lang('extra_edit_field_date'), 'required|strip_tags');
        $this->form_validation->set_rules('duration', lang('extra_edit_field_duration'), 'required|strip_tags');
        $this->form_validation->set_rules('cause', lang('extra_edit_field_cause'), 'required|strip_tags');
        $this->form_validation->set_rules('status', lang('extra_edit_field_status'), 'required|strip_tags');

        if ($this->form_validation->run() === FALSE) {
            $data['title'] = lang('extra_edit_hmtl_title');
            $data['help'] = $this->help->create_help_link('global_link_doc_page_create_overtime');
            $data['id'] = $id;
            $this->load->model('users_model');
            $data['name'] = $this->users_model->getName($data['extra']['employee']);
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('extra/edit', $data);
            $this->load->view('templates/footer');
        } else {
          //Prevent thugs to auto validate their extra requests
          if (!$this->is_hr && !$this->is_admin) {
            if ($this->input->post('status') == LMS_ACCEPTED) {
              $_POST['status'] = LMS_REQUESTED;
            }
          }
          $this->overtime_model->updateExtra($id);       //We don't use the return value
          $this->session->set_flashdata('msg', lang('extra_edit_msg_success'));
          //If the status is requested, send an email to the manager
          if ($this->input->post('status') == LMS_REQUESTED) {
              $this->sendMail($id);
          }
          if (isset($_GET['source'])) {
              redirect($_GET['source']);
          } else {
              redirect('extra');
          }
        }
    }

    /**
     * Send a overtime request email to the manager of the connected employee
     * @param int $id overtime request identifier
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    private function sendMail($id) {
        $this->load->model('users_model');
        $this->load->model('delegations_model');
        $extra = $this->overtime_model->getExtras($id);
        $user = $this->users_model->getUsers($extra['employee']);
        $manager = $this->users_model->getUsers($user['manager']);

        //Test if the manager hasn't been deleted meanwhile
        if (empty($manager['email'])) {
            $this->session->set_flashdata('msg', lang('extra_create_msg_error'));
        } else {
            $acceptUrl = base_url() . 'overtime/accept/' . $id;
            $rejectUrl = base_url() . 'overtime/reject/' . $id;

            //Send an e-mail to the manager
            $this->load->library('email');
            $this->load->library('polyglot');
            $usr_lang = $this->polyglot->code2language($manager['language']);
            //We need to instance an different object as the languages of connected user may differ from the UI lang
            $lang_mail = new CI_Lang();
            $lang_mail->load('email', $usr_lang);
            $lang_mail->load('global', $usr_lang);

            $date = new DateTime($this->input->post('date'));
            $startdate = $date->format($lang_mail->line('global_date_format'));

            $this->load->library('parser');
            $data = array(
                'Title' => $lang_mail->line('email_extra_request_validation_title'),
                'Firstname' => $user['firstname'],
                'Lastname' => $user['lastname'],
                'Date' => $startdate,
                'Duration' => $this->input->post('duration'),
                'Cause' => $this->input->post('cause'),
                'UrlAccept' => $acceptUrl,
                'UrlReject' => $rejectUrl
            );
            $message = $this->parser->parse('emails/' . $manager['language'] . '/overtime', $data, TRUE);
            //Copy to the delegates, if any
            $delegates = $this->delegations_model->listMailsOfDelegates($manager['id']);
            $subject = $lang_mail->line('email_extra_request_reject_subject') . ' ' .
                                $user['firstname'] . ' ' .$user['lastname'];
            sendMailByWrapper($this, $subject, $message, $manager['email'], $delegates);
        }
    }

    /**
     * Delete an overtime request
     * @param int $id identifier of the overtime request
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function delete($id) {
        $can_delete = FALSE;
        //Test if the overtime request exists
        $extra = $this->overtime_model->getExtras($id);
        if (empty($extra)) {
            redirect('notfound');
        } else {
            if ($this->is_hr) {
                $can_delete = TRUE;
            } else {
                if ($extra['status'] == 1 ) {
                    $can_delete = TRUE;
                }
            }
            if ($can_delete === TRUE) {
                $this->overtime_model->deleteExtra($id);
                $this->session->set_flashdata('msg', lang('extra_delete_msg_success'));
            } else {
                $this->session->set_flashdata('msg', lang('extra_delete_msg_error'));
            }
        }
        if (isset($_GET['source'])) {
            redirect($_GET['source']);
        } else {
            redirect('extra');
        }
    }

    /**
     * Export the list of all ovetime requests of the connected user into an Excel file
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function export() {
        $this->load->view('extra/export');
    }
}
