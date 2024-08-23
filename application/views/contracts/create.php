<?php
/**
 * This view allows to create a new contract
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>

<h2><?php echo lang('contract_create_title');?></h2>

<?php echo validation_errors(); ?>

<?php
$attributes = array('id' => 'target');
echo form_open('contracts/create', $attributes); ?>

    <label for="name"><?php echo lang('contract_create_field_name');?></label>
    <input type="text" name="name" id="name" autofocus required /><br />

    <label for="startentdatemonth"><?php echo lang('contract_create_field_start_month');?></label>
    <select name="startentdatemonth" id="startentdatemonth" required>
        <option value="1" selected><?php echo lang('January');?></option>
        <option value="2"><?php echo lang('February');?></option>
        <option value="3"><?php echo lang('March');?></option>
        <option value="4"><?php echo lang('April');?></option>
        <option value="5"><?php echo lang('May');?></option>
        <option value="6"><?php echo lang('June');?></option>
        <option value="7"><?php echo lang('July');?></option>
        <option value="8"><?php echo lang('August');?></option>
        <option value="9"><?php echo lang('September');?></option>
        <option value="10"><?php echo lang('October');?></option>
        <option value="11"><?php echo lang('November');?></option>
        <option value="12"><?php echo lang('December');?></option>
    </select>

    <label for="startentdateday"><?php echo lang('contract_create_field_start_day');?></label>
    <select name="startentdateday" id="startentdateday" required>
        <option value='1' selected>1</option>
        <option value='2'>2</option>
        <option value='3'>3</option>
        <option value='4'>4</option>
        <option value='5'>5</option>
        <option value='6'>6</option>
        <option value='7'>7</option>
        <option value='8'>8</option>
        <option value='9'>9</option>
        <option value='10'>10</option>
        <option value='11'>11</option>
        <option value='12'>12</option>
        <option value='13'>13</option>
        <option value='14'>14</option>
        <option value='15'>15</option>
        <option value='16'>16</option>
        <option value='17'>17</option>
        <option value='18'>18</option>
        <option value='19'>19</option>
        <option value='20'>20</option>
        <option value='21'>21</option>
        <option value='22'>22</option>
        <option value='23'>23</option>
        <option value='24'>24</option>
        <option value='25'>25</option>
        <option value='26'>26</option>
        <option value='27'>27</option>
        <option value='28'>28</option>
        <option value='29'>29</option>
        <option value='30'>30</option>
        <option value='31'>31</option>
    </select>

    <br /><br />

    <label for="endentdatemonth"><?php echo lang('contract_create_field_end_month');?></label>
    <select name="endentdatemonth" id="endentdatemonth" required>
        <option value="1"><?php echo lang('January');?></option>
        <option value="2"><?php echo lang('February');?></option>
        <option value="3"><?php echo lang('March');?></option>
        <option value="4"><?php echo lang('April');?></option>
        <option value="5"><?php echo lang('May');?></option>
        <option value="6"><?php echo lang('June');?></option>
        <option value="7"><?php echo lang('July');?></option>
        <option value="8"><?php echo lang('August');?></option>
        <option value="9"><?php echo lang('September');?></option>
        <option value="10"><?php echo lang('October');?></option>
        <option value="11"><?php echo lang('November');?></option>
        <option value="12" selected><?php echo lang('December');?></option>
    </select>

    <label for="endentdateday"><?php echo lang('contract_create_field_end_day');?></label>
    <select name="endentdateday" id="endentdateday" required>
        <option value='1'>1</option>
        <option value='2'>2</option>
        <option value='3'>3</option>
        <option value='4'>4</option>
        <option value='5'>5</option>
        <option value='6'>6</option>
        <option value='7'>7</option>
        <option value='8'>8</option>
        <option value='9'>9</option>
        <option value='10'>10</option>
        <option value='11'>11</option>
        <option value='12'>12</option>
        <option value='13'>13</option>
        <option value='14'>14</option>
        <option value='15'>15</option>
        <option value='16'>16</option>
        <option value='17'>17</option>
        <option value='18'>18</option>
        <option value='19'>19</option>
        <option value='20'>20</option>
        <option value='21'>21</option>
        <option value='22'>22</option>
        <option value='23'>23</option>
        <option value='24'>24</option>
        <option value='25'>25</option>
        <option value='26'>26</option>
        <option value='27'>27</option>
        <option value='28'>28</option>
        <option value='29'>29</option>
        <option value='30'>30</option>
        <option value='31' selected>31</option>
    </select>

    <label for="default_leave_type"><?php echo lang('contract_edit_default_leave_type');?></label>
    <select class="input-xxlarge" name="default_leave_type" id="default_leave_type">
    <?php foreach ($types as $typeId => $TypeName): ?>
        <option value="<?php echo $typeId; ?>" <?php if ($typeId == $defaultType) echo "selected"; ?>><?php echo $TypeName; ?></option>
    <?php endforeach ?>
    </select>

    <br /><br />
    <button id="send" class="btn btn-primary"><i class="mdi mdi-check"></i>&nbsp;<?php echo lang('contract_create_button_create');?></button>
    &nbsp;
    <a href="<?php echo base_url(); ?>contracts" class="btn btn-danger"><i class="mdi mdi-close"></i>&nbsp;<?php echo lang('contract_create_button_cancel');?></a>
</form>

<script type="text/javascript">
$(function () {
    //Selectize the leave type combo
    $('#default_leave_type').select2();
});
</script>
