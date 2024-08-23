<?php
/**
 * This view builds an Excel5 file containing the list of leave requests declared by the connected employee.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
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

$sheet->setTitle(mb_strimwidth(lang('leaves_export_title'), 0, 28, "..."));  //Maximum 31 characters allowed in sheet title.
$sheet->setCellValue('A1', lang('leaves_export_thead_id'));
$sheet->setCellValue('B1', lang('leaves_export_thead_start_date'));
$sheet->setCellValue('C1', lang('leaves_export_thead_start_date_type'));
$sheet->setCellValue('D1', lang('leaves_export_thead_end_date'));
$sheet->setCellValue('E1', lang('leaves_export_thead_end_date_type'));
$sheet->setCellValue('F1', lang('leaves_export_thead_duration'));
$sheet->setCellValue('G1', lang('leaves_export_thead_type'));
$sheet->setCellValue('H1', lang('leaves_export_thead_status'));
$sheet->setCellValue('I1', lang('leaves_export_thead_cause'));
$sheet->getStyle('A1:I1')->getFont()->setBold(true);
$sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$leaves = $this->leaves_model->getLeavesOfEmployee($this->user_id);
$line = 2;
foreach ($leaves as $leave) {
    $date = new DateTime($leave['startdate']);
    $startdate = $date->format(lang('global_date_format'));
    $date = new DateTime($leave['enddate']);
    $enddate = $date->format(lang('global_date_format'));
    $sheet->setCellValue('A' . $line, $leave['id']);
    $sheet->setCellValue('B' . $line, $startdate);
    $sheet->setCellValue('C' . $line, lang($leave['startdatetype']));
    $sheet->setCellValue('D' . $line, $enddate);
    $sheet->setCellValue('E' . $line, lang($leave['enddatetype']));
    $sheet->setCellValue('F' . $line, $leave['duration']);
    $sheet->setCellValue('G' . $line, $leave['type_name']);
    $sheet->setCellValue('H' . $line, lang($leave['status_name']));
    $sheet->setCellValue('I' . $line, $leave['cause']);
    $line++;
}

//Autofit
foreach(range('A', 'I') as $colD) {
    $sheet->getColumnDimension($colD)->setAutoSize(TRUE);
}

writeSpreadsheet($spreadsheet, 'leaves');
