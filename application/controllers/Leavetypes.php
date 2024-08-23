<?php
/**
 * This controller allows to manage the list of leave types
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class allows to manage the list of leave types
 */
class LeaveTypes extends CI_Controller {

    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {
        parent::__construct();
        setUserContext($this);
        $this->load->model('types_model');
        $this->lang->load('leavetypes', $this->language);
    }

    /**
     * Display the list of leave types
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function index() {
        $this->auth->checkIfOperationIsAllowed('leavetypes_list');
        $data = getUserContext($this);
        $data['leavetypes'] = $this->types_model->getTypes();
        $data['title'] = lang('leavetypes_type_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_edit_leave_type');
        $data['flash_partial_view'] = $this->load->view('templates/flash', $data, TRUE);
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('leavetypes/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Display a form that allows adding a leave type
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function create() {
        $this->auth->checkIfOperationIsAllowed('leavetypes_create');
        $data = getUserContext($this);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['title'] = lang('leavetypes_popup_create_title');
        $data['leavetypes'] = $this->types_model->getTypes();

        $this->form_validation->set_rules('name', lang('leavetypes_popup_create_field_name'), 'required|strip_tags');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('leavetypes/create', $data);
        } else {
            $this->types_model->setTypes();
            $this->session->set_flashdata('msg', lang('leavetypes_popup_create_flash_msg'));
            redirect('leavetypes');
        }
    }

    /**
     * Display a form that allows editing a leave type
     * @param int $id Identitier of the leave type
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function edit($id) {
        $this->auth->checkIfOperationIsAllowed('leavetypes_edit');
        $data = getUserContext($this);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['title'] = lang('leavetypes_popup_update_title');
        $data['id'] = $id;
        $data['leavetypes'] = $this->types_model->getTypes();
        $data['leavetype'] = $this->types_model->getTypes($id);

        $this->form_validation->set_rules('name', lang('leavetypes_popup_update_field_name'), 'required|strip_tags');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('leavetypes/edit', $data);
        } else {
            $this->types_model->updateTypes($id,
                    $this->input->post('name'),
                    $this->input->post('deduct_days_off'),
                    mb_substr($this->input->post('acronym'), 0, 10));
            $this->session->set_flashdata('msg', lang('leavetypes_popup_update_flash_msg'));
            redirect('leavetypes');
        }
    }

    /**
     * Action : delete a leave type
     * @param int $id leave type identifier
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function delete($id) {
        $this->auth->checkIfOperationIsAllowed('leavetypes_delete');
        if ($id != 0) {
            if ($this->types_model->usage($id) > 0) {
                $this->session->set_flashdata('msg', lang('leavetypes_popup_delete_flash_forbidden'));
            } else {
                $this->types_model->deleteType($id);
                $this->session->set_flashdata('msg', lang('leavetypes_popup_delete_flash_msg'));
            }
        } else {
            $this->session->set_flashdata('msg', lang('leavetypes_popup_delete_flash_error'));
        }
        redirect('leavetypes');
    }

    /**
     * Action: export the list of all leave types into an Excel file
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function export() {
        $this->auth->checkIfOperationIsAllowed('leavetypes_export');
        $this->load->view('leavetypes/export');
    }
}
