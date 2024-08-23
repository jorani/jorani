<?php
/**
 * This view allows an employees (or HR admin/Manager) to create a new telework request
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>

<h2><?php echo lang('teleworks_create_title');?> &nbsp;<?php echo $help;?></h2>

<div class="row-fluid">
    <div class="span8">

<?php echo validation_errors(); ?>

<?php
$attributes = array('id' => 'frmTeleworkForm');
echo form_open('teleworks/create', $attributes) ?>

    <label for="viz_startdate"><?php echo lang('teleworks_create_field_start');?></label>
    <input type="text" name="viz_startdate" id="viz_startdate" value="<?php echo set_value('startdate'); ?>" autocomplete="off" />
    <input type="hidden" name="startdate" id="startdate" />
    <select name="startdatetype" id="startdatetype">
        <option value="Morning" selected><?php echo lang('Morning');?></option>
        <option value="Afternoon"><?php echo lang('Afternoon');?></option>
    </select><br />

    <label for="viz_enddate"><?php echo lang('teleworks_create_field_end');?></label>
    <input type="text" name="viz_enddate" id="viz_enddate" value="<?php echo set_value('enddate'); ?>" autocomplete="off" />
    <input type="hidden" name="enddate" id="enddate" />
    <select name="enddatetype" id="enddatetype">
        <option value="Morning"><?php echo lang('Morning');?></option>
        <option value="Afternoon" selected><?php echo lang('Afternoon');?></option>
    </select><br />

    <label for="duration"><?php echo lang('teleworks_create_field_duration');?> <span id="tooltipDayOff"></span></label>
    <?php if ($this->config->item('disable_edit_telework_duration') == TRUE) { ?>
    <input type="text" name="duration" id="duration" value="<?php echo set_value('duration'); ?>" readonly />
    <?php } else { ?>
    <input type="text" name="duration" id="duration" value="<?php echo set_value('duration'); ?>" />
    <?php } ?>

    <span style="margin-left: 2px;position: relative;top: -5px;" id="spnDayType"></span>

    <div class="alert hide alert-error" id="lblPasseAlert" onclick="$('#lblPasseAlert').hide();">
        <button type="button" class="close">&times;</button>
        <?php echo lang('teleworks_create_field_past_date_message');?>
    </div>

    <div class="alert hide alert-error" id="lblOverlappingAlert" onclick="$('#lblOverlappingAlert').hide();">
        <button type="button" class="close">&times;</button>
        <?php echo lang('teleworks_create_field_overlapping_message');?>
    </div>
    
    <div class="alert hide alert-error" id="lblOverlappingLeavesAlert" onclick="$('#lblOverlappingLeavesAlert').hide();">
        <button type="button" class="close">&times;</button>
        <?php echo lang('teleworks_create_field_overlapping_leaves_message');?>
    </div>
    
    <div class="alert hide alert-error" id="lblOverlappingTimeOrganisationsAlert" onclick="$('#lblOverlappingTimeOrganisationsAlert').hide();">
        <button type="button" class="close">&times;</button>
        <?php echo lang('teleworks_create_field_overlapping_time_organisations_message');?>
    </div>

    <div class="alert hide alert-error" id="lblOverlappingDayOffAlert" onclick="$('#lblOverlappingDayOffAlert').hide();">
        <button type="button" class="close">&times;</button>
        <?php echo lang('teleworks_flash_msg_overlap_dayoff');?>
    </div>
    
    <div class="alert hide alert-error" id="lblLimitExceedingAlert" onclick="$('#lblLimitExceedingAlert').hide();">
        <button type="button" class="close">&times;</button>
        <?php echo lang('teleworks_flash_msg_limit_exceeded');?>
    </div>
    
    <div class="alert hide alert-error" id="lblForCampaignDatesAlert" onclick="$('#lblForCampaignDatesAlert').hide();">
        <button type="button" class="close">&times;</button>
        <?php echo lang('teleworks_flash_msg_for_campaign_dates');?>
    </div>
    
    <div class="alert hide alert-error" id="lblDeadlineRespectedAlert" onclick="$('#lblDeadlineRespectedAlert').hide();">
        <button type="button" class="close">&times;</button>
        <?php echo lang('teleworks_flash_msg_deadline_respected');?>
    </div>
    
    <div class="alert hide alert-error" id="lblHalfdayTeleworkAlert" onclick="$('#lblHalfdayTeleworkAlert').hide();">
        <button type="button" class="close">&times;</button>
        <?php echo lang('teleworks_flash_msg_halfday_telework');?>
    </div>

    <label for="cause"><?php echo lang('teleworks_create_field_cause');?></label>
    <textarea name="cause"><?php echo set_value('cause'); ?></textarea>

    <br/><br/>
    <button name="status" value="1" type="submit" class="btn btn-primary"><i class="mdi mdi-calendar-question" aria-hidden="true"></i>&nbsp; <?php echo lang('Planned');?></button>
    &nbsp;&nbsp;
    <button name="status" value="2" type="submit" class="btn btn-primary "><i class="mdi mdi-check"></i>&nbsp; <?php echo lang('Requested');?></button>
    <br/><br/>
    <a href="<?php echo base_url(); ?>teleworks" class="btn btn-danger"><i class="mdi mdi-close"></i>&nbsp; <?php echo lang('teleworks_create_button_cancel');?></a>
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

<?php require_once dirname(BASEPATH) . "/local/triggers/telework_view.php"; ?>
<script>
$(document).on("click", "#showNoneWorkedDay", function(e) {
  showListDayOffHTML();
});
</script>
<script type="text/javascript">
    var baseURL = '<?php echo base_url();?>';
    var userId = <?php echo $user_id; ?>;
    var teleworkId = null;
    var languageCode = '<?php echo $language_code;?>';
    var dateJsFormat = '<?php echo lang('global_date_js_format');?>';
    var dateMomentJsFormat = '<?php echo lang('global_date_momentjs_format');?>';
    
    var isAdmin = <?php if($is_admin) echo $is_admin; else echo 0; ?>;
    var isHr = <?php if($is_hr) echo $is_hr; else echo 0; ?>;
    var isManager = <?php if($is_manager) echo $is_manager; else echo 0; ?>;

    var noContractMsg = "<?php echo lang('teleworks_validate_flash_msg_no_contract');?>";
    var noTwoPeriodsMsg = "<?php echo lang('teleworks_validate_flash_msg_overlap_period');?>";

    var overlappingWithDayOff = "<?php echo lang('teleworks_flash_msg_overlap_dayoff');?>";
    var listOfDaysOffTitle = "<?php echo lang('teleworks_flash_spn_list_days_off');?>";

function validate_form() {
    var fieldname = "";

    //Call custom trigger defined into local/triggers/telework.js
    if (typeof triggerValidateCreateForm == 'function') {
       if (triggerValidateCreateForm() == false) return false;
    }

    if ($('#viz_startdate').val() == "") fieldname = "<?php echo lang('teleworks_create_field_start');?>";
    if ($('#viz_enddate').val() == "") fieldname = "<?php echo lang('teleworks_create_field_end');?>";
    if ($('#duration').val() == "" || $('#duration').val() == 0) fieldname = "<?php echo lang('teleworks_create_field_duration');?>";
    if (fieldname == "") {
        return true;
    } else {
        bootbox.alert(<?php echo lang('teleworks_validate_mandatory_js_msg');?>);
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
<script type="text/javascript" src="<?php echo base_url();?>assets/js/lms/telework.edit-0.7.0.js" type="text/javascript"></script>
