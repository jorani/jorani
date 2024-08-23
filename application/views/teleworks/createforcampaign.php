<?php
/**
 * This view allows an employees (or HR admin/Manager) to create telework request for a campaign
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>

<h2><?php echo lang('teleworks_create_campaign_title');?> &nbsp;<?php echo $help;?></h2>

<div class="row-fluid">
    <div class="span8">

<?php echo validation_errors(); ?>

<?php
$attributes = array('id' => 'frmTeleworkForm');
echo form_open('teleworks/createforcampaign', $attributes) ?>

	<label for="campaign"><?php echo lang('teleworks_create_field_campaign');?></label>   
    <select class="input-xxlarge" name="campaign" id="campaign">
    <option value="" selected="selected"></option>
    <?php foreach ($campaigns as $campaign): ?>
        <option value="<?php echo $campaign['id']; ?>" <?php if ($campaign['id'] == set_value('campaign')) echo "selected"; ?>><?php echo $campaign['name'] . ' (du ' . $campaign['startdate'] . ' au ' . $campaign['enddate'] . ')'; ?></option>
    <?php endforeach ?>
    </select><br />
    
    <label for="day"><?php echo lang('teleworks_create_field_day');?></label>
    <select name="day" id="day">
        <option value="" selected="selected"></option>
        <option value="Monday" <?php if(set_value('day') == 'Monday') echo 'selected';?>><?php echo lang('Monday');?></option>
        <option value="Tuesday" <?php if(set_value('day') == 'Tuesday') echo 'selected';?>><?php echo lang('Tuesday');?></option>
        <option value="Wednesday" <?php if(set_value('day') == 'Wednesday') echo 'selected';?>><?php echo lang('Wednesday');?></option>
        <option value="Thursday" <?php if(set_value('day') == 'Thursday') echo 'selected';?>><?php echo lang('Thursday');?></option>
        <option value="Friday" <?php if(set_value('day') == 'Friday') echo 'selected';?>><?php echo lang('Friday');?></option>
    </select><br />
    
    <label for="recurrence"><?php echo lang('teleworks_create_field_recurrence');?></label>
    <select name="recurrence" id="recurrence">
        <option value="" selected="selected"></option>
        <option value="All" <?php if(set_value('recurrence') == 'All') echo 'selected';?>><?php echo lang('all_recurrence');?></option>
        <option value="Even" <?php if(set_value('recurrence') == 'Even') echo 'selected';?>><?php echo lang('even_week');?></option>
        <option value="Odd" <?php if(set_value('recurrence') == 'Odd') echo 'selected';?>><?php echo lang('odd_week');?></option>        
    </select><br />   

    <label for="daytype"><?php echo lang('teleworks_create_field_daytype');?></label>
    <select name="daytype" id="daytype">
        <option value="" selected="selected"></option>
        <option value="allday" <?php if(set_value('daytype') == 'allday') echo 'selected';?>><?php echo lang('Whole day');?></option>
        <option value="morning" <?php if(set_value('daytype') == 'morning') echo 'selected';?>><?php echo lang('Morning');?></option>
        <option value="afternoon" <?php if(set_value('daytype') == 'afternoon') echo 'selected';?>><?php echo lang('Afternoon');?></option>
    </select>

    <span style="margin-left: 2px;position: relative;top: -5px;" id="spnDayType"></span>

    <br/><br/>
    <button name="status" value="1" type="submit" class="btn btn-primary"><i class="mdi mdi-calendar-question" aria-hidden="true"></i>&nbsp; <?php echo lang('Planned');?></button>
    &nbsp;&nbsp;
    <button name="status" value="2" type="submit" class="btn btn-primary "><i class="mdi mdi-check"></i>&nbsp; <?php echo lang('Requested');?></button>
    <br/><br/>
    <a href="<?php echo base_url(); ?>teleworks" class="btn btn-danger"><i class="mdi mdi-close"></i>&nbsp; <?php echo lang('teleworks_create_button_cancel');?></a>
</form>

    </div>
</div>

<link rel="stylesheet" href="<?php echo base_url();?>assets/css/flick/jquery-ui.custom.min.css">
<script src="<?php echo base_url();?>assets/js/jquery-ui.custom.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>

<script type="text/javascript">
//     $(document).ready(function(){
//     	var campaign = campaign_dates($('#campaign').val());
//     	$("#startdate").val(campaign[0]);
//     	$("#enddate").val(campaign[1]);
    	
//         $('#campaign').change(function() {
//         	var campaign = campaign_dates($('#campaign').val());
//         	$("#startdate").val(campaign[0]);
//         	$("#enddate").val(campaign[1]);
//         });  
//     });  

function validate_form() {
    var fieldname = "";

	if ($('#recurrence').val() == "") fieldname = "<?php echo lang('teleworks_create_field_recurrence');?>";
    if ($('#day').val() == "") fieldname = "<?php echo lang('teleworks_create_field_day');?>";
    if ($('#campaign').val() == "") fieldname = "<?php echo lang('teleworks_create_field_campaign');?>"; 
    if ($('#daytype').val() == "") fieldname = "<?php echo lang('teleworks_create_field_daytype');?>";
    if (fieldname == "") {    	
        return true;
    } else {
        bootbox.alert(<?php echo lang('teleworks_validate_mandatory_js_msg');?>);
        return false;
    }
}

// function campaign_dates(campaign)
// {
// 	var currentDate = new Date();
// 	var year = currentDate.getFullYear();
// 	// returns the month (from 0 to 11)
// 	var monthnumber = currentDate.getMonth() + 1;  
// 	var startdate = null;
// 	var enddate = null; 

//     if ((monthnumber > 2 && monthnumber < 9 && campaign == 1) || (monthnumber <= 2 && campaign == 2)) {
//         startdate = '01/03/' + year;
//         enddate = '31/08/' + year;
//     }

//     if ((monthnumber > 2 && monthnumber < 9 && campaign == 2) || (monthnumber >= 9 && campaign == 1)) {
//         startdate = '01/09/' + year;
//         if(leapYear(year)) enddate = '29/02/' + (year + 1);
//         else enddate = '28/02/' + (year + 1);
//     }

//     if (monthnumber <= 2 && campaign == 1) {
//         startdate = '01/09/' + (year - 1);
//         if(leapYear(year)) enddate = '29/02/' + year;
//         else enddate = '28/02/' + year;
//     }

//     if (monthnumber >= 9 && campaign == 2) {
//         startdate = '01/03/' + (year + 1);
//         enddate = '31/08/' + (year + 1);
//     }

//     return [startdate, enddate];
// }

function leapYear(year)
{
  return ((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0);
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
