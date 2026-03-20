<?php
/**
 * This view displays the login form. Its layout differs from other pages of the application.
 * 
 * @license    http://opensource.org/licenses/MIT MIT
 * @since      0.1.0
 */
?>
<style>
    body {
        background:
            -webkit-linear-gradient(315deg, hsla(0, 4.23%, 86.08%, 1) 0%, hsla(0, 4.23%, 86.08%, 0) 70%),
            -webkit-linear-gradient(65deg, hsla(0, 0%, 100%, 1) 10%, hsla(0, 0%, 100%, 0) 80%),
            -webkit-linear-gradient(135deg, hsla(201.61, 63.64%, 50.39%, 1) 15%, hsla(201.61, 63.64%, 50.39%, 0) 80%),
            -webkit-linear-gradient(205deg, hsla(193.64, 56.9%, 54.51%, 1) 100%, hsla(193.64, 56.9%, 54.51%, 0) 70%);
        background:
            linear-gradient(135deg, hsla(0, 4.23%, 86.08%, 1) 0%, hsla(0, 4.23%, 86.08%, 0) 70%),
            linear-gradient(25deg, hsla(0, 0%, 100%, 1) 10%, hsla(0, 0%, 100%, 0) 80%),
            linear-gradient(315deg, hsla(201.61, 63.64%, 50.39%, 1) 15%, hsla(201.61, 63.64%, 50.39%, 0) 80%),
            linear-gradient(245deg, hsla(193.64, 56.9%, 54.51%, 1) 100%, hsla(193.64, 56.9%, 54.51%, 0) 70%);
    }

    .vertical-center {
        min-height: 90%;
        /* Fallback for browsers not supporting vh unit */
        min-height: 90vh;
        display: flex;
        align-items: center;
    }

    .form-box {
        padding: 20px;
        border: 1px #e4e4e4 solid;
        border-radius: 4px;
        box-shadow: 0 0 6px #ccc;
        background-color: #fff;
    }
</style>

<div class="row vertical-center">
    <div class="span3">&nbsp;</div>
    <div class="span6 form-box">
        <div class="row-fluid">
            <div class="span6">
                <h2><?php echo lang('session_login_title'); ?><?php echo $help; ?></h2>

                <?php echo $flash_partial_view; ?>

                <?php echo validation_errors(); ?>

                <?php
                $attributes = array('id' => 'loginFrom');
                echo form_open('session/login', $attributes);
                $languages = $this->polyglot->nativelanguages($this->config->item('languages')); ?>

                <input type="hidden" name="last_page" value="session/login" />
                <?php if (count($languages) == 1) { ?>
                    <input type="hidden" name="language" value="<?php echo $language_code; ?>" />
                <?php } else { ?>
                    <label for="language"><?php echo lang('session_login_field_language'); ?></label>
                    <select class="input-medium" name="language" id="language">
                        <?php foreach ($languages as $lang_code => $lang_name) { ?>
                            <option value="<?php echo $lang_code; ?>" <?php if ($language_code == $lang_code)
                                   echo 'selected'; ?>>
                                <?php echo $lang_name; ?>
                            </option>
                        <?php } ?>
                    </select>
                    <br />
                <?php } ?>
                <label for="login"><?php echo lang('session_login_field_login'); ?></label>
                <input type="text" class="input-medium" name="login" id="login"
                    value="<?php echo (ENVIRONMENT == 'demo') ? 'bbalet' : set_value('login'); ?>" required />
                <label for="password"><?php echo lang('session_login_field_password'); ?></label>
                <input type="password" class="input-medium" name="password" id="password" autocomplete="on"
                    value="<?php echo (ENVIRONMENT == 'demo') ? 'bbalet' : ''; ?>" /><br />
                <br />
                <button type="submit" class="btn btn-primary"><i
                        class="mdi mdi-login"></i>&nbsp;<?php echo lang('session_login_button_login'); ?></button>
                </form>
            </div>
            <div class="span6" style="height:100%;">
                <div class="row-fluid">
                    <div class="span12">
                        <img src="<?php echo base_url(); ?>assets/images/logo_simple.png">
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">&nbsp;</div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <span style="font-size: 250%; font-weight: bold; line-height: 100%;">
                            <center><?php echo lang('Leave Management System'); ?></center>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="span3">&nbsp;</div>
</div>

<div class="modal hide" id="frmModalAjaxWait" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <h1><?php echo lang('global_msg_wait'); ?></h1>
    </div>
    <div class="modal-body">
        <img src="<?php echo base_url(); ?>assets/images/loading.gif" align="middle">
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
<script type="text/javascript">
    $(function () {
        <?php if ($this->config->item('csrf_protection') == TRUE) { ?>
            $.ajaxSetup({
                data: {
                    <?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash(); ?>",
                }
            });
        <?php } ?>
        //Save the last selected language
        if (Cookies.get('language') !== undefined) {
            var IsLangAvailable = 0 != $('#language option[value=' + Cookies.get('language') + ']').length;
            if (Cookies.get('language') != "<?php echo $language_code; ?>") {
                //Test if the previous selected language is into the list of available languages
                if (IsLangAvailable) {
                    $('#language option[value="' + Cookies.get('language') + '"]').attr('selected', 'selected');
                    $('#loginFrom').prop('action', '<?php echo base_url(); ?>session/language');
                    $('#loginFrom').submit();
                }
            }
        }

        //Refresh page language
        $('#language').select2({ width: '165px' });

        $('#language').on('select2:select', function (e) {
            var value = e.params.data.id;
            Cookies.set('language', value, { expires: 90, path: '/' });
            $('#loginFrom').prop('action', '<?php echo base_url(); ?>session/language');
            $('#loginFrom').submit();
        });

        $('#login').focus();

        //Validate the form if the user press enter key in password field
        $('#password').keypress(function (e) {
            if (e.keyCode == 13)
                submit_form();
        });

    });
</script>