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
                <p><?php echo lang('session_login_no_slo');?></p>
                <p><a href="<?php echo base_url();?>" class="btn btn-primary"><?php echo lang('session_login_button_login');?></a></p>
            </div>
        </div>
    </div>
    <div class="span3">&nbsp;</div>
</div>
