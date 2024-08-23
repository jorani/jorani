<?php
/**
 * This class contains the business logic and manages the persistence of entitled days.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class contains the business logic and manages the persistence of entitled days.
 * Entitled days are a kind of leave credit given at a contract (many employees) or at employee level.
 */
class Entitleddays_model extends CI_Model {

    /**
     * Default constructor
     */
    public function __construct() {
        
    }

    /**
     * Get the list of entitled days or one entitled day record associated to a contract
     * @param int $id optional id of a contract
     * @return array record of entitled days
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getEntitledDaysForContract($contract) {
        $this->db->select('entitleddays.*, types.name as type_name');
        $this->db->from('entitleddays');
        $this->db->join('types', 'types.id = entitleddays.type');
        $this->db->order_by("startdate", "desc");
        $this->db->where('contract =', $contract);
        return $this->db->get()->result_array();
    }
    
    /**
     * Get the list of entitled days or one entitled day record associated to an employee
     * @param int $id optional id of an employee
     * @return array record of entitled days
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getEntitledDaysForEmployee($employee) {
        $this->db->select('entitleddays.*, types.name as type_name');
        $this->db->from('entitleddays');
        $this->db->join('types', 'types.id = entitleddays.type');
        $this->db->order_by("startdate", "desc");
        $this->db->where('employee =', $employee);
        return $this->db->get()->result_array();
    }
    
    /**
     * Insert a new entitled days record (for a contract) into the database and return the id
     * @param int $contract_id contract identifier
     * @param date $startdate Start Date
     * @param date $enddate End Date
     * @param int $days number of days to be added
     * @param int $type Leave type (of the entitled days line)
     * @param int $description Description of the entitled days line
     * @return int last inserted id
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function addEntitledDaysToContract($contract_id, $startdate, $enddate, $days, $type, $description) {
        $data = array(
            'contract' => $contract_id,
            'startdate' => $startdate,
            'enddate' => $enddate,
            'days' => $days,
            'type' => $type,
            'description' => $description
        );
        $this->db->insert('entitleddays', $data);
        return $this->db->insert_id();
    }

    /**
     * Insert a new entitled days record (for an employee) into the database and return the id
     * @param int $user_id employee identifier
     * @param date $startdate Start Date
     * @param date $enddate End Date
     * @param int $days number of days to be added
     * @param int $type Leave type (of the entitled days line)
     * @param int $description Description of the entitled days line
     * @return int last inserted id
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function addEntitledDaysToEmployee($user_id, $startdate, $enddate, $days, $type, $description) {
        $data = array(
            'employee' => $user_id,
            'startdate' => $startdate,
            'enddate' => $enddate,
            'days' => $days,
            'type' => $type,
            'description' => $description
        );
        $this->db->insert('entitleddays', $data);
        return $this->db->insert_id();
    }
    
    /**
     * Delete an entitled days record from the database (for an employee or a contract)
     * @param int $id identifier of the entitleddays record
     * @return int number of rows affected
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function deleteEntitledDays($id) {
        return $this->db->delete('entitleddays', array('id' => $id));
    }

    /**
     * Delete entitled days attached to a user
     * @param int $id identifier of an employee
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function deleteEntitledDaysCascadeUser($id) {
        $this->db->delete('entitleddays', array('employee' => $id));
    }
    
    /**
     * Delete a entitled days attached to a contract
     * @param int $id identifier of a contract
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function deleteEntitledDaysCascadeContract($id) {
        $this->db->delete('entitleddays', array('contract' => $id));
    }
    
    /**
     * Update a record of entitled days (for an employee or a contract)
     * @param int $id line of entitled days identifier (row id)
     * @param date $startdate Start Date
     * @param date $enddate End Date
     * @param int $days number of days to be added
     * @param int $type Leave type (of the entitled days line)
     * @param int $description Description of the entitled days line
     * @return number of affected rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function updateEntitledDays($id, $startdate, $enddate, $days, $type, $description) {
        $data = array(
            'startdate' => $startdate,
            'enddate' => $enddate,
            'days' => $days,
            'type' => $type,
            'description' => $description
        );

        $this->db->where('id', $id);
        return $this->db->update('entitleddays', $data);
    }
    
    /**
     * Increase an entitled days row
     * @param int $id row identifier
     * @param float $step increment step
     * @return int number of affected rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function increase($id, $step) {
        if (!is_numeric($step)) $step = 1;
        $this->db->set('days', 'days + ' . $step, FALSE);
        $this->db->where('id', $id);
        return $this->db->update('entitleddays');
    }
    
    /**
     * Decrease an entitled days row
     * @param int $id row identifier
     * @param float $step increment step
     * @return int number of affected rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function decrease($id, $step) {
        if (!is_numeric($step)) $step = 1;
        $this->db->set('days', 'days - ' . $step, FALSE);
        $this->db->where('id', $id);
        return $this->db->update('entitleddays');
    }
    
    /**
     * Modify the the amount of days for a given entitled days row
     * @param int $id row identifier
     * @param float $days credit in days
     * @return int number of affected rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function updateNbOfDaysOfEntitledDaysRecord($id, $days) {
        if (!is_numeric($days)) $days = 1;
        $this->db->set('days', $days);
        $this->db->where('id', $id);
        return $this->db->update('entitleddays');
    }
    
    /**
     * Purge the table by deleting the records prior $toDate
     * @param date $toDate 
     * @return int number of affected rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function purgeEntitleddays($toDate) {
        $this->db->where('enddate <= ', $toDate);
        return $this->db->delete('entitleddays');
    }

    /**
     * Count the number of rows into the table
     * @return int number of rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function count() {
        $this->db->select('count(*) as number', FALSE);
        $this->db->from('entitleddays');
        $result = $this->db->get();
        return $result->row()->number;
    }
    
    /**
     * List all entitlements overflowing (more than one year).
     * @return array List of possible duplicated leave requests
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function detectOverflow() {
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
