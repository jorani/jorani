<?php 
/**
 * This view allows an HR admin to modify a position (occupied by an employee).
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.2.0
 */
?>

<h2><?php echo lang('positions_edit_title');?><?php echo $position['id']; ?></h2>

<?php echo validation_errors(); ?>

<?php echo form_open('positions/edit/' . $position['id']) ?>

    <label for="name"><?php echo lang('positions_edit_field_name');?></label>
    <input type="text" name="name" id="name" value="<?php echo $position['name']; ?>" autofocus required /><br />

    <label for="description"><?php echo lang('positions_edit_field_description');?></label>
    <textarea type="input" name="description" id="description" /><?php echo $position['description']; ?></textarea>

    <br /><br />
    <button type="submit" class="btn btn-primary"><i class="mdi mdi-check"></i>&nbsp;<?php echo lang('positions_edit_button_update');?></button>
    &nbsp;
    <a href="<?php echo base_url();?>positions" class="btn btn-danger"><i class="mdi mdi-close"></i>&nbsp;<?php echo lang('positions_edit_button_cancel');?></a>
</form>
