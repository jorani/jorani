<?php
/**
 * This file contains the business logic and manages the persistence of non working days
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.1.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

//VObject is used to import an external calendar feed (ICS) containing non working days.
use Sabre\VObject;

/**
 * This class contains the business logic and manages the persistence of non working days.
 * non working days are defined on a contract. This operation must be done every year.
 */
class Dayoffs_model extends CI_Model
{

    /**
     * Default constructor
     */
    public function __construct()
    {

    }

    /**
     * Get the list of dayofs for a contract and a civil year (not to be confused with the yearly period)
     * @param int $contractId identifier of the contract
     * @param string $year year to be displayed on the calendar
     * @return array record of contracts
     */
    public function getDaysOffForCivilYear(int $contractId, string $year): array
    {
        $this->db->select('DAY(date) as d, MONTH(date) as m, YEAR(date) as y, type, title');
        $this->db->where('contract', $contractId);
        $this->db->where('YEAR(date)', $year);
        $query = $this->db->get('dayoffs');
        $dayoffs = [];
        foreach ($query->result() as $row) {
            //We decompose the date before creating the unix timestamp because there are diffrences of
            //few hours depending the configuration of the system hosting the db (due to time part ?).
            $timestamp = mktime(0, 0, 0, $row->m, $row->d, $row->y);
            $dayoffs[$timestamp][0] = $row->type;
            $dayoffs[$timestamp][1] = $row->title;
        }
        return $dayoffs;
    }

    /**
     * Get the list of dayofs for a contract (suitable fo ICS feed)
     * @param int $contractId identifier of the contract
     * @return array record of contracts
     */
    public function getDaysOffForContract(int $contractId): array
    {
        $query = $this->db->get('dayoffs');
        $query->where('contract', $contractId);
        $query->where("date >= DATE_SUB(NOW(),INTERVAL 2 YEAR"); //Security/performance limit
        return $query->result();
    }


    /**
     * Delete a day off into the day offs table
     * @param int $contractId Identifier of the contract
     * @param string $timestamp Date of the day off
     * @return int number of affected rows
     */
    public function deleteDayOff(int $contractId, string $timestamp): int
    {
        $this->db->where('contract', $contractId);
        $this->db->where('date', date('Y/m/d', $timestamp));
        return $this->db->delete('dayoffs');
    }

    /**
     * Delete a day off into the day offs table
     * @param int $contractId Identifier of the contract
     * @return int number of affected rows
     */
    public function deleteDaysOffCascadeContract(int $contractId): int
    {
        $this->db->where('contract', $contractId);
        return $this->db->delete('dayoffs');
    }

    /**
     * Delete a list of day offs into the day offs table
     * @param int $contractId Identifier of the contract
     * @param string $dateList comma-separated list of dates
     * @return bool outcome of the query
     */
    public function deleteListOfDaysOff(int $contractId, string $dateList): bool
    {
        $dates = explode(",", $dateList);
        $this->db->where('contract', $contractId);
        $this->db->where_in('DATE_FORMAT(date, \'%Y-%m-%d\')', $dates);
        return $this->db->delete('dayoffs');
    }

    /**
     * Insert a list of day offs into the day offs table
     * @param int $contractId Identifier of the contract
     * @param int $type 1:day, 2:morning, 3:afternoon
     * @param string $title Short description of the day off
     * @param string $dateList comma-separated list of dates
     * @return bool outcome of the query
     */
    public function addListOfDaysOff(int $contractId, int $type, string $title, string $dateList): bool
    {
        //Prepare a command in order to insert multiple rows with one query MySQL
        $dates = explode(",", $dateList);
        $data = [];
        foreach ($dates as $date) {
            $row = [
                'contract' => $contractId,
                'date' => date('Y-m-d', strtotime($date)),
                'type' => $type,
                'title' => $title
            ];
            array_push($data, $row);
        }
        return $this->db->insert_batch('dayoffs', $data);
    }

    /**
     * Copy a list of days off of a source contract to a destination contract (for a given civil year)
     * @param int $source identifier of the source contract
     * @param int $destination identifier of the destination contract
     * @param string $year civil year (and not yearly period)
     * @return int number of affected rows
     */
    public function copyListOfDaysOff(int $source, int $destination, string $year): int
    {
        //Delete all previous days off defined on the destination contract (avoid duplicated data)
        $this->db->where('contract', $destination);
        $this->db->where('YEAR(date)', $year);
        $this->db->delete('dayoffs');

        //Copy source->destination days off
        $sql = 'INSERT dayoffs(contract, date, type, title) ' .
            ' SELECT ' . $this->db->escape($destination) . ', date, type, title ' .
            ' FROM dayoffs ' .
            ' WHERE contract = ' . $this->db->escape($source) .
            ' AND YEAR(date) = ' . $this->db->escape($year);
        $query = $this->db->query($sql);
        return $query;
    }

    /**
     * Get the length of days off between two dates for a given contract
     * @param int $contractId contract identifier
     * @param string $startDate start date
     * @param string $endDate end date
     * @return int number of days off in the range
     */
    public function lengthDaysOffBetweenDates(int $contractId, string $startDate, string $endDate): int
    {
        //TODO: check if $startDate and $endDate are valid dates
        $this->db->select('sum(CASE `type` WHEN 1 THEN 1 WHEN 2 THEN 0.5 WHEN 3 THEN 0.5 END) as days');
        $this->db->where('contract', $contractId);
        $this->db->where('date >=', $startDate);
        $this->db->where('date <=', $endDate);
        $this->db->from('dayoffs');
        $result = $this->db->get()->result_array();
        return is_null($result[0]['days']) ? 0 : $result[0]['days'];
    }

    /**
     * Get the list of days off between two dates for a given contract (contract of the employee)
     * @param int $employeeId employee identifier
     * @param string $startDate start date
     * @param string $endDate end date
     * @return array list of days off
     */
    public function listOfDaysOffBetweenDates(int $employeeId, string $startDate, string $endDate): array
    {
        //TODO: check if $startDate and $endDate are valid dates
        $this->lang->load('calendar', $this->session->userdata('language'));
        $this->db->select('dayoffs.*');
        $this->db->join('dayoffs', 'users.contract = dayoffs.contract');
        $this->db->where('users.id', $employeeId);
        $this->db->where('date >=', $startDate);
        $this->db->where('date <=', $endDate);
        $this->db->order_by('date');
        $events = $this->db->get('users')->result();
        $listOfDaysOff = [];
        foreach ($events as $entry) {
            switch ($entry->type) {
                case 1://1 : All day
                    $title = $entry->title;
                    $length = 1;
                    break;
                case 2://2 : Morning
                    $title = lang('Morning') . ': ' . $entry->title;
                    $length = 0.5;
                    break;
                case 3://3 : Afternnon
                    $title = lang('Afternoon') . ': ' . $entry->title;
                    $length = 0.5;
                    break;
            }
            $listOfDaysOff[] = [
                'title' => $title,            //Title of Day off
                'date' => $entry->date,       //Date of day off
                'type' => $entry->type,       //1:All day, 2:Morning, 3:Afternoon
                'length' => $length           //1 or 0.5 depending on the type (for sum)
            ];
        }
        return $listOfDaysOff;
    }

    /**
     * Insert a day off into the day offs table
     * @param int $contractId Identifier of the contract
     * @param string $timestampOfDayOff Date of the day off
     * @param int $type 1:day, 2:morning, 3:afternoon
     * @param string $title Short description of the day off
     * @return bool outcome of the query
     */
    public function addDayOff(int $contractId, string $timestampOfDayOff, int $type, string $title): bool
    {
        $this->db->select('id');
        $this->db->where('contract', $contractId);
        $this->db->where('date', date('Y/m/d', $timestampOfDayOff));
        $query = $this->db->get('dayoffs');
        if ($query->num_rows() > 0) {
            $data = [
                'date' => date('Y/m/d', $timestampOfDayOff),
                'type' => $type,
                'title' => $title
            ];
            $this->db->where('id', $query->row('id'));
            return $this->db->update('dayoffs', $data);
        } else {
            $data = [
                'contract' => $contractId,
                'date' => date('Y/m/d', $timestampOfDayOff),
                'type' => $type,
                'title' => $title
            ];
            return $this->db->insert('dayoffs', $data);
        }
    }

    /**
     * Import an ICS feed containing days off (all events are considered as non-working days).
     * This first version is very basic, it supports only full days off.
     * Most of the errors are coming from the web server being not authorized to connect to the external feed.
     * @param int $contractId Identifier of the contract
     * @param string $icsFeedUrl URL of the source ICS feed (obviously, we must be able to open a connection)
     */
    public function importDaysOffFromICS(int $contractId, string $icsFeedUrl): void
    {
        $ical = VObject\Reader::read(fopen($icsFeedUrl, 'r'), VObject\Reader::OPTION_FORGIVING);
        foreach ($ical->VEVENT as $event) {
            $start = new DateTime($event->DTSTART);
            $end = new DateTime($event->DTEND);
            $interval = $start->diff($end);
            //TODO: Make a more complicated version that supports half days
            $length = $interval->d;
            $day = $start;
            for ($ii = 0; $ii < $length; $ii++) {
                $tmp = $day->format('U');
                $this->deletedayoff($contractId, $tmp);
                $this->adddayoff($contractId, $tmp, 1, strval($event->SUMMARY));
                $day->add(new DateInterval('P1D'));
            }
        }
    }

    /**
     * All day offs of a given user
     * @param int $userId connected user
     * @param string $startDate Start date displayed on calendar
     * @param string $endDate End date displayed on calendar
     * @return string JSON encoded list of full calendar events
     */
    public function userDayoffs(int $userId, string $startDate = "", string $endDate = ""): string
    {
        //TODO: check if $start and $end are valid dates only if they are not empty
        $this->lang->load('calendar', $this->session->userdata('language'));
        $this->db->select('dayoffs.*');
        $this->db->join('dayoffs', 'users.contract = dayoffs.contract');
        $this->db->where('users.id', $userId);
        $this->db->where('date >=', $startDate);
        $this->db->where('date <=', $endDate);
        $events = $this->db->get('users')->result();

        $jsonevents = [];
        foreach ($events as $entry) {
            switch ($entry->type) {
                case 1:
                    $title = $entry->title;
                    $startDateEntry = $entry->date . 'T07:00:00';
                    $endDateEntry = $entry->date . 'T18:00:00';
                    $allDay = TRUE;
                    $startdatetype = 'Morning';
                    $enddatetype = 'Afternoon';
                    break;
                case 2:
                    $title = lang('Morning') . ': ' . $entry->title;
                    $startDateEntry = $entry->date . 'T07:00:00';
                    $endDateEntry = $entry->date . 'T12:00:00';
                    $allDay = FALSE;
                    $startdatetype = 'Morning';
                    $enddatetype = 'Morning';
                    break;
                case 3:
                    $title = lang('Afternoon') . ': ' . $entry->title;
                    $startDateEntry = $entry->date . 'T12:00:00';
                    $endDateEntry = $entry->date . 'T18:00:00';
                    $allDay = FALSE;
                    $startdatetype = 'Afternoon';
                    $enddatetype = 'Afternoon';
                    break;
            }
            $jsonevents[] = [
                'id' => $entry->id,
                'title' => $title,
                'start' => $startDateEntry,
                'color' => '#000000',
                'allDay' => $allDay,
                'end' => $endDateEntry,
                'startdatetype' => $startdatetype,
                'enddatetype' => $enddatetype
            ];
        }
        return json_encode($jsonevents);
    }

    /**
     * All day offs for the organization
     * @param string $startDate Start date displayed on calendar
     * @param string $endDate End date displayed on calendar
     * @param integer $entityId identifier of the entity
     * @param boolean $children include all sub entities or not
     * @return string JSON encoded list of full calendar events
     */
    public function allDayoffs(string $startDate, string $endDate, int $entityId, bool $children): string
    {
        //TODO: check if $startDate and $endDate are valid dates
        $this->lang->load('calendar', $this->session->userdata('language'));

        $this->db->select('dayoffs.*, contracts.name');
        $this->db->distinct();
        $this->db->join('contracts', 'dayoffs.contract = contracts.id');
        $this->db->join('users', 'users.contract = contracts.id');
        $this->db->join('organization', 'users.organization = organization.id');
        $this->db->where('date >=', $startDate);
        $this->db->where('date <=', $endDate);

        if ($children === TRUE) {
            $this->load->model('organization_model');
            $list = $this->organization_model->getAllChildren($entityId);
            $ids = [];
            if (count($list) > 0) {
                $ids = explode(",", $list[0]['id']);
            }
            array_push($ids, $entityId);
            $this->db->where_in('organization.id', $ids);
        } else {
            $this->db->where('organization.id', $entityId);
        }

        $events = $this->db->get('dayoffs')->result();

        $jsonevents = [];
        foreach ($events as $entry) {
            switch ($entry->type) {
                case 1:
                    $title = $entry->title;
                    $startdate = $entry->date . 'T07:00:00';
                    $enddate = $entry->date . 'T18:00:00';
                    $allDay = TRUE;
                    $startdatetype = 'Morning';
                    $enddatetype = 'Afternoon';
                    break;
                case 2:
                    $title = lang('Morning') . ': ' . $entry->title;
                    $startdate = $entry->date . 'T07:00:00';
                    $enddate = $entry->date . 'T12:00:00';
                    $allDay = FALSE;
                    $startdatetype = 'Morning';
                    $enddatetype = 'Morning';
                    break;
                case 3:
                    $title = lang('Afternoon') . ': ' . $entry->title;
                    $startdate = $entry->date . 'T12:00:00';
                    $enddate = $entry->date . 'T18:00:00';
                    $allDay = FALSE;
                    $startdatetype = 'Afternoon';
                    $enddatetype = 'Afternoon';
                    break;
            }
            $jsonevents[] = [
                'id' => $entry->id,
                'title' => $entry->name . ': ' . $title,
                'start' => $startdate,
                'color' => '#000000',
                'allDay' => $allDay,
                'end' => $enddate,
                'startdatetype' => $startdatetype,
                'enddatetype' => $enddatetype
            ];
        }
        return json_encode($jsonevents);
    }

    /**
     * All day offs for a list of entities
     * @param string $startDate Start date displayed on calendar
     * @param string $endDate End date displayed on calendar
     * @param integer $listId identifiers of the entities
     * @return string JSON encoded list of full calendar events
     * @author Emilien NICOLAS <milihhard1996@gmail.com>
     */
    public function allDayoffsForList(string $startDate, string $endDate, int $listId): string
    {
        //TODO: check if $startDate and $endDate are valid dates
        $this->lang->load('calendar', $this->session->userdata('language'));
        $this->db->select('dayoffs.*, contracts.name');
        $this->db->distinct();
        $this->db->join('contracts', 'dayoffs.contract = contracts.id');
        $this->db->join('users', 'users.contract = contracts.id');
        $this->db->join('organization', 'users.organization = organization.id');
        $this->db->where('date >=', $startDate);
        $this->db->where('date <=', $endDate);
        $this->db->where('organization.id', $listId);
        $events = $this->db->get('dayoffs')->result();
        return $this->transformToEvent($events);
    }

    /**
     * Transform events to full calendar events
     * @param array $events Array of events
     * @return string JSON encoded list of full calendar events
     */
    private function transformToEvent($events): string
    {
        $jsonevents = [];
        foreach ($events as $entry) {
            switch ($entry->type) {
                case 1:
                    $title = $entry->title;
                    $startdate = $entry->date . 'T07:00:00';
                    $enddate = $entry->date . 'T18:00:00';
                    $allDay = TRUE;
                    $startdatetype = 'Morning';
                    $enddatetype = 'Afternoon';
                    break;
                case 2:
                    $title = lang('Morning') . ': ' . $entry->title;
                    $startdate = $entry->date . 'T07:00:00';
                    $enddate = $entry->date . 'T12:00:00';
                    $allDay = FALSE;
                    $startdatetype = 'Morning';
                    $enddatetype = 'Morning';
                    break;
                case 3:
                    $title = lang('Afternoon') . ': ' . $entry->title;
                    $startdate = $entry->date . 'T12:00:00';
                    $enddate = $entry->date . 'T18:00:00';
                    $allDay = FALSE;
                    $startdatetype = 'Afternoon';
                    $enddatetype = 'Afternoon';
                    break;
            }
            $jsonevents[] = [
                'id' => $entry->id,
                'title' => $entry->name . ': ' . $title,
                'start' => $startdate,
                'color' => '#000000',
                'allDay' => $allDay,
                'end' => $enddate,
                'startdatetype' => $startdatetype,
                'enddatetype' => $enddatetype
            ];
        }
        return json_encode($jsonevents);
    }

    /**
     * Purge the table by deleting the records prior $toDate
     * @param string $toDate
     * @return int number of affected rows
     */
    public function purgeDaysoff($toDate): int
    {
        //TODO: check if $toDate is a valid date
        $this->db->where('date <= ', $toDate);
        return $this->db->delete('entitleddays');
    }

    /**
     * Count the number of rows into the table
     * @return int number of rows
     */
    public function count(): int
    {
        $this->db->select('count(*) as number', FALSE);
        $this->db->from('dayoffs');
        $result = $this->db->get();
        return $result->row()->number;
    }

    /**
     * Count the days off defined for a contract and a year
     * @param int $contractId Id of contract to check
     * @param int $year Year to check
     * @return int number of rows
     */
    public function countDaysOff(int $contractId, int $year): int
    {
        $this->db->select('count(*) as number', FALSE);
        $this->db->from('dayoffs');
        $this->db->where('contract', $contractId);
        $this->db->where('YEAR(date)', $year);
        $result = $this->db->get();
        return $result->row()->number;
    }

    /**
     * All day offs of a given employee and between two dates
     * @param int $userId Id of user
     * @param string $startDate Start date displayed on calendar
     * @param string $endDate End date displayed on calendar
     * @return array list of day offs
     */
    public function lengthDaysOffBetweenDatesForEmployee(int $userId, string $startDate, string $endDate): array
    {
        //TODO: check if $start and $end are valid dates
        $this->db->select('dayoffs.*');
        $this->db->join('dayoffs', 'users.contract = dayoffs.contract');
        $this->db->where('users.id', $userId);
        $this->db->where('date >= DATE(' . $this->db->escape($startDate) . ')');
        $this->db->where('date <= DATE(' . $this->db->escape($endDate) . ')');
        $dayoffs = $this->db->get('users')->result();
        return $dayoffs;
    }

    /**
     * Check if days off have been defined for year - 1, year and year + 1
     * @param int $year Year to check
     * @return array (id, name, y-1, y, y+1)
     */
    public function checkIfDefined(int $year): array
    {
        $ym1 = intval($year) - 1;
        $y = intval($year);
        $yp1 = intval($year) + 1;
        $this->load->model('contracts_model');
        $contracts = $this->contracts_model->getContracts();
        $result = [];
        foreach ($contracts as $contract) {
            $result[] = [
                'contract' => $contract['id'],
                'name' => $contract['name'],
                'ym1' => $this->countDaysOff($contract['id'], $ym1),
                'y' => $this->countDaysOff($contract['id'], $y),
                'yp1' => $this->countDaysOff($contract['id'], $yp1)
            ];
        }
        return $result;
    }
}
