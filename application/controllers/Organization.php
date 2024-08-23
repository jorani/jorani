<?php
/**
 * This controller contains the actions allowing to manage and display the organization tree
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.2.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class allows to manage the organization of of users. Users can be attached to a node of a tree.
 * These nodes are called 'entities' and can be 'departments' or 'sub-departments', 'groups', etc.
 * It allows to use filters on a part of your structure, whatever your organization is.
 */
class Organization extends CI_Controller {

    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {
        parent::__construct();
        //This controller differs from the others, because some endpoints can be public
        //when they are used by public calendars
    }

    /**
     * Main view that allows to describe the entities of the organization
     * And to attach employees to entities (lot of Ajax callbacks)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function index() {
        setUserContext($this);
        $this->auth->checkIfOperationIsAllowed('organization_index');
        $data = getUserContext($this);
        $this->lang->load('organization', $this->language);
        $this->lang->load('datatable', $this->language);
        $this->lang->load('treeview', $this->language);
        $data['title'] = lang('organization_index_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_hr_organization');
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('organization/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Pop-up showing the tree of the organization and allowing a
     * user to choose an entity (filter of a report or a calendar)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function select() {
        if (($this->config->item('public_calendar') === TRUE) && (!$this->session->userdata('logged_in'))) {
            $this->load->library('polyglot');
            $data['language'] = $this->config->item('language');
            $data['language_code'] = $this->polyglot->language2code($data['language']);
            $this->lang->load('organization', $data['language']);
            $this->lang->load('treeview', $data['language']);
            $data['help'] = '';
            $data['logged_in'] = FALSE;
            $this->load->view('organization/select', $data);
        } else {
            setUserContext($this);
            $this->auth->checkIfOperationIsAllowed('organization_select');
            $data = getUserContext($this);
            $this->lang->load('organization', $this->language);
            $this->lang->load('treeview', $this->language);
            $this->load->view('organization/select', $data);
        }
    }

    /**
     * Ajax endpoint: Rename an entity of the organization
     * takes parameters by GET
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function rename() {
        header("Content-Type: application/json");
        setUserContext($this);
        if ($this->auth->isAllowed('edit_organization') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $id = $this->input->get('id', TRUE);
            $text = sanitize($this->input->get('text', TRUE));
            $this->load->model('organization_model');
            $this->organization_model->rename($id, $text);
        }
    }

    /**
     * Ajax endpoint: Create an entity in the organization
     * takes parameters by GET
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function create() {
        header("Content-Type: application/json");
        setUserContext($this);
        if ($this->auth->isAllowed('edit_organization') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $id = $this->input->get('id', TRUE);
            $text = sanitize($this->input->get('text', TRUE));
            $this->load->model('organization_model');
            $this->organization_model->create($id, $text);
        }
    }

    /**
     * Ajax endpoint: Move an entity into the organization
     * takes parameters by GET
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function move() {
        header("Content-Type: application/json");
        setUserContext($this);
        if ($this->auth->isAllowed('edit_organization') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $id = $this->input->get('id', TRUE);
            $parent = $this->input->get('parent', TRUE);
            $this->load->model('organization_model');
            $this->organization_model->move($id, $parent);
        }
    }

    /**
     * Ajax endpoint: Copy an entity into the organization
     * takes parameters by GET
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function copy() {
        header("Content-Type: application/json");
        setUserContext($this);
        if ($this->auth->isAllowed('edit_organization') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $id = $this->input->get('id', TRUE);
            $parent = $this->input->get('parent', TRUE);
            $this->load->model('organization_model');
            $this->organization_model->copy($id, $parent);
        }
    }

    /**
     * Ajax endpoint: Returns the list of the employees attached to an entity
     * Prints the table content in a JSON format expected by jQuery Datatable
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function employees() {
        setUserContext($this);
        $id = $this->input->get('id', TRUE);
        $this->load->model('organization_model');
        $employees = $this->organization_model->employees($id)->result();
        
        //Prepare an object that will be encoded in JSON
        $msg = new \stdClass();
        $msg->draw = 1;
        $msg->recordsTotal = count($employees);
        $msg->recordsFiltered = count($employees);
        $msg->data = array();

        foreach ($employees as $employee) {
            $row = new \stdClass();
            $row->id = $employee->id;
            $row->firstname = $employee->firstname;
            $row->lastname = $employee->lastname;
            $row->email = $employee->email;
            $msg->data[] = $row;
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($msg));
    }
    
    /**
     * Ajax endpoint: Add an employee to an entity of the organization
     * takes parameters by GET
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function addemployee() {
        header("Content-Type: application/json");
        setUserContext($this);
        if ($this->auth->isAllowed('edit_organization') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $id = $this->input->get('user', TRUE);
            $entity = $this->input->get('entity', TRUE);
            $this->load->model('organization_model');
            echo json_encode($this->organization_model->attachEmployee($id, $entity));
        }
    }

    /**
     * Ajax endpoint: Add an employee to an entity of the organization
     * takes parameters by GET
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function delemployee() {
        header("Content-Type: application/json");
        setUserContext($this);
        if ($this->auth->isAllowed('edit_organization') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $id = $this->input->get('user', TRUE);
            $this->load->model('organization_model');
            echo json_encode($this->organization_model->detachEmployee($id));
        }
    }

    /**
     * Ajax endpoint: Cascade delete children and set employees' org to NULL
     * takes parameters by GET
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function delete() {
        header("Content-Type: application/json");
        setUserContext($this);
        if ($this->auth->isAllowed('edit_organization') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $entity = $this->input->get('entity', TRUE);
            $this->load->model('organization_model');
            echo json_encode($this->organization_model->delete($entity));
        }
    }

    /**
     * Ajax endpoint: Returns a JSON string describing the organization structure.
     * In a format expected by jsTree component.
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function root() {
        header("Content-Type: application/json");
        if (($this->config->item('public_calendar') === TRUE) && (!$this->session->userdata('logged_in'))) {
            //nop
        } else {
            setUserContext($this);
            $this->auth->checkIfOperationIsAllowed('organization_select');
        }

        $id = $this->input->get('id', TRUE);
        if ($id == "#") {
            unset($id);
        }
        $this->load->model('organization_model');
        $entities = $this->organization_model->getAllEntities();
        $msg = '[';
        foreach ($entities->result() as $entity) {
            $msg .= '{"id":"' . $entity->id . '",';
            if ($entity->parent_id == -1) {
                $msg .= '"parent":"#",';
            } else {
                $msg .= '"parent":"' . $entity->parent_id . '",';
            }
            $msg .= '"text":"' . $entity->name . '"';
            $msg .= '},';
        }
        $msg = rtrim($msg, ",");
        $msg .= ']';
        echo $msg;
    }

    /**
     * Ajax endpoint:Returns the supervisor of an entity of the organization
     * (string containing an id)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getsupervisor() {
        header("Content-Type: application/json");
        setUserContext($this);
        $entity = $this->input->get('entity', TRUE);
        if (isset($entity)) {
            $this->load->model('organization_model');
            echo json_encode($this->organization_model->getSupervisor($entity));
        } else {
            $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
        }
    }

    /**
     * Ajax endpoint: Select the supervisor of an entity of the organization
     * takes parameters by GET
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function setsupervisor() {
        header("Content-Type: application/json");
        setUserContext($this);
        if ($this->auth->isAllowed('edit_organization') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            if ($this->input->get('user', TRUE) == "") {
                $id = NULL;
            } else {
                $id = $this->input->get('user', TRUE);
            }
            $entity = $this->input->get('entity', TRUE);
            $this->load->model('organization_model');
            echo json_encode($this->organization_model->setSupervisor($id, $entity));
        }
    }

    /**
     * Modal form allowing to create and manage custom lists of employees
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function listsIndex() {
        $data = getCIUserContext();
        $this->auth->checkIfOperationIsAllowed('organization_lists_index');
        $this->load->model('lists_model');
        $data['lists'] = $this->lists_model->getLists($this->user_id);
        //
        $this->lang->load('organization', $this->language);
        $this->lang->load('datatable', $this->language);
        //TODO remove
        //$data['title'] = 'REMOVE ME !!';
        //$this->load->view('templates/header', $data);
        $this->load->view('organization/lists', $data);
    }

    /**
     * Ajax endpoint allowing to create a new list of employees
     * Return the last inserted ID
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function listsCreate() {
        header("Content-Type: application/json");
        $data = getCIUserContext();
        if ($this->auth->isAllowed('organization_lists_index') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $this->load->model('lists_model');
            $id = (int) $this->lists_model->setLists($this->user_id, $this->input->post('name'));
            echo json_encode($id);
        }

    }

    /**
     * Ajax endpoint allowing to rename a list of employees
     * Return the last inserted ID
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function listsRename() {
        header("Content-Type: application/json");
        $data = getCIUserContext();
        if ($this->auth->isAllowed('organization_lists_index') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $this->load->model('lists_model');
            $this->lists_model->updateLists($this->input->post('id'), $this->input->post('name'));
            echo json_encode("");
        }

    }

    /**
     * Ajax endpoint allowing to delete a list of employees
     * Return the last inserted ID
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function listsDelete() {
        header("Content-Type: application/json");
        $data = getCIUserContext();
        if ($this->auth->isAllowed('organization_lists_index') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $this->load->model('lists_model');
            $id = (int) $this->lists_model->deleteList($this->input->post('id'));
            echo json_encode("");
        }
    }

    /**
     * Ajax endpoint: load the list of employees attached to a given list id
     * Format the data as expected by JQuery Datatable 1.10
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function listsEmployees() {
        header("Content-Type: application/json");
        $data = getCIUserContext();
        if ($this->auth->isAllowed('organization_lists_index') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $this->load->model('lists_model');
            $obj = new stdClass;
            $employees = $this->lists_model->getListOfEmployees($this->input->get('list'));
            $msg = '{"draw": 1,';
            $msg .= '"recordsTotal":' . count($employees);
            $msg .= ',"recordsFiltered":' . count($employees);
            $msg .= ',"data":[';
            foreach ($employees as $employee) {
                $msg .= '{"DT_RowId":"' . $employee['id'] . '",';
                $msg .= '"id":"' . $employee['id'] . '",';
                $msg .= '"firstname":"' . $employee['firstname'] . '",';
                $msg .= '"lastname":"' . $employee['lastname'] . '",';
                $msg .= '"entity":"' . $employee['entity'] . '"';
                $msg .= '},';
            }
            $msg = rtrim($msg, ",");
            $msg .= ']}';
            echo $msg;
        }
    }

    /**
     * Ajax endpoint allowing to add a list of employees into a list
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function listsAddEmployees() {
        header("Content-Type: application/json");
        $data = getCIUserContext();
        if ($this->auth->isAllowed('organization_lists_index') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $this->load->model('lists_model');
            $listId = $this->input->post('list');
            $employees = json_decode($this->input->post('employees'));
            $this->lists_model->addEmployees($listId, $employees);
            echo json_encode("");
        }
    }

    /**
     * Ajax endpoint allowing to remove a list of employees from a list
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function listsRemoveEmployees() {
        header("Content-Type: application/json");
        $data = getCIUserContext();
        if ($this->auth->isAllowed('organization_lists_index') == FALSE) {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        } else {
            $this->load->model('lists_model');
            $listId = $this->input->post('id');
            $employees = json_decode($this->input->post('employees'));
            $this->lists_model->removeEmployees($listId, $employees);
            echo json_encode("");
        }
    }

    /**
    * Ajax endpoint allowing to remove a list of employees from a list
    * @author Benjamin BALET <benjamin.balet@gmail.com>
    */
    public function listsReorder() {
      header("Content-Type: application/json");
      $data = getCIUserContext();
      if ($this->auth->isAllowed('organization_lists_index') == FALSE) {
          $this->output->set_header("HTTP/1.1 403 Forbidden");
      } else {
          $this->load->model('lists_model');
          $listId = $this->input->post('id');
          $mooves = json_decode($this->input->post('moves'));
          $this->lists_model->reorderListEmployees($listId, $mooves);
          echo json_encode("");
      }
    }

    /**
    * Ajax endpoint retrieving the name of a list
    * @author Emilien NICOLAS <milihhard1996@gmail.com>
    */
    public function listName(){
      header("Content-Type: application/json");
      $data = getCIUserContext();
      if ($this->auth->isAllowed('organization_lists_index') == FALSE) {
          $this->output->set_header("HTTP/1.1 403 Forbidden");
      } else {
          $this->load->model('lists_model');
          $listId = $this->input->post('id');
          $this->lists_model->getName($listId);
          $list = new stdClass();
          $list->id=$listId;
          $list->name = $this->lists_model->getName($listId);
          echo json_encode($list);
      }
    }
}
