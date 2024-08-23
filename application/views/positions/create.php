<?php 
/**
 * This view allows an HR admin to create a new position (occupied by an employee).
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.2.0
 */
?>

<h2><?php echo lang('positions_create_title');?></h2>

<?php echo validation_errors(); ?>

<?php
$attributes = array('id' => 'target');
echo form_open('positions/create', $attributes); ?>

    <label for="name"><?php echo lang('positions_create_field_name');?></label>
    <input type="text" name="name" id="name" autofocus required /><br />

    <label for="description"><?php echo lang('positions_create_field_description');?></label>
    <textarea type="input" name="description" id="description" /></textarea>
    
    <br /><br />
    <button id="send" class="btn btn-primary"><i class="mdi mdi-check"></i>&nbsp;<?php echo lang('positions_create_button_create');?></button>
    &nbsp;
    <a href="<?php echo base_url(); ?>positions" class="btn btn-danger"><i class="mdi mdi-close"></i>&nbsp;<?php echo lang('positions_create_button_cancel');?></a>
</form>
