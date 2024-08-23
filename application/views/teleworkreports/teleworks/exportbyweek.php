<?php
/**
 * This view exports into a Spreadsheet file the native report listing the approved telework requests of employees attached to an entity.
 * This report is launched by the user from the view teleworkreports/teleworks.
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.4.3
 */

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle(mb_strimwidth(lang('teleworkreports_export_teleworks_title'), 0, 28, "..."));  //Maximum 31 characters allowed in sheet title.

$week = $this->input->get("week") === FALSE ? 0 : $this->input->get("week");
$year = $this->input->get("year") === FALSE ? 0 : $this->input->get("year");
$entity = $this->input->get("entity") === FALSE ? 0 : $this->input->get("entity");
$children = filter_var($this->input->get("children"), FILTER_VALIDATE_BOOLEAN);

if ($children === TRUE) {
    $this->load->model('organization_model');
    $list = $this->organization_model->getAllChildren($entity);
    if ($list[0]['id'] != '') {
        $ids = explode(",", $list[0]['id']);
        array_push($ids, $entity);
    } else {
        $ids[] = $entity;
    }
} else {
    $ids[] = $entity;
}

$teleworks = $this->teleworks_model->getTeleworksByweek($week, $year);
$users = array();
$weekdates = get_week_dates_by_week($week, $year);
for ($i = 0; $i < count($teleworks); $i ++) {
    if (! in_array($teleworks[$i]['employee'], $users) && in_array($teleworks[$i]['organization_id'], $ids))
        $users[$teleworks[$i]['employee']] = array(
            'organization_id' => $teleworks[$i]['organization_id'],
            'parent_id' => $teleworks[$i]['parent_id'],
            'firstname' => $teleworks[$i]['firstname'],
            'lastname' => $teleworks[$i]['lastname'],
            'organization_name' => $teleworks[$i]['organization_name'],
            $weekdates['monday'] . ' Morning' => array(),
            $weekdates['monday'] . ' Afternoon' => array(),
            $weekdates['tuesday'] . ' Morning' => array(),
            $weekdates['tuesday'] . ' Afternoon' => array(),
            $weekdates['wednesday'] . ' Morning' => array(),
            $weekdates['wednesday'] . ' Afternoon' => array(),
            $weekdates['thursday'] . ' Morning' => array(),
            $weekdates['thursday'] . ' Afternoon' => array(),
            $weekdates['friday'] . ' Morning' => array(),
            $weekdates['friday'] . ' Afternoon' => array(),
            'duration' => 0
        );
}
for ($i = 0; $i < count($teleworks); $i ++) {
    $overlapleaves = $this->teleworks_model->detectOverlappingLeavesForTelework($teleworks[$i]['employee'], $teleworks[$i]['startdate'], $teleworks[$i]['enddate'], $teleworks[$i]['startdatetype'], $teleworks[$i]['enddatetype']);
    if (in_array($teleworks[$i]['organization_id'], $ids) && ! $overlapleaves){
        if ($teleworks[$i]['startdatetype'] != $teleworks[$i]['enddatetype']) {
            $users[$teleworks[$i]['employee']][$teleworks[$i]['startdate'] . ' Morning'] = array(
                'duration' => 0.500,
                'type' => $teleworks[$i]['type']
            );
            $users[$teleworks[$i]['employee']][$teleworks[$i]['startdate'] . ' Afternoon'] = array(
                'duration' => 0.500,
                'type' => $teleworks[$i]['type']
            );
            $users[$teleworks[$i]['employee']]['duration'] = $users[$teleworks[$i]['employee']]['duration'] + 1.000;
        } else {
            $users[$teleworks[$i]['employee']][$teleworks[$i]['startdate'] . ' ' . $teleworks[$i]['startdatetype']] = array(
                'duration' => $teleworks[$i]['duration'],
                'type' => $teleworks[$i]['type']
            );
            $users[$teleworks[$i]['employee']]['duration'] = $users[$teleworks[$i]['employee']]['duration'] + 0.500;
        }
    }
}

$max = 0;
$line = 2;
$i18n = array(
    "firstname",
    "lastname",
    "organization_name",
    $weekdates['monday'] . ' Morning',
    $weekdates['monday'] . ' Afternoon',
    $weekdates['tuesday'] . ' Morning',
    $weekdates['tuesday'] . ' Afternoon',
    $weekdates['wednesday'] . ' Morning',
    $weekdates['wednesday'] . ' Afternoon',
    $weekdates['thursday'] . ' Morning',
    $weekdates['thursday'] . ' Afternoon',
    $weekdates['friday'] . ' Morning',
    $weekdates['friday'] . ' Afternoon',
    'duration'
);
foreach ($users as $row) {
    $index = -1;
    foreach ($row as $key => $value) {
        if (in_array($key, $i18n)) {
            if ($line == 2) {
                $colidx = columnName($index) . '1';
                $explode = explode(' ', $key);
                if (count($explode) == 1)
                    $sheet->setCellValue($colidx, lang($key));
                else
                    $sheet->setCellValue($colidx, lang((new DateTime($explode[0]))->format('l')) . ' ' . (new DateTime($explode[0]))->format('d') . ' ' . lang((new DateTime($explode[0]))->format('F')) . ' ' . lang($explode[1]));
                $max ++;
            } 
            $colidx = columnName($index) . $line;
            if (! is_array($value))
                $sheet->setCellValue($colidx, $value);
            else {
                if (count($value) == 0)
                    $sheet->setCellValue($colidx, '');
                else
                    $sheet->setCellValue($colidx, lang($value['type']));
            }
        }
        $index ++;
    }
    $line ++;
}

$colidx = columnName($max) . '1';
// $style = array(
//     'alignment' => array(
//         'horizontal' => Alignment::HORIZONTAL_CENTER,
//         'wrapText' => true
//     ),
//     'font' => array(
//         'bold' => true
//     )
// );
// $sheet->getStyle('A1:' . $colidx)->applyFromArray($style);
$sheet->getStyle('A1:' . $colidx)->getFont()->setBold(true);
$sheet->getStyle('A1:' . $colidx)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:' . $colidx)->getAlignment()->setWrapText(true);

//Autofit
for ($ii=1; $ii <$max; $ii++) {
    $col = columnName($ii);
    $sheet->getColumnDimension($col)->setAutoSize(TRUE);
}

$spreadsheet->exportName = 'telework_requests_by_week_'. $week . '_' . $year;
writeSpreadsheet($spreadsheet);
