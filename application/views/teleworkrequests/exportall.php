<?php
/**
 * This view builds a Spreadsheet file containing the list of telework requests (that a manager must validate).
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.2.0
 */

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle(mb_strimwidth(lang('teleworkrequests_export_title'), 0, 28, "..."));  //Maximum 31 characters allowed in sheet title.
$sheet->setCellValue('A1', lang('teleworkrequests_export_thead_id'));
$sheet->setCellValue('B1', lang('teleworkrequests_export_thead_fullname'));
$sheet->setCellValue('C1', lang('teleworkrequests_export_thead_startdate'));
$sheet->setCellValue('D1', lang('teleworkrequests_export_thead_startdate_type'));
$sheet->setCellValue('E1', lang('teleworkrequests_export_thead_enddate'));
$sheet->setCellValue('F1', lang('teleworkrequests_export_thead_enddate_type'));
$sheet->setCellValue('G1', lang('teleworkrequests_export_thead_duration'));
$sheet->setCellValue('H1', lang('teleworkrequests_export_thead_cause'));
$sheet->setCellValue('I1', lang('teleworkrequests_export_thead_status'));
$sheet->getStyle('A1:I1')->getFont()->setBold(true);
$sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

($filter == 'all')? $showAll = TRUE : $showAll = FALSE;
$requests = $this->teleworks_model->getTeleworksRequestedToManager($this->user_id, $showAll, NULL, $user_id);
$line = 2;
foreach ($requests as $request) {
    $date = new DateTime($request['startdate']);
    $startdate = $date->format(lang('global_date_format'));
    $date = new DateTime($request['enddate']);
    $enddate = $date->format(lang('global_date_format'));
    $sheet->setCellValue('A' . $line, $request['telework_id']);
    $sheet->setCellValue('B' . $line, $request['firstname'] . ' ' . $request['lastname']);
    $sheet->setCellValue('C' . $line, $startdate);
    $sheet->setCellValue('D' . $line, lang($request['startdatetype']));
    $sheet->setCellValue('E' . $line, $enddate);
    $sheet->setCellValue('F' . $line, lang($request['enddatetype']));
    $sheet->setCellValue('G' . $line, $request['duration']);
    $sheet->setCellValue('H' . $line, $request['cause']);
    $sheet->setCellValue('I' . $line, lang($request['status_name']));
    $line++;
}

//Autofit
foreach(range('A', 'I') as $colD) {
    $sheet->getColumnDimension($colD)->setAutoSize(TRUE);
}

$spreadsheet->exportName = 'teleworkrequests';
writeSpreadsheet($spreadsheet);
