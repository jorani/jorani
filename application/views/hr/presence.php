<?php
/**
 * This view builds the monthly presence report of an employee
 * By default, the last month is selected.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.3.1
 */
?>

<div class="row-fluid">
    <div class="span12">

<h2><?php echo lang('hr_presence_title');?><span class="muted">&nbsp;(<?php echo $employee_name;?>&nbsp; |&nbsp;<?php echo lang($month_name);?>&nbsp;<?php echo $year;?>)</span> &nbsp;<?php echo $help;?></h2>

<p><?php echo lang('hr_presence_description');?></p>

<div class="row-fluid">
     <div class="span6">
        <div id='calendar'></div>
    </div>
    <div class="span3">
        <br /><br />
        <p><?php echo lang('hr_presence_employee');?> : <b><?php echo $employee_name;?></b></p>
        <p><?php echo lang('hr_presence_month');?> : <b><?php echo lang($month_name);?>&nbsp;<?php echo $year;?></b></p>
        <p><?php echo lang('hr_presence_days');?> : <b><?php echo $total_days;?></b></p>
        <?php if ($contract_id != '') { ?>
        <p><?php echo lang('hr_presence_contract');?> : <b><?php echo $contract_name;?></b></p>
        <p><?php echo lang('hr_presence_working_days');?> : <b><?php echo $opened_days;?></b></p>
        <?php if ($source == 'collaborators') {?>
        <p><?php echo lang('hr_presence_non_working_days');?> : <b><?php echo $non_working_days;?></b></p>
        <?php } else { ?>
        <p><?php echo lang('hr_presence_non_working_days');?> : <b><a href="<?php echo base_url();?>contracts/<?php echo $contract_id; ?>/calendar"><?php echo $non_working_days;?></a></b></p>
        <?php } ?>
        <?php } else { ?>
        <p><?php echo lang('hr_presence_contract');?> : <i class="mdi mdi-alert"></i><?php echo lang('hr_presence_no_contract');?></p>
        <p><?php echo lang('hr_presence_working_days');?> : <i class="mdi mdi-alert"></i><?php echo lang('hr_presence_no_contract');?></p>
        <p><?php echo lang('hr_presence_non_working_days');?> : <i class="mdi mdi-alert"></i><?php echo lang('hr_presence_no_contract');?></p>
        <?php } ?>
        <p><?php echo lang('hr_presence_work_duration');?> : <b><?php echo $work_duration;?></b>&nbsp;<i class="mdi mdi-alert-circle-outline"></i><?php echo lang('hr_presence_please_check');?></p>
        <p><?php echo lang('hr_presence_leave_duration');?> : <b><?php echo $leave_duration;?></b>&nbsp;<i class="mdi mdi-alert-circle-outline"></i><?php echo lang('hr_presence_please_check');?></p>
        <?php if (count($leaves_detail) > 0) { ?>
        <ul>
            <?php foreach ($leaves_detail as $leaves_type_name => $leaves_type_sum) { ?>
            <li><?php echo $leaves_type_name;?> : <?php echo $leaves_type_sum;?></li>
            <?php } ?>
        </ul>
        <?php } ?>
    </div>
    <div class="span3">
        <br /><br />
        <label for="cboMonth"><?php echo lang('calendar_tabular_field_month');?></label>
        <select name="cboMonth" id="cboMonth">
            <?php for ($ii=1; $ii<13;$ii++) {
                if ($ii == $month) {
                    echo "<option val='" . $ii ."' selected>" . $ii ."</option>";
                } else {
                    echo "<option val='" . $ii ."'>" . $ii ."</option>";
                }
            }?>
        </select>
        <label for="cboYear"><?php echo lang('calendar_tabular_field_year');?></label>
        <select name="cboYear" id="cboYear">
            <?php $len =  date('Y');
            for ($ii=date('Y', strtotime('-6 year')); $ii<= $len; $ii++) {
                if ($ii == $year) {
                    echo "<option val='" . $ii ."' selected>" . $ii ."</option>";
                } else {
                    echo "<option val='" . $ii ."'>" . $ii ."</option>";
                }
            }?>
        </select><br />
        <button id="cmdPrevious" class="btn btn-primary"><i class="mdi mdi-chevron-left"></i></button>
        <button id="cmdExecute" class="btn btn-primary"><?php echo lang('hr_presence_button_execute');?></button>
        <button id="cmdNext" class="btn btn-primary"><i class="mdi mdi-chevron-right"></i></button>
        <br /><br />
        <a href="<?php echo base_url() . 'hr/presence/export/' . $source . '/' . $user_id . '/' . $month . '/' . $year;?>" class="btn btn-primary"><i class="mdi mdi-download"></i>&nbsp;<?php echo lang('hr_presence_button_export');?></a>
     </div>
</div>

<hr />

<h3><?php echo lang('hr_presence_leaves_list_title');?></h3>

<div class="row-fluid">
    <div class="span12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <?php
                        $start = $year . '-' . $month . '-' . '1';    //first date of selected month
                        $lastDay = date("t", strtotime($start));    //last day of selected month
                        for ($ii = 1; $ii <=$lastDay; $ii++) {
                            $dayNum = date("N", strtotime($year . '-' . $month . '-' . $ii));
                            switch ($dayNum)
                            {
                                case 1: echo '<td><b>' . lang('calendar_monday_short') . '</b></td>'; break;
                                case 2: echo '<td><b>' . lang('calendar_tuesday_short') . '</b></td>'; break;
                                case 3: echo '<td><b>' . lang('calendar_wednesday_short') . '</b></td>'; break;
                                case 4: echo '<td><b>' . lang('calendar_thursday_short') . '</b></td>'; break;
                                case 5: echo '<td><b>' . lang('calendar_friday_short') . '</b></td>'; break;
                                case 6: echo '<td><b>' . lang('calendar_saturday_short') . '</b></td>'; break;
                                case 7: echo '<td><b>' . lang('calendar_sunday_short') . '</b></td>'; break;
                            }
                        }?>
                </tr>
                <tr>
                    <?php
                        $start = $year . '-' . $month . '-' . '1';    //first date of selected month
                        $lastDay = date("t", strtotime($start));    //last day of selected month
                        for ($ii = 1; $ii <=$lastDay; $ii++) {
                            echo '<td><b>' . $ii . '</b></td>';
                        }?>
                </tr>
            </thead>
            <tbody>
              <tr>
                <?php foreach ($linear->days as $day) {
                    $overlapping = FALSE;
                    $style = '';
                    if (strstr($day->display, ';')) {
                        $periods = explode(";", $day->display);
                        $statuses = explode(";", $day->status);
                        $types = explode(";", $day->type);
                          switch (intval($statuses[1]))
                          {
                              case 1: $class = "planned"; break;  // Planned
                              case 2: $class = "requested"; break;  // Requested
                              case 3: if($types[1] == 'Campaign' || $types[1] == 'Floating') $class = "allacceptedtw"; elseif($types[1] == 'Time organisation') $class = "allacceptedto"; else $class = "accepted"; break; // Accepted
                              case 4: $class = "rejected"; break;  // Rejected
                              case 5: $class="dayoff"; break;
                              case 6: $class="dayoff"; break;
                          }
                          switch (intval($statuses[0]))
                          {
                              case 1: $class .= "planned"; break;  // Planned
                              case 2: $class .= "requested"; break;  // Requested
                              case 3: if($types[0] == 'Campaign' || $types[0] == 'Floating') $class = "allacceptedtw"; elseif($types[0] == 'Time organisation') $class = "allacceptedto"; else $class = "accepted"; break; // Accepted
                              case 4: $class .= "rejected"; break;  // Rejected
                              case 5: $class .="dayoff"; break;
                              case 6: $class .="dayoff"; break;
                          }
                          //If we have two requests the same day (morning/afternoon)
                          if (($statuses[0] == $statuses[1]) && ($periods[0] != $periods[1])){
                              switch (intval($statuses[0]))
                              {
                                  case 1: $class = "allplanned"; break;  // Planned
                                  case 2: $class = "allrequested"; break;  // Requested
                                  case 3: if($types[0] == 'Campaign' || $types[0] == 'Floating') $class = "allacceptedtw"; elseif($types[0] == 'Time organisation') $class = "allacceptedto"; else $class = "allaccepted"; break; // Accepted
                                  case 4: $class = "allrejected"; break;  // Rejected
                                  //The 2 cases below would be weird...
                                  case 5: $class ="dayoff"; break;
                                  case 6: $class ="dayoff"; break;
                              }
                          }
                    } else {
                      switch ($day->display) {
                          case '9': $class="error"; break;
                          case '0': $class="working"; break;
                          case '4': $class="dayoff"; break;
                          case '5': $class="amdayoff"; break;
                          case '6': $class="pmdayoff"; break;
                          case '1':
                                switch ($day->status)
                                {
                                    case 1: $class = "allplanned"; break;  // Planned
                                    case 2: $class = "allrequested"; break;  // Requested
                                    case 3: if($day->type == 'Campaign' || $day->type == 'Floating') $class = "allacceptedtw"; elseif($day->type == 'Time organisation') $class = "allacceptedto"; else $class = "allaccepted"; break; // Accepted
                                    case 4: $class = "allrejected"; break;  // Rejected
                                }
                                break;
                          case '2':
                              switch ($day->status)
                                {
                                    case 1: $class = "amplanned"; break;  // Planned
                                    case 2: $class = "amrequested"; break;  // Requested
                                    case 3: if($day->type == 'Campaign' || $day->type == 'Floating') $class = "amacceptedtw"; elseif($day->type == 'Time organisation') $class = "amacceptedto"; else $class = "amaccepted"; break; // Accepted
                                    case 4: $class = "amrejected"; break;  // Rejected
                                }
                              break;
                          case '3':
                              switch ($day->status)
                                {
                                    case 1: $class = "pmplanned"; break;  // Planned
                                    case 2: $class = "pmrequested"; break;  // Requested
                                    case 3: if($day->type == 'Campaign' || $day->type == 'Floating') $class = "pmacceptedtw"; elseif($day->type == 'Time organisation') $class = "pmacceptedto"; else $class = "pmaccepted"; break; // Accepted
                                    case 4: $class = "pmrejected"; break;  // Rejected
                                }
                              break;
                      }
                    }
                    //Detect overlapping cases
                    switch ($class) {
                              case "plannedplanned":
                              case "requestedrequested":
                              case "acceptedaccepted":
                              case "rejectedrejected":
                                  $overlapping = TRUE;
                        break;
                    }
                      if ($class == "error"){
                          echo '<td><img src="'.  base_url() .'assets/images/date_error.png"></td>';
                      } else {
                          if ($overlapping) {
                              echo '<td title="' . $day->type . '" class="' . $class . '"><img src="' . base_url() . 'assets/images/date_error.png"></td>';
                          } else {
                              //Acronyms of types
                              if ($day->acronym != "") {
                                  $acronyms = explode(";", $day->acronym);
                                  if (count($acronyms) == 1) {
                                      //One leave request
                                      if ((substr($class, 0, 2) == "am")) {
                                          //Diagonal top left
                                          $style = 'padding:1px; font-size: 0.7em;';
                                      }
                                      if((substr($class, 0, 2) == "pm")) {
                                          //Diagonal bottom right
                                          $style = 'vertical-align: bottom; text-align: right; padding:1px; font-size: 0.7em;';
                                      }
                                      echo "<td title='$day->type' class='$class' style='$style'>$day->acronym</td>";
                                  } else {
                                      echo "<td class='$class' style='font-size: 0.7em;'>";
                                      echo '  <span title="' . $day->type . '" class="pull-left">' . $acronyms[0] . '</span>';
                                      echo '  <span title="' . $day->type . '" class="pull-right" >' . $acronyms[1] . '</span>';
                                      echo '</td>';
                                  }
                              } else {
                                  //We don't display the acronyms of type or it is not available
                                  echo "<td title='$day->type' style='$style' class='$class'>&nbsp;</td>";
                              }
                          }
                      }
               } ?>
                    </tr>
            </tbody>
        </table>
    </div>
</div>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="leaves" width="100%">
    <thead>
        <tr>
            <th><?php echo lang('hr_presence_thead_id');?></th>
            <th><?php echo lang('hr_presence_thead_start');?></th>
            <th><?php echo lang('hr_presence_thead_end');?></th>
            <th><?php echo lang('hr_presence_thead_duration');?></th>
            <th><?php echo lang('hr_presence_thead_type');?></th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($leaves as $leave):
    $date = new DateTime($leave['startdate']);
    $tmpStartDate = $date->getTimestamp();
    $startdate = $date->format(lang('global_date_format'));
    $date = new DateTime($leave['enddate']);
    $tmpEndDate = $date->getTimestamp();
    $enddate = $date->format(lang('global_date_format'));?>
    <tr>
        <td data-order="<?php echo $leave['id']; ?>">
            <a href="<?php echo base_url();?>leaves/edit/<?php echo $leave['id']; ?>?source=hr%2Fpresence%2Femployees%2F<?php echo $user_id; ?>%2F<?php echo $month; ?>%2F<?php echo $year; ?>" title="<?php echo lang('hr_presence_thead_tip_edit');?>"><?php echo $leave['id'] ?></a>
        </td>
        <td data-order="<?php echo $tmpStartDate; ?>"><?php echo $startdate . ' (' . lang($leave['startdatetype']). ')'; ?></td>
        <td data-order="<?php echo $tmpEndDate; ?>"><?php echo $enddate . ' (' . lang($leave['enddatetype']) . ')'; ?></td>
        <td><?php echo $leave['duration']; ?></td>
        <td><?php echo $leave['type']; ?></td>
    </tr>
<?php endforeach ?>
	</tbody>
</table>

<hr />

<div class="row-fluid">
    <div class="span12">
        <h3><?php echo lang('hr_summary_title');?>&nbsp;<?php echo $employee_id; ?>&nbsp;<span class="muted"> (<?php echo $employee_name; ?>)</span>&nbsp;<?php echo $help;?></h3>

        <?php if (is_null($summary)) { ?>
        <div class="alert">
        <?php echo lang('hr_presence_no_contract');?>
        </div>
        <?php } else { ?>

        <p><?php echo lang('hr_summary_date_field');?>&nbsp;
            <input type="text" value="<?php echo $refDate; ?>" readonly />
        </p>

        <table id="counters" cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
            <thead>
                <tr>
                  <th><?php echo lang('hr_summary_thead_type');?></th>
                  <th><?php echo lang('hr_summary_thead_available');?></th>
                  <th><?php echo lang('hr_summary_thead_taken');?></th>
                  <th><?php echo lang('hr_summary_thead_entitled');?></th>
                  <th><?php echo lang('hr_summary_thead_description');?></th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($summary as $key => $value) { ?>
                <tr>
                  <td><?php echo $key; ?></td>
                  <td><?php echo round(((float) $value[1] - (float) $value[0]), 3, PHP_ROUND_HALF_DOWN); ?></td>
                  <td><?php if ($value[2] == '') { echo ((float) $value[0]); } else { echo '-'; } ?></td>
                  <td><?php if ($value[2] == '') { echo ((float) $value[1]); } else { echo '-'; } ?></td>
                  <td><?php echo $value[2]; ?></td>
                </tr>
              <?php } ?>
              </tbody>
        </table>
        <?php } ?>
    </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div class="row-fluid">
    <div class="span12">
      <?php if ($source == 'employees') {?>
      <a href="<?php echo base_url();?>hr/employees" class="btn btn-primary"><i class="mdi mdi-arrow-left-bold"></i>&nbsp;<?php echo lang('hr_presence_button_list');?></a>
      <?php } else { ?>
      <a href="<?php echo base_url();?>requests/collaborators" class="btn btn-primary"><i class="mdi mdi-arrow-left-bold"></i>&nbsp;<?php echo lang('hr_presence_button_list');?></a>
      <?php } ?>
    </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

    </div>
</div>

<link href="<?php echo base_url();?>assets/fullcalendar-2.8.0/fullcalendar.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/fullcalendar-2.8.0/fullcalendar.min.js"></script>
<?php if ($language_code != 'en') {?>
<script type="text/javascript" src="<?php echo base_url();?>assets/fullcalendar-2.8.0/lang/<?php echo strtolower($language_code);?>.js"></script>
<?php }?>
<link href="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
var employee = <?php echo $user_id;?>;

$(function () {

    //Transform the HTML tables into fancy datatables
    var oTable = $('#leaves').dataTable({
            order: [[ 1, "asc" ]],
            language: {
                decimal:            "<?php echo lang('datatable_sInfoThousands');?>",
                processing:       "<?php echo lang('datatable_sProcessing');?>",
                search:              "<?php echo lang('datatable_sSearch');?>",
                lengthMenu:     "<?php echo lang('datatable_sLengthMenu');?>",
                info:                   "<?php echo lang('datatable_sInfo');?>",
                infoEmpty:          "<?php echo lang('datatable_sInfoEmpty');?>",
                infoFiltered:       "<?php echo lang('datatable_sInfoFiltered');?>",
                infoPostFix:        "<?php echo lang('datatable_sInfoPostFix');?>",
                loadingRecords: "<?php echo lang('datatable_sLoadingRecords');?>",
                zeroRecords:    "<?php echo lang('datatable_sZeroRecords');?>",
                emptyTable:     "<?php echo lang('datatable_sEmptyTable');?>",
                paginate: {
                    first:          "<?php echo lang('datatable_sFirst');?>",
                    previous:   "<?php echo lang('datatable_sPrevious');?>",
                    next:           "<?php echo lang('datatable_sNext');?>",
                    last:           "<?php echo lang('datatable_sLast');?>"
                },
                aria: {
                    sortAscending:  "<?php echo lang('datatable_sSortAscending');?>",
                    sortDescending: "<?php echo lang('datatable_sSortDescending');?>"
                }
            },
        });

        $('#counters').dataTable({
            order: [[ 0, "desc" ]],
            language: {
                decimal:            "<?php echo lang('datatable_sInfoThousands');?>",
                processing:       "<?php echo lang('datatable_sProcessing');?>",
                search:              "<?php echo lang('datatable_sSearch');?>",
                lengthMenu:     "<?php echo lang('datatable_sLengthMenu');?>",
                info:                   "<?php echo lang('datatable_sInfo');?>",
                infoEmpty:          "<?php echo lang('datatable_sInfoEmpty');?>",
                infoFiltered:       "<?php echo lang('datatable_sInfoFiltered');?>",
                infoPostFix:        "<?php echo lang('datatable_sInfoPostFix');?>",
                loadingRecords: "<?php echo lang('datatable_sLoadingRecords');?>",
                zeroRecords:    "<?php echo lang('datatable_sZeroRecords');?>",
                emptyTable:     "<?php echo lang('datatable_sEmptyTable');?>",
                paginate: {
                    first:          "<?php echo lang('datatable_sFirst');?>",
                    previous:   "<?php echo lang('datatable_sPrevious');?>",
                    next:           "<?php echo lang('datatable_sNext');?>",
                    last:           "<?php echo lang('datatable_sLast');?>"
                },
                aria: {
                    sortAscending:  "<?php echo lang('datatable_sSortAscending');?>",
                    sortDescending: "<?php echo lang('datatable_sSortDescending');?>"
                }
            }
        });

        //Load a tiny calendar
        $('#calendar').fullCalendar({
            timeFormat: ' ', /*Trick to remove the start time of the event*/
            height: 300,
            defaultDate: moment('<?php echo $default_date;?>'),
            header: {
                left: "",
                center: "",
                right: ""
            },
            loading: function(isLoading) {
                if (isLoading) { //Display/Hide a pop-up showing an animated icon during the Ajax query.
                    $('#frmModalAjaxWait').modal('show');
                } else {
                    $('#frmModalAjaxWait').modal('hide');
                }
            },
            eventRender: function(event, element, view) {
                if(event.imageurl){
                    $(element).find('span:first').prepend('<img src="' + event.imageurl + '" />');
                }
            },
            eventAfterRender: function(event, element, view) {
                //Add tooltip to the element
                $(element).attr('title', event.title);

                if (event.enddatetype == "Morning" || event.startdatetype == "Afternoon") {
                    var nb_days = event.end.diff(event.start, "days");
                    var duration = 0.5;
                    var halfday_length = 0;
                    var length = 0;
                    var width = parseInt(jQuery(element).css('width'));
                    if (nb_days > 0) {
                        if (event.enddatetype == "Afternoon") {
                            duration = nb_days + 0.5;
                        } else {
                            duration = nb_days;
                        }
                        nb_days++;
                        halfday_length = Math.round((width / nb_days) / 2);
                        if (event.startdatetype == "Afternoon" && event.enddatetype == "Morning") {
                            length = width - (halfday_length * 2);
                        } else {
                            length = width - halfday_length;
                        }
                    } else {
                        halfday_length = Math.round(width / 2);   //Average width of a day divided by 2
                        length = halfday_length;
                    }
                }
                $(element).css('width', length + "px");

                //Starting afternoon : shift the position of event to the right
                if (event.startdatetype == "Afternoon") {
                    $(element).css('margin-left', halfday_length + "px");
                }
            },
            windowResize: function(view) {
                $('#calendar').fullCalendar( 'rerenderEvents' );
            }
        });
        $('#calendar').fullCalendar('addEventSource', '<?php echo base_url();?>leaves/individual/' + employee);
        $('#calendar').fullCalendar('addEventSource', '<?php echo base_url();?>contracts/calendar/userdayoffs/' + employee);


        //Execute the report for another date
        $('#cmdExecute').click(function() {
            var month = $('#cboMonth').val();
            var year = $('#cboYear').val();
            var url = '<?php echo base_url();?>hr/presence/<?php echo $source;?>/' + employee + '/' + month+ '/' + year;
            document.location.href = url;
        });
<?php $datePrev = date_create($year . '-' . $month . '-01');
$dateNext = clone $datePrev;
date_add($dateNext, date_interval_create_from_date_string('1 month'));
date_sub($datePrev, date_interval_create_from_date_string('1 month'));?>
        //Previous/Next
        $('#cmdPrevious').click(function() {
            month = <?php echo $datePrev->format('m'); ?>;
            year = <?php echo $datePrev->format('Y'); ?>;
            var url = '<?php echo base_url();?>hr/presence/<?php echo $source;?>/' + employee + '/' + month+ '/' + year;
            document.location.href = url;
        });
        $('#cmdNext').click(function() {
            month = <?php echo $dateNext->format('m'); ?>;
            year = <?php echo $dateNext->format('Y'); ?>;
            var url = '<?php echo base_url();?>hr/presence/<?php echo $source;?>/' + employee + '/' + month+ '/' + year;
            document.location.href = url;
        });

        refresh_calendar();
});
</script>
