<?php
/**
 * This class contains the business logic and manages the persistence of non working days
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

 if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/*
 * History tables are named {table}_history and they are used to store the modifications brought on some tables. They have
 * the same structure than the table they keep history for, but with the following additional columns :
 * modification_id
 * moditifed_type
 * modified_by      (if 0, system or init)
 * change_date
 * 
 * Of course, the PK is no more the identifier of the object but modification_id.
 * Modification types are :
 *     0 - not used
 *     1 - create
 *     2 - update
 *     3 - delete
 */
class History_model extends CI_Model {

    /**
     * Default constructor
     */
    public function __construct() {
        
    }
    
    /**
     * Get the list of changes into the 'leaves' table
     * @param int $leaveId Identifier of the leave request
     * @return result rows as array of arrays
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getLeaveRequestsHistory($leaveId) {
        $this->db->select("CONCAT(users.firstname, ' ', users.lastname) as user_name", FALSE);
        $this->db->select('types.name as type_name, status.name as status_name');
        $this->db->select('leaves_history.*');
        $this->db->join('users', 'leaves_history.changed_by = users.id');
        $this->db->join('types', 'leaves_history.type = types.id');
        $this->db->join('status', 'leaves_history.status = status.id');
        $this->db->where('leaves_history.id', $leaveId);
        $this->db->order_by('change_id', 'asc'); 
        $query = $this->db->get('leaves_history');
        $results = $query->result_array();
        return $results;
    }
    
    
    /**
     * Get the list of deleted leave requests
     * @param int $userId Identifier of the user
     * @return result rows as array of arrays
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getDeletedLeaveRequests($userId) {
        $this->db->select('DISTINCT leaves_history.id', FALSE);
        $this->db->select("CONCAT(users.firstname, ' ', users.lastname) as user_name", FALSE);
        $this->db->select("types.name as type_name, status.name as status_name, leaves_history.*");
        $this->db->join('users', 'leaves_history.changed_by = users.id');
        $this->db->join('users ul', 'leaves_history.employee = ul.id');
        $this->db->join('types', 'leaves_history.type = types.id');
        $this->db->join('status', 'leaves_history.status = status.id');
        $this->db->where('ul.id', $userId);
        $this->db->where('leaves_history.change_type = 3');
        $query = $this->db->get('leaves_history');
        $results = $query->result_array();
        return $results;
    }
    
    /**
     * Get the details of a modification
     * @param string $table Table modified
     * @param type $id Unique Identifier of the modification
     * @return result row as an array
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getHistoryDetail($table, $id) {
        $query = $this->db->get_where($table . '_history', array('modification_id' => $id));
        return $query->row_array();
    }
    
    /**
     * Insert a modification into the history table of the modified object (source table)
     * @param int $type Type of modification (1 - create, 2 - update, 3 - delete)
     * @param string $table Table modified
     * @param int $id Identifier of the object (can be returned by the last inserted id function)
     * @param int $user_id Identifier of the connected user
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function setHistory($type, $table, $id, $user_id) {
        $sql = 'INSERT INTO ' . $table . '_history';
        $sql .= ' SELECT *, NULL,';
        $sql .= ' ' . $type;
        $sql .= ', ' . $user_id;
        $sql .= ', NOW() FROM ' . $table . ' WHERE id = ' . $id;
        $this->db->query($sql);
    }
    
    /**
     * Purge the table by deleting the records prior $toDate
     * @param string $table Source Table
     * @param date $toDate 
     * @return int number of affected rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function purgeHistory($table, $toDate) {
        $this->db->where('change_date <= ', $toDate);
        return $this->db->delete($table);
    }

    /**
     * Count the number of rows into the table
     * @param string $table Source Table
     * @return int number of rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function count($table) {
        $this->db->select('count(*) as number',false);
        $this->db->from($table);
        $result = $this->db->get();
        return $result->row()->number;
    }
    
    //TODO:cascade delete on user delete
}
?>
