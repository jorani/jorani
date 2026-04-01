<?php
/**
 * This view exports into a Spreadsheet file the native report listing the approved leave requests of employees attached to an entity.
 * This report is launched by the user from the view reports/leaves.
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.4.3
 */

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle(mb_strimwidth(lang('reports_export_leaves_title'), 0, 28, "..."));  //Maximum 31 characters allowed in sheet title.


$max = 0;
$line = 2;
$i18n = array("identifier", "firstname", "lastname", "datehired", "department", "position", "contract");
foreach ($result as $user_id => $row) {
    $index = 1;
    foreach ($row as $key => $value) {
        if ($line == 2) {
            $colidx = columnName($index) . '1';
            if (in_array($key, $i18n)) {
                $sheet->setCellValue($colidx, lang($key));
            } else {
                $sheet->setCellValue($colidx, $key);
            }
            $max++;
        }
        $colidx = columnName($index) . $line;
        $sheet->setCellValue($colidx, $value);
        $index++;
    }
    $line++;
    //Display a nested table listing the leave requests
    if ($requests) {
        if (count($leave_requests[$user_id])) {
            $sheet->setCellValue('A' . $line, lang('leaves_index_thead_start_date'));
            $sheet->setCellValue('B' . $line, lang('leaves_index_thead_end_date'));
            $sheet->setCellValue('C' . $line, lang('leaves_index_thead_type'));
            $sheet->setCellValue('D' . $line, lang('leaves_index_thead_duration'));
            $sheet->getStyle('A' . $line . ':D' . $line)->getFont()->setBold(true);
            $sheet->getStyle('A' . $line . ':D' . $line)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $line++;
            //Iterate on leave requests
            foreach ($leave_requests[$user_id] as $request) {
                $date = new DateTime($request['startdate']);
                $startdate = $date->format(lang('global_date_format'));
                $date = new DateTime($request['enddate']);
                $enddate = $date->format(lang('global_date_format'));
                $sheet->setCellValue('A' . $line, $startdate . ' (' . lang($request['startdatetype']) . ')');
                $sheet->setCellValue('B' . $line, $enddate . ' (' . lang($request['enddatetype']) . ')');
                $sheet->setCellValue('C' . $line, $request['type']);
                $sheet->setCellValue('D' . $line, $request['duration']);
                $line++;
            }
        } else {
            $sheet->setCellValue('A' . $line, "----");
            $line++;
        }
    }
}

$colidx = columnName($max) . '1';
$sheet->getStyle('A1:' . $colidx)->getFont()->setBold(true);
$sheet->getStyle('A1:' . $colidx)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

//Autofit
for ($ii = 1; $ii < $max; $ii++) {
    $col = columnName($ii);
    $sheet->getColumnDimension($col)->setAutoSize(TRUE);
}

writeSpreadsheet($spreadsheet, 'leave_requests_' . $month . '_' . $year);
