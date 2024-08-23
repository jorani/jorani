<?php
/**
 * This class manages the authorization to access to pages.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class manages the authorization to access to pages.
 * At the moment, the algo is very basic, but a group-based algo might be implemented later.
 */
class Auth {

    /**
     * Access to CI framework so as to use other libraries
     * @var object Code Igniter framework
     */
    private $CI;
    /**
     * Is the connected user part of HR team
     * @var bool Is Human Resource role
     */
    private $isHR;
    /**
     * Is the connected user part of Admin team
     * @var bool Is Admin role
     */
    private $isAdmin;
    /**
     * Is the connected user a manager of at least one employee
     * @var bool Is the user validating any request
     */
    private $isManager;

    /**
     * Default constructor
     */
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->library('session');

        $this->isHR = ($this->CI->session->userdata('is_hr') === TRUE);
        $this->isAdmin = ($this->CI->session->userdata('is_admin') === TRUE);
        $this->isManager = ($this->CI->session->userdata('is_manager') === TRUE);
    }

    /**
     * Check if the current user can perform a given action on the system.
     * Note that other business rules may be implemented in the controllers.
     * For instance, a user can approve a leave only if it is a manager of the submitter.
     * Or a user may delete a leave only if the leave is at the planned status
     * This function only prevents gross security issues when a user try to access
     * a restricted screen.
     * Note that any operation needs the user to be connected.
     * @param string $operation Operation attempted by the user
     * @param int $id  optional object identifier of the operation (e.g. user id)
     * @return bool true if the user is granted, false otherwise
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function isAllowed($operation, $object_id = 0) {
        switch ($operation) {

            //Admin functions
            case 'purge_database' :
                if ($this->CI->session->userdata('is_admin') == true)
                    if ($this->CI->config->item('enable_purge') == true)
                        return true;
                    else
                        return false;
                else
                    return false;
                break;

            //User management
            case 'list_settings' :
            case 'oauth_clients' :
                if ($this->CI->session->userdata('is_admin') == true)
                    return true;
                else
                    return false;
                break;

            case 'list_users' :
            case 'create_user' :
            case 'delete_user' :
            case 'view_user' :
            case 'edit_user' :
            case 'update_user' :
            case 'import_user' :
            case 'export_user' :
                if ($this->CI->session->userdata('is_hr') == true)
                    return true;
                else
                    return false;
                break;

            //Password management
            case 'change_password' :
                if ($this->CI->session->userdata('is_hr') == true)
                    return true;
                else {//a user can change its own password
                    if ($this->CI->session->userdata('id') == $object_id)
                        return true;
                    else
                        return false;
                }
                break;

            //Configuration of HR objects
            case 'diagnostic' :
            case 'list_employees' :
            case 'employee_contract' :
            case 'employee_manager' :
            case 'list_contracts' :
            case 'export_contracts' :
            case 'view_contract' :
            case 'create_contract' :
            case 'delete_contract' :
            case 'edit_contract' :
            case 'list_telework_campaigns' :            
            case 'view_telework_campaign' :
            case 'create_telework_campaign' :
            case 'delete_telework_campaign' :
            case 'edit_telework_campaign' :
            case 'list_telework_rules' :
            case 'edit_telework_rule' :
            case 'create_telework_rule' :
            case 'delete_telework_rule' :
            case 'edit_telework_rule' :
            case 'import_telework_rules' :
            case 'export_telework_rules' :
            case 'list_time_organisations' :
            case 'edit_time_organisation' :
            case 'create_time_organisation' :
            case 'delete_time_organisation' :
            case 'edit_time_organisation' :
            case 'import_time_organisations' :
            case 'export_time_organisations' :
            case 'delete_positions' :
            case 'edit_positions' :
            case 'create_positions' :
            case 'export_positions' :
            case 'list_positions' :
            case 'edit_organization' :
            case 'calendar_contract' :
            case 'adddayoff_contract' :
            case 'deletedayoff_contract' :
                if ($this->CI->session->userdata('is_hr') == true)
                    return true;
                else
                    return false;
                break;

            case 'native_report_balance':
            case 'native_report_leaves':
            case 'report_list' :
            case 'native_teleworkreport_teleworks':
            case 'teleworkreport_list' :
            case 'report_execute' :
                if ($this->CI->session->userdata('is_hr') == true)
                    return true;
                else
                    return false;
                break;

            //HR
            case 'leavetypes_delete' :
            case 'leavetypes_list' :
            case 'leavetypes_export' :
            case 'leavetypes_create' :
            case 'leavetypes_edit' :
            case 'entitleddays_user' :
            case 'entitleddays_user_delete' :
            case 'entitleddays_contract' :
            case 'entitleddays_contract_delete' :
                if ($this->CI->session->userdata('is_hr') == true)
                    return true;
                else
                    return false;
                break;

            case 'organization_index' :
                if ($this->CI->session->userdata('is_hr') == true)
                    return true;
                else
                    return false;
                break;

            //General
            case 'view_myprofile' :
            case 'employees_list' :
            case 'organization_select' :
                return true;
                break;

            //Leaves
            case 'list_leaves' :
            case 'create_leaves' :
            case 'export_leaves' :
            case 'view_leaves' :
            case 'edit_leaves' :
            case 'counters_leaves' :
                return true;
                break;

            //Extra
            case 'list_extra' :
            case 'create_extra' :
            case 'export_extra' :
            case 'view_extra' :
            case 'edit_extra' :
                return true;
                break;
                
            //Teleworks
            case 'list_teleworks' :
            case 'create_teleworks' :
            case 'create_campaign_teleworks' :
            case 'export_teleworks' :
            case 'view_teleworks' :
            case 'edit_teleworks' :
            case 'counters_teleworks' :
                return true;
                break;
                
            //Additionnal access logic: cannot view/edit/update the leave of another user except for admin/manager
            //Extra Request
            case 'list_overtime' :
            case 'accept_overtime' :
            case 'reject_overtime' :
                return true;
                break;

            //Additionnal access logic: cannot view/edit/update the leave of another user except for admin/manager
            //Request
            case 'list_collaborators' :
            case 'list_requests' :
            case 'accept_requests' :
            case 'reject_requests' :
                return true;
                break;
                
            //Additionnal access logic: cannot view/edit/update the leave of another user except for admin/manager
            //Telework Request
            case 'list_telework_requests' :
            case 'accept_telework_requests' :
            case 'reject_telework_requests' :
            case 'accept_all_telework_requests' :
            case 'reject_all_telework_requests' :
                return true;
                break;
                
            //Access logic is in the controller : if the connected user manages nobody, the list will be empty
            //Calendar
            case 'individual_calendar' :
              return true;
            case 'workmates_calendar' :
              return ($this->isHR || $this->isAdmin || ($this->CI->config->item('disable_workmates_calendar') == FALSE));
            case 'collaborators_calendar' :
              return ($this->isHR || $this->isAdmin || $this->isManager);
            case 'department_calendar' :
              return ($this->CI->config->item('disable_department_calendar') == FALSE);
            case 'organization_calendar' :
                return ($this->isHR || $this->isAdmin || ($this->CI->config->item('hide_global_cals_to_users') == FALSE));
            case 'download_calendar' :
                return true;
                break;

            //Custom lists of employees (filter of tabular calendars)
            case 'organization_lists_index' :
            case 'organization_lists_create' :
            case 'organization_lists_rename' :
            case 'organization_lists_delete' :
            case 'organization_lists_add_user' :
            case 'organization_lists_remove_user' :
            case 'organization_lists_list_reorder' :
                return true;
                break;

            //Additionnal access logic: filter on the connected user
            //Additionnal access logic: filter on the team of the connected user
            default:
                return false;
                break;
        }
    }

    /**
     * Check if the current user can perform a given action on the system.
     * @use isAllowed
     * @param string $operation Operation attempted by the user
     * @param int $id  optional object identifier of the operation (e.g. user id)
     * @return bool true if the user is granted, false otherwise
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function checkIfOperationIsAllowed($operation, $object_id = 0) {
        if (!$this->isAllowed($operation, $object_id)) {
            $this->CI->load->helper('url');
            $this->CI->load->helper('language');
            $this->CI->lang->load('global', $this->CI->session->userdata('language'));
            log_message('error', 'User #' . $this->CI->session->userdata('id') . ' illegally tried to access to ' . $operation);
            $this->CI->session->set_flashdata('msg', sprintf(lang('global_msg_error_forbidden'), $operation));
            redirect('forbidden');
        }
    }
}
