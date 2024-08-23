<?php
/**
 * This class contains the business logic and manages the persistence of delegations
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class contains the business logic and manages the persistence of delegations.
 * A manager (M) can give a delegation to any other employee (E).
 * An employee (E) having the delegation can act as a manager of the employees managed by the manager (M).
 */
class Delegations_model extends CI_Model {

    /**
     * Default constructor
     */
    public function __construct() {

    }

    /**
     * Get the list of delegations for a manager
     * @param int $manager id of manager
     * @return array record of users
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function listDelegationsForManager($manager) {
        $this->db->select('delegations.*, CONCAT(firstname, \' \', lastname) as delegate_name', FALSE);
        $this->db->join('users', 'delegations.delegate_id = users.id');
        $query = $this->db->get_where('delegations', array('manager_id' => $manager));
        return $query->result_array();
    }

    /**
     * Return TRUE if an employee is the delegate of a manager, FALSE otherwise
     * @param int $employee id of the employee to be checked
     * @param int $manager id of a manager
     * @return bool is delegate
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function isDelegateOfManager($employee, $manager) {
        $this->db->from('delegations');
        $this->db->where('delegate_id', $employee);
        $this->db->where('manager_id', $manager);
        $results = $this->db->get()->row_array();
        if (!empty($results)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Return TRUE if an employee has any delegation, FALSE otherwise
     * @param int $employee id of the employee to be checked
     * @return bool has delegation
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function hasDelegation($employee) {
        $this->db->from('delegations');
        $this->db->where('delegate_id', $employee);
        $results = $this->db->get()->row_array();
        if (!empty($results)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Get the list of manager ids for which an employee has the delegation
     * @param int $employee id of an employee
     * @return array of employee identifiers
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function listManagersGivingDelegation($id) {
        $this->db->select("manager_id");
        $this->db->from('delegations');
        $this->db->where('delegate_id', $id);
        $results = $this->db->get()->result_array();
        $ids = array();
        foreach ($results as $row) {
            array_push($ids, $row['manager_id']);
        }
        return $ids;
    }

    /**
     * Get the list of e-mails of employees having the delegation from a manager
     * @param int $id id of a manager
     * @return array record of users
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function listMailsOfDelegates($id) {
        $this->db->select("GROUP_CONCAT(email SEPARATOR ',') as list", FALSE);
        $this->db->from('delegations');
        $this->db->join('users', 'delegations.delegate_id = users.id');
        $this->db->group_by('manager_id');
        $this->db->where('manager_id', $id);
        $query = $this->db->get();
        $results = $query->row_array();
        if (!empty($results)) {
            return $results['list'];
        } else {
            return '';
        }
    }

    /**
     * Give a delegation to an employee
     * @param int $manager id of a manager giving the delegation
     * @param int $delegate id of a employee to whom the delegation is given
     * @return bool outcome of the query
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function addDelegate($manager, $delegate) {
        $data = array(
            'manager_id' => $manager,
            'delegate_id' => $delegate
        );
        $this->db->insert('delegations', $data);
        return $this->db->insert_id();
    }

    /**
     * Delete a delegation from the database
     * @param int $id identifier of the delegation
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function deleteDelegation($id) {
        $this->db->delete('delegations', array('id' => $id));
    }
}
