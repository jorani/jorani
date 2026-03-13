<?php
/**
 * This file contains all the business logic and the persistence layer 
 * for the service accounts (OAuth clients and sessions).
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.6.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * This Class contains all the business logic and the persistence layer for 
 * for the service accounts (OAuth clients).
 * Scopes:
 *  - users
 *  - entitlements
 *  - contracts
 *  - leaves
 *  - selfservice
 */
class OAuthClients_model extends CI_Model
{

    /**
     * Default constructor
     * 
     */
    public function __construct()
    {

    }

    /**
     * Get the list of OAuth clients or one client
     * @param string $clientId optional id of a OAuth client
     * @return ?array list of client(s) or NULL if client was not found
     */
    public function getOAuthClients(string $clientId = ''): ?array
    {
        if ($clientId === '') {
            $query = $this->db->get('oauth_clients');
            return $query->result_array();
        }
        $this->db->where('client_id', $clientId);
        $query = $this->db->get('oauth_clients');
        return $query->row_array();
    }

    /**
     * Insert a new OAuth client. Data are taken from HTML form.
     * @return bool TRUE if the SQL query is successful, FALSE otherwise
     */
    public function setOAuthClients(): bool
    {
        $grantTypes = ($this->input->post('grant_types') === FALSE) ? NULL : $this->input->post('grant_types');
        $scope = ($this->input->post('scope') === FALSE) ? NULL : $this->input->post('scope');
        $userId = ($this->input->post('user_id') === FALSE) ? NULL : $this->input->post('user_id');
        $data = array(
            'client_id' => $this->input->post('client_id'),
            'client_secret' => $this->input->post('client_secret'),
            'redirect_uri' => $this->input->post('redirect_uri')
        );
        if ($grantTypes != '')
            $data['grant_types'] = $grantTypes;
        if ($scope != '')
            $data['scope'] = $scope;
        if ($userId != '')
            $data['user_id'] = $userId;
        return $this->db->insert('oauth_clients', $data);
    }

    /**
     * Delete an OAuth client from the database
     * @param string $clientId identifier of the OAuth client
     */
    public function deleteOAuthClients(string $clientId): void
    {
        $this->db->delete('oauth_clients', array('client_id' => $clientId));
    }

    /**
     * Get the list of OAuth access tokens
     * @return array record of tokens
     */
    public function getAccessTokens(): array
    {
        $this->db->limit(5000);
        $this->db->order_by("expires", "desc");
        $query = $this->db->get('oauth_access_tokens');
        return $query->result_array();
    }

    /**
     * Purge the table of OAuth tokens
     */
    public function purgeAccessTokens(): void
    {
        $this->db->truncate('oauth_access_tokens');
    }

    /**
     * Check if the application was already authorized by the user
     * @param string $clientId id of a OAuth client
     * @param string $userId id of a Jorani user
     * @return bool TRUE if the application is allowed, FALSE otherwise
     */
    public function isOAuthAppAllowed(string $clientId, string $userId): bool
    {
        $this->db->where('client_id', $clientId);
        $this->db->where('user', $userId);
        $query = $this->db->get('oauth_applications');
        $result = $query->row_array();
        return !empty($result);
    }

    /**
     * List applications authorized by a user
     * @param string $userId id of a Jorani user
     * @return array List of client names (name, url)
     */
    public function listOAuthApps(string $userId): array
    {
        $this->db->select('oauth_applications.client_id, redirect_uri');
        $this->db->join('oauth_clients', 'oauth_clients.client_id = oauth_applications.client_id');
        $this->db->order_by("oauth_applications.client_id", "asc");
        $this->db->where('user', $userId);
        $query = $this->db->get('oauth_applications');
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
     */
    public function revokeOAuthApp(string $clientId, string $userId): void
    {
        $this->db->delete(
            'oauth_applications',
            [
                'client_id' => $clientId,
                'user' => $userId
            ]
        );
    }

    /**
     * Allow an OAuth2 application
     * @param string $clientId id of a OAuth client
     * @param string $userId id of a Jorani user
     * @return bool TRUE if the SQL query is successful, FALSE otherwise
     */
    public function allowOAuthApp(string $clientId, string $userId): bool
    {
        $data = [
            'client_id' => $clientId,
            'user' => $userId
        ];
        return $this->db->insert('oauth_applications', $data);
    }
}
