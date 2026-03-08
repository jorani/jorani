<?php
/**
 * This file contains all the business logic and the persistence layer for the positions.
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.1.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * This Class contains all the business logic and the persistence layer for the positions.
 * A postion describes the kind of job of an employee. As Jorani is not an HRM System,
 * This information has no technical value, but can be useful for an HR Manager for
 * verification purposes or if a position grants some kind of entitilments.
 */
class Positions_model extends CI_Model
{

    /**
     * Default constructor
     * 
     */
    public function __construct()
    {

    }

    /**
     * Get the list of positions or one position
     * @param int $id optional id of a position
     * @return array record of positions
     */
    public function getPositions(int $id = 0): array
    {
        if ($id === 0) {
            $query = $this->db->get('positions');
            return $query->result_array();
        }
        $query = $this->db->get_where('positions', ['id' => $id]);
        return $query->row_array();
    }

    /**
     * Get the name of a position
     * @param int $id Identifier of the postion
     * @return string Name of the position
     */
    public function getName(int $id): string
    {
        $record = $this->getPositions($id);
        if (!empty($record)) {
            return $record['name'];
        } else {
            return '';
        }
    }

    /**
     * Insert a new position
     * @param string $name Name of the postion
     * @param string $description Description of the postion
     * @return int number of affected rows
     */
    public function setPositions(string $name, string $description): int
    {
        $data = [
            'name' => $name,
            'description' => $description
        ];
        return $this->db->insert('positions', $data);
    }

    /**
     * Delete a position from the database
     * Cascade update all users having this position (filled with 0)
     * @param int $id identifier of the position record
     * @return bool TRUE if the operation was successful, FALSE otherwise
     */
    public function deletePosition(int $id): bool
    {
        $delete = $this->db->delete('positions', ['id' => $id]);
        $this->db->where('position', $id);
        $update = $this->db->update('users', ['position' => 0]);
        return $delete && $update;
    }

    /**
     * Update a given position in the database.
     * @param int $id Identifier of the position into the database
     * @param string $name Name of the position
     * @param string $description Description of the position
     * @return int number of affected rows
     */
    public function updatePositions(int $id, string $name, string $description): int
    {
        $data = [
            'name' => $name,
            'description' => $description
        ];
        $this->db->where('id', $id);
        return $this->db->update('positions', $data);
    }
}
