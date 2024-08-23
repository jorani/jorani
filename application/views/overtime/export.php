<?php
/**
 * This view builds a Spreadsheet file containing the list of overtime requests (that a manager must validate).
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

$sheet->setTitle(mb_strimwidth(lang('overtime_export_title'), 0, 28, "..."));  //Maximum 31 characters allowed in sheet title.
$sheet->setCellValue('A1', lang('overtime_export_thead_id'));
$sheet->setCellValue('B1', lang('overtime_export_thead_fullname'));
$sheet->setCellValue('C1', lang('overtime_export_thead_date'));
$sheet->setCellValue('D1', lang('overtime_export_thead_duration'));
$sheet->setCellValue('E1', lang('overtime_export_thead_cause'));
$sheet->setCellValue('F1', lang('overtime_export_thead_status'));
$sheet->getStyle('A1:F1')->getFont()->setBold(true);
$sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

if ($filter == 'all') {
    $showAll = true;
} else {
    $showAll = false;
}
$requests = $this->overtime_model->requests($this->user_id, $showAll);
$line = 2;
foreach ($requests as $request) {
    $date = new DateTime($request['date']);
    $startdate = $date->format(lang('global_date_format'));
    $sheet->setCellValue('A' . $line, $request['id']);
    $sheet->setCellValue('B' . $line, $request['firstname'] . ' ' . $request['lastname']);
    $sheet->setCellValue('C' . $line, $startdate);
    $sheet->setCellValue('D' . $line, $request['duration']);
    $sheet->setCellValue('E' . $line, $request['cause']);
    $sheet->setCellValue('F' . $line, lang($request['status_name']));
    $line++;
}

//Autofit
foreach(range('A', 'F') as $colD) {
    $sheet->getColumnDimension($colD)->setAutoSize(TRUE);
}

writeSpreadsheet($spreadsheet, 'overtime');
