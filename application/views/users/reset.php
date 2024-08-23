<?php 
/**
 * This partial view is loaded into a modal form and allows the connected user to change its password.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.2.0
 */
?>

<?php
$attributes = array('id' => 'target');
echo form_open('users/reset/' . $target_user_id, $attributes); ?>
    <input type="hidden" name="CipheredValue" id="CipheredValue" />
</form>
    <label for="password"><?php echo lang('users_reset_field_password');?></label>
    <input type="password" name="password" id="password" required /><br />
    <br />
    <button id="send" class="btn btn-primary"><?php echo lang('users_reset_button_reset');?></button>

<script type="text/javascript">
    $(function () {
        $('#send').click(function() {
            var encrypter = new CryptoTools();
            encrypter.encrypt($('#pubkey').val(), $('#password').val()).then((encrypted) => {
                $('#CipheredValue').val(encrypted);
                $('#target').submit();
            });
        });
        
        //Validate the form if the user press enter key in password field
        $('#password').keypress(function(e){
            if(e.keyCode==13)
            $('#send').click();
        });
    });
</script>

<textarea id="pubkey" style="visibility:hidden;"><?php echo $public_key; ?></textarea>
