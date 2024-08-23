<?php
/**
 * This view builds a Spreadsheet file containing the list of overtime requests created by an employee (from HR menu).
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

$sheet->setTitle(mb_strimwidth(lang('hr_export_overtime_title'), 0, 28, "..."));  //Maximum 31 characters allowed in sheet title.
$sheet->setCellValue('A3', lang('hr_export_overtime_thead_id'));
$sheet->setCellValue('B3', lang('hr_export_overtime_thead_status'));
$sheet->setCellValue('C3', lang('hr_export_overtime_thead_date'));
$sheet->setCellValue('D3', lang('hr_export_overtime_thead_duration'));
$sheet->setCellValue('E3', lang('hr_export_overtime_thead_cause'));
$sheet->getStyle('A3:E3')->getFont()->setBold(true);
$sheet->getStyle('A3:E3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$requests = $this->overtime_model->getExtrasOfEmployee($id);

$fullname = $this->users_model->getName($id);
$sheet->setCellValue('A1', $fullname);

$line = 4;
foreach ($requests as $request) {
    $date = new DateTime($request['date']);
    $startdate = $date->format(lang('global_date_format'));
    $sheet->setCellValue('A' . $line, $request['id']);
    $sheet->setCellValue('B' . $line, lang($request['status_name']));
    $sheet->setCellValue('C' . $line, $startdate);
    $sheet->setCellValue('D' . $line, $request['duration']);
    $sheet->setCellValue('E' . $line, $request['cause']);
    $line++;
}

//Autofit
foreach(range('A', 'E') as $colD) {
    $sheet->getColumnDimension($colD)->setAutoSize(TRUE);
}

writeSpreadsheet($spreadsheet, 'overtime');
