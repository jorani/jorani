<?php
/**
 * This controller displays the calendars of the leave requests
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class displays the calendars of the leave requests.
 * In opposition to the other pages of the application, some calendars can be public (no need to be logged in).
 */
class Calendar extends CI_Controller {
    
    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {
        parent::__construct();
        //This controller differs from the others, because some calendars can be public
    }
    
    /**
     * Display a yearly individual calendar
     * @param int $id identifier of the employee
     * @param int $year Year number
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function year($employee = 0, $year = 0) {
        setUserContext($this);
        $this->lang->load('calendar', $this->language);
        if ($employee == 0 || $employee == $this->user_id) {
            $this->auth->checkIfOperationIsAllowed('individual_calendar');
        } else {
            $this->auth->checkIfOperationIsAllowed('organization_calendar');
        }
        $data = getUserContext($this);
        $this->load->model('users_model');
        $user = $this->users_model->getUsers($employee);
        if ($year==0) $year = date("Y");
        //Either self access, Manager or HR
        if ($employee == 0) {
            $employee = $this->user_id;
            $user = $this->users_model->getUsers($employee);
        } else {
            if (!$this->is_hr) {
                if ($this->user_id != $user['manager']) {
                    $employee = $this->user_id;
                    $user = $this->users_model->getUsers($employee);
                }
            }
        }
        
        $data['employee_id'] = $employee;
        $data['employee_name'] =  $user['firstname'] . ' ' . $user['lastname'];
        //Load the leaves for all the months of the selected year
        $this->load->model('leaves_model');
        $months = array(
            lang('January') => $this->leaves_model->linear($employee, 1, $year, TRUE, TRUE, TRUE, TRUE),
            lang('February') => $this->leaves_model->linear($employee, 2, $year, TRUE, TRUE, TRUE, TRUE),
            lang('March') => $this->leaves_model->linear($employee, 3, $year, TRUE, TRUE, TRUE, TRUE),
            lang('April') => $this->leaves_model->linear($employee, 4, $year, TRUE, TRUE, TRUE, TRUE),
            lang('May') => $this->leaves_model->linear($employee, 5, $year, TRUE, TRUE, TRUE, TRUE),
            lang('June') => $this->leaves_model->linear($employee, 6, $year, TRUE, TRUE, TRUE, TRUE),
            lang('July') => $this->leaves_model->linear($employee, 7, $year, TRUE, TRUE, TRUE, TRUE),
            lang('August') => $this->leaves_model->linear($employee, 8, $year, TRUE, TRUE, TRUE, TRUE),
            lang('September') => $this->leaves_model->linear($employee, 9, $year, TRUE, TRUE, TRUE, TRUE),
            lang('October') => $this->leaves_model->linear($employee, 10, $year, TRUE, TRUE, TRUE, TRUE),
            lang('November') => $this->leaves_model->linear($employee, 11, $year, TRUE, TRUE, TRUE, TRUE),
            lang('December') => $this->leaves_model->linear($employee, 12, $year, TRUE, TRUE, TRUE, TRUE),
        );
        if ($this->config->item('disable_telework') === FALSE) {
            foreach ($months as $month) {
                $this->leaves_model->removeOverlappingTeleworks($month);
            }
        }
        $data['months'] = $months;
        $data['year'] = $year;
        $data['title'] = lang('calendar_year_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_calendar_yearly');
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('calendar/year', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Display the page of the individual calendar (of the connected user)
     * Data (calendar events) is retrieved by AJAX from leaves' controller
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function individual() {
        setUserContext($this);
        $this->lang->load('calendar', $this->language);
        $this->auth->checkIfOperationIsAllowed('individual_calendar');
        $data = getUserContext($this);
        $data['title'] = lang('calendar_individual_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_calendar_individual');
        $data['googleApi'] = FALSE;
        $data['clientId'] = 'key';
        $data['apiKey'] = 'key';
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('calendar/individual', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Display the page of the team calendar (users having the same manager
     * than the connected user)
     * Data (calendar events) is retrieved by AJAX from leaves' controller
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function workmates() {
        setUserContext($this);
        $this->lang->load('calendar', $this->language);
        $this->auth->checkIfOperationIsAllowed('workmates_calendar');
        $data = getUserContext($this);
        $data['title'] = lang('calendar_workmates_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_calendar_workmates');
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('calendar/workmates', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Display the calendar of the employees managed by the connected user
     * Data (calendar events) is retrieved by AJAX from leaves' controller
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function collaborators() {
        setUserContext($this);
        $this->lang->load('calendar', $this->language);
        $this->auth->checkIfOperationIsAllowed('collaborators_calendar');
        $data = getUserContext($this);
        $data['title'] = lang('calendar_collaborators_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_calendar_collaborators');
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('calendar/collaborators', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Display the calendar of the employees working in the same department
     * than the connected user.
     * Data (calendar events) is retrieved by AJAX from leaves' controller
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function department() {
        setUserContext($this);
        $this->lang->load('calendar', $this->language);
        $this->auth->checkIfOperationIsAllowed('department_calendar');
        $data = getUserContext($this);
        $data['title'] = lang('calendar_department_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_calendar_department');
        $this->load->model('organization_model');
        $department = $this->organization_model->getDepartment($this->user_id);
        if (empty($department)) {
            $this->session->set_flashdata('msg', lang('calendar_department_msg_error'));
            redirect('leaves');
        } else {
            $data['department'] = $department->name;
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('calendar/department', $data);
            $this->load->view('templates/footer');
        }
    }
    
    /**
     * Display a global calendar filtered by organization/entity
     * Data (calendar events) is retrieved by AJAX from leaves' controller
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function organization() {
        $month = date('m');
        //When the user uses the calendar for the first time, select the root entity
        $this->load->model('organization_model');
        if (($this->config->item('public_calendar') === TRUE) && (!$this->session->userdata('logged_in'))) {
            $this->load->library('polyglot');;
            $data['language'] = $this->config->item('language');
            $data['language_code'] =  $this->polyglot->language2code($data['language']);
            $data['title'] = lang('calendar_organization_title');
            $data['help'] = '';
            $data['mode'] = 'public';
            $data['logged_in'] = FALSE;
            $data['month'] = $month;
            $data['year'] = date('Y');
            $dateObj = DateTime::createFromFormat('!m', $month);
            $data['monthName'] = lang($dateObj->format('F'));
            $data['departmentName'] = $this->organization_model->getName(0);
            $this->lang->load('calendar', $data['language']);
            $this->load->view('templates/header', $data);
            $this->load->view('calendar/organization', $data);
            $this->load->view('templates/footer_simple');
        } else {
            setUserContext($this);
            $this->lang->load('calendar', $this->language);
            $this->auth->checkIfOperationIsAllowed('organization_calendar');
            $data = getUserContext($this);
            $data['mode'] = 'connected';
            $data['logged_in'] = TRUE;
            $data['month'] = $month;
            $data['year'] = date('Y');
            $dateObj = DateTime::createFromFormat('!m', $month);
            $data['monthName'] = lang($dateObj->format('F'));
            $data['title'] = lang('calendar_organization_title');
            $data['help'] = $this->help->create_help_link('global_link_doc_page_calendar_organization');
            $data['departmentName'] = $this->organization_model->getName(0);
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('calendar/organization', $data);
            $this->load->view('templates/footer');
        }
    }
    
    /**
     * Ajax endpoint : Send a list of fullcalendar events
     * This code is duplicated from controller/leaves for public access
     * @param int $entity_id Entity identifier
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function publicOrganization($entity_id) {
        $this->output->set_content_type('application/json');
        if ($this->config->item('public_calendar') === TRUE) {
            $this->load->model('leaves_model');
            $start = $this->input->get('start', TRUE);
            $end = $this->input->get('end', TRUE);
            $children = filter_var($this->input->get('children', TRUE), FILTER_VALIDATE_BOOLEAN);
            $this->output->set_output($this->leaves_model->department($entity_id, $start, $end, $children));
        } else {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        }
    }
    
    /**
     * Ajax endpoint : Send a list of fullcalendar events: List of all possible day offs
     * This code is duplicated from controller/contract for public access
     * @param int $entity_id Entity identifier
     */
    public function publicDayoffs() {
        $this->output->set_content_type('application/json');
        if ($this->config->item('public_calendar') === TRUE) {
            $start = $this->input->get('start', TRUE);
            $end = $this->input->get('end', TRUE);
            $entity = $this->input->get('entity', TRUE);
            $children = filter_var($this->input->get('children', TRUE), FILTER_VALIDATE_BOOLEAN);
            $this->load->model('dayoffs_model');
            $this->output->set_output($this->dayoffs_model->allDayoffs($start, $end, $entity, $children));
        } else {
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        }
    }
    
    /**
     * Display a global tabular calendar
     * @param int $id identifier of the entity
     * @param int $month Month number
     * @param int $year Year number
     * @param bool $children If TRUE, includes children entity, FALSE otherwise
     * @param bool $displayTypes If TRUE, display leave types, FALSE otherwise
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function tabular($id=-1, $month=0, $year=0, $children=TRUE, $displayTypes=TRUE) {
        if (($this->config->item('public_calendar') === TRUE) && (!$this->session->userdata('logged_in'))) {
            $this->load->library('polyglot');
            $data['mode'] = 'public';
            $data['language'] = $this->config->item('language');
            $data['language_code'] =  $this->polyglot->language2code($data['language']);
            $this->lang->load('global', $data['language']);
            $this->lang->load('calendar', $data['language']);
            $this->load->model('leaves_model');
            $this->load->model('organization_model');
            $data['tabular'] = $this->leaves_model->tabular($id, $month, $year, $children);
            $data['entity'] = $id;
            $data['month'] = $month;
            $data['year'] = $year;
            $dateObj = DateTime::createFromFormat('!m', $month);
            $data['monthName'] = lang($dateObj->format('F'));
            $data['children'] = $children;
            $data['displayTypes'] = $displayTypes;
            $data['department'] = $this->organization_model->getName($id);
            $data['title'] = lang('calendar_tabular_title');
            $data['help'] = '';
            if ($this->config->item('tabular_order_by_organization') === FALSE)
                $data['tabularPartialView'] = $this->load->view('calendar/tabular_partial', $data, TRUE);
            else
                $data['tabularPartialView'] = $this->load->view('calendar/department_tabular_partial', $data, TRUE);
                $this->load->view('templates/header', $data);
                $this->load->view('calendar/tabular', $data);
                $this->load->view('templates/footer_simple');
        } else {
            setUserContext($this);
            $this->lang->load('global', $this->language);
            $this->lang->load('calendar', $this->language);
            $this->auth->checkIfOperationIsAllowed('organization_calendar');
            $data = getUserContext($this);
            $this->load->model('leaves_model');
            $this->load->model('organization_model');
            $data['mode'] = 'connected';
            $data['tabular'] = $this->leaves_model->tabular($id, $month, $year, $children);
            $data['entity'] = $id;
            $data['month'] = $month;
            $data['year'] = $year;
            $dateObj = DateTime::createFromFormat('!m', $month);
            $data['monthName'] = lang($dateObj->format('F'));
            $data['children'] = $children;
            $data['displayTypes'] = $displayTypes;
            $data['department'] = $this->organization_model->getName($id);
            $data['title'] = lang('calendar_tabular_title');
            $data['help'] = $this->help->create_help_link('global_link_doc_page_calendar_tabular');
            if ($this->config->item('tabular_order_by_organization') === FALSE)
                $data['tabularPartialView'] = $this->load->view('calendar/tabular_partial', $data, TRUE);
            else
                $data['tabularPartialView'] = $this->load->view('calendar/department_tabular_partial', $data, TRUE);
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('calendar/tabular', $data);
            $this->load->view('templates/footer');
        }
    }
    
    /**
     * Display a partial tabular widget. Used to reload only a part of the page.
     * @param int $id identifier of the entity
     * @param int $month Month number
     * @param int $year Year number
     * @param bool $children If TRUE, includes children entity, FALSE otherwise
     * @param bool $displayTypes If TRUE, display leave types, FALSE otherwise
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function tabularPartial($id=-1, $month=0, $year=0, $children=TRUE, $displayTypes=TRUE) {
        if (($this->config->item('public_calendar') === TRUE) && (!$this->session->userdata('logged_in'))) {
            $this->load->library('polyglot');
            $data['mode'] = 'public';
            $data['language'] = $this->config->item('language');
            $data['language_code'] =  $this->polyglot->language2code($data['language']);
            $this->lang->load('global', $data['language']);
            $this->lang->load('calendar', $data['language']);
            $this->load->model('leaves_model');
            $statuses = $this->input->get('statuses');
            $data['tabular'] = $this->leaves_model->tabular($id, $month, $year, $children, $statuses);
            $data['entity'] = $id;
            $data['month'] = $month;
            $data['year'] = $year;
            $data['children'] = $children;
            $data['displayTypes'] = $displayTypes;
            if ($this->config->item('tabular_order_by_organization') === FALSE)
                $this->load->view('calendar/tabular_partial', $data);
            else
                $this->load->view('calendar/department_tabular_partial', $data);
        } else {
            setUserContext($this);
            $this->lang->load('global', $this->language);
            $this->lang->load('calendar', $this->language);
            $this->auth->checkIfOperationIsAllowed('organization_calendar');
            $data = getUserContext($this);
            $this->load->model('leaves_model');
            $data['mode'] = 'connected';
            $statuses = $this->input->get('statuses');
            $data['tabular'] = $this->leaves_model->tabular($id, $month, $year, $children, $statuses, TRUE);
            $data['entity'] = $id;
            $data['month'] = $month;
            $data['year'] = $year;
            $data['children'] = $children;
            $data['displayTypes'] = $displayTypes;
            if ($this->config->item('tabular_order_by_organization') === FALSE)
                $this->load->view('calendar/tabular_partial', $data);
            else
                $this->load->view('calendar/department_tabular_partial', $data);
        }
    }
    
    /**
     * Display a partial tabular widget. Used to reload only a part of the page.
     * The difference with tabularPartial function is that this view is not available
     * in public mode and that the list of employees is taken from a custom list
     * built by the user.
     * @param int $id identifier of the list
     * @param int $month Month number
     * @param int $year Year number
     * @param bool $displayTypes If TRUE, display leave types, FALSE otherwise
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function tabularPartialFromList($id, $month=0, $year=0, $displayTypes=TRUE) {
        setUserContext($this);
        $this->lang->load('global', $this->language);
        $this->lang->load('calendar', $this->language);
        $this->auth->checkIfOperationIsAllowed('organization_calendar');
        $data = getUserContext($this);
        $this->load->model('leaves_model');
        $data['mode'] = 'connected';
        $statuses = $this->input->get('statuses');
        $data['tabular'] = $this->leaves_model->tabularList($id, $month, $year, $statuses);
        $data['entity'] = $id;
        $data['month'] = $month;
        $data['year'] = $year;
        $data['children'] = FALSE;  //For compatibility
        $data['displayTypes'] = $displayTypes;
        $this->load->view('calendar/tabular_partial', $data);
    }
    
    /**
     * Export the tabular calendar into Excel. The presentation differs a bit according to the limitation of Excel
     * We'll get one line for the morning and one line for the afternoon
     * @param int $id identifier of the list
     * @param int $month Month number
     * @param int $year Year number
     * @param bool $displayTypes If TRUE, display leave types, FALSE otherwise
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function exportTabularFromList($id=-1, $month=0, $year=0, $displayTypes=TRUE) {
        $data = array();
        setUserContext($this);
        $language = $this->language;
        $data = getUserContext($this);
        $this->lang->load('calendar', $language);
        $this->lang->load('global', $language);
        $this->load->model('lists_model');
        $this->load->model('leaves_model');
        $data['id'] = $id;
        $data['entityName'] = $this->lists_model->getName($id);
        $data['month'] = $month;
        $dateObj = DateTime::createFromFormat('!m', $month);
        $data['monthName'] = lang($dateObj->format('F'));
        $data['year'] = $year;
        $data['children'] = FALSE; //For compatibility
        $data['displayTypes'] = $displayTypes;
        $data['tabular'] = $this->leaves_model->tabularList($id, $month, $year);
        $this->load->view('calendar/export_tabular', $data);
    }
    
    /**
     * Export the tabular calendar into Excel. The presentation differs a bit according to the limitation of Excel
     * We'll get one line for the morning and one line for the afternoon
     * @param int $id identifier of the entity
     * @param int $month Month number
     * @param int $year Year number
     * @param bool $children If TRUE, includes children entity, FALSE otherwise
     * @param bool $displayTypes If TRUE, display leave types, FALSE otherwise
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function exportTabular($id=-1, $month=0, $year=0, $children=TRUE, $displayTypes=TRUE) {
        $data = array();
        //Load the language file (the loaded language depends if it was called from the public view)
        if (($this->config->item('public_calendar') === TRUE) && (!$this->session->userdata('logged_in'))) {
            $this->load->library('polyglot');;
            $language = $this->config->item('language');
            $data['user_id'] = 0;
            $data['is_admin'] = FALSE;
        } else {
            setUserContext($this);
            $language = $this->language;
            $data = getUserContext($this);
        }
        $this->lang->load('calendar', $language);
        $this->lang->load('global', $language);
        $this->load->model('organization_model');
        $this->load->model('leaves_model');
        $data['id'] = $id;
        $data['entityName'] = $this->organization_model->getName($id);
        $data['month'] = $month;
        $dateObj = DateTime::createFromFormat('!m', $month);
        $data['monthName'] = lang($dateObj->format('F'));
        $data['year'] = $year;
        $data['children'] = $children;
        $data['displayTypes'] = $displayTypes;
        $data['tabular'] = $this->leaves_model->tabular($id, $month, $year, $children);
        if ($this->config->item('tabular_order_by_organization') === FALSE)
            $this->load->view('calendar/export_tabular', $data);
        else
            $this->load->view('calendar/export_department_tabular', $data);
    }
    
    /**
     * Export the yearly calendar into Excel. The presentation differs a bit according to the limitation of Excel
     * We'll get one line for the morning and one line for the afternoon
     * @param int $id identifier of the employee
     * @param int $year Year number
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function exportYear($employee = 0, $year = 0) {
        setUserContext($this);
        $this->lang->load('calendar', $this->language);
        if ($employee == 0) {
            $this->auth->checkIfOperationIsAllowed('individual_calendar');
        } else {
            $this->auth->checkIfOperationIsAllowed('organization_calendar');
        }
        $this->load->model('users_model');
        $user = $this->users_model->getUsers($employee);
        //Either self access, Manager or HR
        if ($employee == 0) {
            $employee = $this->user_id;
            $user = $this->users_model->getUsers($employee);
        } else {
            if (!$this->is_hr) {
                if ($this->user_id != $user['manager']) {
                    $employee = $this->user_id;
                    $user = $this->users_model->getUsers($employee);
                }
            }
        }
        if ($year == 0) {
            $year = date("Y");
        }
        $this->load->model('leaves_model');
        $data['employee'] = $employee;
        $data['year'] = $year;
        $this->load->view('calendar/export_year', $data);
    }
}
