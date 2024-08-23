<?php
/**
 * This Class contains all the business logic and the persistence layer 
 * for the service accounts (OAuth clients and sessions).
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.6.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This Class contains all the business logic and the persistence layer for 
 * for the service accounts (OAuth clients).
 * Scopes:
 * users
 * entitlements
 * contracts
 * leaves
 * selfservice
 */
class OAuthClients_model extends CI_Model {

    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {
        
    }

    /**
     * Get the list of OAuth clients or one client
     * @param string $clientId optional id of a OAuth client
     * @return array record of clients
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getOAuthClients($clientId = '') {
        if ($clientId === '') {
            $query = $this->db->get('oauth_clients');
            return $query->result_array();
        }
        $query = $this->db->get_where('oauth_clients', array('client_id' => $clientId));
        return $query->row_array();
    }
    
    /**
     * Insert a new OAuth client. Data are taken from HTML form.
     * @return int number of affected rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function setOAuthClients() {
        $grantTypes = ($this->input->post('grant_types') === FALSE)? NULL: $this->input->post('grant_types');
        $scope = ($this->input->post('scope') === FALSE)? NULL: $this->input->post('scope');
        $userId = ($this->input->post('user_id') === FALSE)? NULL: $this->input->post('user_id');
        $data = array(
            'client_id' => $this->input->post('client_id'),
            'client_secret' => $this->input->post('client_secret'),
            'redirect_uri' => $this->input->post('redirect_uri')
        );
        if ($grantTypes != '') $data['grant_types'] = $grantTypes;
        if ($scope != '') $data['scope'] = $scope;
        if ($userId != '') $data['user_id'] = $userId;
        return $this->db->insert('oauth_clients', $data);
    }
    
    /**
     * Delete a leave type from the database
     * @param string $id identifier of the leave type
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function deleteOAuthClients($clientId) {
        $this->db->delete('oauth_clients', array('client_id' => $clientId));
    }
    
    /**
     * Update a given leave type in the database.
     * @param int $id identifier of the leave type
     * @param string $name name of the type
     * @param bool $deduct Deduct days off
     * @return int number of affected rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function updateOAuthUsers($id, $name, $deduct) {
        $data = array(
            'name' => $name,
            'deduct_days_off' => $deduct
        );
        $this->db->where('id', $id);
        return $this->db->update('oauth_clients', $data);
    }
    
    /**
     * Get the list of OAuth access tokens
     * @return array record of tokens
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getAccessTokens() {
        $this->db->limit(5000);
        $this->db->order_by("expires", "desc");
        $query = $this->db->get('oauth_access_tokens');
        return $query->result_array();
    }
    
    /**
     * Purge the table of OAtuh2 tokens
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function purgeAccessTokens() {
        $this->db->truncate('oauth_access_tokens');
    }
    
    /**
     * Check if the application was already authorized by the user
     * @param string $clientId id of a OAuth client
     * @param string $userId id of a Jorani user
     * @return bool TRUE if the application is allowed
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function isOAuthAppAllowed($clientId, $userId) {
        $query = $this->db->get_where('oauth_applications',
                array(
                    'client_id' => $clientId,
                    'user' => $userId
                ));
        $result = $query->row_array();
        return !empty($result);
    }
    
    /**
     * List applications authorized by a user
     * @param string $userId id of a Jorani user
     * @return array List of client names (name, url)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function listOAuthApps($userId) {
        $this->db->select('oauth_applications.client_id, redirect_uri');
        $this->db->join('oauth_clients', 'oauth_clients.client_id = oauth_applications.client_id');
        $this->db->order_by("oauth_applications.client_id", "asc");
        $query = $this->db->get_where('oauth_applications',
                array('user' => $userId));
        //Try to resolve the icon path of the 3rd application, 
        // use a default icon otherwise
        $apps = $query->result_array();
        foreach ($apps as $key => $value) {
            $iconPath = FCPATH . 'local/images/' . $value['client_id'] . '.png';
            if (file_exists($iconPath)) {
                $apps[$key]['icon_path'] = base_url() . 'local/images/' . $value['client_id'] . '.png';
            } else {
                $apps[$key]['icon_path'] = base_url() . 'assets/images/application.png';
            }
        }
        return $apps;
    }
    
    /**
     * Revoke an OAuth2 application
     * @param string $clientId id of a OAuth client
     * @param string $userId id of a Jorani user
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function revokeOAuthApp($clientId, $userId) {
        $this->db->delete('oauth_applications', 
                array(
                    'client_id' => $clientId,
                    'user' => $userId
                ));
    }
    
    /**
     * Allow an OAuth2 application
     * @param string $clientId id of a OAuth client
     * @param string $userId id of a Jorani user
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function allowOAuthApp($clientId, $userId) {
        $data = array(
            'client_id' => $clientId,
            'user' => $userId
        );
        return $this->db->insert('oauth_applications', $data);
    }
}
