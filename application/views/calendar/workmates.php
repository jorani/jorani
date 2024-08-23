<?php
/**
 * This view displays the leave requests of the workmates of the connected user (employees having the same line manager).
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>

<div class="row-fluid">
    <div class="span12">

<h2><?php echo lang('calendar_workmates_title');?> &nbsp;<?php echo $help;?></h2>

<p><?php echo lang('calendar_workmates_description');?></p>

<div class="row-fluid">
    <div class="span3"><span class="label"><?php echo lang('Planned');?></span></div>
    <div class="span3"><span class="label label-success"><?php echo lang('Accepted');?></span></div>
    <div class="span3"><span class="label label-warning"><?php echo lang('Requested');?></span></div>
    <?php if ($this->config->item('disable_telework') == FALSE) {?>
    	<div class="span3"><span class="label label-info"><?php echo lang('Accepted_Telework');?></span></div>
    <?php }?>
    <div class="span3">&nbsp;</div>
</div>

<div id='calendar'></div>

    </div>
</div>

<div class="modal hide" id="frmModalAjaxWait" data-backdrop="static" data-keyboard="false">
        <div class="modal-header">
            <h1><?php echo lang('global_msg_wait');?></h1>
        </div>
        <div class="modal-body">
            <img src="<?php echo base_url();?>assets/images/loading.gif"  align="middle">
        </div>
 </div>

<link href="<?php echo base_url();?>assets/fullcalendar-2.8.0/fullcalendar.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/fullcalendar-2.8.0/fullcalendar.min.js"></script>
<?php if ($language_code != 'en') {?>
<script type="text/javascript" src="<?php echo base_url();?>assets/fullcalendar-2.8.0/lang/<?php echo strtolower($language_code);?>.js"></script>
<?php }?>
<script src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    
    //Global Ajax error handling mainly used for session expiration
    $( document ).ajaxError(function(event, jqXHR, settings, errorThrown) {
        $('#frmModalAjaxWait').modal('hide');
        if (jqXHR.status == 401) {
            bootbox.alert("<?php echo lang('global_ajax_timeout');?>", function() {
                //After the login page, we'll be redirected to the current page 
               location.reload();
            });
        } else { //Oups
            bootbox.alert("<?php echo lang('global_ajax_error');?>");
        }
      });
    
    //Create a calendar and fill it with AJAX events
    $('#calendar').fullCalendar({
        timeFormat: ' ', /*Trick to remove the start time of the event*/
        header: {
            left: "prev,next today",
            center: "title",
            right: ""
        },
        events: '<?php echo base_url();?>leaves/workmates',
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
});
</script>

