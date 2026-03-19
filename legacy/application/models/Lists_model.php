<?php
/**
 * This file contains all the business logic and the persistence layer for the
 * managing lists of employees. Each user can create and manage its own lists of
 * employees.
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.6.0
 */

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

/**
 * This Class contains all the business logic and the persistence layer for
 * custom lists of employees.
 * @property CI_DB_query_builder $db
 */
class Lists_model extends CI_Model
{

  /**
   * Default constructor
   * 
   */
  public function __construct()
  {

  }

  /**
   * Get the list of custom lists for an employee (often the connected user)
   * @param int $userId identifier of a user owing the lists
   * @return array<string, mixed> record of lists
   */
  public function getLists(int $userId): array
  {
    $this->db->where('user', $userId);
    $query = $this->db->get('org_lists');
    return $query->result_array();
  }

  /**
   * Get the name of a org_lists
   * @param int $id identifier of a list
   * @return string name of the found list, empty string otherwise
   */
  public function getName(int $id): string
  {
    $this->db->from('org_lists');
    $this->db->where('id', $id);
    $query = $this->db->get();
    $record = $query->result_array();
    if (count($record) > 0) {
      return $record[0]['name'];
    } else {
      return '';
    }
  }

  /**
   * Insert a new list into the database
   * @param int $userId User owning the list
   * @param string $name Name of the list
   * @return int DB indentifier of the inserted list
   */
  public function setLists(int $userId, string $name): int
  {
    $data = [
      'user' => $userId,
      'name' => $name
    ];
    $this->db->insert('org_lists', $data);
    return $this->db->insert_id();
  }

  /**
   * Update a given list in the database.
   * @param int $id identifier of the list
   * @param string $name name of the list
   * @return bool TRUE if the SQL query is successful, FALSE otherwise
   */
  public function updateLists(int $id, string $name): bool
  {
    $this->db->where('id', $id);
    return $this->db->update('org_lists', ['name' => $name]);
  }

  /**
   * Delete a list from the database
   * @param int $id identifier of the list
   * @return bool TRUE if the SQL query is successful, FALSE otherwise
   */
  public function deleteList(int $id): bool
  {
    return $this->db->delete('org_lists', ['id' => $id]);
  }

  /**
   * Add employees into a list
   * @param int $id identifier of the list
   * @param array<int> $employees List of employees
   * @return int|bool number of inserted employees, FALSE if error
   */
  public function addEmployees(int $id, array $employees): int|bool
  {
    $data = [];
    $order = $this->getLastOrderList($id);

    foreach ($employees as $employee) {
      if (!$this->hasEmployeeOnList($id, $employee)) {
        $data[] = [
          'list' => $id,
          'user' => $employee,
          'orderlist' => $order
        ];
        $order++;
      }
    }
    if (!empty($data)) {
      return $this->db->insert_batch('org_lists_employees', $data);
    }
    return false;
  }

  /**
   * check if a user is already on the list
   * @param int $listId Id of the list
   * @param int $employeeId Id of the user
   * @return bool TRUE if the user is on the list, FALSE otherwise
   */
  private function hasEmployeeOnList(int $listId, int $employeeId): bool
  {
    $this->db->select('org_lists_employees.user');
    $this->db->from('org_lists_employees');
    $this->db->where('user', $employeeId);
    $this->db->where('list', $listId);
    $record = $this->db->get()->result_array();
    if (count($record) == 0) {
      return false;
    } else {
      return true;
    }
  }

  /**
   * get the last orderlist of a list
   * @param int $listId Id of the list
   * @return int last orderlist
   */
  public function getLastOrderList(int $listId): int
  {
    $this->db->select('org_lists_employees.orderlist');
    $this->db->from('org_lists_employees');
    $this->db->where('list', $listId);
    $this->db->order_by('orderlist', 'DESC');
    $query = $this->db->get();
    $record = $query->result_array();
    if (count($record) > 0) {
      return $record[0]['orderlist'] + 1;
    } else {
      return 1;
    }
  }

  /**
   * Remove a list of employees from a list
   * @param int $listId identifier of the list
   * @param array<int> $employees List of employees
   */
  public function removeEmployees(int $listId, array $employees): void
  {
    $this->db->where('list', $listId);
    $this->db->where_in('orderlist', $employees);
    $this->db->delete('org_lists_employees');
    $this->reorderList($listId);
  }

  /**
   * Get the list of employees for the given list identifier
   * @param int $listId identifier of the list
   * @return array<string, mixed> record of employees
   */
  public function getListOfEmployees(int $listId): array
  {
    $this->db->select('org_lists_employees.user as id');
    $this->db->select('firstname, lastname');
    $this->db->select('organization.name as entity');
    $this->db->from('org_lists');
    $this->db->join('org_lists_employees', 'org_lists_employees.list = org_lists.id');
    $this->db->join('users', 'users.id = org_lists_employees.user');
    $this->db->join('organization', 'organization.id = users.organization');
    $this->db->where('org_lists.id', $listId);
    $this->db->order_by('org_lists_employees.orderlist');
    $query = $this->db->get();
    return $query->result_array();
  }

  /**
   * reorder the list when a employe is removed
   * @param int $listId Id of the list
   */
  private function reorderList(int $listId): void
  {
    $this->db->select('org_lists_employees.orderlist');
    $this->db->from('org_lists_employees');
    $this->db->where('org_lists_employees.list', $listId);
    $this->db->order_by('org_lists_employees.orderlist');
    $employees = $this->db->get()->result_array();
    $count = 1;
    foreach ($employees as $employee) {
      $this->db->where('orderlist', $employee['orderlist']);
      $this->db->update('org_lists_employees', ['orderlist' => $count]);
      $count++;
    }
  }

  /**
   * reorder the list
   * @param int $listId Id of the list
   * @param array<object{user: int, newPos: int}> $moves move of the employees
   */
  public function reorderListEmployees(int $listId, array $moves): void
  {
    foreach ($moves as $move) {
      $this->db->where('user', $move->user);
      $this->db->where('list', $listId);
      $this->db->update('org_lists_employees', ['orderlist' => $move->newPos]);
    }
  }
}
