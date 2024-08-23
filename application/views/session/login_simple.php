<?php
/**
 * This view displays a simplified login form for OAtuh2 authorization.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.6.0
 */
?><!DOCTYPE html>
<html lang="<?php echo $language_code;?>">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta charset="utf-8">
    <title><?php echo $title ?> - Jorani</title>
    <meta description="Jorani a free and open source leave management system. Workflow of approval; e-mail notifications; calendars; reports; export to Excel and more.">
    <meta name="version" content="1.0.1">
    <link href="<?php echo base_url();?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/dist/legacy.css">
<?php CI_Controller::get_instance()->load->helper('language');
$this->lang->load('global', $language);?>
    <!--[if lte IE 9]>
    <script type="text/javascript">
    alert("<?php echo lang('global_msg_old_browser'); ?>");
    </script>
    <![endif]-->
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/legacy.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/bootstrap/js/bootstrap.min.js"></script>
    <link rel="icon" type="image/x-icon" href="<?php echo base_url();?>favicon.ico" sizes="32x32">
    <style>
<?php //Font mapping with languages needing a better font than the default font
$fonts = $this->config->item('fonts');
if (!is_null($fonts)) {
    if (array_key_exists($language_code, $fonts)) { ?>
    @font-face {
      font-family: '<?php echo $fonts[$language_code]['name'];?>';
      src: url('<?php echo base_url(), 'assets/fonts/', $fonts[$language_code]['asset'];?>') format('truetype');
    }
    body, button, input, select, .ui-datepicker, .selectize-input {
        font-family: '<?php echo $fonts[$language_code]['name'];?>' !important;
    }
<?php
        }
    } ?>
</style>
<?php if ($this->config->item('ga_code') != "") { ?>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        <?php if (($this->session->userdata('id') != FALSE) && ($this->config->item('ga_code') != FALSE)){
          $gacode = "ga('create', '%s', { 'userId': '%s' });";
          echo sprintf($gacode, $this->config->item('ga_code'), $this->session->userdata('id'));
        }?>
        ga('create', '<?php echo $this->config->item('ga_code');?>', 'auto');
        ga('send', 'pageview');
    </script>
<?php } ?>
</head>
<body>

<div class="container-fluid">
    <h2><?php echo lang('session_login_title');?></h2>
    <div class="row">
        <div class="span12">
            <img width="100" src="<?php echo base_url();?>assets/images/logo_simple.png">
        </div>
    </div>
    <div class="row">
        <div class="span12">
            <form id="loginForm" method="POST" action="<?php echo base_url();?>api/authorization/login">
                <label for="login"><?php echo lang('session_login_field_login');?></label>
                <input type="text" class="input-medium" name="login" id="login" value="<?php echo set_value('login'); ?>" required />
                <input type="hidden" name="CipheredValue" id="CipheredValue" />
                <input name="state" type="hidden" value="<?php echo $state;?>">
                <input name="response_type" type="hidden" value="<?php echo $responseType;?>">
                <input name="redirect_uri" type="hidden" value="<?php echo $redirectUri;?>">
                <input name="client_id" type="hidden" value="<?php echo $clientId;?>">
            </form>
            <label for="password"><?php echo lang('session_login_field_password');?></label>
            <input class="input-medium" type="password" name="password" id="password" /><br />
            <br />
            <button id="send" class="btn btn-primary"><i class="mdi mdi-login"></i>&nbsp;<?php echo lang('session_login_button_login');?></button>
            <br />
            <input type="hidden" name="salt" id="salt" value="<?php echo $salt; ?>" />
            <textarea id="pubkey" style="visibility:hidden;"><?php echo $public_key; ?></textarea>
        </div>
    </div>
</div>

<script type="text/javascript">

    //Encrypt the password using RSA and send the ciphered value into the form
    function submit_form() {
        var encrypter = new CryptoTools();
        var clearText = $('#password').val() + $('#salt').val();
        encrypter.encrypt($('#pubkey').val(), clearText).then((encrypted) => {
            $('#CipheredValue').val(encrypted);
            $('#loginFrom').submit();
        });
    }

    $(function () {
        $('#login').focus();

        $('#send').click(function() {
            submit_form();
        });

        //Validate the form if the user press enter key in password field
        $('#password').keypress(function(e){
            if(e.keyCode==13)
            submit_form();
        });
    });
</script>
    </body>
</html>
