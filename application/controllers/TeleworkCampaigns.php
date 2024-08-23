<?php
/**
 * This controller allows to manage the telework campaigns
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class allows to manage the telework campaigns.
 */
class TeleworkCampaigns extends CI_Controller {

    /**
     * Default constructor
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function __construct() {
        parent::__construct();
        setUserContext($this);
        $this->lang->load('teleworkcampaigns', $this->language);
        $this->load->model('telework_campaign_model');
    }

    /**
     * Display the list of all telework campaigns defined in the system
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function index() {
        $this->auth->checkIfOperationIsAllowed('list_telework_campaigns');
        $this->lang->load('datatable', $this->language);
        $data = getUserContext($this);
        $data['title'] = lang('telework_campaign_index_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_telework_campaign_list');
        $data['campaigns'] = $this->telework_campaign_model->getTeleworkCampaigns();
        $data['flash_partial_view'] = $this->load->view('templates/flash', $data, TRUE);
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('teleworkcampaigns/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Display a form that allows to update a telework campaign
     * @param int $id telework campaign identifier
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function edit($id) {
        $this->auth->checkIfOperationIsAllowed('edit_telework_campaign');
        $data = getUserContext($this);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['title'] = lang('telework_campaign_edit_title');

        $this->form_validation->set_rules('name', lang('telework_campaign_edit_field_name'), 'required|strip_tags');
        $this->form_validation->set_rules('startdate', lang('telework_campaign_edit_field_startdate'), 'required|strip_tags');
        $this->form_validation->set_rules('enddate', lang('telework_campaign_edit_field_enddate'), 'required|strip_tags');

        $data['campaign'] = $this->telework_campaign_model->getTeleworkCampaigns($id);
        if (empty($data['campaign'])) {
            redirect('notfound');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('teleworkcampaigns/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $this->telework_campaign_model->updateTeleworkCampaign($id);
            $this->session->set_flashdata('msg', lang('telework_campaign_edit_msg_success'));
            redirect('teleworkcampaigns');
        }
    }

    /**
     * Display the form / action Create a new telework campaign
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function create() {
        $this->auth->checkIfOperationIsAllowed('create_telework_campaign');
        $data = getUserContext($this);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['title'] = lang('telework_campaign_create_title');

        $this->form_validation->set_rules('name', lang('telework_campaign_create_field_name'), 'required|strip_tags');
        $this->form_validation->set_rules('startdate', lang('telework_campaign_create_field_startdate'), 'required|strip_tags');  
        $this->form_validation->set_rules('enddate', lang('telework_campaign_create_field_enddate'), 'required|strip_tags');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('teleworkcampaigns/create', $data);
            $this->load->view('templates/footer');
        } else {
            $this->telework_campaign_model->setTeleworkCampaigns();
            $this->session->set_flashdata('msg', lang('telework_campaign_create_msg_success'));
            redirect('teleworkcampaigns');
        }
    }

    /**
     * Delete a given telework campaign
     * @param int $id telework campaign identifier
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function delete($id) {
        $this->auth->checkIfOperationIsAllowed('delete_telework_campaign');
        //Test if the telework campaign exists
        $data['campaigns'] = $this->telework_campaign_model->getTeleworkCampaigns($id);
        if (empty($data['campaigns'])) {
            redirect('notfound');
        } else {
            $this->telework_campaign_model->deleteTeleworkCampaign($id);
        }
        $this->session->set_flashdata('msg', lang('telework_campaign_delete_msg_success'));
        redirect('teleworkcampaigns');
    }   
    
    /**
     * Activate a given telework campaign
     * @param int $id telework campaign identifier
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function activate($id) {
        $this->auth->checkIfOperationIsAllowed('edit_telework_campaign');
        $this->telework_campaign_model->activateTeleworkCampaign($id);
        redirect('teleworkcampaigns');
    } 
    
    /**
     * Deactivate a given telework campaign
     * @param int $id telework campaign identifier
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function deactivate($id) {
        $this->auth->checkIfOperationIsAllowed('edit_telework_campaign');
        $this->telework_campaign_model->deactivateTeleworkCampaign($id);
        redirect('teleworkcampaigns');
    } 
    
    
    /**
     * Ajax endpoint. Result varies according to input :
     *  - try to detect overlapping
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function validate() {
        header("Content-Type: application/json");
        //The above parameters could cause an SQL injection vulnerability due to the non standard
        //SQL query in telework_campaign_model::detectOverlappingCampaigns
        $date = str_replace('/', '-', $this->input->post('startdate', TRUE));
        $d = DateTime::createFromFormat('d-m-Y', $date);
        $startdate = ($d && $d->format('d-m-Y') === $date)?$d->format('Y-m-d'):'1970-01-01';
        $date = str_replace('/', '-', $this->input->post('enddate', TRUE));
        $d = DateTime::createFromFormat('d-m-Y', $date);
        $enddate = ($d && $d->format('d-m-Y') === $date)?$d->format('Y-m-d'):'1970-01-01';
        $campaign_id = $this->input->post('campaign_id', TRUE);
        $campaignValidator = new stdClass;
        if (isset($startdate) && isset($enddate)) {
            $this->load->model('telework_campaign_model');
            if (isset($campaign_id)) {
                $campaignValidator->overlap = $this->telework_campaign_model->detectOverlappingCampaigns($startdate, $enddate, $campaign_id);
            } else {
                $campaignValidator->overlap = $this->telework_campaign_model->detectOverlappingCampaigns($startdate, $enddate);
            }
        }
        //Repeat start and end dates of the telework request
        $campaignValidator->RequestStartDate = $startdate;
        $campaignValidator->RequestEndDate = $enddate;
        
        echo json_encode($campaignValidator);
    }
}
