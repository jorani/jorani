<?php
/**
 * This view displays the leave requests of the collaborators of the connected user (if any).
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>

<div class="row-fluid">
    <div class="span12">

<h2><?php echo lang('calendar_collaborators_title');?> &nbsp;<?php echo $help;?></h2>

<p><?php echo lang('calendar_collaborators_description');?></p>

<div class="row-fluid">
    <div class="span8">
        <span class="label"><?php echo lang('Planned');?></span>
        <span class="label label-success"><?php echo lang('Accepted');?></span>
        <span class="label label-warning"><?php echo lang('Requested');?></span>
        <span class="label label-important" style="background-color: #ff0000;"><?php echo lang('Rejected');?></span>
        <span class="label label-important" style="background-color: #ff0000;"><?php echo lang('Cancellation');?></span>
        <span class="label label-important" style="background-color: #ff0000;"><?php echo lang('Canceled');?></span>
    	<?php if ($this->config->item('disable_telework') == FALSE) {?>
    		<span class="label label-info"><?php echo lang('Accepted_Telework');?></span>
    	<?php }?>
    </div>
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
            <?php $icsUrl = base_url() . 'ics/collaborators/' . $user_id . '?token=' . $this->session->userdata('random_hash');?>
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
        events: '<?php echo base_url();?>leaves/collaborators',
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

