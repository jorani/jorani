<?php
/**
 * This view builds a Spreadsheet file containing the list of telework requests created by an employee (from HR menu).
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

$sheet->setTitle(mb_strimwidth(lang('hr_export_teleworks_title'), 0, 28, "..."));  //Maximum 31 characters allowed in sheet title.
$sheet->setCellValue('A3', lang('hr_export_teleworks_thead_id'));
$sheet->setCellValue('B3', lang('hr_export_teleworks_thead_status'));
$sheet->setCellValue('C3', lang('hr_export_teleworks_thead_start'));
$sheet->setCellValue('D3', lang('hr_export_teleworks_thead_end'));
$sheet->setCellValue('E3', lang('hr_export_teleworks_thead_duration'));
$sheet->setCellValue('F3', lang('hr_export_teleworks_thead_type'));
$sheet->setCellValue('G3', lang('hr_export_teleworks_thead_campaign'));

$sheet->getStyle('A3:G3')->getFont()->setBold(true);
$sheet->getStyle('A3:G3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$teleworks = $this->teleworks_model->getTeleworksOfEmployee($id);
$fullname = $this->users_model->getName($id);
$sheet->setCellValue('A1', $fullname);

$line = 4;
foreach ($teleworks as $telework) {
    $date = new DateTime($telework['startdate']);
    $startdate = $date->format(lang('global_date_format'));
    $date = new DateTime($telework['enddate']);
    $enddate = $date->format(lang('global_date_format'));
    $sheet->setCellValue('A' . $line, $telework['id']);
    $sheet->setCellValue('B' . $line, lang($telework['status_name']));
    $sheet->setCellValue('C' . $line, $startdate);
    $sheet->setCellValue('D' . $line, $enddate);
    $sheet->setCellValue('E' . $line, $telework['duration']);
    $sheet->setCellValue('F' . $line, lang($telework['type']));
    $sheet->setCellValue('G' . $line, $telework['campaign_name']);
    $line++;
}

//Autofit
foreach(range('A', 'G') as $colD) {
    $sheet->getColumnDimension($colD)->setAutoSize(TRUE);
}

$spreadsheet->exportName = 'teleworks';
writeSpreadsheet($spreadsheet);
