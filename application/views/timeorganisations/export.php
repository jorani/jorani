<?php
/**
 * This view builds a Spreadsheet file containing the list of time organisations.
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

$sheet->setTitle(mb_strimwidth(lang('time_organisation_export_title'), 0, 28, "..."));  //Maximum 31 characters allowed in sheet title.
$sheet->setCellValue('A1', lang('time_organisation_export_thead_employee_id'));
$sheet->setCellValue('B1', lang('time_organisation_export_thead_firstname'));
$sheet->setCellValue('C1', lang('time_organisation_export_thead_lastname'));
$sheet->setCellValue('D1', lang('time_organisation_export_thead_duration'));
$sheet->setCellValue('E1', lang('time_organisation_export_thead_day'));
$sheet->setCellValue('F1', lang('time_organisation_export_thead_daytype'));
$sheet->setCellValue('G1', lang('time_organisation_export_thead_recurrence'));

$sheet->getStyle('A1:G1')->getFont()->setBold(true);
$sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$timeorganisations = $this->time_organisation_model->getTimeOrganisationsForExport();
$line = 2;
foreach ($timeorganisations as $timeorganisation) {
    $sheet->setCellValue('A' . $line, $timeorganisation['employee_id']);
    $sheet->setCellValue('B' . $line, $timeorganisation['firstname']);
    $sheet->setCellValue('C' . $line, $timeorganisation['lastname']);
    $sheet->setCellValue('D' . $line, $timeorganisation['duration']);
    $sheet->setCellValue('E' . $line, $timeorganisation['day']);
    $sheet->setCellValue('F' . $line, $timeorganisation['daytype']);
    $sheet->setCellValue('G' . $line, $timeorganisation['recurrence']);
    $line++;
}

//Autofit
foreach(range('A', 'G') as $colD) {
    $sheet->getColumnDimension($colD)->setAutoSize(TRUE);
}

$spreadsheet->exportName = 'timeorganisations';
writeSpreadsheet($spreadsheet);
