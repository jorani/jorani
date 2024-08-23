<?php
/**
 * This Class contains all the business logic and the persistence layer for the positions.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This Class contains all the business logic and the persistence layer for the positions.
 * A postion describes the kind of job of an employee. As Jorani is not an HRM System,
 * This information has no technical value, but can be useful for an HR Manager for
 * verification purposes or if a position grants some kind of entitilments.
 */
class Positions_model extends CI_Model {

    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {

    }

    /**
     * Get the list of positions or one position
     * @param int $id optional id of a position
     * @return array record of positions
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getPositions($id = 0) {
        if ($id === 0) {
            $query = $this->db->get('positions');
            return $query->result_array();
        }
        $query = $this->db->get_where('positions', array('id' => $id));
        return $query->row_array();
    }

    /**
     * Get the name of a position
     * @param int $id Identifier of the postion
     * @return string Name of the position
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getName($id) {
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
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function setPositions($name, $description) {
        $data = array(
            'name' => $name,
            'description' => $description
        );
        return $this->db->insert('positions', $data);
    }

    /**
     * Delete a position from the database
     * Cascade update all users having this postion (filled with 0)
     * @param int $id identifier of the position record
     * @return bool TRUE if the operation was successful, FALSE otherwise
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function deletePosition($id) {
        $delete = $this->db->delete('positions', array('id' => $id));
        $data = array(
            'position' => 0
        );
        $this->db->where('position', $id);
        $update = $this->db->update('users', $data);
        return $delete && $update;
    }

    /**
     * Update a given position in the database.
     * @param int $id Identifier of the database
     * @param string $name Name of the postion
     * @param string $description Description of the postion
     * @return type
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function updatePositions($id, $name, $description) {
        $data = array(
            'name' => $name,
            'description' => $description
        );
        $this->db->where('id', $id);
        return $this->db->update('positions', $data);
    }
}
