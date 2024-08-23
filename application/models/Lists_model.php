<?php
/**
 * This Class contains all the business logic and the persistence layer for the
 * managing lists of employees. Each user can create and manage its own lists of
 * employees.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.6.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This Class contains all the business logic and the persistence layer for
 * custom lists of employees.
 */
class Lists_model extends CI_Model {

    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {

    }

    /**
     * Get the list of custom lists for an employee (often the connected user)
     * @param int $user identifier of a user owing the lists
     * @return array record of lists
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getLists($user) {
        $query = $this->db->get_where('org_lists', array('user' => $user));
        return $query->result_array();
    }

    /**
     * Get the name of a org_lists
     * @param int $id identifier of a list
     * @return string name of the found list, empty string otherwise
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getName($id) {
        $this->db->from('org_lists');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $record = $query->result_array();
        if(count($record) > 0) {
            return $record[0]['name'];
        } else {
            return '';
        }
    }

    /**
     * Insert a new list into the database
     * @param int $user User owning the list
     * @param string $name Name of the list
     * @return int last inserted id
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function setLists($user, $name) {
        $data = array(
            'user' => $user,
            'name' => $name
        );
        $this->db->insert('org_lists', $data);
        return $this->db->insert_id();
    }

    /**
     * Update a given list in the database.
     * @param int $id identifier of the list
     * @param string $name name of the list
     * @return int number of affected rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function updateLists($id, $name) {
        $data = array(
            'name' => $name
        );
        $this->db->where('id', $id);
        return $this->db->update('org_lists', $data);
    }

    /**
     * Delete a list from the database
     * @param int $id identifier of the list
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function deleteList($id) {
        $this->db->delete('org_lists', array('id' => $id));
    }

    /**
     * Add employees into a list
     * @param int $id identifier of the list
     * @param array $employees List of employees
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function addEmployees($id, $employees) {
        $data = array();
        $order = $this->getLastOrderList($id);

        foreach ($employees as $employee) {
          if (!$this->hasEmployeeOnList($id, $employee)){
            $data[] = array(
              'list' => $id,
              'user' => $employee,
              'orderlist' => $order
            );
            $order++;
          }
        }
        if(! empty($data)){
          $this->db->insert_batch('org_lists_employees', $data);
        }
    }

    /**
     * check if a user is already on the list
     * @param int $id Id of the list
     * @param int $employee Id of the user
     * @author Emilien NICOALS <milihhard1996@gmail.com>
     */
    private function hasEmployeeOnList($id, $employee){
      $this->db->select('org_lists_employees.user');
      $this->db->from('org_lists_employees');
      $this->db->where('user', $employee);
      $this->db->where('list', $id);
      $record = $this->db->get()->result_array();
      if(count($record) == 0) {
          return false;
      } else {
          return true;
      }

    }

    /**
     * get the last orderlist
     * @param int $idList Id of the list
     * @author Emilien NICOALS <milihhard1996@gmail.com>
     */
    public function getLastOrderList($idList) {
      $this->db->select('org_lists_employees.orderlist');
      $this->db->from('org_lists_employees');
      $this->db->where('list', $idList);
      $this->db->order_by('orderlist', 'DESC');
      $query = $this->db->get();
      $record = $query->result_array();
      if(count($record) > 0) {
          return $record[0]['orderlist'] + 1;
      } else {
          return 1;
      }
    }

    /**
     * Remove a list of employees from a list
     * @param int $id identifier of the list
     * @param array $employees List of employees
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function removeEmployees($id, $employees) {
        $this->db->where('list', $id);
        $this->db->where_in('orderlist', $employees);
        $this->db->delete('org_lists_employees');

        $this->reorderList($id);

    }

    /**
     * Get the list of employees for the given list identifier
     * @param int $id Identifier of the list of employees
     * @return array record of employees
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getListOfEmployees($id) {
        $this->db->select('org_lists_employees.user as id');
        $this->db->select('firstname, lastname');
        $this->db->select('organization.name as entity');
        $this->db->from('org_lists');
        $this->db->join('org_lists_employees', 'org_lists_employees.list = org_lists.id');
        $this->db->join('users', 'users.id = org_lists_employees.user');
        $this->db->join('organization', 'organization.id = users.organization');
        $this->db->where('org_lists.id', $id);
        $this->db->order_by('org_lists_employees.orderlist');
        $query = $this->db->get();
        return $query->result_array();
    }
    /**
     * reorder the list when a employe is removed
     * @param int $id Id of the list
     * @author Emilien NICOALS <milihhard1996@gmail.com>
     */
    private function reorderList($id){
      $this->db->select('org_lists_employees.orderlist');
      $this->db->from('org_lists_employees');
      $this->db->where('org_lists_employees.list', $id);
      $this->db->order_by('org_lists_employees.orderlist');
      $employees = $this->db->get()->result_array();
      $count = 1;
      foreach ($employees as $employee) {
        $data = array(
            'orderlist' => $count
        );
        $this->db->where('orderlist', $employee['orderlist']);
        $this->db->update('org_lists_employees', $data);
        $count ++;
      }
    }

    /**
     * reorder the list
     * @param int $id Id of the list
     * @param array $moves move of the employees
     * @author Emilien NICOALS <milihhard1996@gmail.com>
     */
    public function reorderListEmployees($id, $moves){
      foreach ($moves as $move) {
        $this->db->where('user', $move->user);
        $this->db->where('list', $id);
        $data = array(
            'orderlist' => $move->newPos
        );
        $this->db->update('org_lists_employees', $data);
      }

    }
}
