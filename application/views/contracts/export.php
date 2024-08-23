<?php
/**
 * This view exports the list of contracts into a Spreadsheet file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle(mb_strimwidth(lang('contract_index_title'), 0, 28, "..."));  //Maximum 31 characters allowed in sheet title.
$sheet->setCellValue('A1', lang('contract_export_thead_id'));
$sheet->setCellValue('B1', lang('contract_export_thead_name'));
$sheet->setCellValue('C1', lang('contract_export_thead_start'));
$sheet->setCellValue('D1', lang('contract_export_thead_end'));
$sheet->getStyle('A1:D1')->getFont()->setBold(true);
$sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$contracts = $this->contracts_model->getContracts();
$line = 2;
foreach ($contracts as $contract) {
    $sheet->setCellValue('A' . $line, $contract['id']);
    $sheet->setCellValue('B' . $line, $contract['name']);
    $startentdate = $contract['startentdate'];
    $endentdate = $contract['endentdate'];
    if (strpos(lang('global_date_format'), 'd') < strpos(lang('global_date_format'), 'm')) {
        $pieces = explode("/", $startentdate);
        $startentdate = $pieces[1] . '/' . $pieces[0];
        $pieces = explode("/", $endentdate);
        $endentdate = $pieces[1] . '/' . $pieces[0];
    }
    $sheet->setCellValue('C' . $line, $startentdate);
    $sheet->setCellValue('D' . $line, $endentdate);
    $line++;
}

//Autofit
foreach(range('A', 'D') as $colD) {
    $sheet->getColumnDimension($colD)->setAutoSize(TRUE);
}

writeSpreadsheet($spreadsheet, 'contracts');
