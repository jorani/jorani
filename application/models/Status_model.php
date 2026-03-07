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
     * @return array record of types
     */
    public function getStatus(int $id = 0): array
    {
        if ($id === 0) {
            $query = $this->db->get('status');
            return $query->result_array();
        }
        $query = $this->db->get_where('status', ['id' => $id]);
        return $query->row_array();
    }

    /**
     * Get the list of status or one status
     * @param string $name status name
     * @return array record of a leave status
     */
    public function getStatusByName(string $name): array
    {
        $query = $this->db->get_where('status', ['name' => $name]);
        return $query->row_array();
    }

    /**
     * Get the list of status as an ordered associative array
     * @return array Associative array of types (id, name)
     */
    public function getStatusAsArray(int $id = 0): array
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
