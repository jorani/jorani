<?php
//This is a sample page showing how to export a custom report to Excel
//If your custom page is making an export to a file (Excel, etc.) simply include 'export' into its name
//http://localhost/jorani/excel-export

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

//Excel Header
$title = 'All Leave Requests';
$ci =& get_instance();
$ci->lang->load('requests', $ci->language);
$ci->lang->load('global', $ci->language);

$sheet->setTitle($title);
$sheet->setCellValue('A1', lang('requests_index_thead_id'));
$sheet->setCellValue('B1', lang('requests_index_thead_fullname'));
$sheet->setCellValue('C1', lang('requests_index_thead_startdate'));
$sheet->setCellValue('D1', lang('requests_index_thead_enddate'));
$sheet->setCellValue('E1', lang('requests_index_thead_duration'));
$sheet->setCellValue('F1', lang('requests_index_thead_type'));
$sheet->setCellValue('G1', lang('requests_index_thead_status'));
$sheet->getStyle('A1:G1')->getFont()->setBold(true);
$sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

//Database query
$ci->db->select('users.firstname, users.lastname, leaves.*');
$ci->db->select('status.name as status_name, types.name as type_name');
$ci->db->from('leaves');
$ci->db->join('status', 'leaves.status = status.id');
$ci->db->join('types', 'leaves.type = types.id');
$ci->db->join('users', 'leaves.employee = users.id');
$ci->db->order_by('users.lastname, users.firstname, leaves.startdate', 'desc');
$rows = $ci->db->get()->result_array();

//Results
$line = 2;
foreach ($rows as $row) {
    $date = new DateTime($row['startdate']);
    $startdate = $date->format(lang('global_date_format'));
    $date = new DateTime($row['enddate']);
    $enddate = $date->format(lang('global_date_format'));
    $sheet->setCellValue('A' . $line, $row['id']);
    $sheet->setCellValue('B' . $line, $row['firstname'] . ' ' . $row['lastname']);
    $sheet->setCellValue('C' . $line, $startdate . ' (' . lang($row['startdatetype']). ')');
    $sheet->setCellValue('D' . $line, $enddate . ' (' . lang($row['enddatetype']) . ')');
    $sheet->setCellValue('E' . $line, $row['duration']);
    $sheet->setCellValue('F' . $line, $row['type_name']);
    $sheet->setCellValue('G' . $line, lang($row['status_name']));
    $line++;
}
//Autofit
foreach(range('A', 'G') as $colD) {
    $sheet->getColumnDimension($colD)->setAutoSize(TRUE);
}

//Export Excel file
writeSpreadsheet($spreadsheet, 'excel-export');