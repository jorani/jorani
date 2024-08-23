<?php
/**
 * This view displays sso failure message. Its layout is the same than the login form.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.5.0
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
        min-height: 90%;  /* Fallback for browsers that do NOT support vh unit */
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
<h2><?php echo lang('session_login_title');?><?php echo $help;?></h2>

    <?php $languages = $this->polyglot->nativelanguages($this->config->item('languages'));?>
    <input type="hidden" name="last_page" value="session/failure" />
    <?php if (count($languages) == 1) { ?>
    <input type="hidden" name="language" value="<?php echo $language_code; ?>" />
    <?php } else { ?>
    <label for="language"><?php echo lang('session_login_field_language');?></label>
    <select class="input-medium" name="language" id="language">
        <?php foreach ($languages as $lang_code => $lang_name) { ?>
        <option value="<?php echo $lang_code; ?>" <?php if ($language_code == $lang_code) echo 'selected'; ?>><?php echo $lang_name; ?></option>
        <?php }?>
    </select>
    <?php } ?>
    <h4><?php echo $message;?></h4>
    <br />
    <a href="<?php echo base_url();?>api/sso" class="btn btn-primary"><i class="mdi mdi-login"></i>&nbsp;<?php echo lang('session_login_button_login');?></a>
    <br /><br />
                </div>
                <div class="span6" style="height:100%;">
                    <div class="row-fluid">
                        <div class="span12">
                            <img src="<?php echo base_url();?>assets/images/logo_simple.png">
                        </div>
                    </div>
                    <div class="row-fluid"><div class="span12">&nbsp;</div></div>
                    <div class="row-fluid">
                        <div class="span12">
                            <span style="font-size: 250%; font-weight: bold; line-height: 100%;"><center><?php echo lang('Leave Management System');?></center></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="span3">&nbsp;</div>
    </div>

<script type="text/javascript">
    $(function () {
        //Refresh page language
        $('#language').select2();
        $('#language').on('select2:select', function (e) {
          var value = e.params.data.id;
          Cookies.set('language', value, { expires: 90, path: '/'});
          window.location.href = '<?php echo base_url();?>session/language?language=' + value;
        });
    });
</script>
