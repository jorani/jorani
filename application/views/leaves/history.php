<?php 
/**
 * This partial view show the history of changes on a leave request
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.5.0
 */
?>

<div class="row-fluid">
    <div class="span12">
<!--<table cellpadding="0" cellspacing="0" border="0" class="display" id="leaveshistory" width="100%">//-->
    <table class="table" id="leaveshistory" width="100%">
    <thead>
        <tr>
            <th class="muted"><?php echo lang('leaves_history_thead_change_type');?></th>
            <th class="muted"><?php echo lang('leaves_history_thead_changed_date');?></th>
            <th class="muted"><?php echo lang('leaves_history_thead_changed_by');?></th>
            <th><?php echo lang('leaves_history_thead_start_date');?></th>
            <th><?php echo lang('leaves_history_thead_end_date');?></th>
            <th><?php echo lang('leaves_history_thead_cause');?></th>
            <th><?php echo lang('leaves_history_thead_duration');?></th>
            <th><?php echo lang('leaves_history_thead_type');?></th>
            <th><?php echo lang('leaves_history_thead_status');?></th>
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
$lastObject->typeName = '';
$lastObject->status = 0;
$lastObject->statusName = '';

foreach ($events as $event) {
    $objLeave = new stdClass;
    $objLeave->typeIcon = '';
    switch ($event['change_type']) {
        case 0: $objLeave->typeIcon = 'icon-info-sign'; break;
        case 1: $objLeave->typeIcon = 'icon-plus'; break;
        case 2: $objLeave->typeIcon = 'icon-pencil'; break;
        case 3: $objLeave->typeIcon = 'icon-trash'; break;
    }
    
    //Pretty print leave request data
    $date = new DateTime($event['change_date']);
    $objLeave->changedDate = $date->format(lang('global_date_format'));
    $objLeave->changedBy = $event['user_name'];
    $date = new DateTime($event['startdate']);
    $startDate = $date->format(lang('global_date_format')) . ' (' . lang($event['startdatetype']) . ')';
    $objLeave->startDate = $startDate;
    $date = new DateTime($event['enddate']);
    $endDate = $date->format(lang('global_date_format')) . ' (' . lang($event['enddatetype']) . ')';
    $objLeave->endDate = $endDate;
    $objLeave->cause = $event['cause'];
    $objLeave->duration = $event['duration'];
    $objLeave->typeName = $event['type_name'];
    $objLeave->status = $event['status'];
    $objLeave->statusName = $event['status_name'];
    $fullObject = clone $objLeave;
    
    //Display only the cells with changes
    $objLeave->startDate = ($startDate==$lastObject->startDate)?'':$startDate;
    $objLeave->endDate = ($endDate==$lastObject->endDate)?'':$endDate;
    $objLeave->cause = ($event['cause']==$lastObject->cause)?'':$event['cause'];
    $objLeave->duration = ($event['duration']==$lastObject->duration)?'':$event['duration'];
    $objLeave->typeName = ($event['type_name']==$lastObject->typeName)?'':$event['type_name'];
    $objLeave->status = $event['status'];
    $objLeave->statusName = ($event['status_name']==$lastObject->statusName)?'':$event['status_name'];
    array_push($history, $objLeave);
    $lastObject = clone $fullObject;
}

if (!empty($leave)) {
    $objLeave = new stdClass;
    $objLeave->typeIcon = 'icon-arrow-right';   //Current value
    $objLeave->changedDate = '';
    $objLeave->changedBy = '';
    $date = new DateTime($leave['startdate']);
    $objLeave->startDate = $date->format(lang('global_date_format')) . ' (' . lang($leave['startdatetype']) . ')';
    $date = new DateTime($leave['enddate']);
    $objLeave->endDate = $date->format(lang('global_date_format')) . ' (' . lang($leave['enddatetype']) . ')';
    $objLeave->cause = $leave['cause'];
    $objLeave->duration = $leave['duration'];
    $objLeave->typeName = $leave['type_name'];
    $objLeave->status = $leave['status'];
    $objLeave->statusName = $leave['status_name'];
    array_push($history, $objLeave);
}

?>
        
    <?php foreach ($history as $objLeave): ?>
    <tr>
        <td class="muted"><i class="<?php echo $objLeave->typeIcon; ?>"></i></td>
        <td class="muted"><?php echo $objLeave->changedDate; ?></td>
        <td class="muted"><?php echo $objLeave->changedBy; ?></td>
        <td><?php echo $objLeave->startDate; ?></td>
        <td><?php echo $objLeave->endDate; ?></td>
        <td><?php echo $objLeave->cause; ?></td>
        <td><?php echo $objLeave->duration; ?></td>
        <td><?php echo $objLeave->typeName; ?></td>
        <td><?php
        switch (intval($objLeave->status)) {
            case LMS_PLANNED: echo "<td><span class='label'>" . lang($objLeave->statusName) . "</span></td>"; break;
            case LMS_REQUESTED: echo "<td><span class='label label-warning'>" . lang($objLeave->statusName) . "</span></td>"; break;
            case LMS_ACCEPTED: echo "<td><span class='label label-success'>" . lang($objLeave->statusName) . "</span></td>"; break;
            default: echo "<td><span class='label label-important' style='background-color: #ff0000;'>" . lang($objLeave->statusName) . "</span></td>"; break;
        }?></td>
    </tr>
    <?php endforeach ?>
    </tbody>
</table>
    </div>
</div>
