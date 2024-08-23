<?php
/**
 * This controller is the entry point for the REST API used by mobile and HTML5
 * Clients. They use CORS requests. Each call to end points uses BasicAuth 
 * except the preflight exchange. So it should be used with a TLS connection
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.3.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class implements a REST API for Administrators of Jorani
 */
class RestAdmin extends MY_RestController {

    /**
     * Default constructor
     */
    public function __construct() {
        parent::__construct();
        //Non admin users are forbidden to access to the methods of this class
        if (!$this->user->isAdmin) {
            $this->forbidden();
        }
    }

    /**
     * Get the current log messages or the log file for a given date
     * @param string $dateLogFile Date of the log file (optional)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function logs($dateLogFile = '') {
        log_message('debug', '++logs = ' . $table);
        $tables = 'Not implemented';
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($tables));
        log_message('debug', '--logs');
    }
}
