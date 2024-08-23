<?php
/**
 * This view builds a Spreadsheet file containing the list of leave requests created by an employee (from HR menu).
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

$sheet->setTitle(mb_strimwidth(lang('hr_export_leaves_title'), 0, 28, "..."));  //Maximum 31 characters allowed in sheet title.
$sheet->setCellValue('A3', lang('hr_export_leaves_thead_id'));
$sheet->setCellValue('B3', lang('hr_export_leaves_thead_status'));
$sheet->setCellValue('C3', lang('hr_export_leaves_thead_start'));
$sheet->setCellValue('D3', lang('hr_export_leaves_thead_end'));
$sheet->setCellValue('E3', lang('hr_export_leaves_thead_duration'));
$sheet->setCellValue('F3', lang('hr_export_leaves_thead_type'));

$sheet->getStyle('A3:F3')->getFont()->setBold(true);
$sheet->getStyle('A3:F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$leaves = $this->leaves_model->getLeavesOfEmployee($id);
$fullname = $this->users_model->getName($id);
$sheet->setCellValue('A1', $fullname);

$line = 4;
foreach ($leaves as $leave) {
    $date = new DateTime($leave['startdate']);
    $startdate = $date->format(lang('global_date_format'));
    $date = new DateTime($leave['enddate']);
    $enddate = $date->format(lang('global_date_format'));
    $sheet->setCellValue('A' . $line, $leave['id']);
    $sheet->setCellValue('B' . $line, lang($leave['status_name']));
    $sheet->setCellValue('C' . $line, $startdate);
    $sheet->setCellValue('D' . $line, $enddate);
    $sheet->setCellValue('E' . $line, $leave['duration']);
    $sheet->setCellValue('F' . $line, $leave['type_name']);
    $line++;
}

//Autofit
foreach(range('A', 'F') as $colD) {
    $sheet->getColumnDimension($colD)->setAutoSize(TRUE);
}

writeSpreadsheet($spreadsheet, 'leaves');
