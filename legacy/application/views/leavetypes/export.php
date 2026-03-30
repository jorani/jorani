<?php
/**
 * This view builds a Spreadsheet file containing the list of leave types.
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.2.0
 */

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle(mb_strimwidth(lang('leavetypes_type_export_title'), 0, 28, "..."));  //Maximum 31 characters allowed in sheet title.
$sheet->setCellValue('A1', lang('leavetypes_type_export_thead_id'));
$sheet->setCellValue('B1', lang('leavetypes_type_export_thead_acronym'));
$sheet->setCellValue('C1', lang('leavetypes_type_export_thead_name'));
$sheet->setCellValue('D1', lang('leavetypes_type_export_thead_deduct'));
$sheet->getStyle('A1:D1')->getFont()->setBold(true);
$sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$line = 2;
foreach ($leavetypes as $type) {
    $sheet->setCellValue('A' . $line, $type->getId());
    $sheet->setCellValue('B' . $line, $type->getAcronym());
    $sheet->setCellValue('C' . $line, $type->getName());
    if ($type->isDeductDaysOff()) {
        $sheet->setCellValue('D' . $line, lang('global_true'));
    } else {
        $sheet->setCellValue('D' . $line, lang('global_false'));
    }
    $line++;
}

//Autofit
foreach (range('A', 'D') as $colD) {
    $sheet->getColumnDimension($colD)->setAutoSize(TRUE);
}

writeSpreadsheet($spreadsheet, 'leave_types');
