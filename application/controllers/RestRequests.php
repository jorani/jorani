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

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\AllOfException;

/**
 * This class implements a REST API for the leave requests sent to a manager
 */
class RestRequests extends MY_RestController {

    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('leaves_model');
    }

    /**
     * Display the list of all requests submitted to you
     * Status is submitted or accepted/rejected depending on the filter parameter.
     * @param string $name Filter the list of submitted leave requests ("all" or "requested")
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function requests($filter = 'requested') {
        log_message('debug', '++requests filter=' . $filter);
        $requests = NULL;
        ($filter == 'all')? $showAll = TRUE : $showAll = FALSE;
        if ($this->config->item('enable_history') == TRUE) {
          $requests = $this->leaves_model->getLeavesRequestedToManagerWithHistory($this->user->id, $showAll);
        }else{
          $requests = $this->leaves_model->getLeavesRequestedToManager($this->user->id, $showAll);
        }

        //Format and translate according to Accept-Language Header
        $langRest = new CI_Lang();
        $langRest->load('global', $this->language);
        foreach ($requests as &$request) {
            unset($request['login']);
            unset($request['password']);
            unset($request['role']);
            unset($request['random_hash']);
            unset($request['ldap_path']);
            unset($request['user_properties']);
            $request['startdatetype'] = $langRest->line($request['startdatetype']);
            $request['enddatetype'] = $langRest->line($request['enddatetype']);
            $request['status_name'] = $langRest->line($request['status_name']);
            $date = new DateTime($request['startdate']);
            $request['startdate'] = $date->format($langRest->line('global_date_format'));
            $date = new DateTime($request['enddate']);
            $request['enddate'] = $date->format($langRest->line('global_date_format'));
            if ($this->config->item('enable_history') == TRUE) {
                $date = new DateTime($request['change_date']);
                $request['change_date'] = $date->format($langRest->line('global_date_format'));
                $date = new DateTime($request['request_date']);
                $request['request_date'] = $date->format($langRest->line('global_date_format'));
            }
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($requests));
        log_message('debug', '--requests');
    }
}
