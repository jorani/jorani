<?php
/**
 * This view allows an employees (or HR admin/Manager) to create a new leave request
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>

<h2><?php echo lang('leaves_create_title');?> &nbsp;<?php echo $help;?></h2>

<div class="row-fluid">
    <div class="span8">

<?php echo validation_errors(); ?>

<?php
$attributes = array('id' => 'frmLeaveForm');
echo form_open('leaves/create', $attributes) ?>

    <label for="type">
        <?php echo lang('leaves_create_field_type');?>
        &nbsp;<span class="muted" id="lblCredit"><?php if (!is_null($credit)) { ?>(<?php echo $credit; ?>)<?php } ?></span>
    </label>
    <select class="input-xxlarge" name="type" id="type">
    <?php foreach ($types as $typeId => $TypeName): ?>
        <option value="<?php echo $typeId; ?>" <?php if ($typeId == $defaultType) echo "selected"; ?>><?php echo $TypeName; ?></option>
    <?php endforeach ?>
    </select>

    <label for="viz_startdate"><?php echo lang('leaves_create_field_start');?></label>
    <input type="text" name="viz_startdate" id="viz_startdate" value="<?php echo set_value('startdate'); ?>" autocomplete="off" />
    <input type="hidden" name="startdate" id="startdate" />
    <select name="startdatetype" id="startdatetype">
        <option value="Morning" selected><?php echo lang('Morning');?></option>
        <option value="Afternoon"><?php echo lang('Afternoon');?></option>
    </select><br />

    <label for="viz_enddate" id="enddatelabel"><?php echo lang('leaves_create_field_end');?></label>
    <input type="text" name="viz_enddate" id="viz_enddate" value="<?php echo set_value('enddate'); ?>" autocomplete="off" />
    <input type="hidden" name="enddate" id="enddate" />
    <select name="enddatetype" id="enddatetype">
        <option value="Morning"><?php echo lang('Morning');?></option>
        <option value="Afternoon" selected><?php echo lang('Afternoon');?></option>
    </select><br />
<!-- Show input field for start time -->
<label for="shorttime" id="timelabel"><?php echo 'Start time (24 hours format)';?></label>
<input type="time" name="shorttime" id="shorttime">

    <label for="duration"><?php echo lang('leaves_create_field_duration');?> <span id="tooltipDayOff"></span></label>
    <?php if ($this->config->item('disable_edit_leave_duration') == TRUE) { ?>
    <input type="text" name="duration" id="duration" value="<?php echo set_value('duration'); ?>" readonly />
    <?php } else { ?>
    <input type="text" name="duration" id="duration" value="<?php echo set_value('duration'); ?>" />
    <?php } ?>

    <span style="margin-left: 2px;position: relative;top: -5px;" id="spnDayType"></span>
<!-- show duration fo short leave -->
  <p id="showduration" >2 hours</p>



    <div class="alert hide alert-error" id="lblCreditAlert" onclick="$('#lblCreditAlert').hide();">
        <button type="button" class="close">&times;</button>
        <?php echo lang('leaves_create_field_duration_message');?>
    </div>

    <div class="alert hide alert-error" id="lblOverlappingAlert" onclick="$('#lblOverlappingAlert').hide();">
        <button type="button" class="close">&times;</button>
        <?php echo lang('leaves_create_field_overlapping_message');?>
    </div>

    <div class="alert hide alert-error" id="lblOverlappingDayOffAlert" onclick="$('#lblOverlappingDayOffAlert').hide();">
        <button type="button" class="close">&times;</button>
        <?php echo lang('leaves_flash_msg_overlap_dayoff');?>
    </div>

    <label for="cause"><?php echo lang('leaves_create_field_cause');?></label>
    <textarea name="cause"><?php echo set_value('cause'); ?></textarea>

    <br/><br/>
    <button name="status" value="1" type="submit" class="btn btn-primary"><i class="mdi mdi-calendar-question" aria-hidden="true"></i>&nbsp; <?php echo lang('Planned');?></button>
    &nbsp;&nbsp;
    <button name="status" value="2" type="submit" class="btn btn-primary "><i class="mdi mdi-check"></i>&nbsp; <?php echo lang('Requested');?></button>
    <br/><br/>
    <a href="<?php echo base_url(); ?>leaves" class="btn btn-danger"><i class="mdi mdi-close"></i>&nbsp; <?php echo lang('leaves_create_button_cancel');?></a>
</form>

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

<link rel="stylesheet" href="<?php echo base_url();?>assets/css/flick/jquery-ui.custom.min.css">
<script src="<?php echo base_url();?>assets/js/jquery-ui.custom.min.js"></script>
<?php //Prevent HTTP-404 when localization isn't needed
if ($language_code != 'en') { ?>
<script src="<?php echo base_url();?>assets/js/i18n/jquery.ui.datepicker-<?php echo $language_code;?>.js"></script>
<?php } ?>
<script src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>

<?php require_once dirname(BASEPATH) . "/local/triggers/leave_view.php"; ?>
<script>
$(document).on("click", "#showNoneWorkedDay", function(e) {
  showListDayOffHTML();
});
</script>

<script>
	// this will hide or show enddate , Startdatetype , enddatetype, enddatelabel, duration etc.
	//  accordimg to type of leave (Short Leave or not). 
$(document).ready(function(){
        $("#shorttime").hide();
            $("#timelabel").hide();
           $('#showduration').hide();

        $("#type").change(function(){
           
            var type= $(this).children("option:selected").text();
            if (type == "Short Leave")
            { $("#startdatetype" ).hide();
               $("#enddatetype").hide();
	    $("#duration" ).hide();
	     $("#enddatelabel" ).hide();


            $("#viz_enddate" ).hide();
            $("#shorttime").show();
            $("#timelabel").show();
            $('#showduration').show();
            }
            else
            { $("#shorttime").hide();
            $("#timelabel").hide();
             $('#showduration').hide();
              $("#startdatetype" ).show();
               $("#enddatetype").show();
                $("#duration" ).show();
	    $("#viz_enddate" ).show();
	     $("#enddatelabel" ).show();


            }
    });
});

// this function set end date and end time for short leave
//
function shortleave(){
 $("#startdatetype option:selected").val($("#shorttime").val());
 var time=   $('#shorttime').val().split(':');;
                 var h=parseInt(time[0]);
                   h=h+2;
    var endtime = h+ ":"+time[1];
                
                     $("#enddatetype option:selected").val(endtime);
             $("#duration" ).val(0.33);
           var sd=$("#startdate").val();
           $("#viz_enddate" ).datepicker('setDate', new Date(sd));

}

</script>
<script type="text/javascript">
    var baseURL = '<?php echo base_url();?>';
    var userId = <?php echo $user_id; ?>;
    var leaveId = null;
    var languageCode = '<?php echo $language_code;?>';
    var dateJsFormat = '<?php echo lang('global_date_js_format');?>';
    var dateMomentJsFormat = '<?php echo lang('global_date_momentjs_format');?>';

    var noContractMsg = "<?php echo lang('leaves_validate_flash_msg_no_contract');?>";
    var noTwoPeriodsMsg = "<?php echo lang('leaves_validate_flash_msg_overlap_period');?>";

    var overlappingWithDayOff = "<?php echo lang('leaves_flash_msg_overlap_dayoff');?>";
    var listOfDaysOffTitle = "<?php echo lang('leaves_flash_spn_list_days_off');?>";

function validate_form() {
    var fieldname = "";

    //Call custom trigger defined into local/triggers/leave.js
    if (typeof triggerValidateCreateForm == 'function') {
       if (triggerValidateCreateForm() == false) return false;
    }

    if ($('#viz_startdate').val() == "") fieldname = "<?php echo lang('leaves_create_field_start');?>";
    if ($('#viz_enddate').val() == "") fieldname = "<?php echo lang('leaves_create_field_end');?>";
    if ($('#duration').val() == "" || $('#duration').val() == 0) fieldname = "<?php echo lang('leaves_create_field_duration');?>";
    if (fieldname == "") {
        return true;
    } else {
        bootbox.alert(<?php echo lang('leaves_validate_mandatory_js_msg');?>);
        return false;
    }
}

//Disallow the use of negative symbols (through a whitelist of symbols)
function keyAllowed(key) {
  var keys = [8, 9, 13, 16, 17, 18, 19, 20, 27, 46, 48, 49, 50,
    51, 52, 53, 54, 55, 56, 57, 91, 92, 93
  ];
  if (key && keys.indexOf(key) === -1)
    return false;
  else
    return true;
}

$(function () {
    //Selectize the leave type combo
    $('#type').select2();

<?php if ($this->config->item('disallow_requests_without_credit') == TRUE) {?>
    var durationField = document.getElementById("duration");
    durationField.setAttribute("min", "0");
    durationField.addEventListener('keypress', function(e) {
        var key = !isNaN(e.charCode) ? e.charCode : e.keyCode;
        if (!keyAllowed(key))
        e.preventDefault();
    }, false);

    // Disable pasting of non-numbers
    durationField.addEventListener('paste', function(e) {
        var pasteData = e.clipboardData.getData('text/plain');
        if (pasteData.match(/[^0-9]/))
        e.preventDefault();
    }, false);
<?php }?>
});

<?php if ($this->config->item('csrf_protection') == TRUE) {?>
$(function () {
    $.ajaxSetup({
        data: {
            <?php echo $this->security->get_csrf_token_name();?>: "<?php echo $this->security->get_csrf_hash();?>",
        }
    });
});
<?php }?>
</script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/lms/leave.edit-0.7.0.js" type="text/javascript"></script>
