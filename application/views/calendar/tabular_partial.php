<?php
/**
 * This partial view builds a "linear" calendar (which is technically a line into an HTML table).
 * A linear calendar displays the leaves of an employee during a month. Each cell is a day.
 * It can be loaded asynchronously.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.6.0
 */
?>

<?php
if (count($tabular) > 0) {?>
<table class="table table-bordered">
    <thead>
        <tr>
            <td>&nbsp;</td>
            <?php
                $start = $year . '-' . $month . '-' . '1';    //first date of selected month
                $lastDay = date("t", strtotime($start));    //last day of selected month
                $isCurrentMonth = date('Y-n') === $year . '-' . (int)$month;
                $currentDay = (int)date('d');
                for ($ii = 1; $ii <=$lastDay; $ii++) {
                    $class = '';
                    if($isCurrentMonth && $ii === $currentDay){
                        $class .= ' currentday-bg';
                    }
                    $dayNum = date("N", strtotime($year . '-' . $month . '-' . $ii));
                    switch ($dayNum)
                    {
                        case 1: echo '<td'.($class?' class="'.$class.'"':'').'><b>' . lang('calendar_monday_short') . '</b></td>'; break;
                        case 2: echo '<td'.($class?' class="'.$class.'"':'').'><b>' . lang('calendar_tuesday_short') . '</b></td>'; break;
                        case 3: echo '<td'.($class?' class="'.$class.'"':'').'><b>' . lang('calendar_wednesday_short') . '</b></td>'; break;
                        case 4: echo '<td'.($class?' class="'.$class.'"':'').'><b>' . lang('calendar_thursday_short') . '</b></td>'; break;
                        case 5: echo '<td'.($class?' class="'.$class.'"':'').'><b>' . lang('calendar_friday_short') . '</b></td>'; break;
                        case 6: echo '<td'.($class?' class="'.$class.'"':'').'><b>' . lang('calendar_saturday_short') . '</b></td>'; break;
                        case 7: echo '<td'.($class?' class="'.$class.'"':'').'><b>' . lang('calendar_sunday_short') . '</b></td>'; break;
                    }
                }?>
        </tr>
        <tr>
            <td><b><?php echo lang('calendar_tabular_thead_employee');?></b></td>
            <?php
                $start = $year . '-' . $month . '-' . '1';    //first date of selected month
                $lastDay = date("t", strtotime($start));    //last day of selected month
                for ($ii = 1; $ii <=$lastDay; $ii++) {
                    $class = '';
                    if($isCurrentMonth && $ii === $currentDay){
                        $class .= ' currentday-bg';
                    }
                    echo '<td'.($class?' class="'.$class.'"':'').'><b>' . $ii . '</b></td>';
                }?>
        </tr>
    </thead>
  <tbody>
  <?php
  $repeater = 0;
  
  foreach ($tabular as $employee) {
      $dayIterator = 0;
      //echo var_dump($employee);
      ?>
    <tr>
      <td><?php echo $employee->name; ?></td>
      <?php foreach ($employee->days as $day) {
          $dayIterator++;
          $overlapping = FALSE;
          $style = '';
          $dataIds= '';
          if (strstr($day->display, ';')) {
              $periods = explode(";", $day->display);
              $statuses = explode(";", $day->status);
              $types = explode(";", $day->type);
                switch (intval($statuses[0]))
                {
                    case 1: $class = "planned"; break;  // Planned
                    case 2: $class = "requested"; break;  // Requested
                    case 3: if($types[0] == 'Campaign' || $types[0] == 'Floating') $class = "allacceptedtw"; elseif($types[0] == 'Time organisation') $class = "allacceptedto"; else $class = "accepted"; break;  // Accepted
                    case 4: $class = "rejected"; break;  // Rejected
                    case 5: $class = "rejected"; break;  // Cancellation
                    case 6: $class = "rejected"; break;  // Canceled
                    case 12: $class = "dayoff"; break;
                    case 13: $class = "dayoff"; break;
                }
                switch (intval($statuses[1]))
                {
                    case 1: $class .= "planned"; break;  // Planned
                    case 2: $class .= "requested"; break;  // Requested
                    case 3: if($types[1] == 'Campaign' || $types[1] == 'Floating') $class = "allacceptedtw"; elseif($types[1] == 'Time organisation') $class = "allacceptedto"; else $class .= "accepted"; break;  // Accepted
                    case 4: $class .= "rejected"; break;  // Rejected
                    case 5: $class .= "rejected"; break;  // Cancellation
                    case 6: $class .= "rejected"; break;  // Canceled
                    case 12: $class .= "dayoff"; break;
                    case 13: $class .= "dayoff"; break;
                }
                //If we have two requests the same day (morning/afternoon)
                if (($statuses[0] == $statuses[1]) && ($periods[0] != $periods[1])){
                    switch (intval($statuses[0]))
                    {
                        case 1: $class = "allplanned"; break;  // Planned
                        case 2: $class = "allrequested"; break;  // Requested
                        case 3: if($types[0] == 'Campaign' || $types[0] == 'Floating') $class = "allacceptedtw"; elseif($types[0] == 'Time organisation') $class = "allacceptedto"; else $class = "allaccepted"; break;  // Accepted
                        case 4: $class = "allrejected"; break;  // Rejected
                        case 5: $class = "allrejected"; break;  // Cancellation
                        case 6: $class = "allrejected"; break;  // Canceled
                    }
                }
          } else {
            switch ($day->display) {
                case '9': $class = "error"; break;
                case '0': $class = "working"; break;
                case '4': $class = "dayoff"; break;
                case '5': $class = "amdayoff"; break;
                case '6': $class = "pmdayoff"; break;
                case '1':
                      switch ($day->status)
                      {
                          case 1: $class = "allplanned"; break;  // Planned
                          case 2: $class = "allrequested"; break;  // Requested
                          case 3: if($day->type == 'Campaign' || $day->type == 'Floating') $class = "allacceptedtw"; elseif($day->type == 'Time organisation') $class = "allacceptedto"; else $class = "allaccepted"; break;  // Accepted
                          case 4: $class = "allrejected"; break;  // Rejected
                          case 5: $class = "allrejected"; break;  // Cancellation
                          case 6: $class = "allrejected"; break;  // Canceled
                      }
                      break;
                case '2':
                    switch ($day->status)
                      {
                          case 1: $class = "amplanned"; break;  // Planned
                          case 2: $class = "amrequested"; break;  // Requested
                          case 3: if($day->type == 'Campaign' || $day->type == 'Floating') $class = "amacceptedtw"; elseif($day->type == 'Time organisation') $class = "amacceptedto"; else $class = "amaccepted"; break;  // Accepted
                          case 4: $class = "amrejected"; break;  // Rejected
                          case 5: $class = "amrejected"; break;  // Cancellation
                          case 6: $class = "amrejected"; break;  // Canceled
                      }
                    break;
                case '3':
                    switch ($day->status)
                      {
                          case 1: $class = "pmplanned"; break;  // Planned
                          case 2: $class = "pmrequested"; break;  // Requested
                          case 3: if($day->type == 'Campaign' || $day->type == 'Floating') $class = "pmacceptedtw"; elseif($day->type == 'Time organisation') $class = "pmacceptedto"; else $class = "pmaccepted"; break;  // Accepted
                          case 4: $class = "pmrejected"; break;  // Rejected
                          case 5: $class = "pmrejected"; break;  // Cancellation
                          case 6: $class = "pmrejected"; break;  // Canceled
                      }
                    break;
            }
          }
          
          //Detect overlapping cases
          if (substr_count($day->display, ";") > 1) $overlapping = TRUE;
          switch ($class) {
                    case "plannedplanned":
                    case "requestedrequested":
                    case "acceptedaccepted":
                    case "rejectedrejected":
                        $overlapping = TRUE;
              break;
          }
          
          // Current day class
          if($isCurrentMonth && $dayIterator === $currentDay){
              $class .= ' currentday-border';
          }
            if ($class == "error"){
                echo '<td><img src="'.  base_url() .'assets/images/date_error.png"></td>';
            } else {
                $acronym = "";
                $dayType = "";
                if ($mode == 'public') {    //In public access, nobody is connected
                    $dayType = "";
                } else {
                    //Hide leave type to users who are not part of HR/Admin
                    if (($is_hr == TRUE) || 
                            ($is_admin == TRUE) || 
                            ($employee->manager == $user_id) || 
                            ($employee->id == $user_id)) {
                        $dayType = $day->type;
                        $acronym = $day->acronym;
                        $dataIds= $day->id;
                        if ((!$overlapping) && ($day->id !== 0)) {
                            $class .= ' clickable';
                        }
                    }
                }
                //Option to disable acronym (passed by URL)
                if (!$displayTypes) {
                    $acronym = "";
                }
                
                if ($overlapping) {
                    echo '<td title="' . $dayType . '" class="' . $class . '"><img src="' . base_url() . 'assets/images/date_error.png"></td>';
                } else {
                    //Acronyms of types
                    if ($acronym != "") {
                        $acronyms = explode(";", $acronym);
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
                            echo "<td title='$dayType' class='$class' style='$style' data-id='$dataIds' data-acronym='$acronym'>$acronym</td>";
                        } else {
                            echo "<td class='$class' style='font-size: 0.7em;' data-id='$dataIds' data-acronym='$acronym'>";
                            echo '  <span title="' . $dayType . '" class="pull-left">' . $acronyms[0] . '</span>';
                            echo '  <span title="' . $dayType . '" class="pull-right" >' . $acronyms[1] . '</span>';
                            echo '</td>';
                        }
                    } else {
                        //We don't display the acronyms of type or it is not available
                        echo "<td title='$dayType' style='$style' class='$class' data-id='$dataIds' data-acronym='$acronym'>&nbsp;</td>";
                    }
                }
            }
            ?>
    <?php } ?>
          </tr>
    <?php      
    if (++$repeater>=10) {
        $repeater = 0;?>
        <tr>
            <td>&nbsp;</td>
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
        <td><b><?php echo lang('calendar_tabular_thead_employee');?></b></td>
        <?php for ($ii = 1; $ii <=$lastDay; $ii++) echo '<td><b>' . $ii . '</b></td>';?>
    </tr>
    <?php }
    }?>
  </tbody>
</table>
<?php } else { ?>
<div class="container">
  <div class="row"><div class="span12"></div></div>
  <div class="row"><div class="span12"></div></div>
  <div class="row">
      <div class="span12 text-center">
        <span style="font-size: 200px; line-height: 1em; color: #bd362f;">
            <i class="mdi mdi-calendar-remove"></i>
        </span>
      </div>
  </div>
</div>
<?php } ?>

<script type="text/javascript">
function sign (p1, p2, p3) {
    return(p1.x - p3.x) * (p2.y - p3.y) - (p2.x - p3.x) * (p1.y - p3.y);
}

$('.clickable').click(function(event){
    //Get the position of the click into the cell
    var cell = $(event.target).closest( 'td' );
    var ids = String(cell.data("id"));
    var leaveId = ids;
    var title = String(cell.data("acronym"));
    if (ids.indexOf(';') !== -1) {    //A day with more than one request
        var cellPos = cell.offset();
        var point = { x: event.pageX - cellPos.left,
                      y: event.pageY - cellPos.top};

        //Describe the coordinates of the upper left triangle
        var t1 = { x: 0, y: 0 };
        var t2 = { x: cell[0].clientWidth, y: 0 };
        var t3 = { x: 0, y: cell[0].clientHeight };

        //Determine if the click occured into the upper triangle (i.e. morning)
        b1 = sign(point, t1, t2) < 0.0;
        b2 = sign(point, t2, t3) < 0.0;
        b3 = sign(point, t3, t1) < 0.0;
        isMorning = ((b1 == b2) && (b2 == b3));
        if (isMorning) {
            leaveId = ids.split(';')[0];
            title = title.split(';')[0];
        } else {
            leaveId = ids.split(';')[1];
            title = title.split(';')[1];
        }
    }
    //Build a link to leaves/view ending by a source argument, e.g.:
    //calendar/tabular/a/b/c/d?statuses=y
    if (leaveId != 0) {
        var currentUrl = 'calendar/tabular/' + entity + '/' + 
                (month + 1) + '/' + year + '/' + children + '/' + displayTypes +
                buildStatusesFilter();
        var sourceLink = encodeURIComponent(currentUrl);
        if(title == "TT")
        	var link = '<?php echo base_url(); ?>teleworks/teleworks/' + leaveId + '?source=' + sourceLink;
        else
        	var link = '<?php echo base_url(); ?>leaves/leaves/' + leaveId + '?source=' + sourceLink;
        window.open(link);
    }
});
</script>