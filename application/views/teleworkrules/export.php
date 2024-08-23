<?php
/**
 * This view builds a Spreadsheet file containing the list of telework rules.
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

$sheet->setTitle(mb_strimwidth(lang('telework_rule_export_title'), 0, 28, "..."));  //Maximum 31 characters allowed in sheet title.
$sheet->setCellValue('A1', lang('telework_rule_export_thead_organization_id'));
$sheet->setCellValue('B1', lang('telework_rule_export_thead_organization'));
$sheet->setCellValue('C1', lang('telework_rule_export_thead_limit'));
$sheet->setCellValue('D1', lang('telework_rule_export_thead_delay'));

$sheet->getStyle('A1:D1')->getFont()->setBold(true);
$sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$teleworkrules = $this->telework_rule_model->getTeleworkRulesForExport();
$line = 2;
foreach ($teleworkrules as $teleworkrule) {
    $sheet->setCellValue('A' . $line, $teleworkrule['organization_id']);
    $sheet->setCellValue('B' . $line, $teleworkrule['name']);
    $sheet->setCellValue('C' . $line, $teleworkrule['limit']);
    $sheet->setCellValue('D' . $line, $teleworkrule['delay']);
    $line++;
}

//Autofit
foreach(range('A', 'D') as $colD) {
    $sheet->getColumnDimension($colD)->setAutoSize(TRUE);
}

$spreadsheet->exportName = 'teleworkrules';
writeSpreadsheet($spreadsheet);


