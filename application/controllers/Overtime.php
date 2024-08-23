<?php
/**
 * This controller contains the actions allowing a manager to list and manage overtime requests
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class loads the actions allowing a manager to list and manage overtime requests
 */
class Overtime extends CI_Controller {

    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {
        parent::__construct();
        setUserContext($this);
        $this->load->model('overtime_model');
        $this->lang->load('overtime', $this->language);
        $this->lang->load('global', $this->language);
    }

    /**
     * Display the list of all overtime requests submitted to the connected manager.
     * Status is submitted or accepted/rejected depending on the filter parameter.
     * @param string $name Filter the list of submitted overtime requests (all or requested)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function index($filter = 'requested') {
        $this->auth->checkIfOperationIsAllowed('list_overtime');
        if ($filter == 'all') {
            $showAll = TRUE;
        } else {
            $showAll = FALSE;
        }

        $data = getUserContext($this);
        $this->lang->load('datatable', $this->language);
        $data['filter'] = $filter;
        $data['title'] = lang('overtime_index_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_overtime_list');
        $data['requests'] = $this->overtime_model->requests($this->user_id, $showAll);
        $data['flash_partial_view'] = $this->load->view('templates/flash', $data, TRUE);
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('overtime/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Accept an overtime request
     * @param int $id overtime request identifier
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function accept($id) {
        $this->auth->checkIfOperationIsAllowed('accept_overtime');
        $this->load->model('users_model');
        $this->load->model('delegations_model');
        $extra = $this->overtime_model->getExtras($id);
        if (empty($extra)) {
            redirect('notfound');
        }
        $employee = $this->users_model->getUsers($extra['employee']);
        $is_delegate = $this->delegations_model->isDelegateOfManager($this->user_id, $employee['manager']);
        if (($this->user_id == $employee['manager']) || ($this->is_hr)  || ($is_delegate)) {
            $this->overtime_model->acceptExtra($id);
            $this->sendMail($id);
            $this->session->set_flashdata('msg', lang('overtime_accept_flash_msg_success'));
            if (isset($_GET['source'])) {
                redirect($_GET['source']);
            } else {
                redirect('overtime');
            }
        } else {
            log_message('error', 'User #' . $this->user_id . ' illegally tried to accept extra #' . $id);
            $this->session->set_flashdata('msg', lang('overtime_accept_flash_msg_error'));
            redirect('leaves');
        }
    }

    /**
     * Reject an overtime request
     * @param int $id overtime request identifier
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function reject($id) {
        $this->auth->checkIfOperationIsAllowed('reject_overtime');
        $this->load->model('users_model');
        $this->load->model('delegations_model');
        $extra = $this->overtime_model->getExtras($id);
        if (empty($extra)) {
            redirect('notfound');
        }
        $employee = $this->users_model->getUsers($extra['employee']);
        $is_delegate = $this->delegations_model->isDelegateOfManager($this->user_id, $employee['manager']);
        if (($this->user_id == $employee['manager']) || ($this->is_hr)  || ($is_delegate)) {
            $this->overtime_model->rejectExtra($id);
            $this->sendMail($id);
            $this->session->set_flashdata('msg', lang('overtime_reject_flash_msg_success'));
            if (isset($_GET['source'])) {
                redirect($_GET['source']);
            } else {
                redirect('overtime');
            }
        } else {
            log_message('error', 'User #' . $this->user_id . ' illegally tried to reject extra #' . $id);
            $this->session->set_flashdata('msg', lang('overtime_reject_flash_msg_error'));
            redirect('leaves');
        }
    }

    /**
     * Send a overtime request email to the employee that requested the overtime
     * The method will check if the overtime request was accepted or rejected before sending the e-mail
     * @param int $id overtime request identifier
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    private function sendMail($id)
    {
        $this->load->model('users_model');
        $this->load->model('organization_model');
        $extra = $this->overtime_model->getExtras($id);
        //Load details about the employee (manager, supervisor of entity)
        $employee = $this->users_model->getUsers($extra['employee']);
        $supervisor = $this->organization_model->getSupervisor($employee['organization']);

        //Send an e-mail to the employee
        $this->load->library('email');
        $this->load->library('polyglot');
        $usr_lang = $this->polyglot->code2language($employee['language']);

        //We need to instance an different object as the languages of connected user may differ from the UI lang
        $lang_mail = new CI_Lang();
        $lang_mail->load('email', $usr_lang);
        $lang_mail->load('global', $usr_lang);

        $date = new DateTime($extra['date']);
        $startdate = $date->format($lang_mail->line('global_date_format'));

        $this->load->library('parser');
        $data = array(
            'Title' => $lang_mail->line('email_overtime_request_validation_title'),
            'Firstname' => $employee['firstname'],
            'Lastname' => $employee['lastname'],
            'Date' => $startdate,
            'Duration' => $extra['duration'],
            'Cause' => $extra['cause']
        );

        if ($extra['status'] == 3) {
            $message = $this->parser->parse('emails/' . $employee['language'] . '/overtime_accepted', $data, TRUE);
            $subject = $lang_mail->line('email_overtime_request_accept_subject');
        } else {
            $message = $this->parser->parse('emails/' . $employee['language'] . '/overtime_rejected', $data, TRUE);
            $subject = $lang_mail->line('email_overtime_request_reject_subject');
        }
        sendMailByWrapper($this, $subject, $message, $employee['email'], is_null($supervisor)?NULL:$supervisor->email);
    }

    /**
     * Export the list of all overtime requests (sent to the connected user) into an Excel file
     * @param string $name Filter the list of submitted overtime requests (all or requested)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function export($filter = 'requested') {
        $data['filter'] = $filter;
        $this->load->view('overtime/export', $data);
    }

}
