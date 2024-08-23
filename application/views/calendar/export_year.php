<?php
/**
 * This view exports a yearly calendar of the leave taken by a user (can be displayed by HR or manager)
 * It builds an Excel 2007 file downloaded by the browser.
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

//Either self access, Manager or HR
if ($employee == 0) {
    $employee = $this->user_id;
} else {
    if (!$this->is_hr) {
        if ($this->manager != $this->user_id) {
            $employee = $this->user_id;
        }
    }
}

$employee_name = $this->users_model->getName($employee);
//Load the leaves for all the months of the selected year

$months = array(
    lang('January') => $this->leaves_model->linear($employee, 1, $year, TRUE, TRUE, TRUE, TRUE),
    lang('February') => $this->leaves_model->linear($employee, 2, $year, TRUE, TRUE, TRUE, TRUE),
    lang('March') => $this->leaves_model->linear($employee, 3, $year, TRUE, TRUE, TRUE, TRUE),
    lang('April') => $this->leaves_model->linear($employee, 4, $year, TRUE, TRUE, TRUE, TRUE),
    lang('May') => $this->leaves_model->linear($employee, 5, $year, TRUE, TRUE, TRUE, TRUE),
    lang('June') => $this->leaves_model->linear($employee, 6, $year, TRUE, TRUE, TRUE, TRUE),
    lang('July') => $this->leaves_model->linear($employee, 7, $year, TRUE, TRUE, TRUE, TRUE),
    lang('August') => $this->leaves_model->linear($employee, 8, $year, TRUE, TRUE, TRUE, TRUE),
    lang('September') => $this->leaves_model->linear($employee, 9, $year, TRUE, TRUE, TRUE, TRUE),
    lang('October') => $this->leaves_model->linear($employee, 10, $year, TRUE, TRUE, TRUE, TRUE),
    lang('November') => $this->leaves_model->linear($employee, 11, $year, TRUE, TRUE, TRUE, TRUE),
    lang('December') => $this->leaves_model->linear($employee, 12, $year, TRUE, TRUE, TRUE, TRUE),
);
if ($this->config->item('disable_telework') === FALSE) {
    foreach ($months as $month) {
        $this->leaves_model->removeOverlappingTeleworks($month);
    }
}

//Print the header with the values of the export parameters
$sheet->setTitle(mb_strimwidth(lang('calendar_year_title'), 0, 28, "..."));  //Maximum 31 characters allowed in sheet title.
$sheet->setCellValue('A1', lang('calendar_year_title') . ' ' . $year . ' (' . $employee_name . ') ');
$sheet->getStyle('A1')->getFont()->setBold(true);
$sheet->mergeCells('A1:C1');

//Print a line with all possible day numbers (1 to 31)
for ($ii = 1; $ii <= 31; $ii++) {
    $col = columnName(3 + $ii);
    $sheet->setCellValue($col . '3', $ii);
}

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

//To fill at the left of months having less than 31 days
 $styleMonthPad = array(
    'fill' => array(
      'fillType' => Fill::FILL_SOLID,
      'startColor' => array('rgb' => '00FFFF')
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

$line = 4;
//Iterate on all employees of the selected entity
foreach ($months as $month_name => $month) {
    //Merge the two line containing the name of the month and apply a border around it
    $sheet->setCellValue('C' . $line, $month_name);
    $sheet->mergeCells('C' . $line . ':C' . ($line + 1));
    $col = columnName(34);
    $sheet->getStyle('C' . $line . ':' . $col . ($line + 1))->applyFromArray($styleBox);

    //Iterate on all days of the selected month
    $dayNum = 0;
    foreach ($month->days as $day) {
        $dayNum++;
        $col = columnName(3 + $dayNum);
        if (strstr($day->display, ';')) {//Two statuses in the cell
            $statuses = explode(";", $day->status);
            $types = explode(";", $day->type);
                //0 - Working day  _
                //1 - All day           []
                //2 - Morning        |\
                //3 - Afternoon      /|
                //4 - All Day Off       []
                //5 - Morning Day Off   |\
                //6 - Afternoon Day Off /|
              $sheet->getComment($col . $line)->getText()->createTextRun($types[0]);
              $sheet->getComment($col . ($line + 1))->getText()->createTextRun($types[1]);
              switch (intval($statuses[1]))
              {
                case 1: $sheet->getStyle($col . $line)->applyFromArray($styleBgPlanned); break;  // Planned
                case 2: $sheet->getStyle($col . $line)->applyFromArray($styleBgRequested); break;  // Requested
                case 3: if($types[1] == 'Campaign' || $types[1] == 'Floating') $sheet->getStyle($col . $line)->applyFromArray($styleBgTWAccepted); else $sheet->getStyle($col . $line)->applyFromArray($styleBgAccepted); break;  // Accepted
                case 4: $sheet->getStyle($col . $line)->applyFromArray($styleBgRejected); break;  // Rejected
                case '5': $sheet->getStyle($col . $line)->applyFromArray($styleBgDayOff); break;    //Day off
                case '6': $sheet->getStyle($col . $line)->applyFromArray($styleBgDayOff); break;    //Day off
              }
              switch (intval($statuses[0]))
              {
                case 1: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgPlanned); break;  // Planned
                case 2: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgRequested); break;  // Requested
                case 3: if($types[0] == 'Campaign' || $types[0] == 'Floating') $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgTWAccepted); else $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgAccepted); break;  // Accepted
                case 4: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Rejected
                case '5': $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgDayOff); break;    //Day off
                case '6': $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgDayOff); break;    //Day off
              }//Two statuses in the cell
        } else {//Only one status in the cell
            switch ($day->display) {
                case '1':   //All day
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
                            case 3: if($day->type == 'Campaign' || $day->type == 'Floating') $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgTWAccepted); else $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgAccepted); break;  // Accepted
                            case 4: $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Rejected
                        }
                        break;
                case '2':   //AM
                    $sheet->getComment($col . $line)->getText()->createTextRun($day->type);
                    switch ($day->status)
                      {
                          case 1: $sheet->getStyle($col . $line)->applyFromArray($styleBgPlanned); break;  // Planned
                          case 2: $sheet->getStyle($col . $line)->applyFromArray($styleBgRequested); break;  // Requested
                          case 3: if($day->type == 'Campaign' || $day->type == 'Floating') $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgTWAccepted); else $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgAccepted); break;  // Accepted
                          case 4: $sheet->getStyle($col . $line)->applyFromArray($styleBgRejected); break;  // Rejected
                      }
                    break;
                case '3':   //PM
                    $sheet->getComment($col . ($line + 1))->getText()->createTextRun($day->type);
                    switch ($day->status)
                      {
                          case 1: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgPlanned); break;  // Planned
                          case 2: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgRequested); break;  // Requested
                          case 3: if($day->type == 'Campaign' || $day->type == 'Floating') $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgTWAccepted); else $sheet->getStyle($col . $line . ':' . $col . ($line + 1))->applyFromArray($styleBgAccepted); break;  // Accepted
                          case 4: $sheet->getStyle($col . ($line + 1))->applyFromArray($styleBgRejected); break;  // Rejected
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
    if ($dayNum < 31) {
        $pad = (int) (35 - (31 - $dayNum));
        $colFrom = columnName($pad);
        $colTo = columnName(34);
        $sheet->mergeCells($colFrom . $line . ':' . $colTo . ($line + 1));
        $sheet->getStyle($colFrom . $line . ':' . $colTo . ($line + 1))->applyFromArray($styleMonthPad);
    }
    $line += 2;
}//Employee

//Autofit for all column containing the days
for ($ii = 1; $ii <= 31; $ii++) {
    $col = columnName($ii + 3);
    $sheet->getStyle($col . '3:' . $col . ($line - 1))->applyFromArray($dayBox);
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

writeSpreadsheet($spreadsheet, 'year');
