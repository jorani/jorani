<?php
/**
 * This file contains the business logic and manages the persistence of entitled days.
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.1.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * This class contains the business logic and manages the persistence of entitled days.
 * Entitled days are a kind of leave credit given at a contract (many employees) or at employee level.
 */
class Entitleddays_model extends CI_Model
{

    /**
     * Default constructor
     */
    public function __construct()
    {

    }

    /**
     * Get the list of entitled days or one entitled day record associated to a contract
     * @param int $contractId id of a contract
     * @return array record of entitled days
     */
    public function getEntitledDaysForContract(int $contractId): array
    {
        $this->db->select('entitleddays.*, types.name as type_name');
        $this->db->from('entitleddays');
        $this->db->join('types', 'types.id = entitleddays.type');
        $this->db->order_by("startdate", "desc");
        $this->db->where('contract =', $contractId);
        return $this->db->get()->result_array();
    }

    /**
     * Get the list of entitled days or one entitled day record associated to an employee
     * @param int $employeeId id of an employee
     * @return array record of entitled days
     */
    public function getEntitledDaysForEmployee(int $employeeId): array
    {
        $this->db->select('entitleddays.*, types.name as type_name');
        $this->db->from('entitleddays');
        $this->db->join('types', 'types.id = entitleddays.type');
        $this->db->order_by("startdate", "desc");
        $this->db->where('employee =', $employeeId);
        return $this->db->get()->result_array();
    }

    /**
     * Insert a new entitled days record (for a contract) into the database and return the id
     * @param int $contractId contract identifier
     * @param string $startDate Start Date
     * @param string $endDate End Date
     * @param int $days number of days to be added
     * @param int $leaveType Leave type (of the entitled days line)
     * @param string $description Description of the entitled days line
     * @return int inserted entitled days record id
     */
    public function addEntitledDaysToContract(int $contractId, string $startDate, string $endDate, int $days, int $leaveType, string $description): int
    {
        $data = [
            'contract' => $contractId,
            'startdate' => $startDate,
            'enddate' => $endDate,
            'days' => $days,
            'type' => $leaveType,
            'description' => $description
        ];
        $this->db->insert('entitleddays', $data);
        return $this->db->insert_id();
    }

    /**
     * Insert a new entitled days record (for an employee) into the database and return the id
     * @param int $userId employee identifier
     * @param string $startDate Start Date
     * @param string $endDate End Date
     * @param int $days number of days to be added
     * @param int $type Leave type (of the entitled days line)
     * @param string $description Description of the entitled days line
     * @return int inserted entitled days record id
     */
    public function addEntitledDaysToEmployee(int $userId, string $startDate, string $endDate, int $days, int $type, string $description): int
    {
        $data = [
            'employee' => $userId,
            'startdate' => $startDate,
            'enddate' => $endDate,
            'days' => $days,
            'type' => $type,
            'description' => $description
        ];
        $this->db->insert('entitleddays', $data);
        return $this->db->insert_id();
    }

    /**
     * Delete an entitled days record from the database (for an employee or a contract)
     * @param int $id identifier of the entitleddays record
     * @return int number of rows affected
     */
    public function deleteEntitledDays(int $id): int
    {
        return $this->db->delete('entitleddays', ['id' => $id]);
    }

    /**
     * Delete entitled days attached to a user
     * @param int $id identifier of an employee
     */
    public function deleteEntitledDaysCascadeUser($id): void
    {
        $this->db->delete('entitleddays', ['employee' => $id]);
    }

    /**
     * Delete a entitled days attached to a contract
     * @param int $contractId identifier of a contract
     */
    public function deleteEntitledDaysCascadeContract(int $contractId): void
    {
        $this->db->delete('entitleddays', ['contract' => $contractId]);
    }

    /**
     * Update a record of entitled days (for an employee or a contract)
     * @param int $id line of entitled days identifier (row id)
     * @param string $startDate Start Date
     * @param string $endDate End Date
     * @param int $days number of days to be added
     * @param int $type Leave type (of the entitled days line)
     * @param string $description Description of the entitled days line
     * @return number of affected rows
     */
    public function updateEntitledDays(int $id, string $startDate, string $endDate, int $days, int $type, string $description): int
    {
        $data = [
            'startdate' => $startDate,
            'enddate' => $endDate,
            'days' => $days,
            'type' => $type,
            'description' => $description
        ];

        $this->db->where('id', $id);
        return $this->db->update('entitleddays', $data);
    }

    /**
     * Increase an entitled days row
     * @param int $id row identifier
     * @param float $step increment step
     * @return int number of affected rows
     */
    public function increase(int $id, float $step): int
    {
        if (!is_numeric($step))
            $step = 1;
        $this->db->set('days', 'days + ' . $step, FALSE);
        $this->db->where('id', $id);
        return $this->db->update('entitleddays');
    }

    /**
     * Decrease an entitled days row
     * @param int $id row identifier
     * @param float $step increment step
     * @return int number of affected rows
     */
    public function decrease(int $id, float $step): int
    {
        if (!is_numeric($step))
            $step = 1;
        $this->db->set('days', 'days - ' . $step, FALSE);
        $this->db->where('id', $id);
        return $this->db->update('entitleddays');
    }

    /**
     * Modify the the amount of days for a given entitled days row
     * @param int $id row identifier
     * @param float $days credit in days
     * @return int number of affected rows
     */
    public function updateNbOfDaysOfEntitledDaysRecord(int $id, float $days): int
    {
        if (!is_numeric($days))
            $days = 1;
        $this->db->set('days', $days);
        $this->db->where('id', $id);
        return $this->db->update('entitleddays');
    }

    /**
     * Purge the table by deleting the records prior $toDate
     * @param string $toDate 
     * @return int number of affected rows
     */
    public function purgeEntitleddays(string $toDate): int
    {
        //TODO: check if $toDate is an actual date
        $this->db->where('enddate <= ', $toDate);
        return $this->db->delete('entitleddays');
    }

    /**
     * Count the number of rows into the table
     * @return int number of rows
     */
    public function count(): int
    {
        $this->db->select('count(*) as number', FALSE);
        $this->db->from('entitleddays');
        $result = $this->db->get();
        return $result->row()->number;
    }

    /**
     * List all entitlements overflowing (more than one year).
     * @return array List of possible duplicated leave requests
     */
    public function detectOverflow(): array
    {
        //Note: the query below detects deletion problems:
        //SELECT * FROM entitleddays 
        //LEFT OUTER JOIN users ON entitleddays.employee = users.id 
        //LEFT OUTER JOIN contracts ON entitleddays.contract = contracts.id 
        //WHERE users.firstname IS NULL AND contracts.name IS NULL
        $this->db->select('CONCAT(users.firstname, \' \', users.lastname) as user_label', FALSE);
        $this->db->select('contracts.name as contract_label');
        $this->db->select('entitleddays.*');
        $this->db->from('entitleddays');
        $this->db->join('users', 'users.id = entitleddays.employee', 'left outer');
        $this->db->join('contracts', 'entitleddays.contract = contracts.id', 'left outer');
        $this->db->where('TIMESTAMPDIFF(YEAR, `startdate`, `enddate`) > 0');   //More than a year
        $this->db->order_by("contracts.id", "asc");
        $this->db->order_by("users.id", "asc");
        return $this->db->get()->result_array();
    }
}
