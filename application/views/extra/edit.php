<?php
/**
 * This view allows the modification of an overtime request.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.2.0
 */
?>

<h2><?php echo lang('extra_edit_title');?><?php echo $extra['id']; ?>&nbsp;<span class="muted">(<?php echo $name ?>)</span>&nbsp;<?php echo $help;?></h2>

<?php echo validation_errors(); ?>

<?php
$attributes = array('id' => 'frmEditExtra');
if (isset($_GET['source'])) {
    echo form_open('extra/edit/' . $id . '?source=' . $_GET['source'], $attributes);
} else {
    echo form_open('extra/edit/' . $id, $attributes);
} ?>

    <label for="viz_date"><?php echo lang('extra_edit_field_date');?></label>
    <input type="text" name="viz_date" id="viz_date" value="<?php $date = new DateTime($extra['date']); echo $date->format(lang('global_date_format'));?>" required />
    <input type="hidden" name="date" id="date" value="<?php echo $extra['date']; ?>" />
    
    <label for="duration"><?php echo lang('extra_edit_field_duration');?></label>
    <input type="text" name="duration" id="duration" value="<?php echo $extra['duration']; ?>" required />&nbsp;<span><?php echo lang('extra_edit_field_duration_description');?></span>
    
    <label for="cause"><?php echo lang('extra_edit_field_cause');?></label>
    <textarea name="cause" required><?php echo $extra['cause']; ?></textarea>
    
    <label for="status"><?php echo lang('extra_edit_field_status');?></label>
    <select name="status" required>
        <option value="1" <?php if ($extra['status'] == 1) echo 'selected'; ?>><?php echo lang('Planned');?></option>
        <option value="2" <?php if (($extra['status'] == 2) || $this->config->item('extra_status_requested')) echo 'selected'; ?>><?php echo lang('Requested');?></optio
n>
        <?php if ($is_hr) {?>
        <option value="3" <?php if ($extra['status'] == 3) echo 'selected'; ?>><?php echo lang('Accepted');?></option>
        <option value="4" <?php if ($extra['status'] == 4) echo 'selected'; ?>><?php echo lang('Rejected');?></option>        
        <?php } ?>
    </select><br />
</form>
    
    <div class="row-fluid"><div class="span12">&nbsp;</div></div>
    <div class="row-fluid"><div class="span12">
        <button id="cmdEditExtra" class="btn btn-primary"><i class="mdi mdi-check"></i>&nbsp; <?php echo lang('extra_edit_button_update');?></button>
        &nbsp;
    <?php if (isset($_GET['source'])) {?>
        <a href="<?php echo base_url() . $_GET['source']; ?>" class="btn btn-danger"><i class="mdi mdi-close"></i>&nbsp;<?php echo lang('extra_edit_button_cancel');?></a>
    <?php } else {?>
        <a href="<?php echo base_url(); ?>extra" class="btn btn-danger"><i class="mdi mdi-close"></i>&nbsp;<?php echo lang('extra_edit_button_cancel');?></a>
    <?php } ?>
    </div></div>
    <div class="row-fluid"><div class="span12">&nbsp;</div></div>
    </div>
</div>

<link rel="stylesheet" href="<?php echo base_url();?>assets/css/flick/jquery-ui.custom.min.css">
<script src="<?php echo base_url();?>assets/js/jquery-ui.custom.min.js"></script>
<?php //Prevent HTTP-404 when localization isn't needed
if ($language_code != 'en') { ?>
<script src="<?php echo base_url();?>assets/js/i18n/jquery.ui.datepicker-<?php echo $language_code;?>.js"></script>
<?php } ?>
<script src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>

<?php require_once dirname(BASEPATH) . "/local/triggers/extra_view.php"; ?>

<script type="text/javascript">
    
    function validate_form() {
        var fieldname = "";
        
        //Call custom trigger defined into local/triggers/leave.js
        if (typeof triggerValidateEditForm == 'function') { 
           if (triggerValidateEditForm() == false) return false;
        }
        
        if ($('#viz_date').val() == "") fieldname = "<?php echo lang('extra_edit_field_date');?>";
        if ($('#duration').val() == "") fieldname = "<?php echo lang('extra_edit_field_duration');?>";
        if ($('#cause').val() == "") fieldname = "<?php echo lang('extra_edit_field_cause');?>";
        if (fieldname == "") {
            return true;
        } else {
            bootbox.alert(<?php echo lang('extra_create_mandatory_js_msg');?>);
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
        $("#viz_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: '<?php echo lang('global_date_js_format');?>',
            altFormat: "yy-mm-dd",
            altField: "#date"
        }, $.datepicker.regional['<?php echo $language_code;?>']);
        
        //Force decimal separator whatever the locale is
        $( "#duration" ).keyup(function() {
            var value = $("#duration").val();
            value = value.replace(",", ".");
            $("#duration").val(value);
        });
        
        $("#cmdEditExtra").click(function() {
            if (validate_form()) {
                $("#frmEditExtra").submit();
            }
        });

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
</script>
