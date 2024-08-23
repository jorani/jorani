<?php
/**
 * This view allows an employees (or HR admin/Manager) to create telework request for a campaign
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>

<h2><?php echo lang('hr_teleworks_create_campaign_title');?> &nbsp;<?php echo $help;?></h2>

<div class="row-fluid">
    <div class="span8">

<?php echo validation_errors(); ?>

<?php
$attributes = array('id' => 'frmTeleworkForm');
echo form_open($form_action, $attributes) ?>

	<label for="campaign"><?php echo lang('hr_teleworks_create_field_campaign');?></label>   
    <select class="input-xxlarge" name="campaign" id="campaign">
    <option value="" selected="selected"></option>
    <?php foreach ($campaigns as $campaign): ?>
        <option value="<?php echo $campaign['id']; ?>" <?php if ($campaign['id'] == set_value('campaign')) echo "selected"; ?>><?php echo $campaign['name'] . ' (du ' . $campaign['startdate'] . ' au ' . $campaign['enddate'] . ')'; ?></option>
    <?php endforeach ?>
    </select><br />
   
    <label for="day"><?php echo lang('hr_teleworks_create_field_day');?></label>
    <select name="day" id="day">
        <option value="" selected="selected"></option>
        <option value="Monday" <?php if(set_value('day') == 'Monday') echo 'selected';?>><?php echo lang('Monday');?></option>
        <option value="Tuesday" <?php if(set_value('day') == 'Tuesday') echo 'selected';?>><?php echo lang('Tuesday');?></option>
        <option value="Wednesday" <?php if(set_value('day') == 'Wednesday') echo 'selected';?>><?php echo lang('Wednesday');?></option>
        <option value="Thursday" <?php if(set_value('day') == 'Thursday') echo 'selected';?>><?php echo lang('Thursday');?></option>
        <option value="Friday" <?php if(set_value('day') == 'Friday') echo 'selected';?>><?php echo lang('Friday');?></option>
    </select><br />
    
    <label for="recurrence"><?php echo lang('hr_teleworks_create_field_recurrence');?></label>
    <select name="recurrence" id="recurrence">
        <option value="" selected="selected"></option>
        <option value="All" <?php if(set_value('recurrence') == 'All') echo 'selected';?>><?php echo lang('all_recurrence');?></option>
        <option value="Even" <?php if(set_value('recurrence') == 'Even') echo 'selected';?>><?php echo lang('even_week');?></option>
        <option value="Odd" <?php if(set_value('recurrence') == 'Odd') echo 'selected';?>><?php echo lang('odd_week');?></option>        
    </select><br />

    <label for="status" required><?php echo lang('hr_teleworks_create_field_status');?></label>
    <select name="status">
        <option value="1" <?php if ($this->config->item('telework_status_requested') == FALSE) echo 'selected'; ?>><?php echo lang('Planned');?></option>
        <option value="2" <?php if ($this->config->item('telework_status_requested') == TRUE) echo 'selected'; ?>><?php echo lang('Requested');?></option>
        <option value="3"><?php echo lang('Accepted');?></option>
        <option value="4"><?php echo lang('Rejected');?></option>
        <option value="5"><?php echo lang('Cancellation');?></option>
        <option value="6"><?php echo lang('Canceled');?></option>
    </select><br />
    
    <span style="margin-left: 2px;position: relative;top: -5px;" id="spnDayType"></span>

	<br/><br/>
    <button type="submit" class="btn btn-primary"><i class="mdi mdi-check"></i>&nbsp; <?php echo lang('hr_teleworks_create_button_create');?></button>
    &nbsp;
    <a href="<?php echo base_url() . $source; ?>" class="btn btn-danger"><i class="mdi mdi-close"></i>&nbsp; <?php echo lang('hr_teleworks_create_button_cancel');?></a>
</form>

    </div>
</div>

<link rel="stylesheet" href="<?php echo base_url();?>assets/css/flick/jquery-ui.custom.min.css">
<script src="<?php echo base_url();?>assets/js/jquery-ui.custom.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>

<script type="text/javascript">
function validate_form() {
    var fieldname = "";

	if ($('#recurrence').val() == "") fieldname = "<?php echo lang('hr_teleworks_create_field_recurrence');?>";
	if ($('#day').val() == "") fieldname = "<?php echo lang('hr_teleworks_create_field_day');?>";
    if ($('#campaign').val() == "") fieldname = "<?php echo lang('hr_teleworks_create_field_campaign');?>";    
    if (fieldname == "") {    	
        return true;
    } else {
        bootbox.alert(<?php echo lang('hr_teleworks_validate_mandatory_js_msg');?>);
        return false;
    }
}

$(function () {   
    $("#frmTeleworkForm").submit(function(e) {
        if (validate_form()) {
            return true;
        } else {
            e.preventDefault();
            return false;
        }
    });
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
