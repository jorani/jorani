<?php
/**
 * This class contains the business logic and manages the persistence of telework campaigns
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class contains the business logic and manages the persistence of telework campaigns.
 */
class Telework_campaign_model extends CI_Model {

    /**
     * Default constructor
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function __construct() {

    }

    /**
     * Get the list of telework campaigns or one telework campaign
     * @param int $id optional id of a telework campaign
     * @return array records of telework campaigns
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getTeleworkCampaigns($id = 0) {
        if ($id === 0) {
            $this->db->order_by("startdate", "desc");
            $query = $this->db->get('telework_campaign');
            return $query->result_array();
        }
        $query = $this->db->get_where('telework_campaign', array('id' => $id));
        return $query->row_array();
    }
    
    /**
     * Get the list of active telework campaigns
     * @return array records of telework campaigns
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getActiveCampaigns() {
        $this->db->order_by("startdate", "asc");
        $query = $this->db->get_where('telework_campaign', array('active' => 1));
        return $query->result_array();
    }

    /**
     * Get the name of a given telework campaign
     * @param int $id Unique identifier of a telework campaign
     * @return string name of the telework campaign
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getName($id) {
        $record = $this->getTeleworkCampaigns($id);
        if (!empty($record)) {
            return $record['name'];
        } else {
            return '';
        }
    }

    /**
     * Insert a new telework campaign into the database. Inserted data are coming from an HTML form
     * @return int number of affected rows
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function setTeleworkCampaigns() {
        $data = array(
            'name' => $this->input->post('name'),
            'startdate' => (new DateTime(str_replace('/', '-', $this->input->post('startdate'))))->format('Y-m-d'),
            'enddate' => (new DateTime(str_replace('/', '-', $this->input->post('enddate'))))->format('Y-m-d')
        );
        return $this->db->insert('telework_campaign', $data);
    }

    /**
     * Delete a telework campaign from the database
     * @param int $id identifier of the telework campaign
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function deleteTeleworkCampaign($id) {
        $this->db->delete('telework_campaign', array('id' => $id));
        $this->deleteTeleworksCascadeCampaign($id);
    }

    /**
     * Update a given telework campaign in the database. Update data are coming from an HTML form
     * @param int $id identifier of the telework campaign
     * @return int number of affected rows
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function updateTeleworkCampaign($id) {
        $data = array(
            'name' => $this->input->post('name'),
            'startdate' => (new DateTime(str_replace('/', '-', $this->input->post('startdate'))))->format('Y-m-d'),
            'enddate' => (new DateTime(str_replace('/', '-', $this->input->post('enddate'))))->format('Y-m-d')
        );
        $this->db->where('id', $this->input->post('id'));
        return $this->db->update('telework_campaign', $data);
    }
    
    /**
     * Activate a given telework campaign in the database. Update data are coming from an HTML form
     * @param int $id identifier of the telework campaign
     * @return int number of affected rows
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function activateTeleworkCampaign($id) {
        $data = array(
            'active' => 1
        );
        $this->db->where('id', $id);
        return $this->db->update('telework_campaign', $data);
    }
    
    /**
     * Deactivate a given telework campaign in the database. Update data are coming from an HTML form
     * @return int number of affected rows
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function deactivateTeleworkCampaign($id) {
        $data = array(
            'active' => 0
        );
        $this->db->where('id', $id);
        return $this->db->update('telework_campaign', $data);
    }
    
    /**
     * Delete teleworks attached to a telework campaign
     * @param int $campaignid identifier of a telework campaign
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function deleteTeleworksCascadeCampaign($campaignid)
    {
        // Select the teleworks of a users (if history feature is enabled)
        // if ($this->config->item('enable_history') === TRUE) {
        // $this->load->model('telework_history_model');
        // $teleworks = $this->getTeleworksOfCampaign($campaignid);
        // // TODO in fact, should we cascade delete ?
        // foreach ($teleworks as $telework) {
        // $this->telework_history_model->setHistory(3, 'teleworks', $telework['id'], $this->session->userdata('id'));
        // }
        // }
        return $this->db->delete('teleworks', array(
            'campaign' => $campaignid
        ));
    }
    
    /**
     * Get the list of teleworks for a given telework campaign
     * @param int $id id of a telework campaign
     * @return array records of telework campaigns
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getTeleworksOfCampaign($id) {
        $this->db->order_by("startdate", "desc");
        $query = $this->db->get_where('teleworks', array('campaign' => $id));
        return $query->result_array();
    }
    
    /**
     * Detect if the campaign overlaps with another campaign
     * @param date $startdate start date of campaign being created
     * @param date $enddate end date of telework campaigncreated
     * @param int $campaign_id When this function is used for editing a campaign, we must not collide with this campaign
     * @return boolean TRUE if another campaign has been created, FALSE otherwise
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function detectOverlappingCampaigns($startdate, $enddate, $campaign_id=NULL) {
        $this->db->where('(startdate <= DATE(\'' . $enddate . '\') AND enddate >= DATE(\'' . $startdate . '\'))');
        if (!is_null($campaign_id)) {
            $this->db->where('id != ', $campaign_id);
        }
        
        if($this->db->get('telework_campaign')->num_rows() > 0)
            return TRUE;
        else 
            return FALSE;
    }
    
    /**
     * Get valid telework campaign dates
     * @return array records of valid telework campaign dates
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function getValidCampaignDates() {
        $now = (new DateTime("now"))->format('Y-m-d');
        $this->db->select('startdate, enddate');
        $this->db->where('(enddate >= DATE(' . $now . '))');
        $this->db->where('active = 1');
        $this->db->order_by('enddate');

        return $this->db->get('telework_campaign')->result();
    }
}
