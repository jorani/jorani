<?php 
/**
 * This partial view show the history of changes on a telework request
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.5.0
 */
?>

<div class="row-fluid">
    <div class="span12">
<!--<table cellpadding="0" cellspacing="0" border="0" class="display" id="teleworkshistory" width="100%">//-->
    <table class="table" id="teleworkshistory" width="100%">
    <thead>
        <tr>
            <th class="muted"><?php echo lang('teleworks_history_thead_change_type');?></th>
            <th class="muted"><?php echo lang('teleworks_history_thead_changed_date');?></th>
            <th class="muted"><?php echo lang('teleworks_history_thead_changed_by');?></th>
            <th><?php echo lang('teleworks_history_thead_start_date');?></th>
            <th><?php echo lang('teleworks_history_thead_end_date');?></th>
            <th><?php echo lang('teleworks_history_thead_cause');?></th>
            <th><?php echo lang('teleworks_history_thead_duration');?></th>
            <th><?php echo lang('teleworks_history_thead_status');?></th>
        </tr>
    </thead>
    <tbody>
<?php 

$history = array();
$lastObject = new stdClass;
$lastObject->startDate = '';
$lastObject->endDate = '';
$lastObject->cause = '';
$lastObject->duration = '';
$lastObject->status = 0;
$lastObject->statusName = '';

foreach ($events as $event) {
    $objTelework = new stdClass;
    $objTelework->typeIcon = '';
    switch ($event['change_type']) {
        case 0: $objTelework->typeIcon = 'icon-info-sign'; break;
        case 1: $objTelework->typeIcon = 'icon-plus'; break;
        case 2: $objTelework->typeIcon = 'icon-pencil'; break;
        case 3: $objTelework->typeIcon = 'icon-trash'; break;
    }
    
    //Pretty print telework request data
    $date = new DateTime($event['change_date']);
    $objTelework->changedDate = $date->format(lang('global_date_format'));
    $objTelework->changedBy = $event['user_name'];
    $date = new DateTime($event['startdate']);
    $startDate = $date->format(lang('global_date_format')) . ' (' . lang($event['startdatetype']) . ')';
    $objTelework->startDate = $startDate;
    $date = new DateTime($event['enddate']);
    $endDate = $date->format(lang('global_date_format')) . ' (' . lang($event['enddatetype']) . ')';
    $objTelework->endDate = $endDate;
    $objTelework->cause = $event['cause'];
    $objTelework->duration = $event['duration'];
    $objTelework->status = $event['status'];
    $objTelework->statusName = $event['status_name'];
    $fullObject = clone $objTelework;
    
    //Display only the cells with changes
    $objTelework->startDate = ($startDate==$lastObject->startDate)?'':$startDate;
    $objTelework->endDate = ($endDate==$lastObject->endDate)?'':$endDate;
    $objTelework->cause = ($event['cause']==$lastObject->cause)?'':$event['cause'];
    $objTelework->duration = ($event['duration']==$lastObject->duration)?'':$event['duration'];
    $objTelework->status = $event['status'];
    $objTelework->statusName = ($event['status_name']==$lastObject->statusName)?'':$event['status_name'];
    array_push($history, $objTelework);
    $lastObject = clone $fullObject;
}

if (!empty($telework)) {
    $objTelework = new stdClass;
    $objTelework->typeIcon = 'icon-arrow-right';   //Current value
    $objTelework->changedDate = '';
    $objTelework->changedBy = '';
    $date = new DateTime($telework['startdate']);
    $objTelework->startDate = $date->format(lang('global_date_format')) . ' (' . lang($telework['startdatetype']) . ')';
    $date = new DateTime($telework['enddate']);
    $objTelework->endDate = $date->format(lang('global_date_format')) . ' (' . lang($telework['enddatetype']) . ')';
    $objTelework->cause = $telework['cause'];
    $objTelework->duration = $telework['duration'];
    $objTelework->status = $telework['status'];
    $objTelework->statusName = $telework['status_name'];
    array_push($history, $objTelework);
}

?>
        
    <?php foreach ($history as $objTelework): ?>
    <tr>
        <td class="muted"><i class="<?php echo $objTelework->typeIcon; ?>"></i></td>
        <td class="muted"><?php echo $objTelework->changedDate; ?></td>
        <td class="muted"><?php echo $objTelework->changedBy; ?></td>
        <td><?php echo $objTelework->startDate; ?></td>
        <td><?php echo $objTelework->endDate; ?></td>
        <td><?php echo $objTelework->cause; ?></td>
        <td><?php echo $objTelework->duration; ?></td>
        <td><?php
        switch (intval($objTelework->status)) {
            case LMS_PLANNED: echo "<td><span class='label'>" . lang($objTelework->statusName) . "</span></td>"; break;
            case LMS_REQUESTED: echo "<td><span class='label label-warning'>" . lang($objTelework->statusName) . "</span></td>"; break;
            case LMS_ACCEPTED: echo "<td><span class='label label-success'>" . lang($objTelework->statusName) . "</span></td>"; break;
            default: echo "<td><span class='label label-important' style='background-color: #ff0000;'>" . lang($objTelework->statusName) . "</span></td>"; break;
        }?></td>
    </tr>
    <?php endforeach ?>
    </tbody>
</table>
    </div>
</div>
