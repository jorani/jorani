<?php
/**
 * This Class contains all the business logic and the persistence layer for the status of leave request.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.7.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This Class contains all the business logic and the persistence layer for the status of leave request.
 */
class Status_model extends CI_Model {

    /**
     * Default constructor
     * @author Emilien NICOLAS <milihhard1996@gmail.com>
     */
    public function __construct() {

    }

    /**
     * Get the list of status or one status
     * @param int $id optional id of a status
     * @return array record of types
     * @author Emilien NICOLAS <milihhard1996@gmail.com>
     */
    public function getStatus($id = 0) {
        if ($id === 0) {
            $query = $this->db->get('status');
            return $query->result_array();
        }
        $query = $this->db->get_where('status', array('id' => $id));
        return $query->row_array();
    }

    /**
     * Get the list of status or one status
     * @param string $name status name
     * @return array record of a leave status
     * @author Emilien NICOLAS <milihhard1996@gmail.com>
     */
    public function getStatusByName($name) {
        $query = $this->db->get_where('status', array('name' => $name));
        return $query->row_array();
    }

    /**
     * Get the list of status as an ordered associative array
     * @return array Associative array of types (id, name)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getStatusAsArray($id = 0) {
        $listOfTypes = array();
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
     * @author Emilien NICOLAS <milihhard1996@gmail.com>
     */
    public function getName($id) {
        $type = $this->getStatus($id);
        return $type['name'];
    }
}
