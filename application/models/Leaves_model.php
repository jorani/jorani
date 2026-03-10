<?php
/**
 * This Model contains all the business logic and the persistence layer for leave request objects.
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/jorani/jorani
 * @since   0.1.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * This class contains the business logic and manages the persistence of leave requests.
 */
class Leaves_model extends CI_Model
{

    /**
     * Default constructor
     */
    public function __construct()
    {

    }

    /**
     * Validate a date in Y-m-d format.
     * @param string $date
     * @return bool TRUE if the date is valid, FALSE otherwise
     */
    private function isValidYmdDate(string $date): bool
    {
        $dateTime = \DateTime::createFromFormat('Y-m-d', $date);
        return $dateTime !== false && $dateTime->format('Y-m-d') === $date;
    }

    /**
     * Get the list of all leave requests or one leave
     * @param int $leaveRequestId Id of the leave request
     * @return array list of records
     */
    public function getLeaves(int $leaveRequestId = 0): array
    {
        $this->db->select('leaves.*');
        $this->db->select('status.name as status_name, types.name as type_name');
        $this->db->from('leaves');
        $this->db->join('status', 'leaves.status = status.id');
        $this->db->join('types', 'leaves.type = types.id');
        if ($leaveRequestId === 0) {
            return $this->db->get()->result_array();
        }
        $this->db->where('leaves.id', $leaveRequestId);
        return $this->db->get()->row_array();
    }

    /**
     * Get the the list of leaves requested for a given employee
     * Id are replaced by label
     * @param int $employeeId ID of the employee
     * @return array list of records
     */
    public function getLeavesOfEmployee(int $employeeId): array
    {
        $this->db->select('leaves.*');
        $this->db->select('status.id as status, status.name as status_name');
        $this->db->select('types.name as type_name');
        $this->db->from('leaves');
        $this->db->join('status', 'leaves.status = status.id');
        $this->db->join('types', 'leaves.type = types.id');
        $this->db->where('leaves.employee', $employeeId);
        $this->db->order_by('leaves.id', 'desc');
        return $this->db->get()->result_array();
    }

    /**
     * Get the list of leaves of an employee with their history
     * @param int $employeeId Id of the employee
     * @return array list of records
     */
    public function getLeavesOfEmployeeWithHistory(int $employeeId): array
    {
        return $this->db->query("SELECT leaves.*, status.name as status_name, types.name as type_name, lastchange.date as change_date, requested.date as request_date
        FROM `leaves`
        inner join status ON leaves.status = status.id
        inner join types ON leaves.type = types.id
        left outer join (
          SELECT id, MAX(change_date) as date
          FROM leaves_history
          GROUP BY id
        ) lastchange ON leaves.id = lastchange.id
        left outer join (
          SELECT id, MIN(change_date) as date
          FROM leaves_history
          WHERE leaves_history.status = 2
          GROUP BY id
        ) requested ON leaves.id = requested.id
        WHERE leaves.employee = $employeeId")->result_array();
    }

    /**
     * Return a list of Accepted leaves between two dates and for a given employee
     * @param int $employeeId ID of the employee
     * @param string $startDate Start date
     * @param string $endDate End date
     * @return array list of records
     * @throws InvalidArgumentException if the dates are not valid or if the start date is after the end date
     */
    public function getAcceptedLeavesBetweenDates(int $employeeId, string $startDate, string $endDate): array
    {
        //check if $startDate and $endDate are valid dates
        if (!$this->isValidYmdDate($startDate) || !$this->isValidYmdDate($endDate)) {
            throw new InvalidArgumentException('Dates must be valid and use format Y-m-d.');
        }
        if ($startDate > $endDate) {
            throw new InvalidArgumentException('Start date must be earlier than or equal to end date.');
        }
        $this->db->select('leaves.*, types.name as type');
        $this->db->from('leaves');
        $this->db->join('status', 'leaves.status = status.id');
        $this->db->join('types', 'leaves.type = types.id');
        $this->db->where('employee', $employeeId);
        $this->db->where("(startdate <= STR_TO_DATE('" . $endDate . "', '%Y-%m-%d') AND enddate >= STR_TO_DATE('" . $startDate . "', '%Y-%m-%d'))");
        $this->db->where('leaves.status', LMS_ACCEPTED);
        $this->db->order_by('startdate', 'asc');
        return $this->db->get()->result_array();
    }

    /**
     * Try to calculate the length of a leave using the start and and date of the leave
     * and the non working days defined on the contract of the employee
     * @param int $employeeId Identifier of the employee
     * @param string $startDate start date of the leave request
     * @param string $endDate end date of the leave request
     * @param string $startdatetype start date type of leave request being created (Morning or Afternoon)
     * @param string $enddatetype end date type of leave request being created (Morning or Afternoon)
     * @return float length of the leave
     * @throws InvalidArgumentException if the dates are not valid or if the start date is after the end date
     */
    public function length(int $employeeId, string $startDate, string $endDate, string $startdatetype, string $enddatetype): float
    {
        //check if $startDate and $endDate are valid dates
        if (!$this->isValidYmdDate($startDate) || !$this->isValidYmdDate($endDate)) {
            throw new InvalidArgumentException('Dates must be valid and use format Y-m-d.');
        }
        if ($startDate > $endDate) {
            throw new InvalidArgumentException('Start date must be earlier than or equal to end date.');
        }
        $this->db->select('sum(CASE `type` WHEN 1 THEN 1 WHEN 2 THEN 0.5 WHEN 3 THEN 0.5 END) as days');
        $this->db->from('users');
        $this->db->join('dayoffs', 'users.contract = dayoffs.contract');
        $this->db->where('users.id', $employeeId);
        $this->db->where('date >=', $startDate);
        $this->db->where('date <=', $endDate);
        $result = $this->db->get()->result_array();
        $startTimeStamp = strtotime($startDate . " UTC");
        $endTimeStamp = strtotime($endDate . " UTC");
        $timeDiff = abs($endTimeStamp - $startTimeStamp);
        $numberDays = $timeDiff / 86400;  // 86400 seconds in one day
        if (count($result) != 0) { //Test if some non working days are defined on a contract
            return $numberDays - $result[0]['days'];
        } else {
            //Special case when the leave request is half a day long,
            //we assume that the non-working day is not at the same time than the leave request
            if ($startdatetype == $enddatetype) {
                return 0.5;
            } else {
                return $numberDays;
            }
        }
    }

    /**
     * Calculate the actual length of a leave request by taking into account the non-working days
     * Detect overlapping with non-working days. It returns a K/V arrays of 3 items.
     * @param string $startdate start date of the leave request
     * @param string $enddate end date of the leave request
     * @param string $startdatetype start date type of leave request being created (Morning or Afternoon)
     * @param string $enddatetype end date type of leave request being created (Morning or Afternoon)
     * @param array $daysoff List of non-working days
     * @param bool $deductDayOff Deduct days off when evaluating the actual length
     * @return array (length=>length of leave, overlapping=>excat match with a non-working day, daysoff=>sum of days off)
     * @throws InvalidArgumentException if the dates are not valid or if the start date is after the end date
     */
    public function actualLengthAndDaysOff(
        string $startdate,
        string $enddate,
        string $startdatetype,
        string $enddatetype,
        array $daysoff,
        bool $deductDayOff = FALSE
    ): array {
        //check if $startdate and $enddate are valid dates
        if (!$this->isValidYmdDate($startdate) || !$this->isValidYmdDate($enddate)) {
            throw new InvalidArgumentException('Dates must be valid and use format Y-m-d.');
        }
        if ($startdate > $enddate) {
            throw new InvalidArgumentException('Start date must be earlier than or equal to end date.');
        }
        $startDateObject = DateTime::createFromFormat('Y-m-d H:i:s', $startdate . ' 00:00:00');
        $endDateObject = DateTime::createFromFormat('Y-m-d H:i:s', $enddate . ' 00:00:00');
        $iDate = clone $startDateObject;

        //Simplify the reading (and logic) by decomposing into atomic variables
        if ($startdate == $enddate)
            $oneDay = TRUE;
        else
            $oneDay = FALSE;
        if ($startdatetype == 'Morning')
            $start_morning = TRUE;
        else
            $start_morning = FALSE;
        if ($startdatetype == 'Afternoon')
            $start_afternoon = TRUE;
        else
            $start_afternoon = FALSE;
        if ($enddatetype == 'Morning')
            $end_morning = TRUE;
        else
            $end_morning = FALSE;
        if ($enddatetype == 'Afternoon')
            $end_afternoon = TRUE;
        else
            $end_afternoon = FALSE;

        //Iteration between start and end dates of the leave request
        $lengthDaysOff = 0;
        $length = 0;
        $hasDayOff = FALSE;
        $overlapDayOff = FALSE;
        while ($iDate <= $endDateObject) {
            if ($iDate == $startDateObject)
                $first_day = TRUE;
            else
                $first_day = FALSE;
            $isDayOff = FALSE;
            //Iterate on the list of days off with two objectives:
            // - Compute sum of days off between the two dates
            // - Detect if the leave request exactly overlaps with a day off
            foreach ($daysoff as $dayOff) {
                $dayOffObject = DateTime::createFromFormat('Y-m-d H:i:s', $dayOff['date'] . ' 00:00:00');
                if ($dayOffObject == $iDate) {
                    $lengthDaysOff += $dayOff['length'];
                    $isDayOff = TRUE;
                    $hasDayOff = TRUE;
                    switch ($dayOff['type']) {
                        case 1: //1 : All day
                            if ($oneDay && $start_morning && $end_afternoon && $first_day)
                                $overlapDayOff = TRUE;
                            if ($deductDayOff)
                                $length++;
                            break;
                        case 2: //2 : Morning
                            if ($oneDay && $start_morning && $end_morning && $first_day)
                                $overlapDayOff = TRUE;
                            else
                                if ($deductDayOff)
                                    $length++;
                                else
                                    $length += 0.5;
                            break;
                        case 3: //3 : Afternnon
                            if ($oneDay && $start_afternoon && $end_afternoon && $first_day)
                                $overlapDayOff = TRUE;
                            else
                                if ($deductDayOff)
                                    $length++;
                                else
                                    $length += 0.5;
                            break;
                        default:
                            break;
                    }
                    break;
                }
            }
            if (!$isDayOff) {
                if ($oneDay) {
                    if ($start_morning && $end_afternoon)
                        $length++;
                    if ($start_morning && $end_morning)
                        $length += 0.5;
                    if ($start_afternoon && $end_afternoon)
                        $length += 0.5;
                } else {
                    if ($iDate == $endDateObject)
                        $last_day = TRUE;
                    else
                        $last_day = FALSE;
                    if (!$first_day && !$last_day)
                        $length++;
                    if ($first_day && $start_morning)
                        $length++;
                    if ($first_day && $start_afternoon)
                        $length += 0.5;
                    if ($last_day && $end_morning)
                        $length += 0.5;
                    if ($last_day && $end_afternoon)
                        $length++;
                }
                $overlapDayOff = FALSE;
            }
            $iDate->modify('+1 day');   //Next day
        }

        //Other obvious cases of overlapping
        if ($hasDayOff && ($length == 0)) {
            $overlapDayOff = TRUE;
        }
        return ['length' => $length, 'daysoff' => $lengthDaysOff, 'overlapping' => $overlapDayOff];
    }

    /**
     * Get all entitled days applicable to the reference date (to contract and employee)
     * Compute Min and max date by type
     * @param int $employeeId Employee identifier
     * @param int $contractId contract identifier
     * @param string $refDate Date of execution
     * @return array Array of entitled days associated to the key type id
     * @throws InvalidArgumentException if the date is not valid or not in Y-m-d format
     */
    public function getSumEntitledDays(int $employeeId, int $contractId, string $refDate): array
    {
        if (!$this->isValidYmdDate($refDate)) {
            throw new InvalidArgumentException('Date must be valid and use format Y-m-d.');
        }
        $this->db->select('types.id as type_id, types.name as type_name');
        $this->db->select('SUM(entitleddays.days) as entitled');
        $this->db->select('MIN(startdate) as min_date');
        $this->db->select('MAX(enddate) as max_date');
        $this->db->from('entitleddays');
        $this->db->join('types', 'types.id = entitleddays.type');
        $this->db->group_by('types.id');
        $this->db->where('entitleddays.startdate <= ', $refDate);
        $this->db->where('entitleddays.enddate >= ', $refDate);
        $where = ' (entitleddays.contract=' . $contractId .
            ' OR entitleddays.employee=' . $employeeId . ')';
        $this->db->where($where, NULL, FALSE);   //Not very safe, but can't do otherwise
        $results = $this->db->get()->result_array();
        //Create an associated array have the leave type as key
        $entitled_days = array();
        foreach ($results as $result) {
            $entitled_days[$result['type_id']] = $result;
        }
        return $entitled_days;
    }

    /**
     * Compute the leave balance of an employee (used by report and counters)
     * @param int $employeeId ID of the employee
     * @param ?string $refDate tmp of the Date of reference (or current date if NULL)
     * @return ?array computed aggregated taken/entitled leaves or NULL if no contract
     * @throws InvalidArgumentException if the date is not valid or not in Y-m-d format
     */
    public function getLeaveBalanceForEmployee(int $employeeId, ?string $refDate = NULL): ?array
    {
        //Determine if we use current date or another date
        if ($refDate == NULL) {
            $refDate = date("Y-m-d");
        } else {
            if (!$this->isValidYmdDate($refDate)) {
                throw new InvalidArgumentException('Date must be valid and use format Y-m-d.');
            }
        }

        //Compute the current leave period and check if the user has a contract
        $this->load->model('contracts_model');
        $startentdate = NULL;
        $endentdate = NULL;
        $hasContract = $this->contracts_model->getBoundaries($employeeId, $startentdate, $endentdate, $refDate);
        if ($hasContract) {
            $this->load->model('types_model');
            $this->load->model('users_model');
            //Fill a list of all existing leave types
            $summary = [];
            $types = $this->types_model->getTypes();
            foreach ($types as $type) {
                $summary[$type['name']][0] = 0; //Taken
                $summary[$type['name']][1] = 0; //Entitled
                $summary[$type['name']][2] = ''; //Description
            }

            //Get the sum of entitled days
            $user = $this->users_model->getUsers($employeeId);
            $entitlements = $this->getSumEntitledDays($employeeId, $user['contract'], $refDate);

            foreach ($entitlements as $entitlement) {
                //Get the total of taken leaves grouped by type
                $this->db->select('SUM(leaves.duration) as taken, types.name as type');
                $this->db->from('leaves');
                $this->db->join('types', 'types.id = leaves.type');
                $this->db->where('leaves.employee', $employeeId);
                $this->db->where_in('leaves.status', [LMS_ACCEPTED, LMS_CANCELLATION]);
                $this->db->where('leaves.startdate >= ', $entitlement['min_date']);
                $this->db->where('leaves.enddate <=', $entitlement['max_date']);
                $this->db->where('leaves.type', $entitlement['type_id']);
                $this->db->group_by("leaves.type");
                $taken_days = $this->db->get()->result_array();
                //Count the number of taken days
                foreach ($taken_days as $taken) {
                    $summary[$taken['type']][0] = (float) $taken['taken']; //Taken
                }
                //Report the number of available days
                $summary[$entitlement['type_name']][3] = $entitlement['type_id'];
                $summary[$entitlement['type_name']][1] = (float) $entitlement['entitled'];
            }

            //List all planned leaves in a third column
            //planned leave requests are not deducted from credit
            foreach ($entitlements as $entitlement) {
                //Get the total of taken leaves grouped by type
                $this->db->select('SUM(leaves.duration) as planned, types.name as type');
                $this->db->from('leaves');
                $this->db->join('types', 'types.id = leaves.type');
                $this->db->where('leaves.employee', $employeeId);
                $this->db->where('leaves.status', LMS_PLANNED);
                $this->db->where('leaves.startdate >= ', $entitlement['min_date']);
                $this->db->where('leaves.enddate <=', $entitlement['max_date']);
                $this->db->where('leaves.type', $entitlement['type_id']);
                $this->db->group_by("leaves.type");
                $planned_days = $this->db->get()->result_array();
                //Count the number of planned days
                foreach ($planned_days as $planned) {
                    $summary[$planned['type']][3] = $entitlement['type_id'];
                    $summary[$planned['type']][4] = (float) $planned['planned']; //Planned
                    $summary[$planned['type']][2] = 'x'; //Planned
                }
                //Report the number of available days
                $summary[$entitlement['type_name']][1] = (float) $entitlement['entitled'];
            }

            //List all requested leaves in a fourth column
            //leave requests having a requested status are not deducted from credit
            foreach ($entitlements as $entitlement) {
                //Get the total of taken leaves grouped by type
                $this->db->select('SUM(leaves.duration) as requested, types.name as type');
                $this->db->from('leaves');
                $this->db->join('types', 'types.id = leaves.type');
                $this->db->where('leaves.employee', $employeeId);
                $this->db->where('leaves.status', LMS_REQUESTED);
                $this->db->where('leaves.startdate >= ', $entitlement['min_date']);
                $this->db->where('leaves.enddate <=', $entitlement['max_date']);
                $this->db->where('leaves.type', $entitlement['type_id']);
                $this->db->group_by("leaves.type");
                $requested_days = $this->db->get()->result_array();
                //Count the number of planned days
                foreach ($requested_days as $requested) {
                    $summary[$requested['type']][3] = $entitlement['type_id'];
                    $summary[$requested['type']][5] = (float) $requested['requested']; //requested
                    $summary[$requested['type']][2] = 'x'; //requested
                }
                //Report the number of available days
                $summary[$entitlement['type_name']][1] = (float) $entitlement['entitled'];
            }

            //Remove all lines having taken and entitled set to set to 0
            foreach ($summary as $key => $value) {
                if ($value[0] == 0 && $value[1] == 0 && $value[2] != 'x') {
                    unset($summary[$key]);
                }
            }
            return $summary;
        } else { //User attached to no contract
            return NULL;
        }
    }

    /**
     * Get the number of days a user can take for a given leave type
     * @param int $employeeId employee identifier
     * @param string $type leave type name
     * @param ?string $startdate Start date of leave request or null
     * @return float number of available days or NULL if the user has no contract
     */
    public function getLeavesTypeBalanceForEmployee(int $employeeId, string $type, ?string $startdate = NULL): ?float
    {
        $summary = $this->getLeaveBalanceForEmployee($employeeId, $startdate);
        //return entitled days - taken (for a given leave type)
        if (is_null($summary)) {
            return NULL;
        } else {
            if (array_key_exists($type, $summary)) {
                return ($summary[$type][1] - $summary[$type][0]);
            } else {
                return 0.0;
            }
        }
    }

    /**
     * Detect if the leave request overlaps with another request of the employee
     * @param int $employeeId employee id
     * @param string $startdate start date of leave request being created
     * @param string $enddate end date of leave request being created
     * @param string $startdatetype start date type of leave request being created (Morning or Afternoon)
     * @param string $enddatetype end date type of leave request being created (Morning or Afternoon)
     * @param int $leave_id When this function is used for editing a leave request, we must not collide with this leave request
     * @return boolean TRUE if another leave request has been emmitted, FALSE otherwise
     * @throws InvalidArgumentException if the date is not valid or not in Y-m-d format
     */
    public function detectOverlappingLeaves(int $employeeId, string $startdate, string $enddate, string $startdatetype, string $enddatetype, ?int $leave_id = NULL): bool
    {
        //check if $startdate and $enddate are valid dates
        if (!$this->isValidYmdDate($startdate) || !$this->isValidYmdDate($enddate)) {
            throw new InvalidArgumentException('Dates must be valid and use format Y-m-d.');
        }
        if ($startdate > $enddate) {
            throw new InvalidArgumentException('Start date must be earlier than or equal to end date.');
        }

        $overlapping = FALSE;
        $this->db->where('employee', $employeeId);
        $this->db->where('status != 4');
        $this->db->where('(startdate <= DATE(\'' . $enddate . '\') AND enddate >= DATE(\'' . $startdate . '\'))');
        if (!is_null($leave_id)) {
            $this->db->where('id != ', $leave_id);
        }
        $leaves = $this->db->get('leaves')->result();

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

        foreach ($leaves as $leave) {
            if ($leave->startdatetype == "Morning") {
                $startTmpDB = strtotime($leave->startdate . " 08:00:00 UTC");
            } else {
                $startTmpDB = strtotime($leave->startdate . " 12:01:00 UTC");
            }
            if ($leave->enddatetype == "Morning") {
                $endTmpDB = strtotime($leave->enddate . " 12:00:00 UTC");
            } else {
                $endTmpDB = strtotime($leave->enddate . " 18:00:00 UTC");
            }
            if (($startTmpDB <= $endTmp) && ($endTmpDB >= $startTmp)) {
                $overlapping = TRUE;
            }
        }
        return $overlapping;
    }

    /**
     * Create a leave request
     * @param int $employeeId Identifier of the employee
     * @return int id of the newly created leave request into the db
     */
    public function setLeaves(int $employeeId): int
    {
        //TODO: decouple input ($this->input->post()) from model
        //TODO: decouple config
        $data = array(
            'startdate' => $this->input->post('startdate'),
            'startdatetype' => $this->input->post('startdatetype'),
            'enddate' => $this->input->post('enddate'),
            'enddatetype' => $this->input->post('enddatetype'),
            'duration' => abs($this->input->post('duration')),
            'type' => $this->input->post('type'),
            'cause' => $this->input->post('cause'),
            'status' => $this->input->post('status'),
            'employee' => $employeeId
        );
        $this->db->insert('leaves', $data);
        $newId = $this->db->insert_id();

        //Trace the modification if the feature is enabled
        if ($this->config->item('enable_history')) {
            $this->load->model('history_model');
            $this->history_model->setHistory(1, 'leaves', $newId, $employeeId);
        }

        return $newId;
    }

    /**
     * Create the same leave request for a list of employees
     * @param int $type Identifier of the leave type
     * @param float $duration duration of the leave
     * @param string $startdate Start date (MySQL format YYYY-MM-DD)
     * @param string $enddate End date (MySQL format YYYY-MM-DD)
     * @param string $startdatetype Start date type of the leave (Morning/Afternoon)
     * @param string $enddatetype End date type of the leave (Morning/Afternoon)
     * @param string $cause Identifier of the leave
     * @param int $status status of the leave
     * @param array $employees List of DB Ids of the affected employees
     * @return int Number of affected rows
     */
    public function createRequestForUserList($type, $duration, $startdate, $enddate, $startdatetype, $enddatetype, $cause, $status, array $employees): int
    {
        //TODO: decouple input ($this->input->post()) from model
        //TODO: sanitize entries

        $affectedRows = 0;
        if ($this->config->item('enable_history')) {
            foreach ($employees as $id) {
                $this->createLeaveByApi(
                    $this->input->post('startdate'),
                    $this->input->post('enddate'),
                    $this->input->post('status'),
                    $id,
                    $this->input->post('cause'),
                    $this->input->post('startdatetype'),
                    $this->input->post('enddatetype'),
                    abs($this->input->post('duration')),
                    $this->input->post('type')
                );
                $affectedRows++;
            }
        } else {
            $data = [];
            foreach ($employees as $id) {
                $data[] = [
                    'startdate' => $this->input->post('startdate'),
                    'startdatetype' => $this->input->post('startdatetype'),
                    'enddate' => $this->input->post('enddate'),
                    'enddatetype' => $this->input->post('enddatetype'),
                    'duration' => abs($this->input->post('duration')),
                    'type' => $this->input->post('type'),
                    'cause' => $this->input->post('cause'),
                    'status' => $this->input->post('status'),
                    'employee' => $id
                ];
            }
            $affectedRows = $this->db->insert_batch('leaves', $data);
        }
        return $affectedRows;
    }

    /**
     * Create a leave request (suitable for API use)
     * @param string $startdate Start date (MySQL format YYYY-MM-DD)
     * @param string $enddate End date (MySQL format YYYY-MM-DD)
     * @param int $status Status of leave (see table status or doc)
     * @param int $employeeId Identifier of the employee
     * @param string $cause Optional reason of the leave
     * @param string $startdatetype Start date type (Morning/Afternoon)
     * @param string $enddatetype End date type (Morning/Afternoon)
     * @param float $duration duration of the leave request
     * @param int $type Type of leave (except compensate, fully customizable by user)
     * @param ?string $comments (optional) JSON encoded comment
     * @param ?string $document Base64 encoded document
     * @return int id of the newly acreated leave request into the db
     * @throws InvalidArgumentException if the date is not valid or not in Y-m-d format
     */
    public function createLeaveByApi(
        string $startdate,
        string $enddate,
        int $status,
        int $employeeId,
        string $cause,
        string $startdatetype,
        string $enddatetype,
        float $duration,
        int $type,
        ?string $comments = NULL,
        ?string $document = NULL
    ): int {

        //check if $startdate and $enddate are valid dates
        if (!$this->isValidYmdDate($startdate) || !$this->isValidYmdDate($enddate)) {
            throw new InvalidArgumentException('Dates must be valid and use format Y-m-d.');
        }
        if ($startdate > $enddate) {
            throw new InvalidArgumentException('Start date must be earlier than or equal to end date.');
        }

        $data = [
            'startdate' => $startdate,
            'enddate' => $enddate,
            'status' => $status,
            'employee' => $employeeId,
            'cause' => $cause,
            'startdatetype' => $startdatetype,
            'enddatetype' => $enddatetype,
            'duration' => abs($duration),
            'type' => $type
        ];
        if (!empty($comments))
            $data['comments'] = $comments;
        if (!empty($document))
            $data['document'] = $document;
        $this->db->insert('leaves', $data);
        $newId = $this->db->insert_id();

        //Trace the modification if the feature is enabled
        if ($this->config->item('enable_history')) {
            $this->load->model('history_model');
            $this->history_model->setHistory(1, 'leaves', $newId, $this->session->userdata('id'));
        }
        return $newId;
    }

    /**
     * Update a leave request in the database with the values posted by an HTTP POST
     * @param int $leaveId of the leave request
     * @param int $userId Identifier of the user (optional)
     */
    public function updateLeaves(int $leaveId, int $userId = 0): void
    {
        //TODO: decouple the logic (not by controler->form)
        if ($userId == 0) {
            $userId = $this->session->userdata('id');
        }

        //TODO: prepareCommentOnStatusChanged smells bad
        $json = $this->prepareCommentOnStatusChanged($leaveId, $this->input->post('status'));
        if ($this->input->post('comment') != NULL) {
            $jsonDecode = json_decode($json);
            $commentObject = new stdClass;
            $commentObject->type = "comment";
            $commentObject->author = $userId;
            $commentObject->value = $this->input->post('comment');
            $commentObject->date = date("Y-n-j");
            if (isset($jsonDecode) && $jsonDecode != null) {
                array_push($jsonDecode->comments, $commentObject);
            } else {
                $jsonDecode = new stdClass;
                $jsonDecode->comments = [$commentObject];
            }
            $json = json_encode($jsonDecode);
        }
        $data = [
            'startdate' => $this->input->post('startdate'),
            'startdatetype' => $this->input->post('startdatetype'),
            'enddate' => $this->input->post('enddate'),
            'enddatetype' => $this->input->post('enddatetype'),
            'duration' => abs($this->input->post('duration')),
            'type' => $this->input->post('type'),
            'cause' => $this->input->post('cause'),
            'status' => $this->input->post('status'),
            'comments' => $json
        ];
        $this->db->where('id', $leaveId);
        $this->db->update('leaves', $data);

        //Trace the modification if the feature is enabled
        if ($this->config->item('enable_history')) {
            $this->load->model('history_model');
            $this->history_model->setHistory(2, 'leaves', $leaveId, $userId);
        }
    }

    /**
     * Delete a leave from the database
     * @param int $leaveId leave request identifier
     * @param int $userId Identifier of the user (optional)
     * @return int number of affected rows
     */
    public function deleteLeave(int $leaveId, int $userId = 0): int
    {
        //TODO: decouple the way we get the configuration item here
        //Trace the modification if the feature is enabled
        if ($this->config->item('enable_history')) {
            if ($userId == 0) {
                $userId = $this->session->userdata('id');
            }
            $this->load->model('history_model');
            $this->history_model->setHistory(3, 'leaves', $leaveId, $userId);
        }
        return $this->db->delete('leaves', array('id' => $leaveId));
    }

    /**
     * Switch the status of a leave request. You may use one of the constants
     * listed into config/constants.php
     * @param int $leaveId leave request identifier
     * @param int $status Next Status
     */
    public function switchStatus(int $leaveId, int $status): void
    {
        //TODO: prepareCommentOnStatusChanged smells bad
        //TODO: decouple the session stuff
        $json = $this->prepareCommentOnStatusChanged($leaveId, $status);
        $data = array(
            'status' => $status,
            'comments' => $json
        );
        $this->db->where('id', $leaveId);
        $this->db->update('leaves', $data);

        //Trace the modification if the feature is enabled
        if ($this->config->item('enable_history')) {
            $this->load->model('history_model');
            $this->history_model->setHistory(2, 'leaves', $leaveId, $this->session->userdata('id'));
        }
    }

    /**
     * Switch the status of a leave request and a comment. You may use one of the constants
     * listed into config/constants.php
     * @param int $id leave request identifier
     * @param int $status Next Status
     * @param string $comment New comment
     */
    public function switchStatusAndComment(int $id, int $status, string $comment): void
    {
        $json_parsed = $this->getCommentsLeave($id);
        $commentObject = new stdClass;
        $commentObject->type = "comment";
        $commentObject->author = $this->session->userdata('id');
        $commentObject->value = $comment;
        $commentObject->date = date("Y-n-j");
        if (isset($json_parsed)) {
            array_push($json_parsed->comments, $commentObject);
        } else {
            $json_parsed = new stdClass;
            $json_parsed->comments = [$commentObject];
        }
        $comment_change = new stdClass;
        $comment_change->type = "change";
        $comment_change->status_number = $status;
        $comment_change->date = date("Y-n-j");
        if (isset($json_parsed->comments)) {
            array_push($json_parsed->comments, $comment_change);
        } else {
            $json_parsed->comments = [$comment_change];
        }
        $json = json_encode($json_parsed);
        $data = array(
            'status' => $status,
            'comments' => $json
        );
        $this->db->where('id', $id);
        $this->db->update('leaves', $data);

        //Trace the modification if the feature is enabled
        if ($this->config->item('enable_history')) {
            $this->load->model('history_model');
            $this->history_model->setHistory(2, 'leaves', $id, $this->session->userdata('id'));
        }
    }

    /**
     * Delete leaves attached to a user
     * @param int $employee identifier of an employee
     * @return int number of affected rows
     */
    public function deleteLeavesCascadeUser(int $employee): int
    {
        //Select the leaves of a users (if history feature is enabled)
        if ($this->config->item('enable_history')) {
            $this->load->model('history_model');
            $leaves = $this->getLeavesOfEmployee($employee);
            //TODO in fact, should we cascade delete ?
            foreach ($leaves as $leave) {
                $this->history_model->setHistory(3, 'leaves', $leave['id'], $this->session->userdata('id'));
            }
        }
        return $this->db->delete('leaves', ['employee' => $employee]);
    }

    /**
     * Leave requests of All leave request of the user (suitable for FullCalendar widget)
     * @param int $user_id connected user
     * @param string $start Unix timestamp / Start date displayed on calendar
     * @param string $end Unix timestamp / End date displayed on calendar
     * @return string JSON encoded list of full calendar events
     */
    public function individual(int $user_id, string $start = "", string $end = ""): string
    {
        $this->db->select('leaves.*, types.name as type');
        $this->db->join('types', 'leaves.type = types.id');
        $this->db->where('employee', $user_id);
        $this->db->where('(leaves.startdate <= DATE(' . $this->db->escape($end) . ') AND leaves.enddate >= DATE(' . $this->db->escape($start) . '))');
        $this->db->order_by('startdate', 'desc');
        $this->db->limit(1024);  //Security limit
        $events = $this->db->get('leaves')->result();

        $jsonevents = [];
        foreach ($events as $entry) {

            if ($entry->startdatetype == "Morning") {
                $startdate = $entry->startdate . 'T07:00:00';
            } else {
                $startdate = $entry->startdate . 'T12:00:00';
            }

            if ($entry->enddatetype == "Morning") {
                $enddate = $entry->enddate . 'T12:00:00';
            } else {
                $enddate = $entry->enddate . 'T18:00:00';
            }

            $imageUrl = '';
            $allDay = FALSE;
            $startdatetype = $entry->startdatetype;
            $enddatetype = $entry->enddatetype;
            if ($startdate == $enddate) { //Deal with invalid start/end date
                $imageUrl = base_url() . 'assets/images/date_error.png';
                $startdate = $entry->startdate . 'T07:00:00';
                $enddate = $entry->enddate . 'T18:00:00';
                $startdatetype = "Morning";
                $enddatetype = "Afternoon";
                $allDay = TRUE;
            }

            $color = '#ff0000';
            switch ($entry->status) {
                case 1:
                    $color = '#999';
                    break;     // Planned
                case 2:
                    $color = '#f89406';
                    break;  // Requested
                case 3:
                    $color = '#468847';
                    break;  // Accepted
                case 4:
                    $color = '#ff0000';
                    break;  // Rejected
            }

            $jsonevents[] = array(
                'id' => $entry->id,
                'title' => $entry->type,
                'imageurl' => $imageUrl,
                'start' => $startdate,
                'color' => $color,
                'allDay' => $allDay,
                'end' => $enddate,
                'startdatetype' => $startdatetype,
                'enddatetype' => $enddatetype
            );
        }
        return json_encode($jsonevents);
    }

    /**
     * Leave requests of All users having the same manager (suitable for FullCalendar widget)
     * @param int $user_id id of the manager
     * @param string $start Unix timestamp / Start date displayed on calendar
     * @param string $end Unix timestamp / End date displayed on calendar
     * @return string JSON encoded list of full calendar events
     */
    public function workmates(int $user_id, string $start = "", string $end = ""): string
    {
        $this->db->join('users', 'users.id = leaves.employee');
        $this->db->where('users.manager', $user_id);
        $this->db->where('leaves.status < ', LMS_REJECTED);       //Exclude rejected requests
        $this->db->where('(leaves.startdate <= DATE(' . $this->db->escape($end) . ') AND leaves.enddate >= DATE(' . $this->db->escape($start) . '))');
        $this->db->order_by('startdate', 'desc');
        $this->db->limit(1024);  //Security limit
        $events = $this->db->get('leaves')->result();

        $jsonevents = [];
        foreach ($events as $entry) {
            if ($entry->startdatetype == "Morning") {
                $startdate = $entry->startdate . 'T07:00:00';
            } else {
                $startdate = $entry->startdate . 'T12:00:00';
            }

            if ($entry->enddatetype == "Morning") {
                $enddate = $entry->enddate . 'T12:00:00';
            } else {
                $enddate = $entry->enddate . 'T18:00:00';
            }

            $imageUrl = '';
            $allDay = FALSE;
            $startdatetype = $entry->startdatetype;
            $enddatetype = $entry->enddatetype;
            if ($startdate == $enddate) { //Deal with invalid start/end date
                $imageUrl = base_url() . 'assets/images/date_error.png';
                $startdate = $entry->startdate . 'T07:00:00';
                $enddate = $entry->enddate . 'T18:00:00';
                $startdatetype = "Morning";
                $enddatetype = "Afternoon";
                $allDay = TRUE;
            }

            $color = '#ff0000';
            switch ($entry->status) {
                case 1:
                    $color = '#999';
                    break;     // Planned
                case 2:
                    $color = '#f89406';
                    break;  // Requested
                case 3:
                    $color = '#468847';
                    break;  // Accepted
                case 4:
                    $color = '#ff0000';
                    break;  // Rejected
            }

            $jsonevents[] = [
                'id' => $entry->id,
                'title' => $entry->firstname . ' ' . $entry->lastname,
                'imageurl' => $imageUrl,
                'start' => $startdate,
                'color' => $color,
                'allDay' => $allDay,
                'end' => $enddate,
                'startdatetype' => $startdatetype,
                'enddatetype' => $enddatetype
            ];
        }
        return json_encode($jsonevents);
    }

    /**
     * Leave requests of All users having the same manager (suitable for FullCalendar widget)
     * @param int $user_id id of the manager
     * @param string $start Unix timestamp / Start date displayed on calendar
     * @param string $end Unix timestamp / End date displayed on calendar
     * @return string JSON encoded list of full calendar events
     */
    public function collaborators(int $user_id, string $start = "", string $end = ""): string
    {
        $this->db->join('users', 'users.id = leaves.employee');
        $this->db->where('users.manager', $user_id);
        $this->db->where('(leaves.startdate <= DATE(' . $this->db->escape($end) . ') AND leaves.enddate >= DATE(' . $this->db->escape($start) . '))');
        $this->db->order_by('startdate', 'desc');
        $this->db->limit(1024);  //Security limit
        $events = $this->db->get('leaves')->result();

        $jsonevents = [];
        foreach ($events as $entry) {
            if ($entry->startdatetype == "Morning") {
                $startdate = $entry->startdate . 'T07:00:00';
            } else {
                $startdate = $entry->startdate . 'T12:00:00';
            }

            if ($entry->enddatetype == "Morning") {
                $enddate = $entry->enddate . 'T12:00:00';
            } else {
                $enddate = $entry->enddate . 'T18:00:00';
            }

            $imageUrl = '';
            $allDay = FALSE;
            $startdatetype = $entry->startdatetype;
            $enddatetype = $entry->enddatetype;
            if ($startdate == $enddate) { //Deal with invalid start/end date
                $imageUrl = base_url() . 'assets/images/date_error.png';
                $startdate = $entry->startdate . 'T07:00:00';
                $enddate = $entry->enddate . 'T18:00:00';
                $startdatetype = "Morning";
                $enddatetype = "Afternoon";
                $allDay = TRUE;
            }

            $color = '#ff0000';
            switch ($entry->status) {
                case 1:
                    $color = '#999';
                    break;     // Planned
                case 2:
                    $color = '#f89406';
                    break;  // Requested
                case 3:
                    $color = '#468847';
                    break;  // Accepted
                case 4:
                    $color = '#ff0000';
                    break;  // Rejected
            }

            $jsonevents[] = [
                'id' => $entry->id,
                'title' => $entry->firstname . ' ' . $entry->lastname,
                'imageurl' => $imageUrl,
                'start' => $startdate,
                'color' => $color,
                'allDay' => $allDay,
                'end' => $enddate,
                'startdatetype' => $startdatetype,
                'enddatetype' => $enddatetype
            ];
        }
        return json_encode($jsonevents);
    }

    /**
     * Leave requests of All users of a department (suitable for FullCalendar widget)
     * @param int $entity_id Entity identifier (the department)
     * @param string $start Unix timestamp / Start date displayed on calendar
     * @param string $end Unix timestamp / End date displayed on calendar
     * @param bool $children Include sub department in the query
     * @param string $statusFilter optional filter on status
     * @return string JSON encoded list of full calendar events
     */
    public function department(int $entity_id, string $start = "", string $end = "", bool $children = FALSE, ?string $statusFilter = NULL): string
    {
        $this->db->select('users.firstname, users.lastname, users.manager');
        $this->db->select('leaves.*');
        $this->db->select('types.name as type, types.acronym as acronym');
        $this->db->from('organization');
        $this->db->join('users', 'users.organization = organization.id');
        $this->db->join('leaves', 'leaves.employee = users.id');
        $this->db->join('types', 'leaves.type = types.id');
        $this->db->where('(leaves.startdate <= DATE(' . $this->db->escape($end) . ') AND leaves.enddate >= DATE(' . $this->db->escape($start) . '))');
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
        //$this->db->where('leaves.status != ', 4); //Exclude rejected requests
        if ($statusFilter != NULL) {
            $statuses = explode('|', $statusFilter);
            $this->db->where_in('status', $statuses);
        }
        $this->db->order_by('startdate', 'desc');
        $this->db->limit(1024);  //Security limit
        $events = $this->db->get()->result();
        $jsonevents = array();
        foreach ($events as $entry) {
            //Date of event
            if ($entry->startdatetype == "Morning") {
                $startdate = $entry->startdate . 'T07:00:00';
            } else {
                $startdate = $entry->startdate . 'T12:00:00';
            }

            if ($entry->enddatetype == "Morning") {
                $enddate = $entry->enddate . 'T12:00:00';
            } else {
                $enddate = $entry->enddate . 'T18:00:00';
            }
            $imageUrl = '';
            $allDay = FALSE;
            $startdatetype = $entry->startdatetype;
            $enddatetype = $entry->enddatetype;
            if ($startdate == $enddate) { //Deal with invalid start/end date
                $imageUrl = base_url() . 'assets/images/date_error.png';
                $startdate = $entry->startdate . 'T07:00:00';
                $enddate = $entry->enddate . 'T18:00:00';
                $startdatetype = "Morning";
                $enddatetype = "Afternoon";
                $allDay = TRUE;
            }

            $color = '#ff0000';
            switch ($entry->status) {
                case 1:
                    $color = '#999';
                    break;     // Planned
                case 2:
                    $color = '#f89406';
                    break;  // Requested
                case 3:
                    $color = '#468847';
                    break;  // Accepted
                case 4:
                    $color = '#ff0000';
                    break;  // Rejected
                default:
                    $color = '#ff0000';
                    break;  // Cancellation and Canceled
            }
            $title = $entry->firstname . ' ' . $entry->lastname;
            //If the connected user can access to the leave request
            //(self, HR admin and manager), add a link and the acronym
            $url = '';
            if (
                ($entry->employee == $this->session->userdata('id')) ||
                ($entry->manager == $this->session->userdata('id')) ||
                ($this->session->userdata('is_hr') === TRUE)
            ) {
                $url = base_url() . 'leaves/leaves/' . $entry->id;
                if (!empty($entry->acronym)) {
                    $title .= ' - ' . $entry->acronym;
                }
            } else {
                //Don't display rejected and cancel* leave requests for other employees
                if ($entry->status > 3) {
                    continue;
                }
            }

            //Create the JSON representation of the event
            $jsonevents[] = [
                'id' => $entry->id,
                'title' => $title,
                'imageurl' => $imageUrl,
                'start' => $startdate,
                'color' => $color,
                'allDay' => $allDay,
                'end' => $enddate,
                'startdatetype' => $startdatetype,
                'enddatetype' => $enddatetype,
                'url' => $url
            ];
        }
        return json_encode($jsonevents);
    }

    /**
     * Leave requests of All users of a list (suitable for FullCalendar widget)
     * @param int $list_id List identifier
     * @param string $start Unix timestamp / Start date displayed on calendar
     * @param string $end Unix timestamp / End date displayed on calendar
     * @param string $statusFilter optional filter on status
     * @return string JSON encoded list of full calendar events
     */
    public function getListRequest(int $list_id, string $start = "", string $end = "", ?string $statusFilter = NULL): string
    {
        $this->db->select('users.firstname, users.lastname, users.manager');
        $this->db->select('leaves.*');
        $this->db->select('types.name as type, types.acronym as acronym');
        $this->db->from('organization');
        $this->db->join('users', 'users.organization = organization.id');
        $this->db->join('leaves', 'leaves.employee = users.id');
        $this->db->join('types', 'leaves.type = types.id');
        $this->db->join('org_lists_employees', 'org_lists_employees.user = users.id');
        $this->db->where('(leaves.startdate <= DATE(' . $this->db->escape($end) . ') AND leaves.enddate >= DATE(' . $this->db->escape($start) . '))');
        $this->db->where('org_lists_employees.list', $list_id);
        //$this->db->where('leaves.status != ', 4); //Exclude rejected requests
        if ($statusFilter != NULL) {
            $statuses = explode('|', $statusFilter);
            $this->db->where_in('status', $statuses);
        }
        $this->db->order_by('startdate', 'desc');
        $this->db->limit(1024);  //Security limit
        $events = $this->db->get()->result();
        return $this->transformToEvent($events);
    }

    /**
     * Transform a list of leaves to a list of full calendar events
     * @param array $events List of leaves
     * @return string JSON encoded list of full calendar events
     */
    private function transformToEvent(array $events): string
    {
        $jsonevents = [];
        foreach ($events as $entry) {
            //Date of event
            if ($entry->startdatetype == "Morning") {
                $startdate = $entry->startdate . 'T07:00:00';
            } else {
                $startdate = $entry->startdate . 'T12:00:00';
            }

            if ($entry->enddatetype == "Morning") {
                $enddate = $entry->enddate . 'T12:00:00';
            } else {
                $enddate = $entry->enddate . 'T18:00:00';
            }
            $imageUrl = '';
            $allDay = FALSE;
            $startdatetype = $entry->startdatetype;
            $enddatetype = $entry->enddatetype;
            if ($startdate == $enddate) { //Deal with invalid start/end date
                $imageUrl = base_url() . 'assets/images/date_error.png';
                $startdate = $entry->startdate . 'T07:00:00';
                $enddate = $entry->enddate . 'T18:00:00';
                $startdatetype = "Morning";
                $enddatetype = "Afternoon";
                $allDay = TRUE;
            }

            $color = '#ff0000';
            switch ($entry->status) {
                case 1:
                    $color = '#999';
                    break;     // Planned
                case 2:
                    $color = '#f89406';
                    break;  // Requested
                case 3:
                    $color = '#468847';
                    break;  // Accepted
                case 4:
                    $color = '#ff0000';
                    break;  // Rejected
                default:
                    $color = '#ff0000';
                    break;  // Cancellation and Canceled
            }
            $title = $entry->firstname . ' ' . $entry->lastname;
            //If the connected user can access to the leave request
            //(self, HR admin and manager), add a link and the acronym
            $url = '';
            if (
                ($entry->employee == $this->session->userdata('id')) ||
                ($entry->manager == $this->session->userdata('id')) ||
                ($this->session->userdata('is_hr') === TRUE)
            ) {
                $url = base_url() . 'leaves/leaves/' . $entry->id;
                if (!empty($entry->acronym)) {
                    $title .= ' - ' . $entry->acronym;
                }
            } else {
                //Don't display rejected and cancel* leave requests for other employees
                if ($entry->status > 3) {
                    continue;
                }
            }

            //Create the JSON representation of the event
            $jsonevents[] = array(
                'id' => $entry->id,
                'title' => $title,
                'imageurl' => $imageUrl,
                'start' => $startdate,
                'color' => $color,
                'allDay' => $allDay,
                'end' => $enddate,
                'startdatetype' => $startdatetype,
                'enddatetype' => $enddatetype,
                'url' => $url
            );
        }
        return json_encode($jsonevents);
    }

    /**
     * Leave requests of All users of an entity
     * @param int $entity_id Entity identifier (the department)
     * @param bool $children Include sub department in the query
     * @return array List of leave requests (DB records)
     */
    public function entity(int $entity_id, bool $children = FALSE): array
    {
        $this->db->select('users.firstname, users.lastname,  leaves.*, types.name as type');
        $this->db->from('organization');
        $this->db->join('users', 'users.organization = organization.id');
        $this->db->join('leaves', 'leaves.employee  = users.id');
        $this->db->join('types', 'leaves.type = types.id');
        if ($children === TRUE) {
            $this->load->model('organization_model');
            $list = $this->organization_model->getAllChildren($entity_id);
            $ids = [];
            if (count($list) > 0) {
                $ids = explode(",", $list[0]['id']);
            }
            array_push($ids, $entity_id);
            $this->db->where_in('organization.id', $ids);
        } else {
            $this->db->where('organization.id', $entity_id);
        }
        $this->db->where('leaves.status != ', 4);       //Exclude rejected requests
        $this->db->order_by('startdate', 'desc');
        $events = $this->db->get()->result_array();
        return $events;
    }

    /**
     * List all leave requests submitted to the connected user (or if delegate of a manager)
     * Can be filtered with "Requested" status.
     * @param int $manager connected user
     * @param bool $all TRUE all requests, FALSE otherwise
     * @return array Recordset (can be empty if no requests or not a manager)
     */
    public function getLeavesRequestedToManager(int $manager, bool $all = FALSE): array
    {
        $this->load->model('delegations_model');
        $ids = $this->delegations_model->listManagersGivingDelegation($manager);
        $this->db->select('leaves.id as leave_id, users.*, leaves.*, types.name as type_label');
        $this->db->select('status.name as status_name, types.name as type_name');
        $this->db->join('status', 'leaves.status = status.id');
        $this->db->join('types', 'leaves.type = types.id');
        $this->db->join('users', 'users.id = leaves.employee');

        if (count($ids) > 0) {
            array_push($ids, $manager);
            $this->db->where_in('users.manager', $ids);
        } else {
            $this->db->where('users.manager', $manager);
        }
        if ($all == FALSE) {
            $this->db->where('leaves.status', LMS_REQUESTED);
            $this->db->or_where('leaves.status', LMS_CANCELLATION);
        }
        $this->db->order_by('leaves.startdate', 'desc');
        $query = $this->db->get('leaves');
        return $query->result_array();
    }

    /**
     * Get the list of history of an employee
     * @param int $manager Id of the employee
     * @param bool $all TRUE all requests, FALSE otherwise
     * @return array list of records
     */
    public function getLeavesRequestedToManagerWithHistory(int $manager, bool $all = FALSE): array
    {
        $this->load->model('delegations_model');
        $manager = intval($manager);
        $query = "SELECT leaves.id as leave_id, users.*, leaves.*, types.name as type_label, status.name as status_name, types.name as type_name, lastchange.date as change_date, requested.date as request_date
        FROM `leaves`
        inner join status ON leaves.status = status.id
        inner join types ON leaves.type = types.id
        inner join users ON users.id = leaves.employee
        left outer join (
          SELECT id, MAX(change_date) as date
          FROM leaves_history
          GROUP BY id
        ) lastchange ON leaves.id = lastchange.id
        left outer join (
          SELECT id, MIN(change_date) as date
          FROM leaves_history
          WHERE leaves_history.status = 2
          GROUP BY id
        ) requested ON leaves.id = requested.id";
        //Case of manager having delegations
        $ids = $this->delegations_model->listManagersGivingDelegation($manager);
        if (count($ids) > 0) {
            array_push($ids, $manager);
            $query .= " WHERE users.manager IN (" . implode(",", $ids) . ")";
        } else {
            $query .= " WHERE users.manager = $manager";
        }
        if ($all == FALSE) {
            $query .= " AND (leaves.status = " . LMS_REQUESTED .
                " OR leaves.status = " . LMS_CANCELLATION . ")";
        }
        $query = $query . " order by leaves.startdate DESC;";
        $this->db->query('SET SQL_BIG_SELECTS=1');
        return $this->db->query($query)->result_array();
    }

    /**
     * Count the number of leave requests submitted to the connected user (or delegates of a manager)
     * @param int $managerId Identifier of a manager
     * @return int number of leave requests
     */
    public function countLeavesRequestedToManager(int $managerId): int
    {
        $this->load->model('delegations_model');
        $ids = $this->delegations_model->listManagersGivingDelegation($managerId);
        $this->db->select('count(*) as number', FALSE);
        $this->db->join('users', 'users.id = leaves.employee');
        $this->db->where_in('leaves.status', [LMS_REQUESTED, LMS_CANCELLATION]);

        if (count($ids) > 0) {
            array_push($ids, $managerId);
            $this->db->where_in('users.manager', $ids);
        } else {
            $this->db->where('users.manager', $managerId);
        }
        $result = $this->db->get('leaves');
        return $result->row()->number;
    }

    /**
     * Purge the table by deleting the records prior $toDate
     * @param string $toDate Date in Y-m-d format
     * @return int number of affected rows
     * @throws InvalidArgumentException if dates are invalid or start date is after end date
     */
    public function purgeLeaves(string $toDate): int
    {
        //TODO : if one day we use this function, what should we do with the history feature?
        //check if $toDate is valid dates
        if (!$this->isValidYmdDate($toDate)) {
            throw new InvalidArgumentException('Date must be valid and use format Y-m-d.');
        }
        $this->db->where(' <= ', $toDate);
        return $this->db->delete('leaves');
    }

    /**
     * Count the number of rows into the table
     * @return int number of rows
     */
    public function count(): int
    {
        $this->db->select('count(*) as number', FALSE);
        $this->db->from('leaves');
        $result = $this->db->get();
        return $result->row()->number;
    }

    /**
     * Get all leaves between two timestamps, no filters
     * @param string $startDate Start date displayed on calendar
     * @param string $endDate End date displayed on calendar
     * @return array Array of results containing leave details
     * @throws InvalidArgumentException if dates are invalid or start date is after end date
     */
    public function all(string $startDate, string $endDate): array
    {
        //check if $startDate and $endDate are valid dates
        if (!$this->isValidYmdDate($startDate) || !$this->isValidYmdDate($endDate)) {
            throw new InvalidArgumentException('Dates must be valid and use format Y-m-d.');
        }
        if ($startDate > $endDate) {
            throw new InvalidArgumentException('Start date must be earlier than or equal to end date.');
        }
        $this->db->select("users.id as user_id, users.firstname, users.lastname, leaves.*", FALSE);
        $this->db->join('users', 'users.id = leaves.employee');
        $this->db->where('( (leaves.startdate >= ' . $this->db->escape($startDate) . ' AND leaves.enddate <= ' . $this->db->escape($endDate) . ')' .
            ' OR (leaves.startdate <= ' . $this->db->escape($endDate) . ' AND leaves.enddate >= ' . $this->db->escape($endDate) . '))');
        $this->db->order_by('startdate', 'desc');
        return $this->db->get('leaves')->result();
    }

    /**
     * Leave requests of users of a department(s)
     * @param int $entity Entity identifier (the department)
     * @param int $month Month number
     * @param int $year Year number
     * @param bool $children Include sub department in the query
     * @param string $statusFilter optional filter on statuses (pipe separated)
     * @param boolean $calendar Is this function called to display a calendar
     * @return array Array of objects containing leave details
     */
    public function tabular(
        int &$entity = -1,
        int &$month = 0,
        int &$year = 0,
        bool &$children = TRUE,
        ?string $statusFilter = NULL,
        bool $calendar = FALSE
    ): array {
        //TODO: not urgent, but find a way to get rid of this pass by ref 
        //TODO: The calendar param is after an optional param : it smells bad
        //Find default values for parameters (passed by ref)
        if ($month == 0)
            $month = intval(date("m"));
        if ($year == 0)
            $year = intval(date("Y"));
        $children = filter_var($children, FILTER_VALIDATE_BOOLEAN);
        //If no entity was selected, select the entity of the connected user or the root of the organization
        if ($entity == -1) {
            if (!$this->session->userdata('logged_in')) {
                $entity = 0;
            } else {
                $this->load->model('users_model');
                $user = $this->users_model->getUsers($this->session->userdata('id'));
                $entity = is_null($user['organization']) ? 0 : $user['organization'];
            }
        }
        $tabular = [];

        //We must show all users of the departement
        $this->load->model('organization_model');
        $employees = $this->organization_model->allEmployees($entity, $children);
        foreach ($employees as $employee) {
            if ($statusFilter != NULL) {
                $statuses = explode('|', $statusFilter);
                $tabular[$employee->id] = $this->linear(
                    $employee->id,
                    $month,
                    $year,
                    in_array("1", $statuses),
                    in_array("2", $statuses),
                    in_array("3", $statuses),
                    in_array("4", $statuses),
                    in_array("5", $statuses),
                    in_array("6", $statuses),
                    $calendar
                );
            } else {
                $tabular[$employee->id] = $this->linear(
                    $employee->id,
                    $month,
                    $year,
                    TRUE,
                    TRUE,
                    TRUE,
                    FALSE,
                    TRUE,
                    FALSE,
                    $calendar
                );
            }
        }
        return $tabular;
    }

    /**
     * Leave requests of users of a list (custom list built by user)
     * @param int $list List identifier
     * @param int $month Month number
     * @param int $year Year number
     * @param ?string $statusFilter optional filter on status
     * @return array Array of objects containing leave details
     */
    public function tabularList(int $list, int &$month = 0, int &$year = 0, ?string $statusFilter = NULL): array
    {
        //TODO: not urgent but find a way to get rid of this pass by ref 
        //Find default values for parameters (passed by ref)
        if ($month == 0)
            $month = intval(date("m"));
        if ($year == 0)
            $year = intval(date("Y"));
        $tabular = array();

        //We must show all users of the departement
        $this->load->model('lists_model');
        $employees = $this->lists_model->getListOfEmployees($list);
        foreach ($employees as $employee) {
            if ($statusFilter != NULL) {
                $statuses = explode('|', $statusFilter);
                $tabular[$employee['id']] = $this->linear(
                    $employee['id'],
                    $month,
                    $year,
                    in_array("1", $statuses),
                    in_array("2", $statuses),
                    in_array("3", $statuses),
                    in_array("4", $statuses),
                    in_array("5", $statuses),
                    in_array("6", $statuses)
                );
            } else {
                $tabular[$employee['id']] = $this->linear($employee['id'], $month, $year, TRUE, TRUE, TRUE, FALSE, TRUE);
            }
        }
        return $tabular;
    }

    /**
     * Count the total duration of leaves for the month. Only accepted leaves are taken into account
     * @param object $linear linear calendar for one employee
     * @return int total of leaves duration
     */
    public function monthlyLeavesDuration(object $linear): int
    {
        $total = 0;
        foreach ($linear->days as $day) {
            if (strstr($day->display, ';')) {
                $display = explode(";", $day->display);
                if ($display[0] == '2')
                    $total += 0.5;
                if ($display[0] == '3')
                    $total += 0.5;
                if ($display[1] == '2')
                    $total += 0.5;
                if ($display[1] == '3')
                    $total += 0.5;
            } else {
                if ($day->display == 2)
                    $total += 0.5;
                if ($day->display == 3)
                    $total += 0.5;
                if ($day->display == 1)
                    $total += 1;
            }
        }
        return $total;
    }

    /**
     * Count the total duration of leaves for the month, grouped by leave type.
     * Only accepted leaves are taken into account.
     * @param object $linear linear calendar for one employee
     * @return array key/value array (k:leave type label, v:sum for the month)
     */
    public function monthlyLeavesByType(object $linear): array
    {
        $by_types = [];
        foreach ($linear->days as $day) {
            if (strstr($day->display, ';')) {
                $display = explode(";", $day->display);
                $type = explode(";", $day->type);
                if ($display[0] == '2')
                    array_key_exists($type[0], $by_types) ? $by_types[$type[0]] += 0.5 : $by_types[$type[0]] = 0.5;
                if ($display[0] == '3')
                    array_key_exists($type[0], $by_types) ? $by_types[$type[0]] += 0.5 : $by_types[$type[0]] = 0.5;
                if ($display[1] == '2')
                    array_key_exists($type[1], $by_types) ? $by_types[$type[1]] += 0.5 : $by_types[$type[1]] = 0.5;
                if ($display[1] == '3')
                    array_key_exists($type[1], $by_types) ? $by_types[$type[1]] += 0.5 : $by_types[$type[1]] = 0.5;
            } else {
                if ($day->display == 2)
                    array_key_exists($day->type, $by_types) ? $by_types[$day->type] += 0.5 : $by_types[$day->type] = 0.5;
                if ($day->display == 3)
                    array_key_exists($day->type, $by_types) ? $by_types[$day->type] += 0.5 : $by_types[$day->type] = 0.5;
                if ($day->display == 1)
                    array_key_exists($day->type, $by_types) ? $by_types[$day->type] += 1 : $by_types[$day->type] = 1;
            }
        }
        return $by_types;
    }

    /**
     * Leave requests of users attached to a department(s)
     * @param int $employeeId Employee identifier
     * @param int $month Month number
     * @param int $year Year number
     * @param bool $planned Include leave requests with status planned
     * @param bool $requested Include leave requests with status requested
     * @param bool $accepted Include leave requests with status accepted
     * @param bool $rejected Include leave requests with status rejected
     * @param bool $cancellation Include leave requests with status cancellation
     * @param bool $canceled Include leave requests with status canceled
     * @param bool $calendar Is this function called to display a calendar
     * @return object Array of objects containing leave details
     */
    public function linear(
        int $employeeId,
        int $month,
        int $year,
        bool $planned = FALSE,
        bool $requested = FALSE,
        bool $accepted = FALSE,
        bool $rejected = FALSE,
        bool $cancellation = FALSE,
        bool $canceled = FALSE,
        bool $calendar = FALSE
    ): object {
        $start = $year . '-' . $month . '-' . '1';    //first date of selected month
        $lastDay = date("t", strtotime($start));    //last day of selected month
        $end = $year . '-' . $month . '-' . $lastDay;    //last date of selected month

        //We must show all users of the departement
        $this->load->model('dayoffs_model');
        $this->load->model('users_model');
        $employee = $this->users_model->getUsers($employeeId);
        $user = new stdClass;
        $user->name = $employee['firstname'] . ' ' . $employee['lastname'];
        $user->manager = (int) $employee['manager'];  //To enable hiding confidential info in view
        $user->id = (int) $employee['id'];
        $user->days = array();

        //Init all days of the month to working day
        for ($ii = 1; $ii <= $lastDay; $ii++) {
            $day = new stdClass;
            $day->id = 0;
            $day->type = '';
            $day->acronym = '';
            $day->status = '';
            $day->display = 0; //working day
            $user->days[$ii] = $day;
        }

        //Force all day offs (mind the case of employees having no leave)
        $dayoffs = $this->dayoffs_model->lengthDaysOffBetweenDatesForEmployee($employeeId, $start, $end);
        foreach ($dayoffs as $dayoff) {
            $iDate = new DateTime($dayoff->date);
            $dayNum = intval($iDate->format('d'));
            $user->days[$dayNum]->display = (string) $dayoff->type + 3;
            $user->days[$dayNum]->status = (string) $dayoff->type + 10;
            $user->days[$dayNum]->type = $dayoff->title;
        }

        //Build the complex query for all leaves
        $this->db->select('leaves.*');
        $this->db->select('types.acronym, types.name as type');
        $this->db->select('users.manager as manager');
        $this->db->from('leaves');
        $this->db->join('types', 'leaves.type = types.id');
        $this->db->join('users', 'leaves.employee = users.id');
        $this->db->where('(leaves.startdate <= DATE(' . $this->db->escape($end) . ') AND leaves.enddate >= DATE(' . $this->db->escape($start) . '))');
        if (!$planned)
            $this->db->where('leaves.status != ', LMS_PLANNED);
        if (!$requested)
            $this->db->where('leaves.status != ', LMS_REQUESTED);
        if (!$accepted)
            $this->db->where('leaves.status != ', LMS_ACCEPTED);
        if (!$rejected)
            $this->db->where('leaves.status != ', LMS_REJECTED);
        if (!$cancellation)
            $this->db->where('leaves.status != ', LMS_CANCELLATION);
        if (!$canceled)
            $this->db->where('leaves.status != ', LMS_CANCELED);

        $this->db->where('leaves.employee = ', $employeeId);
        $this->db->order_by('startdate', 'asc');
        $this->db->order_by('startdatetype', 'desc');
        $events = $this->db->get()->result();
        $limitDate = DateTime::createFromFormat('Y-m-d H:i:s', $end . ' 00:00:00');
        $floorDate = DateTime::createFromFormat('Y-m-d H:i:s', $start . ' 00:00:00');

        $this->load->model('dayoffs_model');
        foreach ($events as $entry) {
            //Hide forbidden entries in calendars
            if ($calendar) {
                //Don't display rejected and cancel* leave requests for other employees
                if (
                    ($entry->employee != $this->session->userdata('id')) &&
                    ($entry->manager != $this->session->userdata('id')) &&
                    ($this->session->userdata('is_hr') === FALSE)
                ) {
                    if ($entry->status > LMS_ACCEPTED) {
                        continue;
                    }
                }
            }

            //Note that $eventStartDate and $eventEndDate are related to the leave request event
            //But $startDate and $endDate are the first and last days being displayed on the calendar
            $eventStartDate = DateTime::createFromFormat('Y-m-d H:i:s', $entry->startdate . ' 00:00:00');
            $startDate = clone $eventStartDate;
            if ($startDate < $floorDate)
                $startDate = $floorDate;
            $iDate = clone $startDate;
            $eventEndDate = DateTime::createFromFormat('Y-m-d H:i:s', $entry->enddate . ' 00:00:00');
            $endDate = clone $eventEndDate;
            if ($endDate > $limitDate)
                $endDate = $limitDate;

            //Iteration between 2 dates
            while ($iDate <= $endDate) {
                if ($iDate > $limitDate)
                    break;     //The calendar displays the leaves on one month
                if ($iDate < $startDate)
                    continue;  //The leave starts before the first day of the calendar
                $dayNum = intval($iDate->format('d'));

                //Simplify the reading (and logic) by using atomic variables
                if ($eventStartDate == $eventEndDate)
                    $oneDay = TRUE;
                else
                    $oneDay = FALSE;
                if ($entry->startdatetype == 'Morning')
                    $start_morning = TRUE;
                else
                    $start_morning = FALSE;
                if ($entry->startdatetype == 'Afternoon')
                    $start_afternoon = TRUE;
                else
                    $start_afternoon = FALSE;
                if ($entry->enddatetype == 'Morning')
                    $end_morning = TRUE;
                else
                    $end_morning = FALSE;
                if ($entry->enddatetype == 'Afternoon')
                    $end_afternoon = TRUE;
                else
                    $end_afternoon = FALSE;
                if ($iDate == $eventStartDate)
                    $first_day = TRUE;
                else
                    $first_day = FALSE;
                if ($iDate == $eventEndDate)
                    $last_day = TRUE;
                else
                    $last_day = FALSE;

                //Display (different from contract/calendar)
                //0 - Working day  _
                //1 - All day           []
                //2 - Morning        |\
                //3 - Afternoon      /|
                //4 - All Day Off       []
                //5 - Morning Day Off   |\
                //6 - Afternoon Day Off /|
                //9 - Error in start/end types

                //Length of leave request is one day long
                if ($oneDay && $start_morning && $end_afternoon)
                    $display = '1';
                if ($oneDay && $start_morning && $end_morning)
                    $display = '2';
                if ($oneDay && $start_afternoon && $end_afternoon)
                    $display = '3';
                if ($oneDay && $start_afternoon && $end_morning)
                    $display = '9';
                //Length of leave request is one day long is more than one day
                //We are in the middle of a long leave request
                if (!$oneDay && !$first_day && !$last_day)
                    $display = '1';
                //First day of a long leave request
                if (!$oneDay && $first_day && $start_morning)
                    $display = '1';
                if (!$oneDay && $first_day && $start_afternoon)
                    $display = '3';
                //Last day of a long leave request
                if (!$oneDay && $last_day && $end_afternoon)
                    $display = '1';
                if (!$oneDay && $last_day && $end_morning)
                    $display = '2';

                //Check if another leave was defined on this day
                if ($user->days[$dayNum]->display != '4') { //Except full day off
                    if ($user->days[$dayNum]->display != 0) { //Overlapping with a day off or another request
                        if (
                            ($user->days[$dayNum]->display == 2) ||
                            ($user->days[$dayNum]->display == 5)
                        ) { //Respect Morning/Afternoon order
                            $user->days[$dayNum]->id .= ';' . $entry->id;
                            $user->days[$dayNum]->type .= ';' . $entry->type;
                            $user->days[$dayNum]->display .= ';' . $display;
                            $user->days[$dayNum]->status .= ';' . $entry->status;
                            $user->days[$dayNum]->acronym .= ';' . $entry->acronym;
                        } else {
                            $user->days[$dayNum]->id = $entry->id . ';' . $user->days[$dayNum]->id;
                            $user->days[$dayNum]->type = $entry->type . ';' . $user->days[$dayNum]->type;
                            $user->days[$dayNum]->display = $display . ';' . $user->days[$dayNum]->display;
                            $user->days[$dayNum]->status = $entry->status . ';' . $user->days[$dayNum]->status;
                            $user->days[$dayNum]->acronym .= $entry->acronym . ';' . $user->days[$dayNum]->acronym;
                        }
                    } else {   //All day entry
                        $user->days[$dayNum]->id = $entry->id;
                        $user->days[$dayNum]->type = $entry->type;
                        $user->days[$dayNum]->display = $display;
                        $user->days[$dayNum]->status = $entry->status;
                        $user->days[$dayNum]->acronym = $entry->acronym;
                    }
                }
                $iDate->modify('+1 day');   //Next day
            }
        }
        return $user;
    }

    /**
     * List all duplicated leave requests (exact same dates, status, etc.)
     * Note: this doesn't detect overlapping requests.
     * @return array List of duplicated leave requests
     */
    public function detectDuplicatedRequests(): array
    {
        $this->db->select('leaves.id, CONCAT(users.firstname, \' \', users.lastname) as user_label', FALSE);
        $this->db->select('leaves.startdate, types.name as type_label');
        $this->db->from('leaves');
        $this->db->join('(SELECT * FROM leaves) dup', 'leaves.employee = dup.employee' .
            ' AND leaves.startdate = dup.startdate' .
            ' AND leaves.enddate = dup.enddate' .
            ' AND leaves.startdatetype = dup.startdatetype' .
            ' AND leaves.enddatetype = dup.enddatetype' .
            ' AND leaves.status = dup.status' .
            ' AND leaves.id != dup.id', 'inner');
        $this->db->join('users', 'users.id = leaves.employee', 'inner');
        $this->db->join('types', 'leaves.type = types.id', 'inner');
        $this->db->where('leaves.status', 3);   //Accepted
        $this->db->order_by("users.id", "asc");
        $this->db->order_by("leaves.startdate", "desc");
        return $this->db->get()->result_array();
    }

    /**
     * List all leave requests with a wrong date type (starting afternoon and ending morning of the same day)
     * @return array List of wrong leave requests
     */
    public function detectWrongDateTypes(): array
    {
        $this->db->select('leaves.*, CONCAT(users.firstname, \' \', users.lastname) as user_label', FALSE);
        $this->db->select('status.name as status_label');
        $this->db->from('leaves');
        $this->db->join('users', 'users.id = leaves.employee', 'inner');
        $this->db->join('status', 'leaves.status = status.id', 'inner');
        $this->db->where('leaves.startdatetype', 'Afternoon');
        $this->db->where('leaves.enddatetype', 'Morning');
        $this->db->where('leaves.startdate = leaves.enddate');
        $this->db->order_by("users.id", "asc");
        $this->db->order_by("leaves.startdate", "desc");
        return $this->db->get()->result_array();
    }

    /**
     * List of leave requests for which they are not entitled days on contracts or employee
     * Note: this might be an expected behaviour (avoid to track them into the balance report).
     * @return array List of duplicated leave requests
     */
    public function detectBalanceProblems(): array
    {
        $query = $this->db->query('SELECT CONCAT(users.firstname, \' \', users.lastname) AS user_label,
        contracts.id, contracts.name AS contract_label,
        types.name AS type_label,
        status.name AS status_label,
        leaves.*
        FROM leaves
        inner join users on leaves.employee = users.id
        inner join contracts on users.contract = contracts.id
        inner join types on types.id = leaves.type
        inner join status on status.id = leaves.status
        LEFT OUTER JOIN entitleddays
            ON (entitleddays.type = leaves.type AND
                (users.id = entitleddays.employee OR contracts.id = entitleddays.contract)
                        and entitleddays.startdate <= leaves.enddate AND entitleddays.enddate >= leaves.startdate)
        WHERE entitleddays.type IS NULL
        ORDER BY users.id ASC, leaves.startdate DESC');
        return $query->result_array();
    }

    /**
     * List of leave requests overlapping on two yearly periods.
     * @return array List of overlapping leave requests
     */
    public function detectOverlappingProblems(): array
    {
        $query = $this->db->query('SELECT CONCAT(users.firstname, \' \', users.lastname) AS user_label,
            contracts.id AS contract_id, contracts.name AS contract_label,
            status.name AS status_label,
            leaves.*
            FROM leaves
            inner join users on leaves.employee = users.id
            inner join contracts on users.contract = contracts.id
            inner join status on status.id = leaves.status
            WHERE leaves.startdate < CAST(CONCAT(YEAR(leaves.enddate), \'-\', REPLACE(contracts.startentdate, \'/\', \'-\')) AS DATE)
            ORDER BY users.id ASC, leaves.startdate DESC');
        return $query->result_array();
    }

    /**
     * Get one leave with his comment
     * @param int $leaveId Id of the leave request
     * @return array list of records
     */
    public function getLeaveWithComments(int $leaveId = 0): array
    {
        $this->db->select('leaves.*');
        $this->db->select('status.name as status_name, types.name as type_name');
        $this->db->from('leaves');
        $this->db->join('status', 'leaves.status = status.id');
        $this->db->join('types', 'leaves.type = types.id');
        $this->db->where('leaves.id', $leaveId);
        $leave = $this->db->get()->row_array();
        if (!empty($leave['comments'])) {
            $leave['comments'] = json_decode($leave['comments']);
        } else {
            $leave['comments'] = null;
        }
        return $leave;
    }

    /**
     * Get the JSON representation of comments posted on a leave request
     * @param int $leaveId Id of the leave request
     * @return array list of records
     */
    public function getCommentsLeaveJson(int $leaveId): array
    {
        $this->db->select('leaves.comments');
        $this->db->from('leaves');
        $this->db->where('leaves.id', $leaveId);
        return $this->db->get()->row_array();
    }

    /**
     * Get one leave request with his comment
     * @param int $leaveId Id of the leave request
     * @return ?object list of records
     */
    public function getCommentsLeave(int $leaveId): ?object
    {
        $request = $this->getCommentsLeaveJson($leaveId);
        $json = $request["comments"];
        if (!empty($json)) {
            return json_decode($json, false);
        } else {
            return null;
        }
    }

    /**
     * Get one leave request with his comment and status
     * @param int $leaveId Id of the leave request
     * @return array list of records
     */
    private function getCommentLeaveAndStatus(int $leaveId): array
    {
        //TODO: this function has a low added-value compared to $this->getCommentsLeave
        $this->db->select('leaves.comments, leaves.status');
        $this->db->from('leaves');
        $this->db->where('leaves.id', $leaveId);
        $request = $this->db->get()->row_array();
        $json = $request["comments"];
        if (!empty($json)) {
            $request["comments"] = json_decode($json);
        } else {
            $request["comments"] = null;
        }
        return $request;
    }

    /**
     * Update the comment of a Leave
     * @param int $leaveId Id of the leave
     * @param string $json new json for the comments of the leave
     */
    public function addComments(int $leaveId, string $json): void
    {
        //TODO: decouple $this->session->userdata('id')
        $data = [
            'comments' => $json
        ];
        $this->db->where('id', $leaveId);
        $this->db->update('leaves', $data);

        //Trace the modification if the feature is enabled
        if ($this->config->item('enable_history')) {
            $this->load->model('history_model');
            $this->history_model->setHistory(2, 'leaves', $leaveId, $this->session->userdata('id'));
        }
    }

    /**
     * Prepare the Json when the status is updated
     * @param int $leaveId Id of the leave
     * @param int $status status which is updated
     * @return string json modified with the new status
     */
    private function prepareCommentOnStatusChanged(int $leaveId, int $status): string
    {
        $request = $this->getCommentLeaveAndStatus($leaveId);
        if ($request['status'] === $status) {
            return json_encode($request['comments']);
        } else {
            $json_parsed = $request['comments'];
            $comment_change = new stdClass;
            $comment_change->type = "change";
            $comment_change->status_number = $status;
            $comment_change->date = date("Y-n-j");
            if (isset($json_parsed)) {
                array_push($json_parsed->comments, $comment_change);
            } else {
                $json_parsed = new stdClass;
                $json_parsed->comments = [$comment_change];
            }
            return json_encode($json_parsed);
        }
    }
}
