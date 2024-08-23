<?php
/**
 * This view exports into a Spreadsheet file the native report listing the approved telework requests of employees attached to an entity.
 * This report is launched by the user from the view teleworkreports/teleworks.
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.4.3
 */

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle(mb_strimwidth(lang('teleworkreports_export_teleworks_title'), 0, 28, "..."));  //Maximum 31 characters allowed in sheet title.

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

$types = array(
    'Campaign',
    'Floating'
);
$telework_requests = array();

//Iterate on all employees of the entity
$users = $this->organization_model->allEmployees($entity, $children);
$result = array();
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
        $leave_duration = $telework_duration = 0;
        for ($ii = 1; $ii < 13; $ii ++) {
            $linear = $this->leaves_model->linear($user->id, $ii, $year, FALSE, FALSE, TRUE, FALSE);
            if ($this->config->item('disable_telework') === FALSE)
                $linear = $this->leaves_model->removeOverlappingTeleworks($linear);
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
        $work_duration = $opened_days - $leave_duration;
        if ($teleworkrequests) $telework_requests[$user->id] = $this->teleworks_model->getAcceptedTeleworksBetweenDates($user->id, $start, $end);
    } else {
        $linear = $this->leaves_model->linear($user->id, $month, $year, FALSE, FALSE, TRUE, FALSE);
        if ($this->config->item('disable_telework') === FALSE)
            $linear = $this->leaves_model->removeOverlappingTeleworks($linear);
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

$max = 0;
$line = 2;
$i18n = array("identifier", "firstname", "lastname", "datehired", "department", "position", "contract");
foreach ($result as $user_id => $row) {
    $index = 1;
    foreach ($row as $key => $value) {
        if ($line == 2) {
            $colidx = columnName($index) . '1';
            if (in_array($key, $i18n)) {
                $sheet->setCellValue($colidx, lang($key));
            } else {
                $sheet->setCellValue($colidx, $key);
            }
            $max++;
        }
        $colidx = columnName($index) . $line;
        $sheet->setCellValue($colidx, $value);
        $index++;
    }
    $line++;
    //Display a nested table listing the telework requests
    if ($teleworkrequests) {
        if (count($telework_requests[$user_id])) {
            $sheet->setCellValue('A' . $line, lang('teleworks_index_thead_start_date'));
            $sheet->setCellValue('B' . $line, lang('teleworks_index_thead_end_date'));
            $sheet->setCellValue('C' . $line, lang('teleworks_index_thead_type'));
            $sheet->setCellValue('D' . $line, lang('teleworks_index_thead_duration'));
            $sheet->getStyle('A' . $line . ':D' . $line)->getFont()->setBold(true);
            $sheet->getStyle('A' . $line . ':D' . $line)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $line++;
            //Iterate on telework requests
            foreach ($telework_requests[$user_id] as $request) {
                $date = new DateTime($request['startdate']);
                $startdate = $date->format(lang('global_date_format'));
                $date = new DateTime($request['enddate']);
                $enddate = $date->format(lang('global_date_format'));
                $sheet->setCellValue('A' . $line, $startdate . ' (' . lang($request['startdatetype']). ')');
                $sheet->setCellValue('B' . $line, $enddate . ' (' . lang($request['enddatetype']). ')');
                $sheet->setCellValue('C' . $line, lang($request['type']));
                $sheet->setCellValue('D' . $line, $request['duration']);
                $line++;
            }
        } else {
            $sheet->setCellValue('A' . $line, "----");
            $line++;
        }
    }
}

$colidx = columnName($max) . '1';
$sheet->getStyle('A1:' . $colidx)->getFont()->setBold(true);
$sheet->getStyle('A1:' . $colidx)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

//Autofit
for ($ii=1; $ii <$max; $ii++) {
    $col = columnName($ii);
    $sheet->getColumnDimension($col)->setAutoSize(TRUE);
}

$spreadsheet->exportName = 'telework_requests_'. $month . '_' . $year;
writeSpreadsheet($spreadsheet);
