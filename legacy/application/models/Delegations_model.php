<?php
/**
 * This file contains the business logic and manages the persistence of delegations
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.1.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * This class contains the business logic and manages the persistence of delegations.
 * A manager (M) can give a delegation to any other employee (E).
 * An employee (E) having the delegation can act as a manager of the employees managed by the manager (M).
 * @property CI_Config $config
 * @property CI_DB $db
 */
class Delegations_model extends CI_Model
{

    /**
     * Default constructor
     */
    public function __construct()
    {

    }

    /**
     * Get the list of delegations for a manager
     * @param int $managerId id of manager
     * @return array<string, mixed> record of users (delegates of the manager)
     */
    public function listDelegationsForManager(int $managerId): array
    {
        $this->db->select('delegations.*, CONCAT(firstname, \' \', lastname) as delegate_name', FALSE);
        $this->db->join('users', 'delegations.delegate_id = users.id');
        $this->db->where('manager_id', $managerId);
        $query = $this->db->get('delegations');
        return $query->result_array();
    }

    /**
     * Return TRUE if an employee is the delegate of a manager, FALSE otherwise
     * @param int $employeeId id of the employee to be checked
     * @param int $managerId id of a manager
     * @return bool is delegate
     */
    public function isDelegateOfManager(int $employeeId, int $managerId): bool
    {
        $this->db->from('delegations');
        $this->db->where('delegate_id', $employeeId);
        $this->db->where('manager_id', $managerId);
        $results = $this->db->get()->row_array();
        if ($results != null) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Return TRUE if an employee has any delegation, FALSE otherwise
     * @param int $employeeId id of the employee to be checked
     * @return bool has delegation
     */
    public function hasDelegation(int $employeeId): bool
    {
        $this->db->from('delegations');
        $this->db->where('delegate_id', $employeeId);
        $results = $this->db->get()->row_array();
        if ($results != null) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Get the list of manager ids for which an employee has the delegation
     * @param int $employeeId id of an employee
     * @return array<int> list of manager identifiers
     */
    public function listManagersGivingDelegation(int $employeeId): array
    {
        $this->db->select("manager_id");
        $this->db->from('delegations');
        $this->db->where('delegate_id', $employeeId);
        $results = $this->db->get()->result_array();
        $ids = [];
        foreach ($results as $row) {
            array_push($ids, $row['manager_id']);
        }
        return $ids;
    }

    /**
     * Get the list of e-mails of employees having the delegation from a manager
     * @param int $managerId id of a manager
     * @return array<string> list of e-mails or empty array
     */
    public function listMailsOfDelegates(int $managerId): array
    {
        $this->db->select("GROUP_CONCAT(email SEPARATOR ',') as list", FALSE);
        $this->db->from('delegations');
        $this->db->join('users', 'delegations.delegate_id = users.id');
        $this->db->group_by('manager_id');
        $this->db->where('manager_id', $managerId);
        $query = $this->db->get();
        $results = $query->row_array();
        if ($results != null) {
            return $results['list'];
        } else {
            return [];
        }
    }

    /**
     * Give a delegation to an employee
     * @param int $managerId id of a manager giving the delegation
     * @param int $delegateId id of a employee to whom the delegation is given
     * @return int id of the new delegation
     */
    public function addDelegate(int $managerId, int $delegateId): int
    {
        $data = array(
            'manager_id' => $managerId,
            'delegate_id' => $delegateId
        );
        $this->db->insert('delegations', $data);
        return $this->db->insert_id();
    }

    /**
     * Delete a delegation from the database
     * @param int $delegationId identifier of the delegation
     */
    public function deleteDelegation(int $delegationId): void
    {
        $this->db->delete('delegations', array('id' => $delegationId));
    }
}
