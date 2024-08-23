<?php
/**
 * This controller allows to manage the time organisations
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class allows to manage the time organisations.
 */
class TimeOrganisations extends CI_Controller {

    /**
     * Default constructor
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function __construct() {
        parent::__construct();
        setUserContext($this);
        $this->lang->load('timeorganisations', $this->language);
        $this->load->model('time_organisation_model');
    }

    /**
     * Display the list of all time organisations defined in the system
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function index() {
        $this->auth->checkIfOperationIsAllowed('list_time_organisations');
        $this->lang->load('datatable', $this->language);
        $data = getUserContext($this);
        $this->lang->load('calendar_lang', $this->language);
        $data['title'] = lang('time_organisation_index_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_time_organisations_list');
        $data['timeorganisations'] = $this->time_organisation_model->getTimeOrganisations();
        $data['flash_partial_view'] = $this->load->view('templates/flash', $data, TRUE);
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('timeorganisations/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Display a form that allows to update a time organisation
     * @param int $id time organisation identifier
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function edit($id) {
        $this->auth->checkIfOperationIsAllowed('edit_time_organisation');
        $data = getUserContext($this);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->lang->load('calendar_lang', $this->language);
        $data['title'] = lang('time_organisation_edit_title');
        $this->load->model('users_model');
        $data['employees'] = $this->users_model->getAllEmployeesSortedByFirstname();

        $this->form_validation->set_rules('employee', lang('time_organisation_edit_field_employee'), 'required|strip_tags');
        $this->form_validation->set_rules('duration', lang('time_organisation_edit_field_duration'), 'required|strip_tags');
        $this->form_validation->set_rules('day', lang('time_organisation_edit_field_day'), 'required|strip_tags');
        $this->form_validation->set_rules('daytype', lang('time_organisation_edit_field_daytype'), 'required|strip_tags');
        $this->form_validation->set_rules('recurrence', lang('time_organisation_edit_field_recurrence'), 'required|strip_tags');

        $data['timeorganisation'] = $this->time_organisation_model->getTimeOrganisations($id);
        if (empty($data['timeorganisation'])) {
            redirect('notfound');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('timeorganisations/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $this->time_organisation_model->updateTimeOrganisation($id);
            $this->session->set_flashdata('msg', lang('time_organisation_edit_msg_success'));
            redirect('timeorganisations');
        }
    }

    /**
     * Display the form / action Create a new time organisation
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function create() {
        $this->auth->checkIfOperationIsAllowed('create_time_organisation');
        $data = getUserContext($this);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->lang->load('calendar_lang', $this->language);
        $data['title'] = lang('time_organisation_create_title');
        $this->load->model('users_model');
        $data['employees'] = $this->users_model->getAllEmployeesSortedByFirstname();

        $this->form_validation->set_rules('employee', lang('time_organisation_create_field_employee'), 'required|strip_tags');
        $this->form_validation->set_rules('duration', lang('time_organisation_create_field_duration'), 'required|strip_tags');
        $this->form_validation->set_rules('day', lang('time_organisation_create_field_day'), 'required|strip_tags');
        $this->form_validation->set_rules('daytype', lang('time_organisation_create_field_daytype'), 'required|strip_tags');
        $this->form_validation->set_rules('recurrence', lang('time_organisation_create_field_recurrence'), 'required|strip_tags');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('timeorganisations/create', $data);
            $this->load->view('templates/footer');
        } else {
            $this->time_organisation_model->setTimeOrganisations();
            $this->session->set_flashdata('msg', lang('time_organisation_create_msg_success'));
            redirect('timeorganisations');
        }
    }

    /**
     * Delete a given time organisation
     * @param int $id time organisation identifier
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function delete($id) {
        $this->auth->checkIfOperationIsAllowed('delete_time_organisation');
        //Test if the time organisation exists
        $data['timeorganisation'] = $this->time_organisation_model->getTimeOrganisations($id);
        if (empty($data['timeorganisation'])) {
            redirect('notfound');
        } else {
            $this->time_organisation_model->deleteTimeOrganisation($id);
        }
        $this->session->set_flashdata('msg', lang('time_organisation_delete_msg_success'));
        redirect('timeorganisations');
    }   
    
    /**
     * Import the list of time organisations
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function import() {
        $this->auth->checkIfOperationIsAllowed('import_time_organisations');
        $data = getUserContext($this);
        $this->load->helper('url', 'form');
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->upload->set_upload_path('./local/upload/timeorganisations/');
        $this->upload->set_allowed_types(array(
            'xls',
            'xlsx'
        ));
        $data['title'] = lang('time_organisation_import_title');       

        // If upload failed, display error
        if (! $this->upload->do_upload('file')) {
            $this->session->set_flashdata('success', $this->upload->display_errors());
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('timeorganisations/import', $data);
            $this->load->view('templates/footer');
        } else {
            if ($this->upload->data('file_name') != null) {
                $file = $this->upload->data('file_name');
                $path = $this->upload->data('file_path');
                $object = \PhpOffice\PhpSpreadsheet\IOFactory::load($path . $file);

                foreach ($object->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow();
                    for ($row = 2; $row <= $highestRow; $row ++) {
                        $data = array(
                            'employee' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                            'duration' => str_replace(',', '.', $worksheet->getCellByColumnAndRow(4, $row)->getValue()),
                            'day' => $worksheet->getCellByColumnAndRow(5, $row)->getValue(),
                            'daytype' => $worksheet->getCellByColumnAndRow(6, $row)->getValue(),                            
                            'recurrence' => $worksheet->getCellByColumnAndRow(7, $row)->getValue()
                        );
                        $this->time_organisation_model->setTimeOrganisationsFromImport($data);
                    }
                }
            }

            $this->session->set_flashdata('msg', lang('time_organisation_import_msg_success'));
            redirect('timeorganisations');
        }     
    } 
    
    /**
     * Export the list of time organisations
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function export() {
        $this->auth->checkIfOperationIsAllowed('export_time_organisations');
        $this->load->view('timeorganisations/export');
    } 
}
