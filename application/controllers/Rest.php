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
 * This class implements a REST API
 */
class Rest extends MY_RestController {

    /**
     * Get the properties of the connected employee
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function self() {
        log_message('debug', '++self');
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->user));
        log_message('debug', '--self');
    }

    /**
     * Get the the profile of the connected employee
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function profile() {
        log_message('debug', '++profile');
        $profile = new \stdClass();
        $this->load->model('positions_model');
        $this->load->model('contracts_model');
        $this->load->model('organization_model');
        $profile->managerName = $this->users_model->getName($this->user->manager);
        $profile->contractName = $this->contracts_model->getName($this->user->contract);
        $profile->positionName = $this->positions_model->getName($this->user->position);
        $profile->organizationName = $this->organization_model->getName($this->user->organization);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($profile));
        log_message('debug', '--profile');
    }

    /**
     * Get the configuration of the Jorani server
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function config() {
        log_message('debug', '++config');
        $config = new \stdClass();
        $config->IsOvertimeDisabled = $this->config->item('disable_overtime');
        $config->IsHistoryEnabled = $this->config->item('enable_history');
        $config->IsPublicCalendarEnabled = $this->config->item('public_calendar');
        $config->IsIcsEnabled = $this->config->item('ics_enabled');
        $config->hideGlobalCalsToUsers = $this->config->item('hide_global_cals_to_users');
        $config->disableDepartmentCalendar = $this->config->item('disable_department_calendar');
        $config->disableWorkmatesCalendar = $this->config->item('disable_workmates_calendar');
        $config->disallowRequestsWithoutCredit = $this->config->item('disallow_requests_without_credit');
        $config->mandatoryCommentOnReject = $this->config->item('mandatory_comment_on_reject');
        $config->requestsByManager = $this->config->item('requests_by_manager');
        $config->extraStatusRequested = $this->config->item('extra_status_requested');
        $config->disableEditLeaveDuration = $this->config->item('disable_edit_leave_duration');
        $config->versionOfJorani = $GLOBALS['versionOfJorani'];
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($config));
        log_message('debug', '--config');
    }

    /**
     * Get the number of submitted leave and overtime requests
     * to the connected manager (all would be equal to 0 for non managers)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function submissions() {
        log_message('debug', '++submissions');
        $submissions = new \stdClass();
        $this->load->model('leaves_model');
        $submissions->isOvertimeDisabled = $this->config->item('disable_overtime');
        $submissions->requestedLeavesCount = $this->leaves_model->countLeavesRequestedToManager($this->user->id);
        $submissions->requestedExtrasCount = 0;
        if ($this->config->item('disable_overtime') == FALSE) {
            $this->load->model('overtime_model');
            $submissions->requestedExtrasCount = $this->overtime_model->countExtraRequestedToManager($this->user->id);
        }
        $submissions->requestsTotal = $submissions->requestedLeavesCount + $submissions->requestedExtrasCount;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($submissions));
        log_message('debug', '--submissions');
    }
    
    /**
     * Compute the checksum of the content of a table or just one table
     * Useful to detect if any change was made since a last sync but costly
     * @param string $name Name of the table into the database (optional)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function checksum($table = '') {
        log_message('debug', '++checksum = ' . $table);
        $tables = array();
        //Compute the checksum of all tables if not specified
        if ($table == '') {
            $list = $this->db->list_tables();
            foreach ($list as $table)
            {
                $tables[$table] = $query = $this->db->query('CHECKSUM TABLE ' . $table)->result_array()[0]['Checksum'];
            }
        } else {
            $table = preg_replace('/\s+/', '', $table); //Should avoid the method to be used with bad intentions
            $tables[$table] = $query = $this->db->query('CHECKSUM TABLE ' . $table)->result_array()[0]['Checksum'];
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($tables));
        log_message('debug', '--checksum');
    }
}
