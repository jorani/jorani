<?php
/**
 * This view allows a manager (if the option is activated) or HR admin to a telework request in lieu of an employee.
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.2.0
 */
?>

<h2><?php echo lang('hr_teleworks_create_title');?> &nbsp;<?php echo $help;?>
</h2>

<div class="row-fluid">
    <div class="span8">

<?php echo validation_errors(); ?>

<?php $attributes = array('id' => 'frmTeleworkForm');
echo form_open($form_action, $attributes) ?>

    <label for="viz_startdate" required><?php echo lang('hr_teleworks_create_field_start');?></label>
    <input type="text" name="viz_startdate" id="viz_startdate" value="<?php echo set_value('startdate'); ?>" />
    <input type="hidden" name="startdate" id="startdate" />
    <select name="startdatetype" id="startdatetype">
        <option value="Morning" selected><?php echo lang('Morning');?></option>
        <option value="Afternoon"><?php echo lang('Afternoon');?></option>
    </select><br />

    <label for="viz_enddate" required><?php echo lang('hr_teleworks_create_field_end');?></label>
    <input type="text" name="viz_enddate" id="viz_enddate" value="<?php echo set_value('enddate'); ?>" />
    <input type="hidden" name="enddate" id="enddate" />
    <select name="enddatetype" id="enddatetype">
        <option value="Morning"><?php echo lang('Morning');?></option>
        <option value="Afternoon" selected><?php echo lang('Afternoon');?></option>
    </select><br />

    <label for="duration" required><?php echo lang('hr_teleworks_create_field_duration');?> <span id="tooltipDayOff"></span></label>
    <input type="text" name="duration" id="duration" value="<?php echo set_value('duration'); ?>" />

    <span style="margin-left: 2px;position: relative;top: -5px;" id="spnDayType"></span>

    <div class="alert hide alert-error" id="lblCreditAlert" onclick="$('#lblCreditAlert').hide();">
        <button type="button" class="close">&times;</button>
        <?php echo lang('hr_teleworks_create_field_duration_message');?>
    </div>

    <div class="alert hide alert-error" id="lblOverlappingAlert" onclick="$('#lblOverlappingAlert').hide();">
        <button type="button" class="close">&times;</button>
        <?php echo lang('hr_teleworks_create_field_overlapping_message');?>
    </div>
    
    <div class="alert hide alert-error" id="lblOverlappingLeavesAlert" onclick="$('#lblOverlappingLeavesAlert').hide();">
        <button type="button" class="close">&times;</button>
        <?php echo lang('hr_teleworks_create_field_overlapping_leaves_message');?>
    </div>
    
    <div class="alert hide alert-error" id="lblOverlappingTimeOrganisationsAlert" onclick="$('#lblOverlappingTimeOrganisationsAlert').hide();">
        <button type="button" class="close">&times;</button>
        <?php echo lang('hr_teleworks_create_field_overlapping_time_organisations_message');?>
    </div>

    <div class="alert hide alert-error" id="lblOverlappingDayOffAlert" onclick="$('#lblOverlappingDayOffAlert').hide();">
        <button type="button" class="close">&times;</button>
        <?php echo lang('hr_teleworks_flash_msg_overlap_dayoff');?>
    </div>
    
    <div class="alert hide alert-error" id="lblLimitExceedingAlert" onclick="$('#lblLimitExceedingAlert').hide();">
        <button type="button" class="close">&times;</button>
        <?php echo lang('hr_teleworks_flash_msg_limit_exceeded');?>
    </div>
    
    <div class="alert hide alert-error" id="lblHalfdayTeleworkAlert" onclick="$('#lblHalfdayTeleworkAlert').hide();">
        <button type="button" class="close">&times;</button>
        <?php echo lang('hr_teleworks_flash_msg_halfday_telework');?>
    </div>

    <label for="cause"><?php echo lang('hr_teleworks_create_field_cause');?></label>
    <textarea name="cause"><?php echo set_value('cause'); ?></textarea>

    <label for="status" required><?php echo lang('hr_teleworks_create_field_status');?></label>
    <select name="status">
        <option value="1" <?php if ($this->config->item('telework_status_requested') == FALSE) echo 'selected'; ?>><?php echo lang('Planned');?></option>
        <option value="2" <?php if ($this->config->item('telework_status_requested') == TRUE) echo 'selected'; ?>><?php echo lang('Requested');?></option>
        <option value="3"><?php echo lang('Accepted');?></option>
        <option value="4"><?php echo lang('Rejected');?></option>
        <option value="5"><?php echo lang('Cancellation');?></option>
        <option value="6"><?php echo lang('Canceled');?></option>
    </select><br />

    <button type="submit" class="btn btn-primary"><i class="mdi mdi-check"></i>&nbsp; <?php echo lang('hr_teleworks_create_button_create');?></button>
    &nbsp;
    <a href="<?php echo base_url() . $source; ?>" class="btn btn-danger"><i class="mdi mdi-close"></i>&nbsp; <?php echo lang('hr_teleworks_create_button_cancel');?></a>
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
<script type="text/javascript" src="<?php echo base_url();?>assets/js/lms/telework.edit-0.7.0.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/selectize.min.js"></script>
<script>
$(document).on("click", "#showNoneWorkedDay", function(e) {
  showListDayOffHTML();
});
</script>
<script type="text/javascript">
<?php if ($this->config->item('csrf_protection') == TRUE) {?>
$(function () {
    $.ajaxSetup({
        data: {
            <?php echo $this->security->get_csrf_token_name();?>: "<?php echo $this->security->get_csrf_hash();?>",
        }
    });
});
<?php }?>
    var baseURL = '<?php echo base_url();?>';
    var userId = <?php echo $employee; ?>;
    var teleworkId = null;
    var languageCode = '<?php echo $language_code;?>';
    var dateJsFormat = '<?php echo lang('global_date_js_format');?>';
    var dateMomentJsFormat = '<?php echo lang('global_date_momentjs_format');?>';
    
    var isAdmin = <?php if($is_admin) echo $is_admin; else echo 0; ?>;
    var isHr = <?php if($is_hr) echo $is_hr; else echo 0; ?>;
    var isManager = <?php if($is_manager) echo $is_manager; else echo 0; ?>;

    var noContractMsg = "<?php echo lang('hr_teleworks_validate_flash_msg_no_contract');?>";
    var noTwoPeriodsMsg = "<?php echo lang('hr_teleworks_validate_flash_msg_overlap_period');?>";

    var overlappingWithDayOff = "<?php echo lang('hr_teleworks_flash_msg_overlap_dayoff');?>";
    var listOfDaysOffTitle = "<?php echo lang('hr_teleworks_flash_spn_list_days_off');?>";

function validate_form() {
    var fieldname = "";

    //Call custom trigger defined into local/triggers/telework.js
    if (typeof triggerValidateCreateForm == 'function') {
       if (triggerValidateCreateForm() == false) return false;
    }

    if ($('#viz_startdate').val() == "") fieldname = "<?php echo lang('hr_teleworks_create_field_start');?>";
    if ($('#viz_enddate').val() == "") fieldname = "<?php echo lang('hr_teleworks_create_field_end');?>";
    if ($('#duration').val() == "" || $('#duration').val() == 0) fieldname = "<?php echo lang('hr_teleworks_create_field_duration');?>";
    if (fieldname == "") {
        return true;
    } else {
        bootbox.alert(<?php echo lang('hr_teleworks_validate_mandatory_js_msg');?>);
        return false;
    }
}
</script>
