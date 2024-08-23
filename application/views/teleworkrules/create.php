<?php
/**
 * This view allows to create a new telework rule
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>

<h2><?php echo lang('telework_rule_create_title');?></h2>

<?php echo validation_errors(); ?>

<?php
$attributes = array('id' => 'frmTeleworkRuleForm');
echo form_open('teleworkrules/create', $attributes); ?>

	<label for="organization"><?php echo lang('telework_rule_create_field_organization');?></label>
	<select class="input-xxlarge" name="organization" id="organization">
	<option value="" selected="selected"></option>
    <?php foreach ($organizations as $organization): ?>
        <option value="<?php echo $organization->id; ?>" <?php if ($organization->id == set_value('organization')) echo "selected"; ?>><?php echo $organization->name; ?></option>
    <?php endforeach ?>
    </select>
    
    <label for="limit"><?php echo lang('telework_rule_create_field_limit');?></label>
    <input type="text" name="limit" id="limit" value="<?php echo set_value('limit'); ?>" /><br />
    
    <label for="delay"><?php echo lang('telework_rule_edit_field_delay');?></label>
    <input type="text" name="delay" id="delay" value="<?php echo set_value('delay'); ?>" /><br />
    
    <br /><br />
    <button id="send" class="btn btn-primary"><i class="mdi mdi-check"></i>&nbsp;<?php echo lang('telework_rule_create_button_create');?></button>
    &nbsp;
    <a href="<?php echo base_url(); ?>teleworkrules" class="btn btn-danger"><i class="mdi mdi-close"></i>&nbsp;<?php echo lang('telework_rule_create_button_cancel');?></a>
</form>

<link rel="stylesheet" href="<?php echo base_url();?>assets/css/flick/jquery-ui.custom.min.css">
<script src="<?php echo base_url();?>assets/js/jquery-ui.custom.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>

<script type="text/javascript">
function validate_form() {
    var fieldname = "";

	if ($('#delay').val() == "") fieldname = "<?php echo lang('telework_rule_edit_field_delay');?>";
	if ($('#limit').val() == "") fieldname = "<?php echo lang('telework_rule_edit_field_limit');?>"; 
	if ($('#organization').val() == "") fieldname = "<?php echo lang('telework_rule_edit_field_organization');?>";       
    if (fieldname == "") {    	
        return true;
    } else {
        bootbox.alert(<?php echo lang('telework_rule_validate_mandatory_js_msg');?>);
        return false;
    }
}

$(function () {   
    $("#frmTeleworkRuleForm").submit(function(e) {
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