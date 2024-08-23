<?php
/**
 * This Model contains all the business logic and the persistence layer for telework request objects.
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class contains the business logic and manages the persistence of telework requests.
 */
class Teleworks_model extends CI_Model {

    /**
     * Default constructor
     */
    public function __construct() {

    }

    /**
     * Get the list of all telework requests or one telework
     * @param int $id Id of the telework request
     * @return array list of records
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getTeleworks($id = 0) {
        $this->db->select('teleworks.*');
        $this->db->select('status.name as status_name');
        $this->db->from('teleworks');
        $this->db->join('status', 'teleworks.status = status.id');
        if ($id === 0) {
            return $this->db->get()->result_array();
        }
        $this->db->where('teleworks.id', $id);
        return $this->db->get()->row_array();
    }
    
    /**
     * Get the list of all telework requests for a week
     * @param int $week week number
     * @param int $year year
     * @return array list of records
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getTeleworksByweek($week, $year) {
        $weekdates = get_week_dates_by_week($week, $year);
        $this->db->select('teleworks.*, users.organization as organization_id, parent_id, users.firstname, users.lastname, name as organization_name');
        $this->db->where('status = 3');
        $this->db->where("(startdate >= '" . $weekdates['monday'] . "' AND enddate <= '" . $weekdates['friday'] . "')");
        $this->db->from('teleworks');
        $this->db->join('users', 'teleworks.employee = users.id');
        $this->db->join('organization', 'users.organization = organization.id');
        $this->db->order_by('employee', 'asc');

        return $this->db->get()->result_array();
    }

    /**
     * Get the the list of telework requested for a given employee
     * Id are replaced by label
     * @param int $employee ID of the employee
     * @return array list of records
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getTeleworksOfEmployee($employee) {
        $this->db->select('teleworks.*');
        $this->db->select('status.id as status, status.name as status_name');
        $this->db->select('telework_campaign.id as campaign, telework_campaign.name as campaign_name');
        $this->db->from('teleworks');
        $this->db->join('status', 'teleworks.status = status.id');
        $this->db->join('telework_campaign', 'teleworks.campaign = telework_campaign.id', 'left outer');
        $this->db->where('teleworks.employee', $employee);
        $this->db->order_by('teleworks.id', 'desc');
        return $this->db->get()->result_array();
    }

    /**
     * Get the list of history of an employee
     * @param int $employee Id of the employee
     * @return array list of records
     * @author Emilien NICOLAS <milihhard1996@gmail.com>
     */
    public function getTeleworksOfEmployeeWithHistory($employee){
        $employee = intval($employee);
        $query = "SELECT teleworks.*, status.name as status_name, telework_campaign.name as campaign_name, lastchange.date as change_date, requested.date as request_date
        FROM `teleworks`
        inner join status ON teleworks.status = status.id 
        left outer join telework_campaign ON teleworks.campaign = telework_campaign.id
        left outer join (
          SELECT id, MAX(change_date) as date
          FROM teleworks_history
          GROUP BY id
        ) lastchange ON teleworks.id = lastchange.id
        left outer join (
          SELECT id, MIN(change_date) as date
          FROM teleworks_history
          WHERE teleworks_history.status = 2
          GROUP BY id
        ) requested ON teleworks.id = requested.id
        WHERE teleworks.employee = $employee";

        return $this->db->query($query)->result_array();
    } 

    /**
     * Return a list of Accepted teleworks between two dates and for a given employee
     * @param int $employee ID of the employee
     * @param string $start Start date
     * @param string $end End date
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getAcceptedTeleworksBetweenDates($employee, $start, $end) {
        $this->db->select('teleworks.*');
        $this->db->from('teleworks');
        $this->db->join('status', 'teleworks.status = status.id');
        $this->db->where('employee', $employee);
        $this->db->where("(startdate <= STR_TO_DATE('" . $end . "', '%Y-%m-%d') AND enddate >= STR_TO_DATE('" . $start . "', '%Y-%m-%d'))");
        $this->db->where('teleworks.status', LMS_ACCEPTED);
        $this->db->order_by('startdate', 'asc');
        return $this->db->get()->result_array();
    }    
    
    /**
     * Get the the list of campaign telework requested for a given employee
     * Id are replaced by label
     * @param int $employee ID of the employee
     * @return array list of records
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getRequestedCampaignTeleworksOfEmployee($employee, $status = array(LMS_REQUESTED, LMS_PLANNED)) {
        $this->db->select('teleworks.*');
        $this->db->from('teleworks');
        $this->db->where('employee', $employee);
        $this->db->where('type', 'Campaign');
        $this->db->where_in('status', $status);
        return $this->db->get()->result_array();
    }

    /**
     * Try to calculate the length of a telework using the start and and date of the telework
     * and the non working days defined on a contract
     * @param int $employee Identifier of the employee
     * @param date $start start date of the telework request
     * @param date $end end date of the telework request
     * @param string $startdatetype start date type of telework request being created (Morning or Afternoon)
     * @param string $enddatetype end date type of telework request being created (Morning or Afternoon)
     * @return float length of telework
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function length($employee, $start, $end, $startdatetype, $enddatetype) {
        $this->db->select('sum(CASE `type` WHEN 1 THEN 1 WHEN 2 THEN 0.5 WHEN 3 THEN 0.5 END) as days');
        $this->db->from('users');
        $this->db->join('dayoffs', 'users.contract = dayoffs.contract');
        $this->db->where('users.id', $employee);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get()->result_array();
        $startTimeStamp = strtotime($start." UTC");
        $endTimeStamp = strtotime($end." UTC");
        $timeDiff = abs($endTimeStamp - $startTimeStamp);
        $numberDays = $timeDiff / 86400;  // 86400 seconds in one day
        if (count($result) != 0) { //Test if some non working days are defined on a contract
            return $numberDays - $result[0]['days'];
        } else {
            //Special case when the telework request is half a day long,
            //we assume that the non-working day is not at the same time than the telework request
            if ($startdatetype == $enddatetype) {
                return 0.5;
            } else {
                return $numberDays;
            }
        }
    }

    /**
     * Calculate the actual length of a telework request by taking into account the non-working days
     * Detect overlapping with non-working days. It returns a K/V arrays of 3 items.
     * @param int $employee Identifier of the employee
     * @param date $startdate start date of the telework request
     * @param date $enddate end date of the telework request
     * @param string $startdatetype start date type of telework request being created (Morning or Afternoon)
     * @param string $enddatetype end date type of telework request being created (Morning or Afternoon)
     * @param array $daysoff List of non-working days
     * @param bool $deductDayOff Deduct days off when evaluating the actual length
     * @return array (length=>length of telework, overlapping=>excat match with a non-working day, daysoff=>sum of days off)
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function actualLengthAndDaysOff($employee, $startdate, $enddate,
            $startdatetype, $enddatetype, $daysoff, $deductDayOff = FALSE) {
        $startDateObject = DateTime::createFromFormat('Y-m-d H:i:s', $startdate . ' 00:00:00');
        $endDateObject = DateTime::createFromFormat('Y-m-d H:i:s', $enddate . ' 00:00:00');
        $iDate = clone $startDateObject;

        //Simplify the reading (and logic) by decomposing into atomic variables
        if ($startdate == $enddate) $oneDay = TRUE; else $oneDay = FALSE;
        if ($startdatetype == 'Morning') $start_morning = TRUE; else $start_morning = FALSE;
        if ($startdatetype == 'Afternoon') $start_afternoon = TRUE; else $start_afternoon = FALSE;
        if ($enddatetype == 'Morning') $end_morning = TRUE; else $end_morning = FALSE;
        if ($enddatetype == 'Afternoon') $end_afternoon = TRUE; else $end_afternoon = FALSE;

        //Iteration between start and end dates of the telework request
        $lengthDaysOff = 0;
        $length = 0;
        $hasDayOff = FALSE;
        $overlapDayOff = FALSE;
        while ($iDate <= $endDateObject)
        {
            if ($iDate == $startDateObject) $first_day = TRUE; else $first_day = FALSE;
            $isDayOff = FALSE;
            //Iterate on the list of days off with two objectives:
            // - Compute sum of days off between the two dates
            // - Detect if the telework request exactly overlaps with a day off
            foreach ($daysoff as $dayOff) {
                $dayOffObject = DateTime::createFromFormat('Y-m-d H:i:s', $dayOff['date'] . ' 00:00:00');
                if ($dayOffObject == $iDate) {
                    $lengthDaysOff+=$dayOff['length'];
                    $isDayOff = TRUE;
                    $hasDayOff = TRUE;
                    switch ($dayOff['type']) {
                        case 1: //1 : All day
                            if ($oneDay && $start_morning && $end_afternoon && $first_day)
                                $overlapDayOff = TRUE;
                                if ($deductDayOff) $length++;
                            break;
                        case 2: //2 : Morning
                            if ($oneDay && $start_morning && $end_morning && $first_day)
                                $overlapDayOff = TRUE;
                            else
                                if ($deductDayOff) $length++; else $length+=0.5;
                            break;
                        case 3: //3 : Afternnon
                            if ($oneDay && $start_afternoon && $end_afternoon && $first_day)
                                $overlapDayOff = TRUE;
                            else
                                if ($deductDayOff) $length++; else $length+=0.5;
                            break;
                        default:
                            break;
                    }
                    break;
                }
            }
            if (!$isDayOff) {
                if ($oneDay) {
                    if ($start_morning && $end_afternoon) $length++;
                    if ($start_morning && $end_morning) $length+=0.5;
                    if ($start_afternoon && $end_afternoon) $length+=0.5;
                } else {
                    if ($iDate == $endDateObject) $last_day = TRUE; else $last_day = FALSE;
                    if (!$first_day && !$last_day) $length++;
                    if ($first_day && $start_morning) $length++;
                    if ($first_day && $start_afternoon) $length+=0.5;
                    if ($last_day && $end_morning) $length+=0.5;
                    if ($last_day && $end_afternoon) $length++;
                }
                $overlapDayOff = FALSE;
            }
            $iDate->modify('+1 day');   //Next day
        }

        //Other obvious cases of overlapping
        if ($hasDayOff && ($length == 0)) {
            $overlapDayOff = TRUE;
        }
        return array('length' => $length, 'daysoff' => $lengthDaysOff, 'overlapping' => $overlapDayOff);
    }

    /**
     * Get all entitled days applicable to the reference date (to contract and employee)
     * Compute Min and max date by type
     * @param int $employee Employee identifier
     * @param int $contract contract identifier
     * @param string $refDate Date of execution
     * @return array Array of entitled days associated to the key type id
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getSumEntitledDays($employee, $contract, $refDate) {
        $this->db->select('SUM(entitleddays.days) as entitled');
        $this->db->select('MIN(startdate) as min_date');
        $this->db->select('MAX(enddate) as max_date');
        $this->db->from('entitleddays');
        $this->db->where('entitleddays.startdate <= ', $refDate);
        $this->db->where('entitleddays.enddate >= ', $refDate);
        $where = ' (entitleddays.contract=' . $contract .
                       ' OR entitleddays.employee=' . $employee . ')';
        $this->db->where($where, NULL, FALSE);   //Not very safe, but can't do otherwise
        $results = $this->db->get()->result_array();
        //Create an associated array have the telework type as key
        $entitled_days = array();
        foreach ($results as $result) {
            $entitled_days[] = $result;
        }
        return $entitled_days;
    }

    /**
     * Compute the telework balance of an employee (used by report and counters)
     *
     * @param int $id
     *            ID of the employee
     * @param bool $sum_extra
     *            TRUE: sum compensate summary
     * @param string $refDate
     *            tmp of the Date of reference (or current date if NULL)
     * @return array computed aggregated taken/entitled teleworks
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getTeleworkCountForEmployee($id, $refDate = NULL)
    {
        // Determine if we use current date or another date
        if ($refDate == NULL) {
            $refDate = date("Y-m-d");
        }

        $startdate = date("Y") . '-01-01';
        $enddate = date("Y") . '-12-31';
     
        // Compute the current telework period and check if the user has a contract
        $this->load->model('contracts_model');
        $hasContract = $this->contracts_model->getBoundaries($id, $startentdate, $endentdate, $refDate);
        if ($hasContract) {
            $this->load->model('users_model');
            // Fill a list of all existing telework types
            $summary = array();
            $types = array(
                'Campaign',
                'Floating'
            );
            foreach ($types as $type) {
                $summary[$type][0] = 0; // Taken
                $summary[$type][2] = ''; // Description
                $summary[$type][3] = ''; // Type
                $summary[$type][4] = 0; // Planned
                $summary[$type][5] = 0; // Requested
            }

            // Get the total of taken teleworks grouped by type
            $this->db->select('SUM(teleworks.duration) as taken, type');
            $this->db->from('teleworks');
            $this->db->where('teleworks.employee', $id);
            $this->db->where_in('teleworks.status', array(
                LMS_ACCEPTED,
                LMS_CANCELLATION
            ));
            $this->db->where('teleworks.startdate >= ', $startdate);
            $this->db->where('teleworks.enddate <=', $enddate);
            $this->db->group_by('type');
            $taken_days = $this->db->get()->result_array();
            // Count the number of taken days
            foreach ($taken_days as $taken) {
                $summary[$taken['type']][3] = $taken['type'];
                $summary[$taken['type']][0] = (float) $taken['taken']; // Taken
            }

            // List all planned teleworks in a third column
            // planned leave requests are not deducted from credit
            // Get the total of taken leaves grouped by type
            $this->db->select('SUM(teleworks.duration) as planned, type');
            $this->db->from('teleworks');
            $this->db->where('teleworks.employee', $id);
            $this->db->where('teleworks.status', LMS_PLANNED);
            $this->db->where('teleworks.startdate >= ', $startdate);
            $this->db->where('teleworks.enddate <=', $enddate);
            $this->db->group_by('type');
            $planned_days = $this->db->get()->result_array();
            // Count the number of planned days
            foreach ($planned_days as $planned) {
                $summary[$planned['type']][3] = $planned['type'];
                $summary[$planned['type']][4] = (float) $planned['planned']; // Planned
                $summary[$planned['type']][2] = 'x'; // Planned
            }

            // List all requested teleworks in a fourth column
            // telework requests having a requested status are not deducted from credit
            // Get the total of taken teleworks grouped by type
            $this->db->select('SUM(teleworks.duration) as requested, type');
            $this->db->from('teleworks');
            $this->db->where('teleworks.employee', $id);
            $this->db->where('teleworks.status', LMS_REQUESTED);
            $this->db->where('teleworks.startdate >= ', $startdate);
            $this->db->where('teleworks.enddate <=', $enddate);
            $this->db->group_by('type');
            $requested_days = $this->db->get()->result_array();
            // Count the number of planned days
            foreach ($requested_days as $requested) {
                $summary[$requested['type']][3] = $requested['type'];
                $summary[$requested['type']][5] = (float) $requested['requested']; // requested
                $summary[$requested['type']][2] = 'x'; // requested
            }
//             echo '<pre>';
//             print_r($summary);
//             die('<pre>');
            return $summary;
        } else { // User attached to no contract
            return NULL;
        }
    }

    /**
     * Detect if the telework request overlaps with another request of the employee
     * @param int $id employee id
     * @param date $startdate start date of telework request being created
     * @param date $enddate end date of telework request being created
     * @param string $startdatetype start date type of telework request being created (Morning or Afternoon)
     * @param string $enddatetype end date type of telework request being created (Morning or Afternoon)
     * @param int $telework_id When this function is used for editing a telework request, we must not collide with this telework request
     * @return boolean TRUE if another telework request has been emmitted, FALSE otherwise
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function detectOverlappingTeleworks($id, $startdate, $enddate, $startdatetype, $enddatetype, $telework_id=NULL) {
        $overlapping = FALSE;
        $this->db->where('employee', $id);
        $this->db->where('status != 4 AND status != 6');
        $this->db->where('(startdate <= DATE(\'' . $enddate . '\') AND enddate >= DATE(\'' . $startdate . '\'))');
        if (!is_null($telework_id)) {
            $this->db->where('id != ', $telework_id);
        }
        $teleworks = $this->db->get('teleworks')->result();        
        
        if ($startdatetype == "Morning") {
            $startTmp = strtotime($startdate." 08:00:00 UTC");
        } else {
            $startTmp = strtotime($startdate." 12:01:00 UTC");
        }
        if ($enddatetype == "Morning") {
            $endTmp = strtotime($enddate." 12:00:00 UTC");
        } else {
            $endTmp = strtotime($enddate." 18:00:00 UTC");
        }
        
        foreach ($teleworks as $telework) {
            if ($telework->startdatetype == "Morning") {
                $startTmpDB = strtotime($telework->startdate." 08:00:00 UTC");
            } else {
                $startTmpDB = strtotime($telework->startdate." 12:01:00 UTC");
            }
            if ($telework->enddatetype == "Morning") {
                $endTmpDB = strtotime($telework->enddate." 12:00:00 UTC");
            } else {
                $endTmpDB = strtotime($telework->enddate." 18:00:00 UTC");
            }
            if (($startTmpDB <= $endTmp) && ($endTmpDB >= $startTmp)) {
                $overlapping = TRUE;
            }
        }
        return $overlapping;
    }
    
    /**
     * Detect if the leave request overlaps with the telework request of the employee
     * @param int $id employee id
     * @param date $startdate start date of leave request being created
     * @param date $enddate end date of leave request being created
     * @param string $startdatetype start date type of leave request being created (Morning or Afternoon)
     * @param string $enddatetype end date type of leave request being created (Morning or Afternoon)
     * @return boolean TRUE if another leave request has been emmitted, FALSE otherwise
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function detectOverlappingLeavesForTelework($id, $startdate, $enddate, $startdatetype, $enddatetype) {
        $overlapping = FALSE;
        $this->db->where('employee', $id);
        $this->db->where('status != 4 AND status != 6');
        $this->db->where('type != 23');
        $this->db->where('(startdate <= DATE(\'' . $enddate . '\') AND enddate >= DATE(\'' . $startdate . '\'))');
        $leaves = $this->db->get('leaves')->result();
        
        if ($startdatetype == "Morning") {
            $startTmp = strtotime($startdate." 08:00:00 UTC");
        } else {
            $startTmp = strtotime($startdate." 12:01:00 UTC");
        }
        if ($enddatetype == "Morning") {
            $endTmp = strtotime($enddate." 12:00:00 UTC");
        } else {
            $endTmp = strtotime($enddate." 18:00:00 UTC");
        }
        
        foreach ($leaves as $leave) {
            if ($leave->startdatetype == "Morning") {
                $startTmpDB = strtotime($leave->startdate." 08:00:00 UTC");
            } else {
                $startTmpDB = strtotime($leave->startdate." 12:01:00 UTC");
            }
            if ($leave->enddatetype == "Morning") {
                $endTmpDB = strtotime($leave->enddate." 12:00:00 UTC");
            } else {
                $endTmpDB = strtotime($leave->enddate." 18:00:00 UTC");
            }
            if (($startTmpDB <= $endTmp) && ($endTmpDB >= $startTmp)) {
                $overlapping = TRUE;
            }
        }
        return $overlapping;
    }
    
   /**
    * Detect if a time organisation overlaps with the telework request of the employee
    * @param int $id employee id
    * @param date $startdate start date of leave request being created
    * @param date $enddate end date of leave request being created
    * @param string $startdatetype start date type of leave request being created (Morning or Afternoon)
    * @param string $enddatetype end date type of leave request being created (Morning or Afternoon)
    * @return boolean TRUE if a time organisation has been defined, FALSE otherwise
    * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
    */
    public function detectOverlappingTimeOrganisations($id, $startdate, $enddate, $startdatetype, $enddatetype) {
        $overlapping = FALSE;
        $this->load->model('time_organisation_model');
        $dates = $this->time_organisation_model->getTimeOrganisationDates($id);
        $timeorganisation = $this->time_organisation_model->getTimeOrganisationForEmployee($id);
        if ($timeorganisation) {
            $daytype = $timeorganisation['daytype'];
            if ($startdatetype == "Morning") {
                $startTmp = strtotime($startdate . " 08:00:00 UTC");
            } else {
                $startTmp = strtotime($startdate . " 12:01:00 UTC");
            }
            if ($enddatetype == "Morning") {
                $endTmp = strtotime($enddate . " 12:00:00 UTC");
            } else {
                $endTmp = strtotime($enddate . " 18:00:00 UTC");
            }

            foreach ($dates as $date) {
                for ($i = 0; $i < count($date); $i ++) {
                    if ($daytype == "Morning") {
                        $startTmpDB = strtotime($date[$i] . " 08:00:00 UTC");
                        $endTmpDB = strtotime($date[$i] . " 12:00:00 UTC");
                    } else if ($daytype == "Afternoon") {
                        $startTmpDB = strtotime($date[$i] . " 12:01:00 UTC");
                        $endTmpDB = strtotime($date[$i] . " 18:00:00 UTC");
                    } else {
                        $startTmpDB = strtotime($date[$i] . " 08:00:00 UTC");
                        $endTmpDB = strtotime($date[$i] . " 18:00:00 UTC");
                    }
                    if (($startTmpDB <= $endTmp) && ($endTmpDB >= $startTmp)) {
                        $overlapping = TRUE;
                    }
                }
            }
        }
        return $overlapping;
    }
    
    /**
     * Detect if the telework request exceeds the number of days allowed
     * @param int $id employee id
     * @param date $startdate start date of telework request being created
     * @return boolean TRUE if another telework request has been emmitted, FALSE otherwise
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function detectLimitExceeding($id, $startdate, $telework_id = 0) {
        $monday = get_week_dates($startdate, 1);
        $friday = get_week_dates($startdate, 5);
        $duration = 0;
        
        $this->db->where('employee', $id);
        $this->db->where('status != 4 AND status != 6');
        $this->db->where('(startdate > DATE(\'' . $monday . '\') AND enddate < DATE(\'' . $friday . '\'))');
        $result = $this->db->get('teleworks')->result();   
        
        if (count($result) > 0 ) {
            for ($i = 0; $i < count($result); $i ++) {
                if ($result[$i]->id != $telework_id)
                    $duration += abs($result[$i]->duration);
            }
        }

        $this->db->where('employee', $id);
        $this->db->where('status != 4 AND status != 6');
        $this->db->where('(startdate <= DATE(\'' . $monday . '\') AND enddate >= DATE(\'' . $monday . '\'))');
        $result = $this->db->get('teleworks')->result();
        
        if (count($result) > 0 && $result[0]->id != $telework_id) {
            $date = new DateTime($monday);
            $start = new DateTime($result[0]->startdate);
            $end = new DateTime($result[0]->enddate);

            if ($start == $end || $date == $start) {
                $duration += abs($result[0]->duration);
            } else {
                $interval = ($date->diff($end)->d) + 1;
                if ($result[0]->enddatetype == 'Afternoon')
                    $duration += $interval;
                else
                    $duration += $interval - 0.5;
            }
        }
        
        $this->db->where('employee', $id);
        $this->db->where('status != 4 AND status != 6');
        $this->db->where('(startdate <= DATE(\'' . $friday . '\') AND enddate >= DATE(\'' . $friday . '\'))'); 
        $result = $this->db->get('teleworks')->result();

        if (count($result) > 0 && $result[0]->id != $telework_id) {
            $date = new DateTime($friday);
            $start = new DateTime($result[0]->startdate);
            $end = new DateTime($result[0]->enddate);

            if ($start == $end || $date == $end) {
                $duration += abs($result[0]->duration);
            } else {
                $interval = ($start->diff($date)->d) + 1;
                if ($result[0]->startdatetype == 'Morning')
                    $duration += $interval;
                else
                    $duration += $interval - 0.5;
            }
        }

        return $duration;
    }

    /**
     * Create a telework request
     * @param int $employeeId Identifier of the employee
     * @return int id of the newly created telework request into the db
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function setTeleworks($employeeId) {
        $data = array(
            'startdate' => $this->input->post('startdate'),
            'startdatetype' => $this->input->post('startdatetype'),
            'enddate' => $this->input->post('enddate'),
            'enddatetype' => $this->input->post('enddatetype'),
            'duration' => abs($this->input->post('duration')),
            'cause' => $this->input->post('cause'),
            'type' => 'Floating',
            'status' => $this->input->post('status'),
            'employee' => $employeeId
        );
        $this->db->insert('teleworks', $data);
        $newId = $this->db->insert_id();

        //Trace the modification if the feature is enabled
        if ($this->config->item('enable_teleworks_history') === TRUE) {
            $this->load->model('telework_history_model');
            $this->telework_history_model->setHistory(1, 'teleworks', $newId, $employeeId);
        }

        return $newId;
    }

    /**
     * Create a telework request for a campaign
     * @param int $employeeId Identifier of the employee
     * @return array id of newly created telework requests into the db
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function setTeleworksForCampaign($employeeId) {
        $this->load->model('leaves_model');
        $this->load->model('dayoffs_model');
        $this->load->model('telework_campaign_model');
        $this->load->model('telework_rule_model');
        $this->load->model('time_organisation_model');
        $startdatetype = 'Morning';
        $enddatetype = 'Afternoon';
        $duration = 1.0;
		if($this->input->post('daytype') == 'morning') {
            $startdatetype = $enddatetype = 'Morning';
            $duration = 0.5;
        }
        if($this->input->post('daytype') == 'afternoon') {
            $startdatetype = $enddatetype = 'Afternoon';
            $duration = 0.5;
        }
        $recurrence = $this->input->post('recurrence');
        $timeorganisation = $this->time_organisation_model->getTimeOrganisationForEmployee($employeeId);
		$weeklyAllowedTelework = $this->telework_rule_model->getTeleworkRuleForEmployee($employeeId);
		if ($timeorganisation) {
			if ($timeorganisation['day'] == $this->input->post('day')) {
				if ($timeorganisation['daytype'] != 'Whole day') {
					if($recurrence == $timeorganisation['recurrence']) {
						$duration = 0.5;
						if ($timeorganisation['daytype'] == 'Morning')
							$startdatetype = $enddatetype = 'Afternoon';
						else
							$startdatetype = $enddatetype = 'Morning';
					} else {
						if($recurrence == 'All') {
							if ($timeorganisation['recurrence'] == 'Odd')
								$recurrence = 'Even';
							if ($timeorganisation['recurrence'] == 'Even')
								$recurrence = 'Odd';
						}
						if(($recurrence == 'Odd' || $recurrence == 'Even') && $timeorganisation['recurrence'] == 'All') {
							$duration = 0.5;
							if ($timeorganisation['daytype'] == 'Morning')
								$startdatetype = $enddatetype = 'Afternoon';
							else
								$startdatetype = $enddatetype = 'Morning';
						}
					}
				} else {
					if($recurrence == $timeorganisation['recurrence']) {
						if ($timeorganisation['recurrence'] == 'Odd')
							$recurrence = 'Even';
						if ($timeorganisation['recurrence'] == 'Even')
							$recurrence = 'Odd';
						if ($timeorganisation['recurrence'] == 'All')
							$duration = 0;
					} else {
						if($recurrence == 'All') {
							if ($timeorganisation['recurrence'] == 'Odd')
								$recurrence = 'Even';
							if ($timeorganisation['recurrence'] == 'Even')
								$recurrence = 'Odd';
						}
						if(($recurrence == 'Odd' || $recurrence == 'Even') && $timeorganisation['recurrence'] == 'All')
							$duration  = 0;
					}
				}
			}
		}

        $campaignId = $this->input->post('campaign');
        $campaign = $this->telework_campaign_model->getTeleworkCampaigns($campaignId);
        $dates = list_days_for_campaign($campaign['startdate'], $campaign['enddate'], $this->input->post('day'));
        if ($recurrence == 'Even' || $recurrence == 'Odd') {
            for ($i = 0; $i < count($dates); $i ++) {
                if ($recurrence == 'Odd' && (new DateTime($dates[$i]))->format('W') % 2 == 0)
                    array_splice($dates, $i, 1);
                if ($recurrence == 'Even' && (new DateTime($dates[$i]))->format('W') % 2 != 0)
                    array_splice($dates, $i, 1);
            }
        }

        $newIds = array();
        for ($i = 0; $i < count($dates); $i ++) {
            $overlapping = $this->detectOverlappingTeleworks($employeeId, $dates[$i], $dates[$i], $startdatetype, $enddatetype);
            $overlappingleaves = $this->detectOverlappingLeavesForTelework($employeeId, $dates[$i], $dates[$i], $startdatetype, $enddatetype);
            $dayoff = $this->dayoffs_model->listOfDaysOffBetweenDates($employeeId, $dates[$i], $dates[$i]);
            // Detect if the telework request exceeds the number of days allowed
            $limitExceeding = $this->detectLimitExceeding($employeeId, $dates[$i]) + $duration;
			if($timeorganisation) {
				if($timeorganisation['recurrence'] == 'All' || ($timeorganisation['recurrence'] == 'Even' && (new DateTime($dates[$i]))->format('W') % 2 == 0) || ($timeorganisation['recurrence'] == 'Odd' && (new DateTime($dates[$i]))->format('W') % 2 != 0))
					$limitExceeding += $timeorganisation['duration'];
			}

            if ($duration > 0 && ! $overlapping && ! $overlappingleaves && count($dayoff) == 0 && (($weeklyAllowedTelework && $limitExceeding <= $weeklyAllowedTelework['limit']) || ! $weeklyAllowedTelework)) {
                $data = array(
                    'startdate' => $dates[$i],
                    'startdatetype' => $startdatetype,
                    'enddate' => $dates[$i],
                    'enddatetype' => $enddatetype,
                    'duration' => $duration,
                    'type' => 'Campaign',
                    'status' => $this->input->post('status'),
                    'employee' => $employeeId,
                    'campaign' => $campaignId
                );
                $this->db->insert('teleworks', $data);
                $newId = $this->db->insert_id();

                $newIds[] = $newId;

                // Trace the modification if the feature is enabled
                if ($this->config->item('enable_teleworks_history') === TRUE) {
                    $this->load->model('telework_history_model');
                    $this->telework_history_model->setHistory(1, 'teleworks', $newId, $employeeId);
                }
            }
        }

        return $newIds;
    }
 
    /**
     * Create the same telework request for a list of employees
     * @param int $type Identifier of the telework type
     * @param float $duration duration of the telework
     * @param string $startdate Start date (MySQL format YYYY-MM-DD)
     * @param string $enddate End date (MySQL format YYYY-MM-DD)
     * @param string $startdatetype Start date type of the telework (Morning/Afternoon)
     * @param string $enddatetype End date type of the telework (Morning/Afternoon)
     * @param string $cause Identifier of the telework
     * @param int $status status of the telework
     * @param array $employees List of DB Ids of the affected employees
     * @return int Result
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function createRequestForUserList($type, $duration, $startdate, $enddate, $startdatetype, $enddatetype, $cause, $status, $employees) {
        $affectedRows = 0;
        if ($this->config->item('enable_teleworks_history') === TRUE) {
            foreach ($employees as $id) {
                $this->createTeleworkByApi($this->input->post('startdate'),
                        $this->input->post('enddate'),
                        $this->input->post('status'),
                        $id,
                        $this->input->post('cause'),
                        $this->input->post('startdatetype'),
                        $this->input->post('enddatetype'),
                        abs($this->input->post('duration')),
                        'Floating');
                $affectedRows++;
            }
        } else {
            $data = array();
            foreach ($employees as $id) {
                $data[] = array(
                    'startdate' => $this->input->post('startdate'),
                    'startdatetype' => $this->input->post('startdatetype'),
                    'enddate' => $this->input->post('enddate'),
                    'enddatetype' => $this->input->post('enddatetype'),
                    'duration' => abs($this->input->post('duration')),
                    'type' => 'Floating',
                    'cause' => $this->input->post('cause'),
                    'status' => $this->input->post('status'),
                    'employee' => $id);
            }
            $affectedRows = $this->db->insert_batch('teleworks', $data);
        }
        return $affectedRows;
    }

    /**
     * Create a telework request (suitable for API use)
     * @param string $startdate Start date (MySQL format YYYY-MM-DD)
     * @param string $enddate End date (MySQL format YYYY-MM-DD)
     * @param int $status Status of telework (see table status or doc)
     * @param int $employee Identifier of the employee
     * @param string $cause Optional reason of the telework
     * @param string $startdatetype Start date type (Morning/Afternoon)
     * @param string $enddatetype End date type (Morning/Afternoon)
     * @param float $duration duration of the telework request
     * @param int $type Type of telework (except compensate, fully customizable by user)
     * @param string $comments (optional) JSON encoded comment
     * @param string $document Base64 encoded document
     * @return int id of the newly acreated telework request into the db
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function createTeleworkByApi($startdate, $enddate, $status, $employee, $cause,
            $startdatetype, $enddatetype, $duration, $type,
            $comments = NULL,
            $document = NULL) {

        $data = array(
            'startdate' => $startdate,
            'enddate' => $enddate,
            'status' => $status,
            'employee' => $employee,
            'cause' => $cause,
            'startdatetype' => $startdatetype,
            'enddatetype' => $enddatetype,
            'duration' => abs($duration),
            'type' => 'Floating'
        );
        if (!empty($comments)) $data['comments'] = $comments;
        if (!empty($document)) $data['document'] = $document;
        $this->db->insert('teleworks', $data);
        $newId = $this->db->insert_id();

        //Trace the modification if the feature is enabled
        if ($this->config->item('enable_teleworks_history') === TRUE) {
            $this->load->model('telework_history_model');
            $this->telework_history_model->setHistory(1, 'teleworks', $newId, $this->session->userdata('id'));
        }
        return $newId;
    }

    /**
     * Update a telework request in the database with the values posted by an HTTP POST
     * @param int $teleworkId of the telework request
     * @param int $userId Identifier of the user (optional)
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function updateTeleworks($teleworkId, $userId = 0) {
        if ($userId == 0) {
            $userId = $this->session->userdata('id');
        }
        $json = $this->prepareCommentOnStatusChanged($teleworkId, $this->input->post('status'));
        if($this->input->post('comment') != NULL){
          $jsonDecode = json_decode($json);
          $commentObject = new stdClass;
          $commentObject->type = "comment";
          $commentObject->author = $userId;
          $commentObject->value = $this->input->post('comment');
          $commentObject->date = date("Y-n-j");
          if (isset($jsonDecode)){
            array_push($jsonDecode->comments, $commentObject);
          }else {
            $jsonDecode->comments = array($commentObject);
          }
          $json = json_encode($jsonDecode);
        }
        $data = array(
            'startdate' => $this->input->post('startdate'),
            'startdatetype' => $this->input->post('startdatetype'),
            'enddate' => $this->input->post('enddate'),
            'enddatetype' => $this->input->post('enddatetype'),
            'duration' => abs($this->input->post('duration')),
            'type' => $this->input->post('type'),
            'cause' => $this->input->post('cause'),
            'status' => $this->input->post('status'),
            'comments' => $json
        );
        $this->db->where('id', $teleworkId);
        $this->db->update('teleworks', $data);

        //Trace the modification if the feature is enabled
        if ($this->config->item('enable_teleworks_history') === TRUE) {
            $this->load->model('telework_history_model');
            $this->telework_history_model->setHistory(2, 'teleworks', $teleworkId, $userId);
        }
    }

    /**
     * Delete a telework from the database
     * @param int $teleworkId telework request identifier
     * @param int $userId Identifier of the user (optional)
     * @return int number of affected rows
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function deleteTelework($teleworkId, $userId = 0) {
        //Trace the modification if the feature is enabled
        if ($this->config->item('enable_teleworks_history') === TRUE) {
            if ($userId == 0) {
                $userId = $this->session->userdata('id');
            }
            $this->load->model('telework_history_model');
            $this->telework_history_model->setHistory(3, 'teleworks', $teleworkId, $userId);
        }
        return $this->db->delete('teleworks', array('id' => $teleworkId));
    }

    /**
     * Switch the status of a telework request. You may use one of the constants
     * listed into config/constants.php
     * @param int $id telework request identifier
     * @param int $status Next Status
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function switchStatus($id, $status) {
        $json = $this->prepareCommentOnStatusChanged($id, $status);
        $data = array(
            'status' => $status,
            'comments' => $json
        );
        $this->db->where('id', $id);
        $this->db->update('teleworks', $data);

        //Trace the modification if the feature is enabled
        if ($this->config->item('enable_teleworks_history') === TRUE) {
            $this->load->model('telework_history_model');
            $this->telework_history_model->setHistory(2, 'teleworks', $id, $this->session->userdata('id'));
        }
    }

    /**
     * Switch the status of a telework request and a comment. You may use one of the constants
     * listed into config/constants.php
     * @param int $id telework request identifier
     * @param int $status Next Status
     * @param int $comment New comment
     * @author Emilien NICOLAS <milihhard1996@gmail.com>
     */
    public function switchStatusAndComment($id, $status, $comment) {
        $json_parsed = $this->getCommentsTelework($id);
        $commentObject = new stdClass;
        $commentObject->type = "comment";
        $commentObject->author = $this->session->userdata('id');
        $commentObject->value = $comment;
        $commentObject->date = date("Y-n-j");
        if (isset($json_parsed)){
          array_push($json_parsed->comments, $commentObject);
        }else {
          $json_parsed->comments = array($commentObject);
        }
        $comment_change = new stdClass;
        $comment_change->type = "change";
        $comment_change->status_number = $status;
        $comment_change->date = date("Y-n-j");
        if (isset($json_parsed)){
          array_push($json_parsed->comments, $comment_change);
        }else {
          $json_parsed->comments = array($comment_change);
        }
        $json = json_encode($json_parsed);
        $data = array(
            'status' => $status,
            'comments' => $json
        );
        $this->db->where('id', $id);
        $this->db->update('teleworks', $data);

        //Trace the modification if the feature is enabled
        if ($this->config->item('enable_teleworks_history') === TRUE) {
            $this->load->model('telework_history_model');
            $this->telework_history_model->setHistory(2, 'teleworks', $id, $this->session->userdata('id'));
        }
    }

    /**
     * Delete teleworks attached to a user
     * @param int $employee identifier of an employee
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function deleteTeleworksCascadeUser($employee) {
        //Select the teleworks of a users (if history feature is enabled)
        if ($this->config->item('enable_teleworks_history') === TRUE) {
            $this->load->model('telework_history_model');
            $teleworks = $this->getTeleworksOfEmployee($employee);
            //TODO in fact, should we cascade delete ?
            foreach ($teleworks as $telework) {
                $this->telework_history_model->setHistory(3, 'teleworks', $telework['id'], $this->session->userdata('id'));
            }
        }
        return $this->db->delete('teleworks', array('employee' => $employee));
    }
    
    /**
     * Telework requests of All telework request of the user (suitable for FullCalendar widget)
     * @param int $user_id connected user
     * @param string $start Unix timestamp / Start date displayed on calendar
     * @param string $end Unix timestamp / End date displayed on calendar
     * @return string JSON encoded list of full calendar events
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function individual($user_id, $start = "", $end = "") {
        $this->db->select('teleworks.*');
        $this->db->where('employee', $user_id);
        $this->db->where('(teleworks.startdate <= DATE(' . $this->db->escape($end) . ') AND teleworks.enddate >= DATE(' . $this->db->escape($start) . '))');
        $this->db->order_by('startdate', 'desc');
        $this->db->limit(1024);  //Security limit

        return $this->db->get('teleworks')->result();
    }    
    
    /**
     * Telework requests of All users having the same manager (suitable for FullCalendar widget)
     * @param int $user_id id of the manager
     * @param string $start Unix timestamp / Start date displayed on calendar
     * @param string $end Unix timestamp / End date displayed on calendar
     * @return string JSON encoded list of full calendar events
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function workmates($user_id, $start = "", $end = "") {
        $this->db->join('users', 'users.id = teleworks.employee');
        $this->db->where('users.manager', $user_id);
        $this->db->where('teleworks.status < ', LMS_REJECTED); // Exclude rejected requests
        $this->db->where('(teleworks.startdate <= DATE(' . $this->db->escape($end) . ') AND teleworks.enddate >= DATE(' . $this->db->escape($start) . '))');
        $this->db->order_by('startdate', 'desc');
        $this->db->limit(1024); // Security limit
        
        return $this->db->get('teleworks')->result();
    }

    /**
     * Telework requests of All users having the same manager (suitable for FullCalendar widget)
     * @param int $user_id id of the manager
     * @param string $start Unix timestamp / Start date displayed on calendar
     * @param string $end Unix timestamp / End date displayed on calendar
     * @return string JSON encoded list of full calendar events
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function collaborators($user_id, $start = "", $end = "") {
        $this->load->model('teleworks_model');
        $this->db->join('users', 'users.id = teleworks.employee');
        $this->db->where('users.manager', $user_id);
        $this->db->where('(teleworks.startdate <= DATE(' . $this->db->escape($end) . ') AND teleworks.enddate >= DATE(' . $this->db->escape($start) . '))');
        $this->db->order_by('startdate', 'desc');
        $this->db->limit(1024); // Security limit
        
        return $this->db->get('teleworks')->result();
    }

    /**
     * Telework requests of All users of a department (suitable for FullCalendar widget)
     * @param int $entity_id Entity identifier (the department)
     * @param string $start Unix timestamp / Start date displayed on calendar
     * @param string $end Unix timestamp / End date displayed on calendar
     * @param bool $children Include sub department in the query
     * @param string $statusFilter optional filter on status
     * @return string JSON encoded list of full calendar events
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function department($entity_id, $start = "", $end = "", $children = FALSE, $statusFilter = NULL) {
        $this->lang->load('calendar', $this->language);
        $this->db->select('users.firstname, users.lastname, users.manager');
        $this->db->select('teleworks.*');
        $this->db->select('"' . lang('telework_acronym') . '" as acronym');
        $this->db->from('organization');
        $this->db->join('users', 'users.organization = organization.id');
        $this->db->join('teleworks', 'teleworks.employee = users.id');
        $this->db->where('(teleworks.startdate <= DATE(' . $this->db->escape($end) . ') AND teleworks.enddate >= DATE(' . $this->db->escape($start) . '))');
        if ($children === TRUE) {
            $this->load->model('organization_model');
            $list = $this->organization_model->getAllChildren($entity_id);
            $ids = array();
            if ($list[0]['id'] != '') {
                $ids = explode(",", $list[0]['id']);
                array_push($ids, $entity_id);
                $this->db->where_in('organization.id', $ids);
            } else {
                $this->db->where('organization.id', $entity_id);
            }
        } else {
            $this->db->where('organization.id', $entity_id);
        }
        // $this->db->where('leaves.status != ', 4); //Exclude rejected requests
        if ($statusFilter != NULL) {
            $statuses = explode('|', $statusFilter);
            $this->db->where_in('status', $statuses);
        }
        $this->db->order_by('startdate', 'desc');
        $this->db->limit(1024); // Security limit

        return $this->db->get()->result();
    }

    /**
     * Telework requests of All users of a list (suitable for FullCalendar widget)
     * @param int $list_id List identifier
     * @param string $start Unix timestamp / Start date displayed on calendar
     * @param string $end Unix timestamp / End date displayed on calendar
     * @param bool $children Include sub department in the query
     * @param string $statusFilter optional filter on status
     * @return string JSON encoded list of full calendar events
     * @author Emilien NICOLAS <milihhard1996@gmail.com>
     */
    public function getListRequest($list_id, $start = "", $end = "", $statusFilter = NULL) {
      $this->db->select('users.firstname, users.lastname, users.manager');
      $this->db->select('teleworks.*');
      $this->db->from('organization');
      $this->db->join('users', 'users.organization = organization.id');
      $this->db->join('teleworks', 'teleworks.employee = users.id');
      $this->db->join('org_lists_employees', 'org_lists_employees.user = users.id');
      $this->db->where('(teleworks.startdate <= DATE(' . $this->db->escape($end) . ') AND teleworks.enddate >= DATE(' . $this->db->escape($start) . '))');
      $this->db->where('org_lists_employees.list', $list_id);
      //$this->db->where('teleworks.status != ', 4); //Exclude rejected requests
      if ($statusFilter != NULL) {
          $statuses = explode ('|', $statusFilter);
          $this->db->where_in('status', $statuses );
      }
      $this->db->order_by('startdate', 'desc');
      $this->db->limit(1024);  //Security limit
      return $this->db->get()->result();
    }

    /**
     * Telework requests of All users of an entity
     * @param int $entity_id Entity identifier (the department)
     * @param bool $children Include sub department in the query
     * @return array List of telework requests (DB records)
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function entity($entity_id, $children = FALSE) {
        $this->db->select('users.firstname, users.lastname,  teleworks.*');
        $this->db->from('organization');
        $this->db->join('users', 'users.organization = organization.id');
        $this->db->join('teleworks', 'teleworks.employee  = users.id');
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
        $this->db->where('teleworks.status != ', 4);       //Exclude rejected requests
        $this->db->order_by('startdate', 'desc');
        $events = $this->db->get()->result_array();
        return $events;
    }

    /**
     * List all floating telework requests submitted to the connected user (or if delegate of a manager)
     * Can be filtered with "Requested" status.
     * @param int $manager connected user
     * @param int $id employee id
     * @param bool $all TRUE all requests, FALSE otherwise
     * @return array Recordset (can be empty if no requests or not a manager)
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getTeleworksRequestedToManager($manager, $all = FALSE, $type = NULL, $id = 0) {
        $this->load->model('delegations_model');
        $ids = $this->delegations_model->listManagersGivingDelegation($manager);
        $this->db->select('teleworks.id as telework_id, users.*, teleworks.*');
        $this->db->select('status.name as status_name');
        $this->db->select('telework_campaign.name as campaign_name');
        $this->db->join('status', 'teleworks.status = status.id');
        $this->db->join('telework_campaign', 'telework_campaign.id = teleworks.campaign', 'left outer');
        $this->db->join('users', 'users.id = teleworks.employee');
        if ($id != 0)
            $this->db->where('teleworks.employee', "$id");
        if ($type != NULL)
            $this->db->where('teleworks.type', "$type");
        if (count($ids) > 0) {
            array_push($ids, $manager);
            $this->db->where_in('users.manager', $ids);
        } else {
            $this->db->where('users.manager', $manager);
        }
        if ($all == FALSE) {
            $this->db->where('teleworks.status', LMS_REQUESTED);
            $this->db->or_where('teleworks.status', LMS_CANCELLATION);
        }
        $this->db->order_by('teleworks.startdate', 'desc');
        $query = $this->db->get('teleworks');
        return $query->result_array();
    }

    /**
     * Get the list of history of an employee
     * @param int $manager Id of the employee
     * @param bool $all TRUE all requests, FALSE otherwise
     * @return array list of records
     * @author Emilien NICOLAS <milihhard1996@gmail.com>
     */
    public function getTeleworksRequestedToManagerWithHistory($manager, $all = FALSE, $type = NULL, $id = 0){
      $this->load->model('delegations_model');
        $manager = intval($manager);
        $query = 'SELECT teleworks.id as telework_id, users.*, teleworks.*, status.name as status_name, telework_campaign.name as campaign_name, lastchange.date as change_date, requested.date as request_date
        FROM `teleworks`
        inner join status ON teleworks.status = status.id
        left outer join telework_campaign ON teleworks.campaign = telework_campaign.id
        inner join users ON users.id = teleworks.employee
        left outer join (
          SELECT id, MAX(change_date) as date
          FROM teleworks_history
          GROUP BY id
        ) lastchange ON teleworks.id = lastchange.id
        left outer join (
          SELECT id, MIN(change_date) as date
          FROM teleworks_history
          WHERE teleworks_history.status = 2
          GROUP BY id
        ) requested ON teleworks.id = requested.id';
        // Case of manager having delegations
        $ids = $this->delegations_model->listManagersGivingDelegation($manager);
        if (count($ids) > 0) {
            array_push($ids, $manager);
            $query .= " WHERE users.manager IN (" . implode(",", $ids) . ")";
        } else {
            $query .= " WHERE users.manager = $manager";
        }
        if ($all == FALSE) {
            $query .= " AND (teleworks.status = " . LMS_REQUESTED . " OR teleworks.status = " . LMS_CANCELLATION . ")";
        }
        if ($type != NULL)
            $query .= ' AND teleworks.type = "' . $type . '"';
        if ($id != 0)
            $query .= ' AND teleworks.employee = "' . $id . '"';
        $query = $query . " order by teleworks.startdate DESC;";
        $this->db->query('SET SQL_BIG_SELECTS=1');
        return $this->db->query($query)->result_array();
    }

    /**
     * Count floating telework requests submitted to the connected user (or if delegate of a manager)
     * @param int $manager connected user
     * @return int number of requests
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function countTeleworksRequestedToManager($manager) {
        $this->load->model('delegations_model');
        $ids = $this->delegations_model->listManagersGivingDelegation($manager);
        $this->db->select('count(*) as number', FALSE);
        $this->db->join('users', 'users.id = teleworks.employee');
        $this->db->where('teleworks.type', "Floating");
        $this->db->where_in('teleworks.status', array(LMS_REQUESTED, LMS_CANCELLATION));

        if (count($ids) > 0) {
            array_push($ids, $manager);
            $this->db->where_in('users.manager', $ids);
        } else {
            $this->db->where('users.manager', $manager);
        }
        $result = $this->db->get('teleworks');
        return $result->row()->number;
    }
    
    /**
     * Count campaign telework requests submitted to the connected user (or if delegate of a manager)
     * @param int $manager connected user
     * @return int number of requests
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function countCampaignTeleworksRequestedToManager($manager) {
        $this->load->model('delegations_model');
        $ids = $this->delegations_model->listManagersGivingDelegation($manager);
        $this->db->select('count(distinct teleworks.employee) as number', FALSE);
        $this->db->join('users', 'users.id = teleworks.employee');
        $this->db->where('teleworks.type', "Campaign");
        $this->db->where_in('teleworks.status', array(LMS_REQUESTED, LMS_CANCELLATION));        
        
        if (count($ids) > 0) {
            array_push($ids, $manager);
            $this->db->where_in('users.manager', $ids);
        } else {
            $this->db->where('users.manager', $manager);
        }
        $result = $this->db->get('teleworks');
        return $result->row()->number;
    }

    /**
     * Purge the table by deleting the records prior $toDate
     * @param date $toDate
     * @return int number of affected rows
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function purgeTeleworks($toDate) {
        //TODO : if one day we use this function, should what should we do with the history feature?
        $this->db->where(' <= ', $toDate);
        return $this->db->delete('teleworks');
    }

    /**
     * Count the number of rows into the table
     * @return int number of rows
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function count() {
        $this->db->select('count(*) as number', FALSE);
        $this->db->from('teleworks');
        $result = $this->db->get();
        return $result->row()->number;
    }

    /**
     * All teleworks between two timestamps, no filters
     * @param string $startDate Start date displayed on calendar
     * @param string $endDate End date displayed on calendar
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function all($startDate, $endDate) {
        $this->db->select("users.id as user_id, users.firstname, users.lastname, teleworks.*", FALSE);
        $this->db->join('users', 'users.id = teleworks.employee');
        $this->db->where('( (teleworks.startdate <= ' . $this->db->escape($startDate) . ' AND teleworks.enddate >= ' . $this->db->escape($endDate) . ')' .
                                   ' OR (teleworks.startdate >= ' . $this->db->escape($endDate) . ' AND teleworks.enddate <= ' . $this->db->escape($endDate) . '))');
        $this->db->order_by('startdate', 'desc');
        return $this->db->get('teleworks')->result();
    }

    /**
     * Count the total duration of teleworks for the month.
     * Only accepted teleworks are taken into account
     *
     * @param array $linear
     *            linear calendar for one employee
     * @return int total of teleworks duration
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function monthlyTeleworksDuration($linear)
    {
        $total = 0;
        $this->lang->load('calendar', $this->language);
        foreach ($linear->days as $day) {
            if (strstr($day->display, ';')) {
                $acronym = explode(";", $day->acronym);
                $display = explode(";", $day->display);
                for ($i = 0; $i < count($acronym); $i ++) {
                    if ($acronym[$i] == lang('telework_acronym')) {
                        if ($display[$i] == '1')
                            $total += 1;
                        if ($display[$i] == '2')
                            $total += 0.5;
                        if ($display[$i] == '3')
                            $total += 0.5;
                    }
                }
            } else {
                if ($day->acronym == lang('telework_acronym')) {
                    if ($day->display == 2)
                        $total += 0.5;
                    if ($day->display == 3)
                        $total += 0.5;
                    if ($day->display == 1)
                        $total += 1;
                }
            }
        }
        return $total;
    }

    /**
     * Telework requests of users of a department(s)
     *
     * @param int $employee
     *            Employee identifier
     * @param int $month
     *            Month number
     * @param int $year
     *            Year number
     * @param boolean $planned
     *            Include telework requests with status planned
     * @param boolean $requested
     *            Include telework requests with status requested
     * @param boolean $accepted
     *            Include telework requests with status accepted
     * @param boolean $rejected
     *            Include telework requests with status rejected
     * @param boolean $cancellation
     *            Include telework requests with status cancellation
     * @param boolean $canceled
     *            Include telework requests with status canceled
     * @param boolean $calendar
     *            Is this function called to display a calendar
     * @return array Array of objects containing telework details
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function linear($employee_id, $month, $year, $planned = FALSE, $requested = FALSE, $accepted = FALSE, $rejected = FALSE, $cancellation = FALSE, $canceled = FALSE, $calendar = FALSE)
    {
        $start = $year . '-' . $month . '-' . '1'; // first date of selected month
        $lastDay = date("t", strtotime($start)); // last day of selected month
        $end = $year . '-' . $month . '-' . $lastDay; // last date of selected month
        $this->lang->load('calendar', $this->language);
                
        //Build the complex query for all teleworks
        $this->db->select('teleworks.*');
        $this->db->select('"' . lang('telework_acronym') . '" as acronym');
        $this->db->select('users.manager as manager');
        $this->db->from('teleworks');
        $this->db->join('users', 'teleworks.employee = users.id');
        $this->db->where('(teleworks.startdate <= DATE(' . $this->db->escape($end) . ') AND teleworks.enddate >= DATE(' . $this->db->escape($start) . '))');
        if (! $planned)
            $this->db->where('teleworks.status != ', LMS_PLANNED);
        if (! $requested)
            $this->db->where('teleworks.status != ', LMS_REQUESTED);
        if (! $accepted)
            $this->db->where('teleworks.status != ', LMS_ACCEPTED);
        if (! $rejected)
            $this->db->where('teleworks.status != ', LMS_REJECTED);
        if (! $cancellation)
            $this->db->where('teleworks.status != ', LMS_CANCELLATION);
        if (! $canceled)
            $this->db->where('teleworks.status != ', LMS_CANCELED);

        $this->db->where('teleworks.employee = ', $employee_id);
        $this->db->order_by('startdate', 'asc');
        $this->db->order_by('startdatetype', 'desc');
        
        return $this->db->get()->result();        
    }  
    
    /**
     * List all duplicated telework requests (exact same dates, status, etc.)
     * Note: this doesn't detect overlapping requests.
     * @return array List of duplicated telework requests
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function detectDuplicatedRequests() {
        $this->db->select('teleworks.id, CONCAT(users.firstname, \' \', users.lastname) as user_label', FALSE);
        $this->db->select('teleworks.startdate, teleworks.type as type_label');
        $this->db->from('teleworks');
        $this->db->join('(SELECT * FROM teleworks) dup', 'teleworks.employee = dup.employee' . ' AND teleworks.startdate = dup.startdate' . ' AND teleworks.enddate = dup.enddate' . ' AND teleworks.startdatetype = dup.startdatetype' . ' AND teleworks.enddatetype = dup.enddatetype' . ' AND teleworks.status = dup.status' . ' AND teleworks.id != dup.id', 'inner');
        $this->db->join('users', 'users.id = teleworks.employee', 'inner');
        $this->db->where('teleworks.status', 3); // Accepted
        $this->db->order_by("users.id", "asc");
        $this->db->order_by("teleworks.startdate", "desc");
        return $this->db->get()->result_array();
    }

    /**
     * List all telework requests with a wrong date type (starting afternoon and ending morning of the same day)
     * @return array List of wrong telework requests
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function detectWrongDateTypes() {
        $this->db->select('teleworks.*, CONCAT(users.firstname, \' \', users.lastname) as user_label', FALSE);
        $this->db->select('status.name as status_label');
        $this->db->from('teleworks');
        $this->db->join('users', 'users.id = teleworks.employee', 'inner');
        $this->db->join('status', 'teleworks.status = status.id', 'inner');
        $this->db->where('teleworks.startdatetype', 'Afternoon');
        $this->db->where('teleworks.enddatetype', 'Morning');
        $this->db->where('teleworks.startdate = teleworks.enddate');
        $this->db->order_by("users.id", "asc");
        $this->db->order_by("teleworks.startdate", "desc");
        return $this->db->get()->result_array();
    }    

    /**
     * List of telework requests overlapping on two yearly periods.
     * @return array List of overlapping telework requests
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function detectOverlappingProblems() {
        $query = $this->db->query('SELECT CONCAT(users.firstname, \' \', users.lastname) AS user_label,
            contracts.id AS contract_id, contracts.name AS contract_label,
            status.name AS status_label,
            teleworks.*
            FROM teleworks
            inner join users on teleworks.employee = users.id
            inner join contracts on users.contract = contracts.id
            inner join status on status.id = teleworks.status
            WHERE teleworks.startdate < CAST(CONCAT(YEAR(teleworks.enddate), \'-\', REPLACE(contracts.startentdate, \'/\', \'-\')) AS DATE)
            ORDER BY users.id ASC, teleworks.startdate DESC', FALSE);
        return $query->result_array();
    }

    /**
     * Get one telework with his comment
     * @param int $teleworkId Id of the telework request
     * @return array list of records
     * @author Emilien NICOLAS <milihhard1996@gmail.com>
     */
    public function getTeleworkWithComments($teleworkId = 0) {
        $this->db->select('teleworks.*');
        $this->db->select('status.name as status_name');
        $this->db->from('teleworks');
        $this->db->join('status', 'teleworks.status = status.id');
        $this->db->where('teleworks.id', $teleworkId);
        $telework = $this->db->get()->row_array();
        if(!empty($telework['comments'])){
          $telework['comments'] = json_decode($telework['comments']);
        } else {
          $telework['comments'] = null;
        }
        return $telework;
    }

    /**
     * Get the JSON representation of comments posted on a telework request
     * @param int $id Id of the telework request
     * @return array list of records
     * @author Emilien NICOLAS <milihhard1996@gmail.com>
     */
    public function getCommentsTeleworkJson($id){

      $this->db->select('teleworks.comments');
      $this->db->from('teleworks');
      $this->db->where('teleworks.id', "$id");
      return $this->db->get()->row_array();
    }

    /**
     * Get one telework with his comment
     * @param int $id Id of the telework request
     * @return array list of records
     * @author Emilien NICOLAS <milihhard1996@gmail.com>
     */
    public function getCommentsTelework($id){
      $request = $this->getCommentsTeleworkJson($id);
      $json = $request["comments"];
      if(!empty($json)){
        return json_decode($json);
      } else {
        return null;
      }
    }

    private function getCommentTeleworkAndStatus($id){
      $this->db->select('teleworks.comments, teleworks.status');
      $this->db->from('teleworks');
      $this->db->where('teleworks.id', "$id");
      $request = $this->db->get()->row_array();
      $json = $request["comments"];
      if(!empty($json)){
        $request["comments"] = json_decode($json);
      } else {
        $request["comments"] = null;
      }
      return $request;
    }

    /**
    * Update the comment of a Telework
    * @param int $id Id of the telework
    * @param string $json new json for the comments of the telework
    * @author Emilien NICOLAS <milihhard1996@gmail.com>
    */
    public function addComments($id, $json){
      $data = array(
          'comments' => $json
      );
      $this->db->where('id', $id);
      $this->db->update('teleworks', $data);

      //Trace the modification if the feature is enabled
      if ($this->config->item('enable_teleworks_history') === TRUE) {
          $this->load->model('telework_history_model');
          $this->telework_history_model->setHistory(2, 'teleworks', $id, $this->session->userdata('id'));
      }
    }

    /**
    * Prepare the Json when the status is updated
    * @param int $id Id of the telework
    * @param int $status status which is updated
    * @return string json modified with the new status
    * @author Emilien NICOLAS <milihhard1996@gmail.com>
    */
    private function prepareCommentOnStatusChanged($id,$status){
      $request = $this->getCommentTeleworkAndStatus($id);
      if($request['status'] === $status){
        return json_encode($request['comments']);
      } else {
        $json_parsed = $request['comments'];
        $comment_change = new stdClass;
        $comment_change->type = "change";
        $comment_change->status_number = $status;
        $comment_change->date = date("Y-n-j");
        if (isset($json_parsed)){
          array_push($json_parsed->comments, $comment_change);
        }else {
          $json_parsed = new stdClass;
          $json_parsed->comments = array($comment_change);
        }
        return json_encode($json_parsed);
      }
    }
}
