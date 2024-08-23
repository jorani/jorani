<?php
/**
 * This view exports a tabular calendar of the leave taken by a group of users
 * It builds a Spreadsheet file downloaded by the browser.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
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

//Print the header with the values of the export parameters
$sheet->setTitle(mb_strimwidth(lang('calendar_tabular_export_title'), 0, 28, "..."));  //Maximum 31 characters allowed in sheet title.
$sheet->setCellValue('A1', lang('calendar_tabular_export_param_entity'));
$sheet->setCellValue('A2', lang('calendar_tabular_export_param_month'));
$sheet->setCellValue('A3', lang('calendar_tabular_export_param_year'));
$sheet->setCellValue('A4', lang('calendar_tabular_export_param_children'));
$sheet->getStyle('A1:A4')->getFont()->setBold(true);
$sheet->setCellValue('B1', $entityName);
$sheet->setCellValue('B2', $month . ' (' . $monthName . ')');
$sheet->setCellValue('B3', $year);
if ($children == TRUE) {
    $sheet->setCellValue('B4', lang('global_true'));
} else {
    $sheet->setCellValue('B4', lang('global_false'));
}

//Print two lines : the short name of all days for the selected month (horizontally aligned)
$start = $year . '-' . $month . '-' . '1';    //first date of selected month
$lastDay = date("t", strtotime($start));    //last day of selected month
for ($ii = 1; $ii <=$lastDay; $ii++) {
    $dayNum = date("N", strtotime($year . '-' . $month . '-' . $ii));
    $col = columnName(3 + $ii);
    //Print day number
    $sheet->setCellValue($col . '9', $ii);
    //Print short name of the day
    switch ($dayNum)
    {
        case 1: $sheet->setCellValue($col . '8', lang('calendar_monday_short')); break;
        case 2: $sheet->setCellValue($col . '8', lang('calendar_tuesday_short')); break;
        case 3: $sheet->setCellValue($col . '8', lang('calendar_wednesday_short')); break;
        case 4: $sheet->setCellValue($col . '8', lang('calendar_thursday_short')); break;
        case 5: $sheet->setCellValue($col . '8', lang('calendar_friday_short')); break;
        case 6: $sheet->setCellValue($col . '8', lang('calendar_saturday_short')); break;
        case 7: $sheet->setCellValue($col . '8', lang('calendar_sunday_short')); break;
    }
}
//Label for employee name
$sheet->setCellValue('C8', lang('calendar_tabular_export_thead_employee'));
$sheet->mergeCells('C8:C9');
//The header is horizontally aligned
$col = columnName(3 + $lastDay);
$sheet->getStyle('C8:' . $col . '9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

//Box around the lines for each employee
$styleBox = array(
    'borders' => array(
        'top' => array(
            'borderStyle' => Border::BORDER_THIN
        ),
        'bottom' => array(
            'borderStyle' => Border::BORDER_THIN
        )
    )
  );

//Box around a day
$dayBox =  array(
    'borders' => array(
        'left' => array(
            'borderStyle' => Border::BORDER_DASHDOT,
            'color' => array('rgb' => '808080')
        ),
        'right' => array(
            'borderStyle' => Border::BORDER_DASHDOT,
            'color' => array('rgb' => '808080')
        )
    )
 );

//Background colors for the calendar according to the type of leave
$styleBgPlanned = array(
    'fill' => array(
        'fillType' => Fill::FILL_SOLID,
        'startColor' => array('rgb' => 'DDD')
    )
);
$styleBgRequested = array(
    'fill' => array(
        'fillType' => Fill::FILL_SOLID,
        'startColor' => array('rgb' => 'F89406')
    )
);
$styleBgAccepted = array(
    'fill' => array(
        'fillType' => Fill::FILL_SOLID,
        'startColor' => array('rgb' => '468847')
    )
);
$styleBgRejected = array(
    'fill' => array(
        'fillType' => Fill::FILL_SOLID,
        'startColor' => array('rgb' => 'FF0000')
    )
);
$styleBgDayOff = array(
    'fill' => array(
        'fillType' => Fill::FILL_SOLID,
        'startColor' => array('rgb' => '000000')
    )
);
if ($this->config->item('disable_telework') == FALSE)
    $styleBgTWAccepted = array(
        'fill' => array(
            'fillType' => Fill::FILL_SOLID,
            'startColor' => array('rgb' => '3a87ad')
        )
    );

if ($this->config->item('disable_time_organisation') == FALSE && $this->config->item('hide_time_orgnisation_in_cals') === FALSE)
    $styleBgTOAccepted = array(
        'fill' => array(
            'fillType' => Fill::FILL_SOLID,
            'startColor' => array('rgb' => '9d159d')
        )
    );

$canSeeType = TRUE;
$line = 10;
//Iterate on all employees of the selected entity
foreach ($tabular as $employee) {
    //Merge the two line containing the name of the employee and apply a border around it
    $sheet->setCellValue('C' . $line, $employee->name);
    $sheet->mergeCells('C' . $line . ':C' . ($line + 1));
    $col = columnName($lastDay + 3);
    $sheet->getStyle('C' . $line . ':' . $col . ($line + 1))->applyFromArray($styleBox);

    //Iterate on all days of the selected month
    $dayNum = 0;
    foreach ($employee->days as $day) {
        if (($is_hr == TRUE) ||
                ($is_admin == TRUE) ||
                ($employee->manager == $user_id) ||
                ($employee->id == $user_id)) {
            $canSeeType = TRUE;
        } else {
            $canSeeType = FALSE;
        }
        $dayNum++;
        $col = columnName(3 + $dayNum);
        if (strstr($day->display, ';')) {//Two statuses in the cell
            $statuses = explode(";", $day->status);
            $types = explode(";", $day->type);
            $displays = explode(";", $day->display);
            $acronyms = explode(";", $day->acronym);
                //0 - Working day  _
                //1 - All day           []
                //2 - Morning        |\
                //3 - Afternoon      /|
                //4 - All Day Off       []
                //5 - Morning Day Off   |\
                //6 - Afternoon Day Off /|
            switch ($displays[0]) {
                case '1':   //All day
                    if ($displayTypes && $canSeeType) {
                        $sheet->setCellValue($col . $line, $acronyms[0]);
                        $sheet->setCellValue($col . ($line + 1), $acronyms[0]);
                    }
                    $sheet->getComment($col . $line)->getText()->createTextRun($types[0]);
                    $sheet->getComment($col . ($line + 1))->getText()->createTextRun($types[0]);
                    switch ($statuses[0])
                    {
                        // 1 : 'Planned';
                        // 2 : 'Requested';
                        // 3 : 'Accepted';
                        // 4 : 'Rejected';
                        case 1: $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgPlanned); break;  // Planned
                        case 2: $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgRequested); break; // Requested
                        case 3: if($types[0] == 'Campaign' || $types[0] == 'Floating') $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgTWAccepted); elseif($types[0] == 'Time organisation') $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgTOAccepted); else $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgAccepted); break;  // Accepted
                        case 4: $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Rejected
                        case 5: $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Cancellation
                        case 6: $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Canceled
                    }
                    break;
                case '2':   //AM
                    if ($displayTypes && $canSeeType) {
                        $sheet->setCellValue($col . $line, $acronyms[0]);
                    }
                    $sheet->getComment($col . $line)->getText()->createTextRun($types[0]);
                    switch ($statuses[0])
                    {
                        case 1: $sheet->getStyle($col . $line)->applyFromArray($styleBgPlanned); break;  // Planned
                        case 2: $sheet->getStyle($col . $line)->applyFromArray($styleBgRequested); break;  // Requested
                        case 3: if($types[0] == 'Campaign' || $types[0] == 'Floating') $sheet->getStyle($col . $line)->applyFromArray($styleBgTWAccepted); elseif($types[0] == 'Time organisation') $sheet->getStyle($col . $line)->applyFromArray($styleBgTOAccepted); else $sheet->getStyle($col . $line)->applyFromArray($styleBgAccepted); break;  // Accepted
                        case 4: $sheet->getStyle($col . $line)->applyFromArray($styleBgRejected); break;  // Rejected
                        case 5: $sheet->getStyle($col . $line)->applyFromArray($styleBgRejected); break;  // Cancellation
                        case 6: $sheet->getStyle($col . $line)->applyFromArray($styleBgRejected); break;  // Canceled
                    }
                    break;
                case '3':   //PM
                    if ($displayTypes && $canSeeType) {
                        $sheet->setCellValue($col . ($line + 1), $acronyms[0]);
                    }
                    $sheet->getComment($col . ($line + 1))->getText()->createTextRun($types[0]);
                    switch ($statuses[0])
                    {
                        case 1: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgPlanned); break;  // Planned
                        case 2: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgRequested); break;  // Requested
                        case 3: if($types[0] == 'Campaign' || $types[0] == 'Floating') $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgTWAccepted); elseif($types[0] == 'Time organisation') $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgTOAccepted); else $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgAccepted); break;  // Accepted
                        case 4: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Rejected
                        case 5: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Cancellation
                        case 6: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Canceled
                    }
                    break;
                case '4': //Full day off
                    $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgDayOff);
                    $sheet->getComment($col . $line)->getText()->createTextRun($types[0]);
                    $sheet->getComment($col . ($line + 1))->getText()->createTextRun($types[0]);
                    break;
                case '5':  //AM off
                    $sheet->getStyle($col . $line)->applyFromArray($styleBgDayOff);
                    $sheet->getComment($col . $line)->getText()->createTextRun($types[0]);
                    break;
                case '6':   //PM off
                    $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgDayOff);
                    $sheet->getComment($col . ($line + 1))->getText()->createTextRun($types[0]);
                    break;
            }
            switch ($displays[1]) {
                case '1':   //All day
                    if ($displayTypes && $canSeeType) {
                        $sheet->setCellValue($col . $line, $acronyms[1]);
                        $sheet->setCellValue($col . ($line + 1), $acronyms[1]);
                    }
                    $sheet->getComment($col . $line)->getText()->createTextRun($types[1]);
                    $sheet->getComment($col . ($line + 1))->getText()->createTextRun($types[1]);
                    switch ($statuses[1])
                    {
                        // 1 : 'Planned';
                        // 2 : 'Requested';
                        // 3 : 'Accepted';
                        // 4 : 'Rejected';
                        case 1: $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgPlanned); break;  // Planned
                        case 2: $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgRequested); break; // Requested
                        case 3: if($types[1] == 'Campaign' || $types[1] == 'Floating') $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgTWAccepted); elseif($types[1] == 'Time organisation') $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgTOAccepted); else $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgAccepted); break;  // Accepted
                        case 4: $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Rejected
                        case 5: $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Cancellation
                        case 6: $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Canceled
                    }
                    break;
                case '2':   //AM
                    if ($displayTypes && $canSeeType) {
                        $sheet->setCellValue($col . $line, $acronyms[1]);
                    }
                    $sheet->getComment($col . $line)->getText()->createTextRun($types[1]);
                    switch ($statuses[1])
                    {
                        case 1: $sheet->getStyle($col . $line)->applyFromArray($styleBgPlanned); break;  // Planned
                        case 2: $sheet->getStyle($col . $line)->applyFromArray($styleBgRequested); break;  // Requested
                        case 3: if($types[1] == 'Campaign' || $types[1] == 'Floating') $sheet->getStyle($col . $line)->applyFromArray($styleBgTWAccepted); elseif($types[1] == 'Time organisation') $sheet->getStyle($col . $line)->applyFromArray($styleBgTOAccepted); else $sheet->getStyle($col . $line)->applyFromArray($styleBgAccepted); break;  // Accepted
                        case 4: $sheet->getStyle($col . $line)->applyFromArray($styleBgRejected); break;  // Rejected
                        case 5: $sheet->getStyle($col . $line)->applyFromArray($styleBgRejected); break;  // Cancellation
                        case 6: $sheet->getStyle($col . $line)->applyFromArray($styleBgRejected); break;  // Canceled
                    }
                    break;
                case '3':   //PM
                    if ($displayTypes && $canSeeType) {
                        $sheet->setCellValue($col . ($line + 1), $acronyms[1]);
                    }
                    $sheet->getComment($col . ($line + 1))->getText()->createTextRun($types[1]);
                    switch ($statuses[1])
                    {
                        case 1: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgPlanned); break;  // Planned
                        case 2: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgRequested); break;  // Requested
                        case 3: if($types[1] == 'Campaign' || $types[1] == 'Floating') $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgTWAccepted); elseif($types[1] == 'Time organisation') $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgTOAccepted); else $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgAccepted); break;  // Accepted
                        case 4: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Rejected
                        case 5: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Cancellation
                        case 6: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Canceled
                    }
                    break;
                case '4': //Full day off
                    $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgDayOff);
                    $sheet->getComment($col . $line)->getText()->createTextRun($types[1]);
                    $sheet->getComment($col . ($line + 1))->getText()->createTextRun($types[1]);
                    break;
                case '5':  //AM off
                    $sheet->getStyle($col . $line)->applyFromArray($styleBgDayOff);
                    $sheet->getComment($col . $line)->getText()->createTextRun($types[1]);
                    break;
                case '6':   //PM off
                    $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgDayOff);
                    $sheet->getComment($col . ($line + 1))->getText()->createTextRun($types[1]);
                    break;
            }
        } else {//Only one status in the cell
            switch ($day->display) {
                case '1':   //All day
                        if ($displayTypes && $canSeeType) {
                            $sheet->setCellValue($col . $line, $day->acronym);
                            $sheet->setCellValue($col . ($line + 1), $day->acronym);
                        }
                        $sheet->getComment($col . $line)->getText()->createTextRun($day->type);
                        $sheet->getComment($col . ($line + 1))->getText()->createTextRun($day->type);
                        switch ($day->status)
                        {
                            // 1 : 'Planned';
                            // 2 : 'Requested';
                            // 3 : 'Accepted';
                            // 4 : 'Rejected';
                            case 1: $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgPlanned); break;  // Planned
                            case 2: $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgRequested); break; // Requested
                            case 3: if($day->type == 'Campaign' || $day->type == 'Floating') $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgTWAccepted); elseif($day->type == 'Time organisation') $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgTOAccepted); else $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgAccepted); break;  // Accepted
                            case 4: $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Rejected
                            case 5: $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Cancellation
                            case 6: $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Canceled
                        }
                        break;
                case '2':   //AM
                    if ($displayTypes && $canSeeType) {
                        $sheet->setCellValue($col . $line, $day->acronym);
                    }
                    $sheet->getComment($col . $line)->getText()->createTextRun($day->type);
                    switch ($day->status)
                      {
                          case 1: $sheet->getStyle($col . $line)->applyFromArray($styleBgPlanned); break;  // Planned
                          case 2: $sheet->getStyle($col . $line)->applyFromArray($styleBgRequested); break;  // Requested
                          case 3: if($day->type == 'Campaign' || $day->type == 'Floating') $sheet->getStyle($col . $line)->applyFromArray($styleBgTWAccepted); elseif($day->type == 'Time organisation') $sheet->getStyle($col . $line)->applyFromArray($styleBgTOAccepted); else $sheet->getStyle($col . $line)->applyFromArray($styleBgAccepted); break;  // Accepted
                          case 4: $sheet->getStyle($col . $line)->applyFromArray($styleBgRejected); break;  // Rejected
                          case 5: $sheet->getStyle($col . $line)->applyFromArray($styleBgRejected); break;  // Cancellation
                          case 6: $sheet->getStyle($col . $line)->applyFromArray($styleBgRejected); break;  // Canceled
                      }
                    break;
                case '3':   //PM
                    if ($displayTypes && $canSeeType) {
                        $sheet->setCellValue($col . ($line + 1), $day->acronym);
                    }
                    $sheet->getComment($col . ($line + 1))->getText()->createTextRun($day->type);
                    switch ($day->status)
                      {
                          case 1: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgPlanned); break;  // Planned
                          case 2: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgRequested); break;  // Requested
                          case 3: if($day->type == 'Campaign' || $day->type == 'Floating') $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgTWAccepted); elseif($day->type == 'Time organisation') $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgTOAccepted); else $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgAccepted); break;  // Accepted
                          case 4: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Rejected
                          case 5: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Cancellation
                          case 6: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Canceled
                      }
                    break;
                case '4': //Full day off
                    $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgDayOff);
                    $sheet->getComment($col . $line)->getText()->createTextRun($day->type);
                    $sheet->getComment($col . ($line + 1))->getText()->createTextRun($day->type);
                    break;
                case '5':  //AM off
                    $sheet->getStyle($col . $line)->applyFromArray($styleBgDayOff);
                    $sheet->getComment($col . $line)->getText()->createTextRun($day->type);
                    break;
                case '6':   //PM off
                    $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgDayOff);
                    $sheet->getComment($col . ($line + 1))->getText()->createTextRun($day->type);
                    break;
            }
          }//Only one status in the cell
    }//day
    $line += 2;
}//Employee

//Autofit for all column containing the days
for ($ii = 1; $ii <=$lastDay; $ii++) {
    $col = columnName($ii + 3);
    $sheet->getStyle($col . '8:' . $col . ($line - 1))->applyFromArray($dayBox);
    $sheet->getColumnDimension($col)->setAutoSize(TRUE);
}
$sheet->getColumnDimension('A')->setAutoSize(TRUE);
$sheet->getColumnDimension('B')->setAutoSize(TRUE);
$sheet->getColumnDimension('C')->setWidth(40);

//Set layout to landscape and make the Excel sheet to fit to the page
$sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
$sheet->getPageSetup()->setFitToPage(TRUE);
$sheet->getPageSetup()->setFitToWidth(1);
$sheet->getPageSetup()->setFitToHeight(0);

writeSpreadsheet($spreadsheet, 'tabular');
