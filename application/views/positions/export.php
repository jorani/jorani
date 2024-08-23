<?php
/**
 * This view builds a Spreadsheet file containing the list of positions.
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

$sheet->setTitle(mb_strimwidth(lang('positions_export_title'), 0, 28, "..."));  //Maximum 31 characters allowed in sheet title.
$sheet->setCellValue('A1', lang('positions_export_thead_id'));
$sheet->setCellValue('B1', lang('positions_export_thead_name'));
$sheet->setCellValue('C1', lang('positions_export_thead_description'));
$sheet->getStyle('A1:C1')->getFont()->setBold(true);
$sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$positions = $this->positions_model->getPositions();
$line = 2;
foreach ($positions as $position) {
    $sheet->setCellValue('A' . $line, $position['id']);
    $sheet->setCellValue('B' . $line, $position['name']);
    $sheet->setCellValue('C' . $line, $position['description']);
    $line++;
}

//Autofit
foreach(range('A', 'C') as $colD) {
    $sheet->getColumnDimension($colD)->setAutoSize(TRUE);
}

writeSpreadsheet($spreadsheet, 'positions');
