<?php
/**
 * This controller serves the administration pages
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.4.2
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class serves the administration pages (readonly settings page for the moment).
 * In Jorani the settings are set into a configuration file and not into DB.
 */
class Admin extends CI_Controller {
    
    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {
        parent::__construct();
        setUserContext($this);
        $this->lang->load('global', $this->language);
        $this->lang->load('admin', $this->language);
    }
    
    /**
     * Display the settings of the system (extract of config.php)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function settings() {
        $this->auth->checkIfOperationIsAllowed('list_settings');
        $data = getUserContext($this);
        $data['title'] = 'application/config/config.php';
        $data['help'] = $this->help->create_help_link('global_link_doc_page_settings');
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('admin/settings', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Display the diagnostic of the content (duplicated requests, etc.) and configuration
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function diagnostic() {
        $this->auth->checkIfOperationIsAllowed('diagnostic');
        $data = getUserContext($this);
        $data['title'] = lang('admin_diagnostic_title');
        $data['help'] = '';
        $this->load->model('leaves_model');
        $this->load->model('entitleddays_model');
        $this->load->model('dayoffs_model');
        $this->load->model('contracts_model');
        $this->load->model('overtime_model');
        $data['duplicatedLeaves'] = $this->leaves_model->detectDuplicatedRequests();
        $data['wrongDateType'] = $this->leaves_model->detectWrongDateTypes();
        $data['entitlmentOverflow'] = $this->entitleddays_model->detectOverflow();
        $data['daysOffYears'] = $this->dayoffs_model->checkIfDefined(date("Y"));
        $data['negativeOvertime'] = $this->overtime_model->detectNegativeOvertime();
        $data['unusedContracts'] = $this->contracts_model->notUsedContracts();
        $data['leaveBalance'] = $this->leaves_model->detectBalanceProblems();
        $data['overlappingLeaves'] = $this->leaves_model->detectOverlappingProblems();
        
        //Count the number of items (will be used for badges in tab 
        $data['duplicatedLeaves_count'] = count($data['duplicatedLeaves']);
        $data['wrongDateType_count'] = count($data['wrongDateType']);
        $data['entitlmentOverflow_count'] = count($data['entitlmentOverflow']);
        $data['negativeOvertime_count'] = count($data['negativeOvertime']);
        $data['unusedContracts_count'] = count($data['unusedContracts']);
        $data['leaveBalance_count'] = count($data['leaveBalance']);
        $data['overlappingLeaves_count'] = count($data['overlappingLeaves']);
        $data['daysOffYears_count'] = 0;
        foreach ($data['daysOffYears'] as $yearDef) {
            if ($yearDef['y'] == 0) {
                $data['daysOffYears_count']++;
            }
        }
        
        if ($this->config->item('disable_telework') === FALSE) {
            $this->load->model('teleworks_model');
            $data['wrongDateTypeTeleworks'] = $this->teleworks_model->detectWrongDateTypes();            
            $data['duplicatedTeleworks'] = $this->teleworks_model->detectDuplicatedRequests();
            $data['overlappingTeleworks'] = $this->teleworks_model->detectOverlappingProblems();
            
            //Count the number of items (will be used for badges in tab
            $data['duplicatedTeleworks_count'] = count($data['duplicatedTeleworks']);
            $data['wrongDateTypeTeleworks_count'] = count($data['wrongDateTypeTeleworks']);
            $data['overlappingTeleworks_count'] = count($data['overlappingTeleworks']);
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('admin/diagnostic', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Display the list of OAuth clients
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function oauthClients() {
        $this->auth->checkIfOperationIsAllowed('oauth_clients');
        $data = getUserContext($this);
        $this->lang->load('datatable', $this->language);
        $this->load->model('oauthclients_model');
        $data['clients'] = $this->oauthclients_model->getOAuthClients();
        $data['tokens'] = $this->oauthclients_model->getAccessTokens();
        $data['title'] = lang('admin_oauthclients_title');
        $data['help'] = '';
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('admin/oauth_clients', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Ajax action: create an OAuth clients
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function oauthClientsCreate() {
        if ($this->auth->isAllowed('oauth_clients') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $clientId = $this->input->post('client_id', TRUE);
            $clientSecret = $this->input->post('client_secret', TRUE);
            $redirectUri = $this->input->post('redirect_uri', TRUE);
            if (isset($clientId) && isset($clientSecret) && isset($redirectUri)) {
                $this->output->set_content_type('text/plain');
                $this->load->model('oauthclients_model');
                $exists = $this->oauthclients_model->getOAuthClients($clientId);
                if (empty($exists)) {
                    $result = $this->oauthclients_model->setOAuthClients();
                } else {
                    $result = "DUPLICATE";
                }
                $this->output->set_output($result);
            } else {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
            }
        }
    }

    /**
     * Ajax action: delete an OAuth client
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function oauthClientsDelete() {
        if ($this->auth->isAllowed('oauth_clients') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $clientId = $this->input->post('client_id', TRUE);
            $clientSecret = $this->input->post('client_secret', TRUE);
            $redirectUri = $this->input->post('redirect_uri', TRUE);
            if (isset($clientId) && isset($clientSecret) && isset($redirectUri)) {
                $this->output->set_content_type('text/plain');
                $this->load->model('oauthclients_model');
                $result = $this->oauthclients_model->deleteOAuthClients($this->input->post('client_id'));
                $this->output->set_output($result);
            } else {
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
            }
        }
    }
    
    /**
     * purgeAccessTokens
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function oauthTokensPurge() {
        $this->auth->checkIfOperationIsAllowed('oauth_clients');
        $this->load->model('oauthclients_model');
        $this->oauthclients_model->purgeAccessTokens();
        redirect('admin/oauthclients#sessions');
    }
    
}
