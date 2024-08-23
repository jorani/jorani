<?php
/**
 * This view builds a Spreadsheet file containing the list of leave types.
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

$sheet->setTitle(mb_strimwidth(lang('leavetypes_type_export_title'), 0, 28, "..."));  //Maximum 31 characters allowed in sheet title.
$sheet->setCellValue('A1', lang('leavetypes_type_export_thead_id'));
$sheet->setCellValue('B1', lang('leavetypes_type_export_thead_acronym'));
$sheet->setCellValue('C1', lang('leavetypes_type_export_thead_name'));
$sheet->setCellValue('D1', lang('leavetypes_type_export_thead_deduct'));
$sheet->getStyle('A1:D1')->getFont()->setBold(true);
$sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$types = $this->types_model->getTypes();
$line = 2;
foreach ($types as $type) {
    $sheet->setCellValue('A' . $line, $type['id']);
    $sheet->setCellValue('B' . $line, $type['acronym']);
    $sheet->setCellValue('C' . $line, $type['name']);
    if ($type['deduct_days_off'] == TRUE) {
        $sheet->setCellValue('D' . $line, lang('global_true'));
    } else {
        $sheet->setCellValue('D' . $line, lang('global_false'));
    }
    $line++;
}

//Autofit
foreach(range('A', 'D') as $colD) {
    $sheet->getColumnDimension($colD)->setAutoSize(TRUE);
}

writeSpreadsheet($spreadsheet, 'leave_types');
