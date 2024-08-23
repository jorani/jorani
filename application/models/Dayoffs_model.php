<?php
/**
 * This class contains the business logic and manages the persistence of non working days
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

 if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

//VObject is used to import an external calendar feed (ICS) containing non working days.
use Sabre\VObject;

/**
 * This class contains the business logic and manages the persistence of non working days.
 * non working days are defined on a contract..
 */
class Dayoffs_model extends CI_Model {

    /**
     * Default constructor
     */
    public function __construct() {

    }

    /**
     * Get the list of dayofs for a contract and a civil year (not to be confused with the yearly period)
     * @param int $contract identifier of the contract
     * @param string $year year to be displayed on the calendar
     * @return array record of contracts
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getDaysOffForCivilYear($contract, $year) {
        $this->db->select('DAY(date) as d, MONTH(date) as m, YEAR(date) as y, type, title');
        $this->db->where('contract', $contract);
        $this->db->where('YEAR(date)', $year);
        $query = $this->db->get('dayoffs');
        $dayoffs =array();
        foreach($query->result() as $row)
        {
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
     * @param int $contract identifier of the contract
     * @return array record of contracts
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getDaysOffForContract($contract) {
        $this->db->where('contract', $contract);
        $query = $this->db->get('dayoffs');
        $this->db->where("date >= DATE_SUB(NOW(),INTERVAL 2 YEAR"); //Security/performance limit
        return $query->result();
    }


    /**
     * Delete a day off into the day offs table
     * @param int $contract Identifier of the contract
     * @param string $timestamp Date of the day off
     * @return int number of affected rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function deleteDayOff($contract, $timestamp) {
        $this->db->where('contract', $contract);
        $this->db->where('date', date('Y/m/d', $timestamp));
        return $this->db->delete('dayoffs');
    }

    /**
     * Delete a day off into the day offs table
     * @param int $contract Identifier of the contract
     * @return int number of affected rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function deleteDaysOffCascadeContract($contract) {
        $this->db->where('contract', $contract);
        return $this->db->delete('dayoffs');
    }

    /**
     * Delete a list of day offs into the day offs table
     * @param int $contract Identifier of the contract
     * @param string $dateList comma-separated list of dates
     * @return bool outcome of the query
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function deleteListOfDaysOff($contract, $dateList) {
        $dates = explode(",", $dateList);
        $this->db->where('contract', $contract);
        $this->db->where_in('DATE_FORMAT(date, \'%Y-%m-%d\')', $dates);
        return $this->db->delete('dayoffs');
    }

    /**
     * Insert a list of day offs into the day offs table
     * @param int $contract Identifier of the contract
     * @param int $type 1:day, 2:morning, 3:afternoon
     * @param string $title Short description of the day off
     * @param string $dateList comma-separated list of dates
     * @return bool outcome of the query
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function addListOfDaysOff($contract, $type, $title, $dateList) {
        //Prepare a command in order to insert multiple rows with one query MySQL
        $dates = explode(",", $dateList);
        $data = array();
        foreach ($dates as $date) {
            $row = array(
                'contract' => $contract,
                'date' => date('Y-m-d', strtotime($date)),
                'type' => $type,
                'title' => $title
            );
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
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function copyListOfDaysOff($source, $destination, $year) {
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
     * @param int $contract contract identifier
     * @param date $start start date
     * @param date $end end date
     * @return int number of days off
     */
    public function lengthDaysOffBetweenDates($contract, $start, $end) {
        $this->db->select('sum(CASE `type` WHEN 1 THEN 1 WHEN 2 THEN 0.5 WHEN 3 THEN 0.5 END) as days');
        $this->db->where('contract', $contract);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->from('dayoffs');
        $result = $this->db->get()->result_array();
        return is_null($result[0]['days'])?0:$result[0]['days'];
    }

    /**
     * Get the list of days off between two dates for a given contract (contract of the employee)
     * @param int $employee employee identifier
     * @param date $start start date
     * @param date $end end date
     * @return array list of days off
     */
    public function listOfDaysOffBetweenDates($employee, $start, $end) {
        $this->lang->load('calendar', $this->session->userdata('language'));
        $this->db->select('dayoffs.*');
        $this->db->join('dayoffs', 'users.contract = dayoffs.contract');
        $this->db->where('users.id', $employee);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->order_by('date');
        $events = $this->db->get('users')->result();
        $listOfDaysOff = array();
        foreach ($events as $entry) {
            switch ($entry->type)
            {
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
            $listOfDaysOff[] = array(
                'title' => $title,                                  //Title of Day off
                'date' => $entry->date,                      //Date of day off
                'type' => $entry->type,                      //1:All day, 2:Morning, 3:Afternoon
                'length' => $length                            //1 or 0.5 depending on the type (for sum)
            );
        }
        return $listOfDaysOff;
    }

    /**
     * Insert a day off into the day offs table
     * @param int $contract Identifier of the contract
     * @param string $timestamp Date of the day off
     * @param int $type 1:day, 2:morning, 3:afternoon
     * @param string $title Short description of the day off
     * @return bool outcome of the query
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function addDayOff($contract, $timestamp, $type, $title) {
        $this->db->select('id');
        $this->db->where('contract', $contract);
        $this->db->where('date', date('Y/m/d', $timestamp));
        $query = $this->db->get('dayoffs');
        if ($query->num_rows() > 0) {
            $data = array(
                'date' => date('Y/m/d', $timestamp),
                'type' => $type,
                'title' => $title
            );
            $this->db->where('id', $query->row('id'));
            return $this->db->update('dayoffs', $data);
        } else {
            $data = array(
                'contract' => $contract,
                'date' => date('Y/m/d', $timestamp),
                'type' => $type,
                'title' => $title
            );
            return $this->db->insert('dayoffs', $data);
        }
    }

    /**
     * Import an ICS feed containing days off (all events are considered as non-working days).
     * This first version is very basic, it supports only full days off.
     * Most of the errors are coming from the web server being not authorized to connect to the external feed.
     * @param int $contract Identifier of the contract
     * @param string $url URL of the source ICS feed (obviously, we must be able to open a connection)
     * @return string error message or empty string
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function importDaysOffFromICS($contract, $url) {
        $ical = VObject\Reader::read(fopen($url,'r'), VObject\Reader::OPTION_FORGIVING);
        foreach($ical->VEVENT as $event) {
            $start = new DateTime($event->DTSTART);
            $end = new DateTime($event->DTEND);
            $interval = $start->diff($end);
            //TODO : Make a more complicated version that supports half days
            $length = $interval->d;
            $day = $start;
            for ($ii = 0; $ii < $length; $ii++) {
                $tmp = $day->format('U');
                $this->deletedayoff($contract, $tmp);
                $this->adddayoff($contract, $tmp, 1, strval($event->SUMMARY));
                $day->add(new DateInterval('P1D'));
            }
        }
    }

    /**
     * All day offs of a given user
     * @param int $user_id connected user
     * @param string $start Start date displayed on calendar
     * @param string $end End date displayed on calendar
     * @return string JSON encoded list of full calendar events
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function userDayoffs($user_id, $start = "", $end = "") {
        $this->lang->load('calendar', $this->session->userdata('language'));
        $this->db->select('dayoffs.*');
        $this->db->join('dayoffs', 'users.contract = dayoffs.contract');
        $this->db->where('users.id', $user_id);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $events = $this->db->get('users')->result();

        $jsonevents = array();
        foreach ($events as $entry) {
            switch ($entry->type)
            {
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
            $jsonevents[] = array(
                'id' => $entry->id,
                'title' => $title,
                'start' => $startdate,
                'color' => '#000000',
                'allDay' => $allDay,
                'end' => $enddate,
                'startdatetype' => $startdatetype,
                'enddatetype' => $enddatetype
            );
        }
        return json_encode($jsonevents);
    }

    /**
     * All day offs for the organization
     * @param string $start Start date displayed on calendar
     * @param string $end End date displayed on calendar
     * @param integer $entity_id identifier of the entity
     * @param boolean $children include all sub entities or not
     * @return string JSON encoded list of full calendar events
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function allDayoffs($start, $end, $entity_id, $children) {
        $this->lang->load('calendar', $this->session->userdata('language'));

        $this->db->select('dayoffs.*, contracts.name');
        $this->db->distinct();
        $this->db->join('contracts', 'dayoffs.contract = contracts.id');
        $this->db->join('users', 'users.contract = contracts.id');
        $this->db->join('organization', 'users.organization = organization.id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        if ($children === TRUE) {
            $this->load->model('organization_model');
            $list = $this->organization_model->getAllChildren($entity_id);
            $ids = array();
            if (count($list) > 0) {
                $ids = explode(",", $list[0]['id']);
            }
            array_push($ids, $entity_id);
            $this->db->where_in('organization.id', $ids);
        } else {
            $this->db->where('organization.id', $entity_id);
        }

        $events = $this->db->get('dayoffs')->result();

        $jsonevents = array();
        foreach ($events as $entry) {
            switch ($entry->type)
            {
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
            $jsonevents[] = array(
                'id' => $entry->id,
                'title' => $entry->name . ': ' . $title,
                'start' => $startdate,
                'color' => '#000000',
                'allDay' => $allDay,
                'end' => $enddate,
                'startdatetype' => $startdatetype,
                'enddatetype' => $enddatetype
            );
        }
        return json_encode($jsonevents);
    }

    /**
     * All day offs for a list
     * @param string $start Start date displayed on calendar
     * @param string $end End date displayed on calendar
     * @param integer $list_id identifier of the entity
     * @return string JSON encoded list of full calendar events
     * @author Emilien NICOLAS <milihhard1996@gmail.com>
     */
    public function allDayoffsForList($start, $end, $list_id) {
      $this->lang->load('calendar', $this->session->userdata('language'));
      $this->db->select('dayoffs.*, contracts.name');
      $this->db->distinct();
      $this->db->join('contracts', 'dayoffs.contract = contracts.id');
      $this->db->join('users', 'users.contract = contracts.id');
      $this->db->join('organization', 'users.organization = organization.id');
      $this->db->where('date >=', $start);
      $this->db->where('date <=', $end);
      $this->db->where('organization.id', $list_id);
      $events = $this->db->get('dayoffs')->result();
      return $this->transformToEvent($events);
    }

    private function transformToEvent($events){
      $jsonevents = array();
      foreach ($events as $entry) {
          switch ($entry->type)
          {
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
          $jsonevents[] = array(
              'id' => $entry->id,
              'title' => $entry->name . ': ' . $title,
              'start' => $startdate,
              'color' => '#000000',
              'allDay' => $allDay,
              'end' => $enddate,
              'startdatetype' => $startdatetype,
              'enddatetype' => $enddatetype
          );
      }
      return json_encode($jsonevents);
    }

    /**
     * Purge the table by deleting the records prior $toDate
     * @param date $toDate
     * @return int number of affected rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function purgeDaysoff($toDate) {
        $this->db->where('date <= ', $toDate);
        return $this->db->delete('entitleddays');
    }

    /**
     * Count the number of rows into the table
     * @return int number of rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function count() {
        $this->db->select('count(*) as number', FALSE);
        $this->db->from('dayoffs');
        $result = $this->db->get();
        return $result->row()->number;
    }

    /**
     * Count the days off defined for a contract and a year
     * @param int $contract Contract to check
     * @param int $year Year to check
     * @return int number of rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function countDaysOff($contract, $year) {
        $this->db->select('count(*) as number', FALSE);
        $this->db->from('dayoffs');
        $this->db->where('contract', $contract);
        $this->db->where('YEAR(date)', $year);
        $result = $this->db->get();
        return $result->row()->number;
    }

    /**
     * All day offs of a given employee and between two dates
     * @param int $user_id connected user
     * @param string $start Start date displayed on calendar
     * @param string $end End date displayed on calendar
     * @return array list of day offs
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function lengthDaysOffBetweenDatesForEmployee($id, $start, $end) {
        $this->db->select('dayoffs.*');
        $this->db->join('dayoffs', 'users.contract = dayoffs.contract');
        $this->db->where('users.id', $id);
        $this->db->where('date >= DATE(' . $this->db->escape($start) . ')');
        $this->db->where('date <= DATE(' . $this->db->escape($end) . ')');
        $dayoffs = $this->db->get('users')->result();
        return $dayoffs;
    }

    /**
     * Check if days off have been defined for year - 1, year and year + 1
     * @param int $year Year to check
     * @return array (id, name, y-1, y, y+1)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function checkIfDefined($year) {
        $ym1 = intval($year) - 1;
        $y = intval($year);
        $yp1 = intval($year) + 1;
        $this->load->model('contracts_model');
        $contracts = $this->contracts_model->getContracts();
        $result = array();
        foreach ($contracts as $contract) {
            $result[] = array (
                'contract' => $contract['id'],
                'name' => $contract['name'],
                'ym1' => $this->countDaysOff($contract['id'], $ym1),
                'y' => $this->countDaysOff($contract['id'], $y),
                'yp1' => $this->countDaysOff($contract['id'], $yp1)
            );
        }
        return $result;
    }
}
