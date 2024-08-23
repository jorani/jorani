<?php
/**
 * This view displays the leave requests of the connected user.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>

<div class="row-fluid">
    <div class="span12">

<h2><?php echo lang('calendar_individual_title');?> &nbsp;<?php echo $help;?></h2>

<div class="row-fluid">
    <div class="span12"><?php echo lang('calendar_individual_description');?></div>
</div>

<div class="row-fluid">
    <div class="span6">
        <button id="cmdPrevious" class="btn btn-primary"><i class="mdi mdi-chevron-left"></i></button>
        <button id="cmdToday" class="btn btn-primary"><?php echo lang('today');?></button>
        <button id="cmdNext" class="btn btn-primary"><i class="mdi mdi-chevron-right"></i></button>
    </div>
    <div class="span6">
        <div class="pull-right">
            <button id="cmdDisplayDayOff" class="btn btn-primary"><i class="mdi mdi-calendar"></i>&nbsp;<?php echo lang('calendar_individual_day_offs');?></button>
        </div>
    </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div class="row-fluid">
    <div class="span2"><span class="label"><?php echo lang('Planned');?></span></div>
    <div class="span2"><span class="label label-success"><?php echo lang('Accepted');?></span></div>
    <div class="span2"><span class="label label-warning"><?php echo lang('Requested');?></span></div>
    <div class="span2"><span class="label label-important" style="background-color: #ff0000;"><?php echo lang('Rejected');?></span></div>
    <?php if ($this->config->item('disable_telework') == FALSE) {?>
    	<div class="span2"><span class="label label-info"><?php echo lang('Accepted_Telework');?></span></div>
    	<div class="span2">
    <?php } else ?>
    	<div class="span4">
        <?php if ($this->config->item('ics_enabled') == FALSE) {?>
        &nbsp;
        <?php } else {?>
        <span class="pull-right"><a id="lnkICS" href="#"><i class="mdi mdi-earth nolink"></i> ICS</a></span>
        <?php }?>
    </div>
</div>

<div id='calendar'></div>

    </div>
</div>

<div id="frmEvent" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmEvent').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('calendar_individual_popup_event_title');?></h3>
    </div>
    <div class="modal-body">
        <a href="#" id="lnkDownloadCalEvnt"><?php echo lang('calendar_individual_popup_event_link_ical');?></a> <?php echo lang('calendar_individual_popup_event_link_ical_description');?>

    </div>
    <div class="modal-footer">
        <a href="#" onclick="$('#frmEvent').modal('hide');" class="btn"><?php echo lang('calendar_individual_popup_event_button_close');?></a>
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

<div id="frmLinkICS" class="modal hide fade">
    <div class="modal-header">
        <h3>ICS<a href="#" onclick="$('#frmLinkICS').modal('hide');" class="close">&times;</a></h3>
    </div>
    <div class="modal-body" id="frmSelectDelegateBody">
        <div class='input-append'>
                <?php $icsUrl = base_url() . 'ics/individual/' . $user_id . '?token=' . $this->session->userdata('random_hash');?>
                <input type="text" class="input-xlarge" id="txtIcsUrl" onfocus="this.select();" onmouseup="return false;"
                    value="<?php echo $icsUrl;?>" />
                 <button id="cmdCopy" class="btn" data-clipboard-text="<?php echo $icsUrl;?>">
                     <i class="mdi mdi-content-copy"></i>
                 </button>
                <a href="#" id="tipCopied" data-toggle="tooltip" title="<?php echo lang('copied');?>" data-placement="right" data-container="#cmdCopy"></a>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#" onclick="$('#frmLinkICS').modal('hide');" class="btn btn-primary"><?php echo lang('OK');?></a>
    </div>
</div>

<link href="<?php echo base_url();?>assets/fullcalendar-2.8.0/fullcalendar.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/fullcalendar-2.8.0/fullcalendar.min.js"></script>
<?php if ($language_code != 'en') {?>
<script type="text/javascript" src="<?php echo base_url();?>assets/fullcalendar-2.8.0/lang/<?php echo strtolower($language_code);?>.js"></script>
<?php }?>
<script src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>
<script type="text/javascript">
var toggleDayoffs = false;

$(function () {
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

    $("#frmEvent").alert();

    //Load FullCalendar widget
    $('#calendar').fullCalendar({
        timeFormat: ' ', /*Trick to remove the start time of the event*/
        header: {
            left: "",
            center: "title",
            right: ""
        },
        /*defaultView: 'agendaWeek',*/
        events: '<?php echo base_url();?>leaves/individual',
        eventClick: function(calEvent, jsEvent, view) {
            if (calEvent.color != '#000000') {
                var link = "<?php echo base_url();?>ics/ical/" + calEvent.id;
                $("#lnkDownloadCalEvnt").attr('href', link);
                $('#frmEvent').modal('show');
            }
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

    //Prevent to load always the same content (refreshed each time)
    $('#frmEvent').on('hidden', function() {
        $(this).removeData('modal');
    });

    //Toggle day offs display
    $('#cmdDisplayDayOff').on('click', function() {
        toggleDayoffs = !toggleDayoffs;
        if (toggleDayoffs) {
            $('#calendar').fullCalendar('addEventSource', '<?php echo base_url();?>contracts/calendar/userdayoffs');
        } else {
            $('#calendar').fullCalendar('removeEventSources', '<?php echo base_url();?>contracts/calendar/userdayoffs');
        }
    });

    //Manage Prev/Next buttons
    $('#cmdNext').click(function() {
        $('#calendar').fullCalendar('next');
    });
    $('#cmdPrevious').click(function() {
        $('#calendar').fullCalendar('prev');
    });

    //On click on today, if the current month is the same than the displayed month, we refetch the events
    $('#cmdToday').click(function() {
        var displayedDate = new Date($('#calendar').fullCalendar('getDate'));
        var currentDate = new Date();
        if (displayedDate.getMonth() == currentDate.getMonth()) {
            $('#calendar').fullCalendar('refetchEvents');
        } else {
            $('#calendar').fullCalendar('today');
        }
    });

    //Copy/Paste ICS Feed
    var client = new ClipboardJS("#cmdCopy");
    $('#lnkICS').click(function () {
        $("#frmLinkICS").modal('show');
    });
    client.on( "success", function() {
        $('#tipCopied').tooltip('show');
        setTimeout(function() {$('#tipCopied').tooltip('hide')}, 1000);
    });
});
</script>
