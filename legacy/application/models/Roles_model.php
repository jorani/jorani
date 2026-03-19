<?php
/**
 * This Class contains all the business logic and the persistence layer for the roles.
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.1.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Not fully implemented, this class will allow to tweak user management with 
 * a binary mask indicating the authorizations granted for each role.
 * As of today, the user management is simplified with libraries/Auth
 */
class Roles_model extends CI_Model
{

    /**
     * Default constructor
     */
    public function __construct()
    {

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
     * @return array<string, mixed>|null record of role(s) or NULL if role was not found
     */
    public function getRoles(int $id = 0): ?array
    {
        if ($id === 0) {
            $query = $this->db->get('roles');
            return $query->result_array();
        }
        $this->db->where('id', $id);
        $query = $this->db->get('roles');
        return $query->row_array();
    }
}
