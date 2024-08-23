<?php
/**
 * This controller allows to manage the telework rules
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class allows to manage the telework rules.
 */
class TeleworkRules extends CI_Controller {

    /**
     * Default constructor
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function __construct() {
        parent::__construct();
        setUserContext($this);
        $this->lang->load('teleworkrules', $this->language);
        $this->load->model('telework_rule_model');
    }

    /**
     * Display the list of all telework rules defined in the system
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function index() {
        $this->auth->checkIfOperationIsAllowed('list_telework_rules');
        $this->lang->load('datatable', $this->language);
        $data = getUserContext($this);
        $data['title'] = lang('telework_rule_index_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_telework_rule_list');
        $data['rules'] = $this->telework_rule_model->getTeleworkRules();
        $data['flash_partial_view'] = $this->load->view('templates/flash', $data, TRUE);
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('teleworkrules/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Display a form that allows to update a telework rule
     * @param int $id telework rule identifier
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function edit($id) {
        $this->auth->checkIfOperationIsAllowed('edit_telework_rule');
        $data = getUserContext($this);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['title'] = lang('telework_rule_edit_title');
        $this->load->model('organization_model');
        $data['organizations'] = $this->organization_model->getAllEntities()->result();

        $this->form_validation->set_rules('organization', lang('telework_rule_edit_field_organization'), 'required|strip_tags');
        $this->form_validation->set_rules('limit', lang('telework_rule_edit_field_limit'), 'required|strip_tags');
        $this->form_validation->set_rules('delay', lang('telework_rule_edit_field_delay'), 'required|strip_tags');

        $data['rule'] = $this->telework_rule_model->getTeleworkRules($id);
        if (empty($data['rule'])) {
            redirect('notfound');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('teleworkrules/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $this->telework_rule_model->updateTeleworkRule($id);
            $this->session->set_flashdata('msg', lang('telework_rule_edit_msg_success'));
            redirect('teleworkrules');
        }
    }

    /**
     * Display the form / action Create a new telework rule
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function create() {
        $this->auth->checkIfOperationIsAllowed('create_telework_rule');
        $data = getUserContext($this);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['title'] = lang('telework_rule_create_title');
        $this->load->model('organization_model');
        $data['organizations'] = $this->organization_model->getAllEntities()->result();

        $this->form_validation->set_rules('organization', lang('telework_rule_create_field_organization'), 'required|strip_tags');
        $this->form_validation->set_rules('limit', lang('telework_rule_create_field_limit'), 'required|strip_tags');
        $this->form_validation->set_rules('delay', lang('telework_rule_edit_field_delay'), 'required|strip_tags');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('teleworkrules/create', $data);
            $this->load->view('templates/footer');
        } else {
            $this->telework_rule_model->setTeleworkRules();
            $this->session->set_flashdata('msg', lang('telework_rule_create_msg_success'));
            redirect('teleworkrules');
        }
    }

    /**
     * Delete a given telework rule
     * @param int $id telework rule identifier
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function delete($id) {
        $this->auth->checkIfOperationIsAllowed('delete_telework_rule');
        //Test if the telework rule exists
        $data['rules'] = $this->telework_rule_model->getTeleworkRules($id);
        if (empty($data['rules'])) {
            redirect('notfound');
        } else {
            $this->telework_rule_model->deleteTeleworkRule($id);
        }
        $this->session->set_flashdata('msg', lang('telework_rule_delete_msg_success'));
        redirect('teleworkrules');
    }   
    
    /**
     * Import the list of telework rules
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function import() {
        $this->auth->checkIfOperationIsAllowed('import_telework_rules');
        $data = getUserContext($this);
        $this->load->helper('url', 'form');
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->upload->set_upload_path('./local/upload/teleworkrules/');
        $this->upload->set_allowed_types(array(
            'xls',
            'xlsx'
        ));
        $data['title'] = lang('telework_rule_import_title');
        
        // If upload failed, display error
        if (! $this->upload->do_upload('file')) {
            $this->session->set_flashdata('success', $this->upload->display_errors());
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('teleworkrules/import', $data);
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
                            'organization' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                            'limit' => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
                            'delay' => $worksheet->getCellByColumnAndRow(4, $row)->getValue()
                        );
                        $this->telework_rule_model->setTeleworkRulesFromImport($data);
                    }
                }
            }

            $this->session->set_flashdata('msg', lang('telework_rule_import_msg_success'));
            redirect('teleworkrules');
        }
    }
    
    /**
     * Export the list of telework rules
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function export() {
        $this->auth->checkIfOperationIsAllowed('export_telework_rules');
        $this->load->view('teleworkrules/export');
    } 
}
