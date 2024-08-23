<?php
/**
 * This controller is the entry point for the REST API used by mobile and HTML5
 * Clients. They use CORS requests. Each call to end points uses BasicAuth 
 * except the preflight exchange. So it should be used with a TLS connection
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.3.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\AllOfException;

/**
 * This class implements a REST API for the leave requests
 */
class RestLeaves extends MY_RestController {

    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('leaves_model');
    }


    /**
     * Get the list of leave requests of the connected employee
     * @param int $leaveId Unique identifier of a leave request
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function leaves($leaveId = 0) {
        log_message('debug', '++leaves id=' . $leaveId);
        
        //Format and translate according to Accept-Language Header
        $langRest = new CI_Lang();
        $langRest->load('global', $this->language);

        $requests = NULL;
        if ($leaveId == 0) {
            $leaves = $this->leaves_model->getLeavesOfEmployee($this->user->id);
            log_message('debug', 'We have ' . (is_null($leaves)?0:count($leaves)) . ' leave(s)');
            foreach ($leaves as &$leave) {
                $leave['startdatetype'] = $langRest->line($leave['startdatetype']);
                $leave['enddatetype'] = $langRest->line($leave['enddatetype']);
                $leave['status_name'] = $langRest->line($leave['status_name']);
                $date = new DateTime($leave['startdate']);
                $leave['startdate'] = $date->format($langRest->line('global_date_format'));
                $date = new DateTime($leave['enddate']);
                $leave['enddate'] = $date->format($langRest->line('global_date_format'));
            }
        } else {
            $leaves = $this->leaves_model->getLeaves($leaveId);
            if (empty($leaves)) {
                $this->notFound();
            }
            if ($leaves['employee'] != $this->user->id) {
                $this->forbidden();
            }
            $date = new DateTime($leaves['startdate']);
            $leaves['startdate'] = $date->format($langRest->line('global_date_format'));
            $date = new DateTime($leaves['enddate']);
            $leaves['enddate'] = $date->format($langRest->line('global_date_format'));
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($leaves));
        log_message('debug', '--leaves');
    }

    /**
     * Create a leave request
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function create() {
        log_message('debug', '++create');
        try {
            $_POST['employee'] = $this->user->id;
            v::date()->assert($this->input->post('startdate'));
            v::date()->assert($this->input->post('enddate'));
            v::numeric()->assert($this->input->post('status'));
            v::numeric()->assert($this->input->post('type'));
            v::oneOf(v::equals('Morning'), v::equals('Afternoon')
                    )->assert($this->input->post('startdatetype'));
            v::oneOf(v::equals('Morning'), v::equals('Afternoon')
                    )->assert($this->input->post('enddatetype'));
            v::numeric()->assert($this->input->post('duration'));

            //Prevent users to auto validate their leave requests
            if (!$this->user->isHr && !$this->user->isAdmin) {
                if ($this->input->post('status') > LMS_REQUESTED) {
                    log_message('error', 'User #' . $this->user->id . ' tried to submit an wrong status = ' . 
                        $this->input->post('status'));
                    $_POST['status'] = LMS_REQUESTED;
                }
            }
            
            //Users must use an existing leave type, otherwise
            //force leave type to default leave type
            $this->load->model('contracts_model');
            $leaveTypesDetails = $this->contracts_model->getLeaveTypesDetailsOTypesForUser($this->user->id);
            if (!array_key_exists($this->input->post('type'), $leaveTypesDetails->types)) {
                log_message('error', 'User #' . $this->user->id . ' tried to submit an wrong LR type = ' . 
                $this->input->post('type'));
                $_POST['type'] = $leaveTypesDetails->defaultType;
                log_message('debug', 'LR type forced to ' . $leaveTypesDetails->defaultType); 
            }

            $leaveId = $this->leaves_model->setLeaves($this->user->id);
            //If the status is requested, send an email to the manager
            if ($this->input->post('status') == LMS_REQUESTED) {
                $this->sendMailOnLeaveRequestCreation($leaveId);
            }
        } catch (AllOfException $exception) {
            log_message('error', 'An exception occured while creating the leave request' . 
                $exception->getFullMessage());
            $this->badRequest();
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($leaveId));
        log_message('debug', '--create / new id=' . $leaveId);
    }

    /**
     * Edit a leave request
     * @param int $leaveId Identifier of the leave request
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function edit($leaveId) {
        log_message('debug', '++edit / LR = ' . $leaveId);
        $leave = $this->leaves_model->getLeaveWithComments($leaveId);
        //Check if exists
        if (empty($leave)) {
            $this->notFound();
        }
        //If the user is not its own manager and if the leave is
        //already requested, the employee can't modify it
        if (!$this->user->isHr) {
            if (($this->user->manager != $this->user->id) &&
                    $leave['status'] != LMS_PLANNED) {
                if ($this->config->item('edit_rejected_requests') == FALSE ||
                    $leave['status'] != LMS_REJECTED) {//Configuration switch that allows editing the rejected leave requests
                    log_message('error', 'User #' . $this->user->id . ' illegally tried to edit leave #' . $leaveId);
                    $this->forbidden();
                 }
            }
        } //Admin

        //Prevent thugs to auto validate their leave requests
        if (!$this->user->isHr && !$this->user->isAdmin) {
            if ($this->input->post('status') == LMS_ACCEPTED) {
                log_message('error', 'User #' . $this->user->id . 
                    ' tried to submit a LR with an wrong status = ' . $this->input->post('status'));
                $_POST['status'] = LMS_REQUESTED;
            }
            if ($this->input->post('status') == LMS_CANCELED) {
                log_message('error', 'User #' . $this->user->id . 
                    ' tried to submit a LR with an wrong status = ' . $this->input->post('status'));
                $_POST['status'] = LMS_CANCELLATION;
            }
        }

        //Disallow users to modify someone else's LR except if HR or manager
        if ($leave['employee'] != $this->user->id) {
            if (!$this->user->isHr && !$this->user->isAdmin) {
                $this->forbidden();
            }
        }

        //Users must use an existing leave type, otherwise
        //force leave type to default leave type
        $this->load->model('contracts_model');
        $leaveTypesDetails = $this->contracts_model->getLeaveTypesDetailsOTypesForUser($this->user->id);
        if (!array_key_exists($this->input->post('type'), $leaveTypesDetails->types)) {
            log_message('error', 'User #' . $this->user->id . ' tried to submit an wrong LR type = ' . 
            $this->input->post('type'));
            $_POST['type'] = $leaveTypesDetails->defaultType;
            log_message('debug', 'LR type forced to ' . $leaveTypesDetails->defaultType); 
        }

        try {
            $_POST['employee'] = $this->user->id;
            v::date()->assert($this->input->post('startdate'));
            v::date()->assert($this->input->post('enddate'));
            v::numeric()->assert($this->input->post('status'));
            v::numeric()->assert($this->input->post('type'));
            v::oneOf(v::equals('Morning'), v::equals('Afternoon')
                    )->assert($this->input->post('startdatetype'));
            v::oneOf(v::equals('Morning'), v::equals('Afternoon')
                    )->assert($this->input->post('enddatetype'));
            v::numeric()->assert($this->input->post('duration'));
            
            $this->leaves_model->updateLeaves($leaveId, $this->user->id);       //We don't use the return value

        } catch (AllOfException $exception) {
            log_message('error', 'An exception occured while editing the leave request' . 
                $exception->getFullMessage());
            $this->badRequest();
        }
        
        //If the status is requested or cancellation, send an email to the manager
        if ($this->input->post('status') == LMS_REQUESTED) {
            $this->sendMailOnLeaveRequestCreation($leaveId);
        }
        if ($this->input->post('status') == LMS_CANCELLATION) {
            $this->sendMailOnLeaveRequestCreation($leaveId);
        }
        log_message('debug', '--edit');
    }
    
    /**
     * Delete a leave request
     * @param int $leaveId identifier of the leave request
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function delete($leaveId) {
        log_message('debug', '++delete / Leave ID = ' . $leaveId);
        $canDelete = FALSE;
        //Test if the leave request exists
        $leaves = $this->leaves_model->getLeaves($leaveId);
        if (empty($leaves)) {
            $this->notFound();
        } else {
            if ($this->user->isHr) {
                $canDelete = TRUE;
            } else {
                if (($leaves['status'] == LMS_PLANNED) &&
                        $leaves['employee'] == $this->user->id) {
                    $canDelete = TRUE;
                }
                if ($this->config->item('delete_rejected_requests') == TRUE ||
                    $leaves['status'] == LMS_REJECTED) {
                    $canDelete = TRUE;
                }
            }
            if ($canDelete === TRUE) {
                $this->leaves_model->deleteLeave($leaveId, $this->user->id);
            } else {
                $this->forbidden();
            }
        }
        log_message('debug', '--delete');
    }

    /**
     * Send a leave request creation email to the manager of the connected employee
     * @param int $leaveId Leave request identifier
     * @param int $reminder In case where the employee wants to send a reminder
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    private function sendMailOnLeaveRequestCreation($leaveId, $reminder=FALSE) {
        log_message('debug', '++sendMailOnLeaveRequestCreation');
        $this->load->model('users_model');
        $this->load->model('types_model');
        $this->load->model('delegations_model');
        //We load everything from DB as the LR can be edited from HR/Employees
        $leave = $this->leaves_model->getLeaves($leaveId);
        $user = $this->users_model->getUsers($leave['employee']);
        $manager = $this->users_model->getUsers($user['manager']);
        if (!empty($manager['email'])) {
            //Send an e-mail to the manager
            $this->load->library('email');
            $this->load->library('polyglot');
            $usr_lang = $this->polyglot->code2language($manager['language']);

            //We need to instance an different object as the languages of connected user may differ from the UI lang
            $lang_mail = new CI_Lang();
            $lang_mail->load('email', $usr_lang);
            $lang_mail->load('global', $usr_lang);

            if ($reminder) {
                $this->sendGenericMail($leave, $user, $manager, $lang_mail,
                    $lang_mail->line('email_leave_request_reminder') . ' ' .
                    $lang_mail->line('email_leave_request_creation_title'),
                    $lang_mail->line('email_leave_request_reminder') . ' ' .
                    $lang_mail->line('email_leave_request_creation_subject'),
                    'request');
            } else {
                $this->sendGenericMail($leave, $user, $manager, $lang_mail,
                    $lang_mail->line('email_leave_request_creation_title'),
                    $lang_mail->line('email_leave_request_creation_subject'),
                    'request');
            }
        }
        log_message('debug', '--sendMailOnLeaveRequestCreation');
    }

    /**
     * Send a generic email from the collaborator to the manager (delegate in copy) when a leave request is created or cancelled
     * @param $leave Leave request
     * @param $user Connected employee
     * @param $manager Manger of connected employee
     * @param $lang_mail Email language library
     * @param $title Email Title
     * @param $detailledSubject Email detailled Subject
     * @param $emailModel template email to use
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     *
     */
    private function sendGenericMail($leave, $user, $manager, $lang_mail, $title, $detailledSubject, $emailModel) {
        log_message('debug', '++sendGenericMail');
        $date = new DateTime($leave['startdate']);
        $startdate = $date->format($lang_mail->line('global_date_format'));
        $date = new DateTime($leave['enddate']);
        $enddate = $date->format($lang_mail->line('global_date_format'));

        $comments = $leave['comments'];
        $comments = json_decode($comments);
        $comment = '';
        if(!empty($comments)){
          foreach ($comments->comments as $comments_item) {
            if($comments_item->type == "comment"){
              $comment = $comments_item->value;
            }
          }
        }

        log_message('debug', "comment : " . $comment);
        $this->load->library('parser');
        $data = array(
            'Title' => $title,
            'Firstname' => $user['firstname'],
            'Lastname' => $user['lastname'],
            'StartDate' => $startdate,
            'EndDate' => $enddate,
            'StartDateType' => $lang_mail->line($leave['startdatetype']),
            'EndDateType' => $lang_mail->line($leave['enddatetype']),
            'Type' => $this->types_model->getName($leave['type']),
            'Duration' => $leave['duration'],
            'Balance' => $this->leaves_model->getLeavesTypeBalanceForEmployee($leave['employee'] , $leave['type_name'], $leave['startdate']),
            'Reason' => $leave['cause'],
            'BaseUrl' => $this->config->base_url(),
            'LeaveId' => $leave['id'],
            'UserId' => $this->user->id,
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
        log_message('debug', '--sendGenericMail');
    }
}
