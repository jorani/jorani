<?php
/**
 * This controller serves all the ICS (webcal, ical) feeds exposed by Jorani.
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.4.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

//VObject is used to build an ICS feed (webcal, ical feed)
use Sabre\VObject;

/**
 * This class builds all the ICS (webcal, ical) feeds exposed by Jorani.
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
class Ics extends CI_Controller
{

    /**
     * String representing the timezone of an employee or
     * a default timezone if it is not set for the user.
     * @var string
     */
    private $timezone;

    /**
     * Default constructor
     * Initializing of Sabre VObjets library
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('users_model');
        $methodName = $this->router->fetch_method();
        if (!in_array($methodName, array("ical"))) {
            if (!$this->checkIfAccessIsGranted()) {
                $this->output->set_header("HTTP/1.0 403 Forbidden")->_display();
                die();
            }
        }
        $this->load->library('polyglot');
    }

    /**
     * If legacy feeds are disabled, we must check if the feed is queried
     * for/by an existing user in the database. It is a pseudo authentication
     * That prevents illegal access from outside
     * @return bool TRUE if the user is authenticated, FALSE otherwise
     */
    private function checkIfAccessIsGranted(): bool
    {
        $icsEnabled = filter_var($this->config->item('ics_enabled'), FILTER_VALIDATE_BOOLEAN, ['' => FILTER_NULL_ON_FAILURE]);
        $legacyFeeds = filter_var($this->config->item('legacy_feeds'), FILTER_VALIDATE_BOOLEAN, ['' => FILTER_NULL_ON_FAILURE]);
        if ($icsEnabled === FALSE) {
            return FALSE;
        }
        $this->load->model('users_model');
        if ($legacyFeeds === FALSE) {
            if ($this->input->get('token')) {
                if (!$this->users_model->checkUserByHash($this->input->get('token', TRUE))) {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * Get timezone and language of the user
     * @param int $userId Identifier of an employee
     */
    private function getTimezoneAndLanguageOfUser(int $userId): void
    {
        $employee = $this->users_model->getUsers($userId);
        if (!is_null($employee['timezone'])) {
            $this->timezone = $employee['timezone'];
        } else {
            $this->timezone = $this->config->item('default_timezone');
            if ($this->timezone == null)
                $this->timezone = 'Europe/Paris';
        }
        $this->lang->load('global', $this->polyglot->code2language($employee['language']));
    }

    /**
     * Get the list of dayoffs for a given contract identifier
     * @param int $userId identifier of the user wanting to view the list (mind timezone)
     * @param int $contract identifier of a contract
     */
    public function dayoffs(int $userId, int $contract): void
    {
        //Get timezone and language of the user
        $this->getTimezoneAndLanguageOfUser($userId);
        $vcalendar = new VObject\Component\VCalendar();

        //Load the list of day off associated to the contract
        $this->load->model('dayoffs_model');
        $result = $this->dayoffs_model->getDaysOffForContract($contract);
        if (!empty($result)) {
            foreach ($result as $event) {
                $startdate = new \DateTime($event->date, new \DateTimeZone($this->timezone));
                $enddate = new \DateTime($event->date, new \DateTimeZone($this->timezone));
                switch ($event->type) {
                    case 1:
                        $startdate->setTime(0, 0);
                        $enddate->setTime(0, 0);
                        $enddate->modify('+1 day');
                        break;
                    case 2:
                        $startdate->setTime(0, 0);
                        $enddate->setTime(12, 0);
                        break;
                    case 3:
                        $startdate->setTime(12, 0);
                        $enddate->setTime(0, 0);
                        $enddate->modify('+1 day');
                        break;
                }
                //In order to support Outlook, we convert start and end dates to UTC
                $startdate->setTimezone(new DateTimeZone("UTC"));
                $enddate->setTimezone(new DateTimeZone("UTC"));
                $vcalendar->add('VEVENT', array(
                    'SUMMARY' => $event->title,
                    'CATEGORIES' => lang('day off'),
                    'DTSTART' => $startdate,
                    'DTEND' => $enddate
                ));
            }
        }
        header("Content-Type: text/calendar");
        echo $vcalendar->serialize();
    }

    /**
     * Get the list of leaves for a given employee identifier
     * @param int $userId identifier of an employee
     */
    public function individual(int $userId): void
    {
        $this->load->model('leaves_model');
        $result = $this->leaves_model->getLeavesOfEmployee($userId);
        if (empty($result)) {
            echo "";
        } else {
            //Get timezone and language of the user
            $this->getTimezoneAndLanguageOfUser($userId);

            $vcalendar = new VObject\Component\VCalendar();
            foreach ($result as $event) {
                if (($event['status'] != LMS_CANCELED) && ($event['status'] != LMS_REJECTED)) {
                    $startdate = new \DateTime($event['startdate'], new \DateTimeZone($this->timezone));
                    $enddate = new \DateTime($event['enddate'], new \DateTimeZone($this->timezone));
                    if ($event['startdatetype'] == 'Morning')
                        $startdate->setTime(0, 0);
                    if ($event['startdatetype'] == 'Afternoon')
                        $startdate->setTime(12, 0);
                    if ($event['enddatetype'] == 'Morning')
                        $enddate->setTime(12, 0);
                    if ($event['enddatetype'] == 'Afternoon') {
                        $enddate->setTime(0, 0);
                        $enddate->modify('+1 day');
                    }

                    //In order to support Outlook, we convert start and end dates to UTC
                    $startdate->setTimezone(new DateTimeZone("UTC"));
                    $enddate->setTimezone(new DateTimeZone("UTC"));
                    $vcalendar->add('VEVENT', array(
                        'SUMMARY' => lang('leave'),
                        'CATEGORIES' => lang('leave'),
                        'DTSTART' => $startdate,
                        'DTEND' => $enddate,
                        'DESCRIPTION' => $event['cause'],
                        'URL' => base_url() . "leaves/" . $event['id'],
                    ));
                }
            }
            header("Content-Type: text/calendar");
            echo $vcalendar->serialize();
        }
    }

    /**
     * Get the list of leaves for a group of employees attached to an entity
     * @param int $userId identifier of the user wanting to view the list (mind timezone)
     * @param int $entity identifier of an entity
     * @param bool $children TRUE include sub-entity, FALSE otherwise (default)
     */
    public function entity(int $userId, int $entity, bool $children): void
    {
        $this->load->model('leaves_model');
        $children = filter_var($children, FILTER_VALIDATE_BOOLEAN);
        $result = $this->leaves_model->entity($entity, $children);
        if (empty($result)) {
            echo "";
        } else {
            //Get timezone and language of the user
            $this->getTimezoneAndLanguageOfUser($userId);

            $vcalendar = new VObject\Component\VCalendar();
            foreach ($result as $event) {
                if (($event['status'] != LMS_CANCELED) && ($event['status'] != LMS_REJECTED)) {
                    $startdate = new \DateTime($event['startdate'], new \DateTimeZone($this->timezone));
                    $enddate = new \DateTime($event['enddate'], new \DateTimeZone($this->timezone));
                    if ($event['startdatetype'] == 'Morning')
                        $startdate->setTime(0, 1);
                    if ($event['startdatetype'] == 'Afternoon')
                        $startdate->setTime(12, 0);
                    if ($event['enddatetype'] == 'Morning')
                        $enddate->setTime(12, 0);
                    if ($event['enddatetype'] == 'Afternoon')
                        $enddate->setTime(23, 59);

                    //In order to support Outlook, we convert start and end dates to UTC
                    $startdate->setTimezone(new DateTimeZone("UTC"));
                    $enddate->setTimezone(new DateTimeZone("UTC"));
                    $vcalendar->add('VEVENT', array(
                        'SUMMARY' => $event['firstname'] . ' ' . $event['lastname'],
                        'CATEGORIES' => lang('leave'),
                        'DTSTART' => $startdate,
                        'DTEND' => $enddate,
                        'DESCRIPTION' => $event['type'] . ($event['cause'] != '' ? (' / ' . $event['cause']) : ''),
                        'URL' => base_url() . "leaves/" . $event['id'],
                    ));
                }
            }
            header("Content-Type: text/calendar");
            echo $vcalendar->serialize();
        }
    }

    /**
     * Get the list of leaves of the collaborators of the connected user (manager)
     * @param int $userId identifier of the user wanting to view the list (mind timezone)
     */
    public function collaborators(int $userId): void
    {
        $this->load->model('leaves_model');
        $result = $this->leaves_model->getLeavesRequestedToManager($userId, TRUE);
        if (empty($result)) {
            echo "";
        } else {
            //Get timezone and language of the user
            $this->getTimezoneAndLanguageOfUser($userId);

            $vcalendar = new VObject\Component\VCalendar();
            foreach ($result as $event) {
                if (($event['status'] != LMS_CANCELED) && ($event['status'] != LMS_REJECTED)) {
                    $startdate = new \DateTime($event['startdate'], new \DateTimeZone($this->timezone));
                    $enddate = new \DateTime($event['enddate'], new \DateTimeZone($this->timezone));
                    if ($event['startdatetype'] == 'Morning')
                        $startdate->setTime(0, 1);
                    if ($event['startdatetype'] == 'Afternoon')
                        $startdate->setTime(12, 0);
                    if ($event['enddatetype'] == 'Morning')
                        $enddate->setTime(12, 0);
                    if ($event['enddatetype'] == 'Afternoon')
                        $enddate->setTime(23, 59);

                    //In order to support Outlook, we convert start and end dates to UTC
                    $startdate->setTimezone(new DateTimeZone("UTC"));
                    $enddate->setTimezone(new DateTimeZone("UTC"));
                    $vcalendar->add('VEVENT', array(
                        'SUMMARY' => $event['firstname'] . ' ' . $event['lastname'],
                        'CATEGORIES' => lang('leave'),
                        'DTSTART' => $startdate,
                        'DTEND' => $enddate,
                        'DESCRIPTION' => $event['type_label'] . ($event['cause'] != '' ? (' / ' . $event['cause']) : ''),
                        'URL' => base_url() . "leaves/" . $event['id'],
                    ));
                }
            }
            header("Content-Type: text/calendar");
            echo $vcalendar->serialize();
        }
    }

    /**
     * Action : download an iCal event corresponding to a leave request
     * @param int $leaveRequestId leave request id
     */
    public function ical(int $leaveRequestId): void
    {
        header('Content-type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename=leave.ics');
        $this->load->model('leaves_model');
        $leave = $this->leaves_model->getLeaves($leaveRequestId);
        //Get timezone and language of the user
        $this->getTimezoneAndLanguageOfUser($leave['employee']);

        $startdate = new \DateTime($leave['startdate'], new \DateTimeZone($this->timezone));
        $enddate = new \DateTime($leave['enddate'], new \DateTimeZone($this->timezone));
        //In order to support Outlook, we convert start and end dates to UTC
        $startdate->setTimezone(new DateTimeZone("UTC"));
        $enddate->setTimezone(new DateTimeZone("UTC"));

        $vcalendar = new VObject\Component\VCalendar();
        $vcalendar->add('VEVENT', array(
            'SUMMARY' => lang('leave'),
            'CATEGORIES' => lang('leave'),
            'DESCRIPTION' => $leave['cause'],
            'DTSTART' => $startdate,
            'DTEND' => $enddate,
            'URL' => base_url() . "leaves/" . $leaveRequestId,
        ));
        header("Content-Type: text/calendar");
        echo $vcalendar->serialize();
    }
}
