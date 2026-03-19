<?php
/**
 * This file contains all the business logic and the persistence layer for the status of leave request.
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.7.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * This Class contains all the business logic and the persistence layer for the status of leave request.
 * @property CI_DB_query_builder $db
 */
class Status_model extends CI_Model
{

    /**
     * Default constructor
     */
    public function __construct()
    {

    }

    /**
     * Get the list of status or one status
     * @param int $id optional id of a status
     * @return array<string, mixed>|null record of status(es) or NULL if status was not found
     */
    public function getStatus(int $id = 0): ?array
    {
        if ($id === 0) {
            $query = $this->db->get('status');
            return $query->result_array();
        }
        $this->db->where('id', $id);
        $query = $this->db->get('status');
        return $query->row_array();
    }

    /**
     * Get the list of status or one status
     * @param string $name status name
     * @return array<string, mixed> record of a leave status
     */
    public function getStatusByName(string $name): array
    {
        $this->db->where('name', $name);
        $query = $this->db->get('status');
        return $query->row_array();
    }

    /**
     * Get the list of status as an ordered associative array
     * @return array<int, string> Associative array of types (id, name)
     */
    public function getStatusAsArray(): array
    {
        $listOfTypes = [];
        $this->db->from('status');
        $this->db->order_by('name');
        $rows = $this->db->get()->result_array();
        foreach ($rows as $row) {
            $listOfTypes[$row['id']] = $row['name'];
        }
        return $listOfTypes;
    }

    /**
     * Get the name of a given status id
     * @param int $id ID of the status
     * @return string label of the status
     */
    public function getName(int $id): string
    {
        $type = $this->getStatus($id);
        return $type['name'];
    }
}
