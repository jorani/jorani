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

$title = 'Types';
$chartTitle = 'Distribution of leave types';
$label = 'Leave type';
$value = 'Number of days';
$requests = 'Requests';

$ci =& get_instance();
$sheet->setTitle($title);
$sheet->setCellValue('A1', $label);
$sheet->setCellValue('B1', $value);
$sheet->setCellValue('C1', $requests);
$sheet->getStyle('A1:C1')->getFont()->setBold(true);
$sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$ci->load->model('organization_model');
$entity = ($ci->input->get('txtEntityID', TRUE) != FALSE)? $ci->input->get('txtEntityID', TRUE) : 0;
$include_children = TRUE;
$include_children = filter_var($ci->input->get('chkIncludeChildren'), FILTER_VALIDATE_BOOLEAN);
$users = $ci->organization_model->allEmployees($entity, $include_children);
$ids = array(0);
foreach ($users as $user) {
    array_push($ids, (int) $user->id);
}

$ci->db->select('count(*) as number, sum(duration) as duration', FALSE);
$ci->db->select('types.name as type_name');
$ci->db->from('leaves');
$ci->db->join('types', 'leaves.type = types.id');
$ci->db->where('leaves.status', 3);
if (is_null($ci->input->get('cboYear', TRUE))) {
    $ci->db->where('YEAR(startdate) = YEAR(CURDATE())');
} else {
    $ci->db->where('YEAR(startdate) = ' . $ci->db->escape($ci->input->get('cboYear', TRUE)));
}
$ci->db->where_in('leaves.employee', $ids);
$ci->db->group_by('type');
$ci->db->order_by('number', 'desc');
$rows = $ci->db->get()->result_array();

$line = 2;
foreach ($rows as $row) {
    $sheet->setCellValue('A' . $line, $row['type_name']);
    $sheet->setCellValue('B' . $line, $row['duration']);
    $sheet->setCellValue('C' . $line, $row['number']);
    $line++;
}
//Autofit
foreach(range('A', 'C') as $colD) {
    $sheet->getColumnDimension($colD)->setAutoSize(TRUE);
}

$dataseriesLabels1 = array(
	new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $title . '!$A$1', null, 1),
);

$xAxisTickValues1 = array(
	new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $title . '!$A$2:$A$' . $line, null, 4),
);

$dataSeriesValues1 = array(
	new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $title . '!$B$2:$B$' . $line, null, 4),
);

$series1 = new DataSeries(
	DataSeries::TYPE_PIECHART,			// plotType
	null, // plotGrouping (Pie charts don't have any grouping)
	range(0, count($dataSeriesValues1) - 1),				// plotOrder
	$dataseriesLabels1,						// plotLabel
	$xAxisTickValues1,						// plotCategory
	$dataSeriesValues1						// plotValues
);

$layout1 = new Layout();
$layout1->setShowVal(TRUE);
$layout1->setShowPercent(TRUE);
$plotarea1 = new PlotArea($layout1, array($series1));
$legend1 = new Legend(Legend::POSITION_RIGHT, null, false);
$title1 = new Title($chartTitle);

$chart1 = new Chart(
	'chart1',		// name
	$title1,		// title
	$legend1,		// legend
	$plotarea1,	// plotArea
	true,		// plotVisibleOnly
	0,		// displayBlanksAs
	null,		// xAxisLabel
	null		// yAxisLabel
);

$chart1->setTopLeftPosition('E3');
$chart1->setBottomRightPosition('K20');
$sheet->addChart($chart1);

writeSpreadsheet($spreadsheet, 'excel-export');
