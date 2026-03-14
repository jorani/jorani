<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Sessions Model
 *
 * This model manages the purging of old session data.
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @since   1.1.0
 */
class Sessions_model extends CI_Model
{
    /**
     * Purge old data from session and oauth tables.
     * Deletes rows older than a month.
     *
     * @return void
     */
    public function purgeOldData(): void
    {
        // One month ago for CI sessions (Unix timestamp)
        $oneMonthAgoTimestamp = time() - (30 * 24 * 60 * 60);

        // One month ago for OAuth tables (SQL timestamp string)
        $oneMonthAgoSql = date('Y-m-d H:i:s', $oneMonthAgoTimestamp);

        // Purge ci_sessions
        $this->db->where('timestamp <', $oneMonthAgoTimestamp);
        $this->db->delete('ci_sessions');

        // Purge oauth_access_tokens
        $this->db->where('expires <', $oneMonthAgoSql);
        $this->db->delete('oauth_access_tokens');

        // Purge oauth_refresh_tokens
        $this->db->where('expires <', $oneMonthAgoSql);
        $this->db->delete('oauth_refresh_tokens');

        // Purge oauth_authorization_codes
        $this->db->where('expires <', $oneMonthAgoSql);
        $this->db->delete('oauth_authorization_codes');
    }
}
