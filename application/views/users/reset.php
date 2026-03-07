<?php
/**
 * This partial view is loaded into a modal form and allows the connected user to change its password.
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/jorani/jorani
 * @since         0.2.0
 */
?>

<?php
$attributes = array('id' => 'target');
echo form_open('users/reset/' . $target_user_id, $attributes); ?>
<input type="hidden" name="CipheredValue" id="CipheredValue" />
<label for="password"><?php echo lang('users_reset_field_password'); ?></label>
<input type="password" name="password" id="password" required /><br />
<br />
<button type="submit" class="btn btn-primary"><?php echo lang('users_reset_button_reset'); ?></button>
</form>