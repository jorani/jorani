<?php
/**
 * This Class contains all the business logic and the persistence layer for the roles.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * Not fully implemented, this class will allow to tweak user management with 
 * a binary mask indicating the authorizations granted for each role.
 * As of today, the user management is simplified with libraries/Auth
 */
class Roles_model extends CI_Model {

    /**
     * Default constructor
     */
    public function __construct() {

        /*
            00000001 1  Admin
            00000010 2	User
            00000100 8	HR Officier / Local HR Manager
            00001000 16	HR Manager
            00010000 32	General Manager
            00100000 34	Global Manager
         * 
         */
    }

    /**
     * Get the list of roles or one role
     * @param int $id optional id of one role
     * @return array record of roles
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getRoles($id = 0) {
        if ($id === 0) {
            $query = $this->db->get('roles');
            return $query->result_array();
        }
        $query = $this->db->get_where('roles', array('id' => $id));
        return $query->row_array();
    }
}
