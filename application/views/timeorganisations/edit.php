<?php
/**
 * This view allows to edit a time organisation
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>

<h2><?php echo lang('time_organisation_edit_description');?> <?php echo $timeorganisation['id']; ?></h2>

<?php echo validation_errors(); ?>

<?php
$attributes = array('id' => 'frmTimeOrganisationForm');
echo form_open('timeorganisations/edit/' . $timeorganisation['id'], $attributes); ?>

    <input type="hidden" name="id" value="<?php echo $timeorganisation['id']; ?>" required />
    
    <label for="employee"><?php echo lang('time_organisation_edit_field_employee');?></label>
    <select class="input-xxlarge" name="employee" id="employee">
    <?php foreach ($employees as $employee): ?>
        <option value="<?php echo $employee['id']; ?>" <?php if ($employee['id'] == $timeorganisation['employee']) echo "selected"; ?>><?php echo $employee['firstname'] . ' ' . $employee['lastname']; ?></option>
    <?php endforeach ?>
    </select>    
    
    <label for="duration"><?php echo lang('time_organisation_edit_field_duration');?></label>
    <input type="text" name="duration" id="duration" value="<?php echo $timeorganisation['duration']; ?>" />&nbsp;<span><?php echo lang('time_organisation_edit_field_duration_description');?></span><br />
    
    <label for="day"><?php echo lang('time_organisation_edit_field_day');?></label>
    <select name="day" id="day">
        <option value="Monday" <?php if($timeorganisation['day'] == 'Monday') echo 'selected';?>><?php echo lang('Monday');?></option>
        <option value="Tuesday" <?php if($timeorganisation['day'] == 'Tuesday') echo 'selected';?>><?php echo lang('Tuesday');?></option>
        <option value="Wednesday" <?php if($timeorganisation['day'] == 'Wednesday') echo 'selected';?>><?php echo lang('Wednesday');?></option>
        <option value="Thursday" <?php if($timeorganisation['day'] == 'Thursday') echo 'selected';?>><?php echo lang('Thursday');?></option>
        <option value="Friday" <?php if($timeorganisation['day'] == 'Friday') echo 'selected';?>><?php echo lang('Friday');?></option>
    </select>
    
    <label for="daytype"><?php echo lang('time_organisation_edit_field_daytype');?></label>
    <select name="daytype" id="daytype">
    <option value="Whole day" <?php if($timeorganisation['daytype'] == 'Whole day') echo 'selected';?>><?php echo lang('Whole day');?></option>
        <option value="Morning" <?php if($timeorganisation['daytype'] == 'Morning') echo 'selected';?>><?php echo lang('Morning');?></option>
        <option value="Afternoon" <?php if($timeorganisation['daytype'] == 'Afternoon') echo 'selected';?>><?php echo lang('Afternoon');?></option>
    </select>
    
    <label for="recurrence"><?php echo lang('time_organisation_edit_field_recurrence');?></label>
    <select name="recurrence" id="recurrence">
        <option value="" selected="selected"></option>
        <option value="All" <?php if($timeorganisation['recurrence'] == 'All') echo 'selected';?>><?php echo lang('all_recurrence');?></option>
        <option value="Even" <?php if($timeorganisation['recurrence'] == 'Even') echo 'selected';?>><?php echo lang('even_week');?></option>
        <option value="Odd" <?php if($timeorganisation['recurrence'] == 'Odd') echo 'selected';?>><?php echo lang('odd_week');?></option>        
    </select><br />

    <br /><br />
    <button type="submit" class="btn btn-primary"><i class="mdi mdi-check"></i>&nbsp;<?php echo lang('time_organisation_edit_button_update');?></button>
    &nbsp;
    <a href="<?php echo base_url();?>timeorganisations" class="btn btn-danger"><i class="mdi mdi-close"></i>&nbsp;<?php echo lang('time_organisation_edit_button_cancel');?></a>
</form>

<link rel="stylesheet" href="<?php echo base_url();?>assets/css/flick/jquery-ui.custom.min.css">
<script src="<?php echo base_url();?>assets/js/jquery-ui.custom.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>

<script type="text/javascript">
function validate_form() {
    var fieldname = "";

	if ($('#recurrence').val() == "") fieldname = "<?php echo lang('time_organisation_edit_field_recurrence');?>";
    if ($('#daytype').val() == "") fieldname = "<?php echo lang('time_organisation_edit_field_daytype');?>";
    if ($('#day').val() == "") fieldname = "<?php echo lang('time_organisation_edit_field_day');?>";
	if ($('#duration').val() == "") fieldname = "<?php echo lang('time_organisation_edit_field_duration');?>";
	if ($('#employee').val() == "") fieldname = "<?php echo lang('time_organisation_edit_field_employee');?>"; 	 
    if (fieldname == "") {    	
        return true;
    } else {
        bootbox.alert(<?php echo lang('time_organisation_validate_mandatory_js_msg');?>);
        return false;
    }
}

$(function () {  	
    $("#frmTimeOrganisationForm").submit(function(e) {
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