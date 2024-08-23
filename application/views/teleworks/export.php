<?php
/**
 * This view builds an Excel5 file containing the list of telework requests declared by the connected employee.
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

$sheet->setTitle(mb_strimwidth(lang('teleworks_export_title'), 0, 28, "..."));  //Maximum 31 characters allowed in sheet title.
$sheet->setCellValue('A1', lang('teleworks_export_thead_id'));
$sheet->setCellValue('B1', lang('teleworks_export_thead_start_date'));
$sheet->setCellValue('C1', lang('teleworks_export_thead_start_date_type'));
$sheet->setCellValue('D1', lang('teleworks_export_thead_end_date'));
$sheet->setCellValue('E1', lang('teleworks_export_thead_end_date_type'));
$sheet->setCellValue('F1', lang('teleworks_export_thead_duration'));
$sheet->setCellValue('G1', lang('teleworks_export_thead_type'));
$sheet->setCellValue('H1', lang('teleworks_export_thead_campaign'));
$sheet->setCellValue('I1', lang('teleworks_export_thead_status'));
$sheet->setCellValue('J1', lang('teleworks_export_thead_cause'));
$sheet->getStyle('A1:J1')->getFont()->setBold(true);
$sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$teleworks = $this->teleworks_model->getTeleworksOfEmployee($this->user_id);
$line = 2;
foreach ($teleworks as $telework) {
    $date = new DateTime($telework['startdate']);
    $startdate = $date->format(lang('global_date_format'));
    $date = new DateTime($telework['enddate']);
    $enddate = $date->format(lang('global_date_format'));
    $sheet->setCellValue('A' . $line, $telework['id']);
    $sheet->setCellValue('B' . $line, $startdate);
    $sheet->setCellValue('C' . $line, lang($telework['startdatetype']));
    $sheet->setCellValue('D' . $line, $enddate);
    $sheet->setCellValue('E' . $line, lang($telework['enddatetype']));
    $sheet->setCellValue('F' . $line, $telework['duration']);
    $sheet->setCellValue('G' . $line, lang($telework['type']));
    $sheet->setCellValue('H' . $line, $telework['campaign_name']);
    $sheet->setCellValue('I' . $line, lang($telework['status_name']));
    $sheet->setCellValue('J' . $line, $telework['cause']);
    $line++;
}

//Autofit
foreach(range('A', 'J') as $colD) {
    $sheet->getColumnDimension($colD)->setAutoSize(TRUE);
}

$spreadsheet->exportName = 'teleworks';
writeSpreadsheet($spreadsheet);
