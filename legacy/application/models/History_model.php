<?php
/**
 * This file contains the business logic and manages the persistence of non working days
 *
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.1.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 * History tables are named {table}_history and they are used to store the modifications performed on some tables.
 * They have the same structure than the table they keep history for, but with the following additional columns :
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
 * 
 * @property CI_DB_query_builder $db
 */
class History_model extends CI_Model
{
    /**
     * @var array<string> list of allowed tables
     */
    private array $allowedTables = ['leaves', 'overtime'];

    /**
     * Default constructor
     */
    public function __construct()
    {

    }

    /**
     * Get the list of changes into the 'leaves' table
     * @param int $leaveId Identifier of the leave request
     * @return array<string, mixed> result rows as array of arrays
     */
    public function getLeaveRequestsHistory(int $leaveId): array
    {
        $this->db->select("CONCAT(users.firstname, ' ', users.lastname) as user_name", FALSE);
        $this->db->select('types.name as type_name, status.name as status_name');
        $this->db->select('leaves_history.*');
        $this->db->join('users', 'leaves_history.changed_by = users.id');
        $this->db->join('types', 'leaves_history.type = types.id');
        $this->db->join('status', 'leaves_history.status = status.id');
        $this->db->where('leaves_history.id', $leaveId);
        $this->db->order_by('change_id', 'asc');
        $query = $this->db->get('leaves_history');
        return $query->result_array();
    }

    /**
     * Get the list of deleted leave requests
     * @param int $userId Identifier of the user
     * @return array<string, mixed> rows as array of arrays
     */
    public function getDeletedLeaveRequests(int $userId): array
    {
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
        return $query->result_array();
    }

    /**
     * Get the details of a modification
     * @param string $table Table modified
     * @param int $id Unique Identifier of the modification
     * @return array<string, mixed>|null row as an array or null if not found
     * @throws InvalidArgumentException if the table is not allowed
     */
    public function getHistoryDetail(string $table, int $id): ?array
    {
        if (!in_array($table, $this->allowedTables, true)) {
            throw new InvalidArgumentException("The provided table is not allowed for history details.");
        }
        $this->db->where('modification_id', $id);
        $query = $this->db->get($table . '_history');
        return $query->row_array();
    }

    /**
     * Insert a modification into the history table of the modified object (source table)
     * @param int $type Type of modification (1 - create, 2 - update, 3 - delete)
     * @param string $table Table modified
     * @param int $id Identifier of the object (can be returned by the last inserted id function)
     * @param int $userId Identifier of the connected user
     * @throws InvalidArgumentException if the table is not allowed
     */
    public function setHistory(int $type, string $table, int $id, int $userId): void
    {
        if (!in_array($table, $this->allowedTables, true)) {
            throw new InvalidArgumentException("The provided table is not allowed for history details.");
        }
        $historyTable = $this->db->protect_identifiers($table . '_history');
        $sourceTable = $this->db->protect_identifiers($table);
        $sql = "INSERT INTO {$historyTable}";
        $sql .= ' SELECT *, NULL, ?, ?, NOW()';
        $sql .= " FROM {$sourceTable} WHERE id = ?";
        $this->db->query($sql, [$type, $userId, $id]);
    }

    /**
     * Purge the table by deleting the records prior $toDate
     * @param string $table Source Table
     * @param string $toDate
     * @return int number of affected rows
     */
    public function purgeHistory(string $table, string $toDate): int
    {
        //TODO: check if $toDate is a valid date
        $this->db->where('change_date <= ', $toDate);
        return $this->db->delete($table);
    }

    /**
     * Count the number of rows into the table
     * @param string $table Source Table
     * @return int number of rows
     * @throws InvalidArgumentException if the table is not allowed
     */
    public function count(string $table): int
    {
        if (!in_array($table, $this->allowedTables, true)) {
            throw new InvalidArgumentException("The provided table is not allowed for history details.");
        }
        $this->db->select('count(*) as number', false);
        $this->db->from($table);
        $result = $this->db->get();
        return $result->row()->number;
    }

    //TODO:cascade delete on user delete
}
