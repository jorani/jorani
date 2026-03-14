<?php
/**
 * This view displays a simplified login form for OAtuh2 authorization.
 * 
 * @license    http://opensource.org/licenses/MIT MIT
 * @link       https://github.com/jorani/jorani
 * @since      0.6.0
 */
?><!DOCTYPE html>
<html lang="<?php echo $language_code; ?>">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta charset="utf-8">
    <title><?php echo $title ?> - Jorani</title>
    <meta
        description="Jorani a free and open source leave management system. Workflow of approval; e-mail notifications; calendars; reports; export to Excel and more.">
    <meta name="version" content="1.0.1">
    <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/legacy.css">
    <?php CI_Controller::get_instance()->load->helper('language');
    $this->lang->load('global', $language); ?>
    <!--[if lte IE 9]>
    <script type="text/javascript">
    alert("<?php echo lang('global_msg_old_browser'); ?>");
    </script>
    <![endif]-->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/legacy.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
    <link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>favicon.ico" sizes="32x32">
    <style>
        <?php //Font mapping with languages needing a better font than the default font
        $fonts = $this->config->item('fonts');
        if (!is_null($fonts)) {
            if (array_key_exists($language_code, $fonts)) { ?>
                @font-face {
                    font-family: '<?php echo $fonts[$language_code]['name']; ?>';
                    src: url('<?php echo base_url(), 'assets/fonts/', $fonts[$language_code]['asset']; ?>') format('truetype');
                }

                body,
                button,
                input,
                select,
                .ui-datepicker,
                .selectize-input {
                    font-family: '<?php echo $fonts[$language_code]['name']; ?>' !important;
                }

                <?php
            }
        } ?>
    </style>
</head>

<body>

    <div class="container-fluid">
        <h2><?php echo lang('session_login_title'); ?></h2>
        <div class="row">
            <div class="span12">
                <img width="100" src="<?php echo base_url(); ?>assets/images/logo_simple.png">
            </div>
        </div>
        <div class="row">
            <div class="span12">
                <form id="loginForm" method="POST" action="<?php echo base_url(); ?>api/authorization/login">
                    <label for="login"><?php echo lang('session_login_field_login'); ?></label>
                    <input type="text" class="input-medium" name="login" id="login"
                        value="<?php echo set_value('login'); ?>" required />
                    <input name="state" type="hidden" value="<?php echo $state; ?>">
                    <input name="response_type" type="hidden" value="<?php echo $responseType; ?>">
                    <input name="redirect_uri" type="hidden" value="<?php echo $redirectUri; ?>">
                    <input name="client_id" type="hidden" value="<?php echo $clientId; ?>">
                    <label for="password"><?php echo lang('session_login_field_password'); ?></label>
                    <input class="input-medium" type="password" name="password" id="password" /><br />
                    <br />
                    <button type="submit" class="btn btn-primary"><i
                            class="mdi mdi-login"></i>&nbsp;<?php echo lang('session_login_button_login'); ?></button>
                </form>
                <br />
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function () {
            $('#login').focus();

            //Validate the form if the user press enter key in password field
            $('#password').keypress(function (e) {
                if (e.keyCode == 13)
                    submit_form();
            });
        });
    </script>
</body>

</html>