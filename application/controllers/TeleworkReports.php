<?php
/**
 * This controller serves the list of custom telework reports and the system reports.
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.2.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class TeleworkReports extends CI_Controller {

    /**
     * Default constructor
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     */
    public function __construct() {
        parent::__construct();
        setUserContext($this);
        $this->lang->load('teleworks', $this->language);
        $this->lang->load('teleworkreports', $this->language);
        $this->lang->load('global', $this->language);
        $this->lang->load('calendar', $this->language);
    }

    /**
     * Landing page of the shipped-in teleworks report
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     * @since 0.4.3
     */
    public function teleworks() {
        $this->auth->checkIfOperationIsAllowed('native_teleworkreport_teleworks');
        $data = getUserContext($this);
        $data['title'] = lang('teleworkreports_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_teleworks_report');
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('teleworkreports/teleworks/index', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Landing page of the shipped-in teleworks report by week
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     * @since 0.4.3
     */
    public function byweek() {
        $this->auth->checkIfOperationIsAllowed('native_teleworkreport_teleworks');
        $data = getUserContext($this);
        $data['title'] = lang('teleworkreports_title');
        $data['help'] = $this->help->create_help_link('global_link_doc_page_teleworks_report');
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('teleworkreports/teleworks/byweek', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Report telework requests for a month and an entity
     * This report is inspired by the monthly presence report, but applicable to a set of employee.
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     * @since 0.4.3
     */
    public function executeTeleworksReport() {
        $this->auth->checkIfOperationIsAllowed('native_teleworkreport_teleworks');

        $month = $this->input->get("month") === FALSE ? 0 : $this->input->get("month");
        $year = $this->input->get("year") === FALSE ? 0 : $this->input->get("year");
        $entity = $this->input->get("entity") === FALSE ? 0 : $this->input->get("entity");
        $children = filter_var($this->input->get("children"), FILTER_VALIDATE_BOOLEAN);
        $teleworkrequests = filter_var($this->input->get("teleworkrequests"), FILTER_VALIDATE_BOOLEAN);

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
        $this->load->model('teleworks_model');
        $this->load->model('leaves_model');
        $this->load->model('dayoffs_model');
        $types = array(
            'Campaign',
            'Floating'
        );

        //Iterate on all employees of the entity
        $users = $this->organization_model->allEmployees($entity, $children);
        $result = array();
        $telework_requests = array();

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
                $telework_duration = $leave_duration = 0;
                for ($ii = 1; $ii <13; $ii++) {
                    $linear = $this->leaves_model->removeOverlappingTeleworks($this->leaves_model->linear($user->id, $ii, $year, FALSE, FALSE, TRUE, FALSE));
                    $telework_duration += $this->teleworks_model->monthlyTeleworksDuration($linear); 
                    $leave_duration += $this->leaves_model->monthlyLeavesDuration($linear);
                    $leaves_detail = $this->leaves_model->monthlyLeavesByType($linear);
                    //Init type columns
                    foreach ($types as $type) {
                        if (array_key_exists($type, $leaves_detail)) {
                            if (!array_key_exists($type, $result[$user->id])) {
                                $result[$user->id][$type] = 0;
                            }
                            $result[$user->id][$type] +=
                            $leaves_detail[$type];
                        } else {
                            $result[$user->id][$type] = '';
                        }
                    }
                }
                if ($teleworkrequests) $telework_requests[$user->id] = $this->teleworks_model->getAcceptedTeleworksBetweenDates($user->id, $start, $end);
                $work_duration = $opened_days - $leave_duration;
            } else {
                $linear = $this->leaves_model->removeOverlappingTeleworks($this->leaves_model->linear($user->id, $month, $year, FALSE, FALSE, TRUE, FALSE));
                $telework_duration = $this->teleworks_model->monthlyTeleworksDuration($linear);
                $leave_duration = $this->leaves_model->monthlyLeavesDuration($linear);
                $work_duration = $opened_days - $leave_duration;
                $leaves_detail = $this->leaves_model->monthlyLeavesByType($linear);
                //Init type columns
                foreach ($types as $type) {
                    if (array_key_exists($type, $leaves_detail)) {
                        $result[$user->id][$type] = $leaves_detail[$type];
                    } else {
                        $result[$user->id][$type] = '';
                    }
                }
                if ($teleworkrequests) $telework_requests[$user->id] = $this->teleworks_model->getAcceptedTeleworksBetweenDates($user->id, $start, $end);
            }
            $result[$user->id]['leave_duration'] = $leave_duration;
            $result[$user->id]['total_days'] = $total_days;
            $result[$user->id]['non_working_days'] = $non_working_days;
            $result[$user->id]['work_duration'] = $work_duration;
            $result[$user->id]['telework_duration'] = $telework_duration;
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
            //Display a nested table listing the telework requests
            if ($teleworkrequests) {
                if (count($telework_requests[$user_id])) {
                    $tbody .= '<tr><td colspan="' . count($row) . '">';
                    $tbody .= '<table class="table table-bordered table-hover" style="width: auto !important;">';
                    $tbody .= '<thead><tr>';
                    $tbody .= '<th>' . lang('teleworks_index_thead_id'). '</th>';
                    $tbody .= '<th>' . lang('teleworks_index_thead_start_date'). '</th>';
                    $tbody .= '<th>' . lang('teleworks_index_thead_end_date'). '</th>';
                    $tbody .= '<th>' . lang('teleworks_index_thead_type'). '</th>';
                    $tbody .= '<th>' . lang('teleworks_index_thead_duration'). '</th>';
                    $tbody .= '</tr></thead>';
                    $tbody .= '<tbody>';
                    //Iterate on telework requests
                    foreach ($telework_requests[$user_id] as $request) {
                        $date = new DateTime($request['startdate']);
                        $startdate = $date->format(lang('global_date_format'));
                        $date = new DateTime($request['enddate']);
                        $enddate = $date->format(lang('global_date_format'));
                        $tbody .= '<tr>';
                        $tbody .= '<td><a href="' . base_url() . 'teleworks/view/'. $request['id']. '" target="_blank">'. $request['id']. '</a></td>';
                        $tbody .= '<td>'. $startdate . ' (' . lang($request['startdatetype']). ')</td>';
                        $tbody .= '<td>'. $enddate . ' (' . lang($request['enddatetype']). ')</td>';
                        $tbody .= '<td>'. lang($request['type']) . '</td>';
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
     * Export the teleworks report into Excel
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     * @since 0.4.3
     */
    public function exportTeleworksReport() {
        $this->auth->checkIfOperationIsAllowed('native_teleworkreport_teleworks');
        $this->load->model('organization_model');
        $this->load->model('teleworks_model');
        $this->load->model('leaves_model');
        $this->load->model('dayoffs_model');
        $data['refDate'] = date("Y-m-d");
        if (isset($_GET['refDate']) && $_GET['refDate'] != NULL) {
            $data['refDate'] = date("Y-m-d", $_GET['refDate']);
        }
        $data['include_children'] = filter_var($_GET['children'], FILTER_VALIDATE_BOOLEAN);
        $this->load->view('teleworkreports/teleworks/export', $data);
    }
    
    /**
     * Report telework requests for a week and an entity
     * This report is inspired by the weekly presence report, but applicable to a set of employee.
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     * @since 0.4.3
     */
    public function executeTeleworksReportByweek() {
        $this->auth->checkIfOperationIsAllowed('native_teleworkreport_teleworks');

        $week = $this->input->get("week") === FALSE ? 0 : $this->input->get("week");
        $year = $this->input->get("year") === FALSE ? 0 : $this->input->get("year");
        $entity = $this->input->get("entity") === FALSE ? 0 : $this->input->get("entity");
        $children = filter_var($this->input->get("children"), FILTER_VALIDATE_BOOLEAN);

        $this->load->model('organization_model');
        $this->load->model('teleworks_model');
        $this->load->model('leaves_model');

        if ($children === TRUE) {
            $this->load->model('organization_model');
            $list = $this->organization_model->getAllChildren($entity);
            if ($list[0]['id'] != '') {
                $ids = explode(",", $list[0]['id']);
                array_push($ids, $entity);
            } else {
                $ids[] = $entity;
            }
        } else {
            $ids[] = $entity;
        }

        $teleworks = $this->teleworks_model->getTeleworksByweek($week, $year);
        $users = array();
        $weekdates = get_week_dates_by_week($week, $year);
        for ($i = 0; $i < count($teleworks); $i ++) {
            if (! in_array($teleworks[$i]['employee'], $users) && in_array($teleworks[$i]['organization_id'], $ids))
                $users[$teleworks[$i]['employee']] = array(
                    'organization_id' => $teleworks[$i]['organization_id'],
                    'parent_id' => $teleworks[$i]['parent_id'],
                    'firstname' => $teleworks[$i]['firstname'],
                    'lastname' => $teleworks[$i]['lastname'],
                    'organization_name' => $teleworks[$i]['organization_name'],
                    $weekdates['monday'] . ' Morning' => array(),
                    $weekdates['monday'] . ' Afternoon' => array(),
                    $weekdates['tuesday'] . ' Morning' => array(),
                    $weekdates['tuesday'] . ' Afternoon' => array(),
                    $weekdates['wednesday'] . ' Morning' => array(),
                    $weekdates['wednesday'] . ' Afternoon' => array(),
                    $weekdates['thursday'] . ' Morning' => array(),
                    $weekdates['thursday'] . ' Afternoon' => array(),
                    $weekdates['friday'] . ' Morning' => array(),
                    $weekdates['friday'] . ' Afternoon' => array(),
                    'duration' => 0
                );
        }
        for ($i = 0; $i < count($teleworks); $i ++) {
            $overlapleaves = $this->teleworks_model->detectOverlappingLeavesForTelework($teleworks[$i]['employee'], $teleworks[$i]['startdate'], $teleworks[$i]['enddate'], $teleworks[$i]['startdatetype'], $teleworks[$i]['enddatetype']);
            if (in_array($teleworks[$i]['organization_id'], $ids) && ! $overlapleaves) {
                if ($teleworks[$i]['startdatetype'] != $teleworks[$i]['enddatetype']) {
                    $users[$teleworks[$i]['employee']][$teleworks[$i]['startdate'] . ' Morning'] = array(
                        'duration' => $overlapleaves,
                        'type' => $teleworks[$i]['type']
                    );
                    $users[$teleworks[$i]['employee']][$teleworks[$i]['startdate'] . ' Afternoon'] = array(
                        'duration' => $overlapleaves,
                        'type' => $teleworks[$i]['type']
                    );
                    $users[$teleworks[$i]['employee']]['duration'] = $users[$teleworks[$i]['employee']]['duration'] + 1.000;
                } else {
                    $users[$teleworks[$i]['employee']][$teleworks[$i]['startdate'] . ' ' . $teleworks[$i]['startdatetype']] = array(
                        'duration' => $overlapleaves,
                        'type' => $teleworks[$i]['type']
                    );
                    $users[$teleworks[$i]['employee']]['duration'] = $users[$teleworks[$i]['employee']]['duration'] + 0.500;
                }
            }
        }
//         echo '<pre>';print_r($teleworks);die('</pre>');
        $table = '';
        $thead = '';
        $tbody = '';
        $line = 2;
        $i18n = array(
            "firstname",
            "lastname",
            "organization_name",
            $weekdates['monday'] . ' Morning',
            $weekdates['monday'] . ' Afternoon',
            $weekdates['tuesday'] . ' Morning',
            $weekdates['tuesday'] . ' Afternoon',
            $weekdates['wednesday'] . ' Morning',
            $weekdates['wednesday'] . ' Afternoon',
            $weekdates['thursday'] . ' Morning',
            $weekdates['thursday'] . ' Afternoon',
            $weekdates['friday'] . ' Morning',
            $weekdates['friday'] . ' Afternoon',
            'duration'
        );
        foreach ($users as $row) {
            $index = 1;
            $tbody .= '<tr>';
            foreach ($row as $key => $value) {
                if (in_array($key, $i18n)) {
                    if ($line == 2) {
                        $explode = explode(' ', $key);
                        if (count($explode) == 1)
                            $thead .= '<th>' . lang($explode[0]) . '</th>';
                        else
                            $thead .= '<th>' . lang((new DateTime($explode[0]))->format('l')) . ' ' . (new DateTime($explode[0]))->format('d') . ' ' . lang((new DateTime($explode[0]))->format('F')) . '<br>' . lang($explode[1]) . '</th>';
                    }
                    if (! is_array($value))
                        $tbody .= '<td>' . $value . '</td>';
                    else {
                        if (count($value) == 0)
                            $tbody .= '<td></td>';
                        else
                            $tbody .= '<td>' . lang($value['type']) . '</td>';
                    }
                }
                $index ++;
            }
            $tbody .= '</tr>';
            $line ++;
        }
        $table = '<table class="table table-bordered table-hover">' . '<thead>' . '<tr>' . $thead . '</tr>' . '</thead>' . '<tbody>' . $tbody . '</tbody>' . '</table>';
        $this->output->set_output($table);
    }
    
    /**
     * Export the teleworks report into Excel for a week and an entity
     * @author Maithyly SIVAPALAN <maithyly.sivapalan@inha.fr>
     * @since 0.4.3
     */
    public function exportTeleworksReportByweek() {
        $this->auth->checkIfOperationIsAllowed('native_teleworkreport_teleworks');
        $this->load->model('organization_model');
        $this->load->model('teleworks_model');
        $this->load->model('leaves_model');
        $data['refDate'] = date("Y-m-d");
        if (isset($_GET['refDate']) && $_GET['refDate'] != NULL) {
            $data['refDate'] = date("Y-m-d", $_GET['refDate']);
        }
        $data['children'] = filter_var($_GET['children'], FILTER_VALIDATE_BOOLEAN);
        $this->load->view('teleworkreports/teleworks/exportbyweek', $data);
    }
}
