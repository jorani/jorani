<?php
/**
 * This class contains the business logic and manages the persistence of telework rules
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class contains the business logic and manages the persistence of telework rules.
 */
class Telework_rule_model extends CI_Model {

    /**
     * Default constructor
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function __construct() {

    }

    /**
     * Get the list of telework rules or one telework rule
     * @param int $id optional id of a telework rule
     * @return array records of telework rules
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getTeleworkRules($id = 0) {
        $this->db->select('telework_rule.*');
        $this->db->select('organization.name as organization_name');
        $this->db->from('telework_rule');
        $this->db->join('organization', 'telework_rule.organization = organization.id');
        if ($id === 0) {
            return $this->db->get()->result_array();
        }
        $this->db->where('telework_rule.id', $id);
        return $this->db->get()->row_array();
    }
    
    /**
     * Get the list of organizations with and without telework rule
     * @return array records of organizations with and without telework rule
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getTeleworkRulesForExport() {
        $this->db->select('telework_rule.*');
        $this->db->select('organization.id as organization_id, name');
        $this->db->from('organization');
        $this->db->join('telework_rule', 'telework_rule.organization = organization.id', 'left outer');
        return $this->db->get()->result_array();
    }

    /**
     * Insert a new telework rule into the database. Inserted data are coming from an HTML form
     * @return int number of affected rows
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function setTeleworkRules() {
        $data = array(
            'organization' => $this->input->post('organization'),
            'limit' => $this->input->post('limit'),
            'delay' => $this->input->post('delay')
        );
        $teleworkrule = $this->getTeleworkRuleForOrganisation($data['organization']);
        if (empty($teleworkrule))
            return $this->db->insert('telework_rule', $data);
        else {
            $this->db->where('organization', $data['organization']);
            return $this->db->update('telework_rule', $data);
        }
    }
    
    /**
     * Insert a new telework rule into the database. Inserted data are coming from an imported file
     * @return int number of affected rows
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function setTeleworkRulesFromImport($data) {
        if($data['limit'] != NULL && $data['delay'] != NULL){
            $teleworkrule = $this->getTeleworkRuleForOrganisation($data['organization']);
            if (empty($teleworkrule))
                return $this->db->insert('telework_rule', $data);
            else {
                $this->db->where('organization', $data['organization']);
                return $this->db->update('telework_rule', $data);
            }
        } 
    }

    /**
     * Update a given telework rule in the database. Update data are coming from an HTML form
     * @param int $id identifier of the telework rule
     * @return int number of affected rows
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function updateTeleworkRule($id) {
        $data = array(
            'organization' => $this->input->post('organization'),
            'limit' => $this->input->post('limit'),
            'delay' => $this->input->post('delay')
        );
        $this->db->where('id', $this->input->post('id'));
        return $this->db->update('telework_rule', $data);
    } 
    
    /**
     * Delete a telework rule from the database
     * @param int $id identifier of the telework rule
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function deleteTeleworkRule($id) {
        $this->db->delete('telework_rule', array('id' => $id));
    }
    
    /**
     * Get number of days allowed for the given employee id
     * @param int $id id of an employee
     * @return int number of days allowed
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getTeleworkRuleForEmployee($id) {
        $this->db->select('delay, limit');
        $this->db->from('users');
        $this->db->join('telework_rule', 'telework_rule.organization = users.organization');
        $this->db->where('users.id', $id);

        return $this->db->get()->row_array();
    }
    
    /**
     * Get number of days allowed for the given employee id
     * @param int $id id of an organization
     * @return int number of days allowed
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getTeleworkRuleForOrganisation($id) {
        $this->db->where('organization', $id);        
        return $this->db->get('telework_rule')->row_array();
    }
}
