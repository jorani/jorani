<?php
/**
 * This controller serves the list of custom reports and the system reports.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.2.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This classe loads:
 *  - the list of custom reports described into local/reports/*.ini
 *  - the system reports implemented into Jorani.
 * The custom reports need to be implemented into local/pages/{lang}/ (see Controller Page)
 */
class Reports extends CI_Controller {

    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {
        parent::__construct();
        setUserContext($this);
        $this->lang->load('reports', $this->language);
        $this->lang->load('global', $this->language);
    }

    /**
     * List the available custom reports (provided they are described into local/reports/*.ini)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function index() {
        $this->auth->checkIfOperationIsAllowed('report_list');
        $data = getUserContext($this);
        $this->lang->load('datatable', $this->language);

        $reports = array();
        //List all the available reports
        $files = glob(FCPATH . '/local/reports/*.ini');
        foreach($files as $file) {
            $ini_array = parse_ini_file($file, TRUE);
            //Test if the report is available for the language being used
            if (array_key_exists($this->language_code, $ini_array)) {
                //If available, push the report into the list to be displayed with a description
                $reports[$ini_array[$this->language_code]['name']] = array(
                    basename($file),
                    $ini_array[$this->language_code]['description'],
                    $ini_array['configuration']['path'],
                );
            }
        }

        $data['title'] = lang('reports_index_title');
        $data['reports'] = $reports;
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('reports/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Landing page of the shipped-in balance report
     * @param string $refTmp Optional Unix timestamp (set a date of reference for the report).
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function balance($refTmp = NULL) {
        $this->auth->checkIfOperationIsAllowed('native_report_balance');
        $data = getUserContext($this);
        $refDate = date("Y-m-d");
        if ($refTmp != NULL) {
            $refDate = date("Y-m-d", $refTmp);
        }
        $data['refDate'] = $refDate;
        $data['title'] = lang('reports_balance_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_leave_balance_report');
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('reports/balance/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Ajax end-point : execute the balance report
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function executeBalanceReport() {
        $this->auth->checkIfOperationIsAllowed('native_report_balance');
        $this->load->model('leaves_model');
        $this->load->model('types_model');
        $this->load->model('organization_model');
        $result = array();
        $types = $this->types_model->getTypes();
        $this->lang->load('global', $this->language);

        $refDate = date("Y-m-d");
        if (isset($_GET['refDate']) && $_GET['refDate'] != NULL) {
            $refDate = date("Y-m-d", $_GET['refDate']);
        }
        $include_children = filter_var($_GET['children'], FILTER_VALIDATE_BOOLEAN);
        $users = $this->organization_model->allEmployees($_GET['entity'], $include_children);
        foreach ($users as $user) {
            $result[$user->id]['identifier'] = $user->identifier;
            $result[$user->id]['firstname'] = $user->firstname;
            $result[$user->id]['lastname'] = $user->lastname;
            $date = new DateTime($user->datehired);
            $result[$user->id]['datehired'] = $date->format(lang('global_date_format'));
            $result[$user->id]['department'] = $user->department;
            $result[$user->id]['position'] = $user->position;
            $result[$user->id]['contract'] = $user->contract;
            //Init type columns
            foreach ($types as $type) {
                $result[$user->id][$type['name']] = '';
            }

            $summary = $this->leaves_model->getLeaveBalanceForEmployee($user->id, TRUE, $refDate);
            if (!is_null($summary)) {
              if (count($summary) > 0 ) {
                  foreach ($summary as $key => $value) {
                      $result[$user->id][$key] = round($value[1] - $value[0], 3, PHP_ROUND_HALF_DOWN);
                  }
              }
            }
        }

        $table = '';
        $thead = '';
        $tbody = '';
        $line = 2;
        $i18n = array("identifier", "firstname", "lastname", "datehired", "department", "position", "contract");
        foreach ($result as $row) {
            $index = 1;
            $tbody .= '<tr>';
            foreach ($row as $key => $value) {
                if ($line == 2) {
                    if (in_array($key, $i18n)) {
                        $thead .= '<th>' . lang($key) . '</th>';
                    } else {
                        $thead .= '<th>' . $key . '</th>';
                    }
                }
                $tbody .= '<td>' . $value . '</td>';
                $index++;
            }
            $tbody .= '</tr>';
            $line++;
        }

        //Check if there is any diagnostic alert on balance (LR without entitlments)
        $alerts = $this->leaves_model->detectBalanceProblems();
        if (count($alerts)) {
            $table = "<div class='alert'>" .
                     "<button type='button' class='close' data-dismiss='alert'>&times;</button>" .
                     "<a href='" . base_url() . "admin/diagnostic#balance'>" .
                     "<i class='mdi mdi-alert'></i>" .
                     "&nbsp;Error</a>" .
                     "</div>";
        }
        $table .= '<table class="table table-bordered table-hover">' .
                    '<thead>' .
                        '<tr>' .
                            $thead .
                        '</tr>' .
                    '</thead>' .
                    '<tbody>' .
                        $tbody .
                    '</tbody>' .
                '</table>';
        $this->output->set_output($table);
    }

    /**
     * Export the balance report into Excel
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function exportBalanceReport() {
        $this->auth->checkIfOperationIsAllowed('native_report_balance');
        $this->load->model('leaves_model');
        $this->load->model('types_model');
        $this->load->model('organization_model');
        $data['refDate'] = date("Y-m-d");
        if (isset($_GET['refDate']) && $_GET['refDate'] != NULL) {
            $data['refDate'] = date("Y-m-d", $_GET['refDate']);
        }
        $data['include_children'] = filter_var($_GET['children'], FILTER_VALIDATE_BOOLEAN);
        $this->load->view('reports/balance/export', $data);
    }

    /**
     * Landing page of the shipped-in leaves report
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     * @since 0.4.3
     */
    public function leaves() {
        $this->auth->checkIfOperationIsAllowed('native_report_leaves');
        $data = getUserContext($this);
        $data['title'] = lang('reports_leaves_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_leaves_report');
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('reports/leaves/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Report leaves request for a month and an entity
     * This report is inspired by the monthly presence report, but applicable to a set of employee.
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     * @since 0.4.3
     */
    public function executeLeavesReport() {
        $this->auth->checkIfOperationIsAllowed('native_report_leaves');
        $this->lang->load('leaves', $this->language);

        $month = $this->input->get("month") === FALSE ? 0 : $this->input->get("month");
        $year = $this->input->get("year") === FALSE ? 0 : $this->input->get("year");
        $entity = $this->input->get("entity") === FALSE ? 0 : $this->input->get("entity");
        $children = filter_var($this->input->get("children"), FILTER_VALIDATE_BOOLEAN);
        $requests = filter_var($this->input->get("requests"), FILTER_VALIDATE_BOOLEAN);

        //Compute facts about dates and the selected month
        if ($month == 0) {
            $start = sprintf('%d-01-01', $year);
            $end = sprintf('%d-12-31', $year);
            $total_days = date("z", mktime(0,0,0,12,31,$year)) + 1;
        } else {
            $start = sprintf('%d-%02d-01', $year, $month);
            $lastDay = date("t", strtotime($start));    //last day of selected month
            $end = sprintf('%d-%02d-%02d', $year, $month, $lastDay);
            $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        }

        $this->load->model('organization_model');
        $this->load->model('leaves_model');
        $this->load->model('types_model');
        $this->load->model('dayoffs_model');
        $types = $this->types_model->getTypes();

        //Iterate on all employees of the entity
        $users = $this->organization_model->allEmployees($entity, $children);
        $result = array();
        $leave_requests = array();

        foreach ($users as $user) {
            $result[$user->id]['identifier'] = $user->identifier;
            $result[$user->id]['firstname'] = $user->firstname;
            $result[$user->id]['lastname'] = $user->lastname;
            $date = new DateTime($user->datehired);
            $result[$user->id]['datehired'] = $date->format(lang('global_date_format'));
            $result[$user->id]['department'] = $user->department;
            $result[$user->id]['position'] = $user->position;
            $result[$user->id]['contract'] = $user->contract;
            $non_working_days = $this->dayoffs_model->lengthDaysOffBetweenDates($user->contract_id, $start, $end);
            $opened_days = $total_days - $non_working_days;

            //If the user has selected All months
            if ($month == 0) {
                $leave_duration = 0;
                for ($ii = 1; $ii <13; $ii++) {
                    $linear = $this->leaves_model->linear($user->id, $ii, $year, FALSE, FALSE, TRUE, FALSE);
                    if ($this->config->item('disable_telework') === FALSE)
                        $linear = $this->leaves_model->removeOverlappingTeleworks($linear);
                    $leave_duration += $this->leaves_model->monthlyLeavesDuration($linear);
                    $leaves_detail = $this->leaves_model->monthlyLeavesByType($linear);
                    //Init type columns
                    foreach ($types as $type) {
                        if (array_key_exists($type['name'], $leaves_detail)) {
                            if (!array_key_exists($type['name'], $result[$user->id])) {
                                $result[$user->id][$type['name']] = 0;
                            }
                            $result[$user->id][$type['name']] +=
                                    $leaves_detail[$type['name']];
                        } else {
                            $result[$user->id][$type['name']] = '';
                        }
                    }
                }
                if ($requests) $leave_requests[$user->id] = $this->leaves_model->getAcceptedLeavesBetweenDates($user->id, $start, $end);
                $work_duration = $opened_days - $leave_duration;
            } else {
                $linear = $this->leaves_model->linear($user->id, $month, $year, FALSE, FALSE, TRUE, FALSE);
                if ($this->config->item('disable_telework') === FALSE)
                    $linear = $this->leaves_model->removeOverlappingTeleworks($linear);
                $leave_duration = $this->leaves_model->monthlyLeavesDuration($linear);
                $work_duration = $opened_days - $leave_duration;
                $leaves_detail = $this->leaves_model->monthlyLeavesByType($linear);
                if ($requests) $leave_requests[$user->id] = $this->leaves_model->getAcceptedLeavesBetweenDates($user->id, $start, $end);
                //Init type columns
                foreach ($types as $type) {
                    if (array_key_exists($type['name'], $leaves_detail)) {
                        $result[$user->id][$type['name']] = $leaves_detail[$type['name']];
                    } else {
                        $result[$user->id][$type['name']] = '';
                    }
                }
            }
            $result[$user->id]['leave_duration'] = $leave_duration;
            $result[$user->id]['total_days'] = $total_days;
            $result[$user->id]['non_working_days'] = $non_working_days;
            $result[$user->id]['work_duration'] = $work_duration;
        }

        $table = '';
        $thead = '';
        $tbody = '';
        $line = 2;
        $i18n = array("identifier", "firstname", "lastname", "datehired", "department", "position", "contract");
        foreach ($result as $user_id => $row) {
            $index = 1;
            $tbody .= '<tr>';
            foreach ($row as $key => $value) {
                if ($line == 2) {
                    if (in_array($key, $i18n)) {
                        $thead .= '<th>' . lang($key) . '</th>';
                    } else {
                        $thead .= '<th>' . $key . '</th>';
                    }
                }
                $tbody .= '<td>' . $value . '</td>';
                $index++;
            }
            $tbody .= '</tr>';
            //Display a nested table listing the leave requests
            if ($requests) {
                if (count($leave_requests[$user_id])) {
                    $tbody .= '<tr><td colspan="' . count($row) . '">';
                    $tbody .= '<table class="table table-bordered table-hover" style="width: auto !important;">';
                    $tbody .= '<thead><tr>';
                    $tbody .= '<th>' . lang('leaves_index_thead_id'). '</th>';
                    $tbody .= '<th>' . lang('leaves_index_thead_start_date'). '</th>';
                    $tbody .= '<th>' . lang('leaves_index_thead_end_date'). '</th>';
                    $tbody .= '<th>' . lang('leaves_index_thead_type'). '</th>';
                    $tbody .= '<th>' . lang('leaves_index_thead_duration'). '</th>';
                    $tbody .= '</tr></thead>';
                    $tbody .= '<tbody>';
                    //Iterate on leave requests
                    foreach ($leave_requests[$user_id] as $request) {
                        $date = new DateTime($request['startdate']);
                        $startdate = $date->format(lang('global_date_format'));
                        $date = new DateTime($request['enddate']);
                        $enddate = $date->format(lang('global_date_format'));
                        $tbody .= '<tr>';
                        $tbody .= '<td><a href="' . base_url() . 'leaves/view/'. $request['id']. '" target="_blank">'. $request['id']. '</a></td>';
                        $tbody .= '<td>'. $startdate . ' (' . lang($request['startdatetype']). ')</td>';
                        $tbody .= '<td>'. $enddate . ' (' . lang($request['enddatetype']). ')</td>';
                        $tbody .= '<td>'. $request['type'] . '</td>';
                        $tbody .= '<td>'. $request['duration'] . '</td>';
                        $tbody .= '</tr>';
                    }
                    $tbody .= '</tbody>';
                    $tbody .= '</table>';
                    $tbody .= '</td></tr>';
                } else {
                    $tbody .= '<tr><td colspan="' . count($row) . '">----</td></tr>';
                }
            }
            $line++;
        }
        $table = '<table class="table table-bordered table-hover">' .
                    '<thead>' .
                        '<tr>' .
                            $thead .
                        '</tr>' .
                    '</thead>' .
                    '<tbody>' .
                        $tbody .
                    '</tbody>' .
                '</table>';
        $this->output->set_output($table);
    }

    /**
     * Export the leaves report into Excel
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     * @since 0.4.3
     */
    public function exportLeavesReport() {
        $this->auth->checkIfOperationIsAllowed('native_report_leaves');
        $this->lang->load('leaves', $this->language);
        $this->load->model('organization_model');
        $this->load->model('leaves_model');
        $this->load->model('types_model');
        $this->load->model('dayoffs_model');
        $data['refDate'] = date("Y-m-d");
        if (isset($_GET['refDate']) && $_GET['refDate'] != NULL) {
            $data['refDate'] = date("Y-m-d", $_GET['refDate']);
        }
        $data['include_children'] = filter_var($_GET['children'], FILTER_VALIDATE_BOOLEAN);
        $this->load->view('reports/leaves/export', $data);
    }

}
