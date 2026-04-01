<?php
/**
 * This view builds a Spreadsheet file of the native report 'balance of leave requests'.
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.2.0
 */

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle(mb_strimwidth(lang('reports_export_balance_title'), 0, 28, "..."));  //Maximum 31 characters allowed in sheet title.
$max = 0;
$line = 2;
$i18n = ["identifier", "firstname", "lastname", "datehired", "department", "position", "contract"];
foreach ($result as $row) {
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
}

$colidx = columnName($max) . '1';
$sheet->getStyle('A1:' . $colidx)->getFont()->setBold(true);
$sheet->getStyle('A1:' . $colidx)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

//Autofit
for ($ii = 1; $ii < $max; $ii++) {
    $col = columnName($ii);
    $sheet->getColumnDimension($col)->setAutoSize(TRUE);
}

writeSpreadsheet($spreadsheet, 'leave_balance');
