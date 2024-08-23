<?php
/**
 * This controller serves the user management REST API
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.6.6
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This REST controller serves the API calls related to users management
 * The operations are allowed to Admin and HR users only
 */
class RestUsers extends MY_RestController {

    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
        //Is the connected user an admin or HR?
        if (!$this->user->isAdmin || !$this->user->isHr) {
            $this->forbidden();
        }
    }

    /**
     * Display the list of all users or one user
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function users($userID = 0) {
        log_message('debug', '++users / userID = ' . $userID);
        //Access control is done in constructor
        if ($userID != 0) {
            $users = $this->users_model->getUsers($userID);
            unset($users['password']);
            unset($users['random_hash']);
        } else {
            $users = $this->users_model->getUsersAndRoles();
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($users));
        log_message('debug', '--users');
    }

    /**
     * Enable a user
     * @param int $userID User identifier
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function enable($userID) {
        log_message('debug', '++enable');
        //Access control is done in constructor
        $this->users_model->setActive($userID, TRUE);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode('OK'));
        log_message('debug', '--enable');
    }

    /**
     * Disable a user
     * @param int $userID User identifier
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function disable($userID) {
        log_message('debug', '++disable');
        //Access control is done in constructor
        $this->users_model->setActive($userID, FALSE);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode('OK'));
        log_message('debug', '--disable');
    }
}
