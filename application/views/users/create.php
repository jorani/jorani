<?php
/**
 * This view allows to create a new employee
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>

<div class="row-fluid">
    <div class="span12">
<h2><?php echo lang('users_create_title');?><?php echo $help;?></h2>

<?php echo validation_errors(); ?>
    </div>
</div>

<?php
$attributes = array('id' => 'target', 'class' => 'form-horizontal');
echo form_open('users/create', $attributes); ?>

    <input type="hidden" name="CipheredValue" id="CipheredValue" />

<div class="row">
    <div class="span4">
        <div class="control-group">
            <label class="control-label" for="firstname"><?php echo lang('users_create_field_firstname');?></label>
            <div class="controls">
                <input type="text" name="firstname" id="firstname" required />
            </div>
        </div>
    </div>

    <div class="span4">
        <div class="control-group">
            <label class="control-label" for="lastname"><?php echo lang('users_create_field_lastname');?></label>
            <div class="controls">
                <input type="text" name="lastname" id="lastname" required />
            </div>
        </div>
    </div>

    <div class="span4">
        <div class="control-group">
            <label class="control-label" for="login"><?php echo lang('users_create_field_login');?></label>
            <div class="controls">
                <div class="input-append">
                    <input type="text" name="login" id="login" required />
                    <a id="cmdRefreshLogin" class="btn btn-primary"><i class="mdi mdi-refresh"></i></a>
                </div>
            </div>
        </div>
        <div style="width:100%;" class="alert hide alert-error" id="lblLoginAlert">
            <button type="button" class="close" onclick="$('#lblLoginAlert').hide();">&times;</button>
            <?php echo lang('users_create_flash_msg_error');?>
        </div>
    </div>
</div>

<div class="row">
    <div class="span4">
        <div class="control-group">
            <label class="control-label" for="email"><?php echo lang('users_create_field_email');?></label>
            <div class="controls">
                <input type="email" id="email" name="email" required />
            </div>
        </div>
    </div>

    <div class="span8">
        <input type="hidden" name="manager" id="manager" />
        <div class="control-group">
            <label class="control-label" for="txtManager">
                <?php echo lang('users_create_field_manager');?>
                <a style="color:black;" href="#" data-toggle="tooltip" title="<?php echo lang('users_create_field_manager_description');?>">
                    <i class="mdi mdi-information"></i>
                </a>
            </label>
            <div class="controls">
                <div class="input-append">
                    <input type="text" id="txtManager" name="txtManager" required readonly />
                    <a id="cmdSelfManager" class="btn btn-primary"><?php echo lang('users_create_button_self');?></a>
                    <a id="cmdSelectManager" class="btn btn-primary"><?php echo lang('users_create_button_select');?></a>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="span4">
        <div class="control-group">
            <label class="control-label" for="role[]"><?php echo lang('users_create_field_role');?></label>
            <div class="controls">
                <select name="role[]" multiple="multiple" size="3" required>
                <?php foreach ($roles as $roles_item): ?>
                    <option value="<?php echo $roles_item['id'] ?>" <?php if ($roles_item['id'] == 2) echo "selected"; ?>><?php echo $roles_item['name'] ?></option>
                <?php endforeach ?>
                </select>
            </div>
        </div>
    </div>

    <div class="span4">
        &nbsp;
    </div>

    <div class="span4">
        &nbsp;
    </div>
</div>

<div class="row">
    <div class="span6">
        <?php if ($this->config->item('ldap_enabled')=== FALSE && $this->config->item('saml_enabled') === FALSE) {?>
        <div class="control-group">
            <label class="control-label" for="password"><?php echo lang('users_create_field_password');?></label>
            <div class="controls">
                <div class="input-append">
                    <input type="password" name="password" id="password" required />
                    <a class="btn" id="cmdGeneratePassword"><i class="mdi mdi-refresh"></i>&nbsp;<?php echo lang('users_create_button_generate_password');?></a>
                </div>
            </div>
        </div>
        <?php } else { ?>
        &nbsp;
        <?php } ?>
    </div>

    <div class="span6">
        <div class="control-group">
            <label class="control-label" for="contract"><?php echo lang('users_create_field_contract');?></label>
            <div class="controls">
                <select name="contract" id="contract" class="selectized input-xlarge">
                <?php $index = 0;
                     foreach ($contracts as $contract) { ?>
                    <option value="<?php echo $contract['id'] ?>" <?php if ($index == 0) echo "selected"; ?>><?php echo $contract['name']; ?></option>
                <?php
                        $index++;
                    } ?>
                </select>
            </div>
        </div>
    </div>
</div>

<hr />

<div class="row">
    <div class="span12">
        <input type="hidden" name="entity" id="entity" />
        <div class="control-group">
            <label class="control-label" for="txtEntity"><?php echo lang('users_create_field_entity');?></label>
            <div class="controls">
                <div class="input-append">
                    <input type="text" id="txtEntity" name="txtEntity" readonly />
                    <a id="cmdSelectEntity" class="btn btn-primary"><?php echo lang('users_create_button_select');?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="span12">
        <input type="hidden" name="position" id="position" />
        <div class="control-group">
            <label class="control-label" for="txtPosition"><?php echo lang('users_create_field_position');?></label>
            <div class="controls">
                <div class="input-append">
                    <input type="text" id="txtPosition" name="txtPosition" readonly />
                    <a id="cmdSelectPosition" class="btn btn-primary"><?php echo lang('users_create_button_select');?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="span4">
        <input type="hidden" name="datehired" id="datehired" />
        <div class="control-group">
            <label class="control-label" for="viz_datehired"><?php echo lang('users_create_field_hired');?></label>
            <div class="controls">
                <input type="text" id="viz_datehired" name="viz_datehired" />
            </div>
        </div>
    </div>

    <div class="span4">
        <div class="control-group">
            <label class="control-label" for="identifier"><?php echo lang('users_create_field_identifier');?></label>
            <div class="controls">
                <input type="text" name="identifier" />
            </div>
        </div>
    </div>

    <div class="span4">
        &nbsp;
    </div>
</div>

<div class="row">
    <div class="span4">
        <div class="control-group">
            <label class="control-label" for="language"><?php echo lang('users_create_field_language');?></label>
            <div class="controls">
                <select name="language">
                     <?php
                     $languages = $this->polyglot->nativelanguages($this->config->item('languages'));
                     $default_lang = $this->polyglot->language2code($this->config->item('language'));
                     foreach ($languages as $code => $language): ?>
                    <option value="<?php echo $code; ?>" <?php if ($code == $default_lang) echo "selected"; ?>><?php echo $language; ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
    </div>

    <div class="span4">
        <div class="control-group">
            <label class="control-label" for="timezone"><?php echo lang('users_create_field_timezone');?></label>
            <div class="controls">
                <?php
                $tzdef = $this->config->item('default_timezone');
                if ($tzdef == FALSE) $tzdef = 'Europe/Paris';
                $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);?>
                <select id="timezone" name="timezone" class="selectized input-xlarge">
                <?php foreach ($tzlist as $tz) { ?>
                    <option value="<?php echo $tz ?>" <?php if ($tz == $tzdef) echo "selected"; ?>><?php echo $tz; ?></option>
                <?php
                        $index++;
                    } ?>
                </select>
            </div>
        </div>
    </div>

    <div class="span4">
        <?php if ($this->config->item('ldap_basedn_db')) {?>
        <div class="control-group">
            <label class="control-label" for="ldap_path"><?php echo lang('users_create_field_ldap_path');?></label>
            <div class="controls">
                <input type="text" class="input-xxlarge" name="ldap_path" />
            </div>
        </div>
        <?php } else { ?>
        &nbsp;
        <?php } ?>
    </div>
</div>

</form>

<div class="row-fluid">
    <div class="span12">
        <button id="send" class="btn btn-primary">
            <i class="mdi mdi-check"></i>&nbsp;<?php echo lang('users_create_button_create');?>
        </button>
        &nbsp;
        <a href="<?php echo base_url(); ?>users" class="btn btn-danger">
            <i class="mdi mdi-close"></i>&nbsp;<?php echo lang('users_create_button_cancel');?>
        </a>
    </div>
</div>

<div id="frmSelectManager" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmSelectManager').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('users_create_popup_manager_title');?></h3>
    </div>
    <div class="modal-body" id="frmSelectManagerBody">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="select_manager();" class="btn"><?php echo lang('users_create_popup_manager_button_ok');?></a>
        <a href="#" onclick="$('#frmSelectManager').modal('hide');" class="btn"><?php echo lang('users_create_popup_manager_button_cancel');?></a>
    </div>
</div>

<div id="frmSelectEntity" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmSelectEntity').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('users_create_popup_entity_title');?></h3>
    </div>
    <div class="modal-body" id="frmSelectEntityBody">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="select_entity();" class="btn"><?php echo lang('users_create_popup_entity_button_ok');?></a>
        <a href="#" onclick="$('#frmSelectEntity').modal('hide');" class="btn"><?php echo lang('users_create_popup_entity_button_cancel');?></a>
    </div>
</div>

<div id="frmSelectPosition" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmSelectPosition').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('users_create_popup_position_title');?></h3>
    </div>
    <div class="modal-body" id="frmSelectPositionBody">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="select_position();" class="btn"><?php echo lang('users_create_popup_position_button_ok');?></a>
        <a href="#" onclick="$('#frmSelectPosition').modal('hide');" class="btn"><?php echo lang('users_create_popup_position_button_cancel');?></a>
    </div>
</div>

<link rel="stylesheet" href="<?php echo base_url();?>assets/bootstrap-datepicker-1.8.0/css/bootstrap-datepicker.min.css">
<script src="<?php echo base_url();?>assets/bootstrap-datepicker-1.8.0/js/bootstrap-datepicker.min.js"></script>
<?php if ($language_code != 'en') {?>
<script src="<?php echo base_url();?>assets/bootstrap-datepicker-1.8.0/locales/bootstrap-datepicker.<?php echo $language_code;?>.min.js"></script>
<?php }?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>
<script type="text/javascript">

    //Popup select position: on click OK, find the user id for the selected line
    function select_manager() {
        var employees = $('#employees').DataTable();
        if ( employees.rows({ selected: true }).any() ) {
            var manager = employees.rows({selected: true}).data()[0][0];
            var text = employees.rows({selected: true}).data()[0][1] + ' ' + employees.rows({selected: true}).data()[0][2];
            $('#manager').val(manager);
            $('#txtManager').val(text);
        }
        $("#frmSelectManager").modal('hide');
    }

    //Popup select entity: on click OK, find the entity id for the selected node
    function select_entity() {
        var entity = $('#organization').jstree('get_selected')[0];
        var text = $('#organization').jstree().get_text(entity);
        $('#entity').val(entity);
        $('#txtEntity').val(text);
        $("#frmSelectEntity").modal('hide');
    }

    //Popup select position: on click OK, find the position id for the selected line
    function select_position() {
        var positions = $('#positions').DataTable();
        if ( positions.rows({ selected: true }).any() ) {
            var position = positions.rows({selected: true}).data()[0][0];
            var text = positions.rows({selected: true}).data()[0][1];
            $('#position').val(position);
            $('#txtPosition').val(text);
        }
        $("#frmSelectPosition").modal('hide');
    }

    //Check for mandatory fields
    function validate_form() {
        result = false;
        var fieldname = "";
        if ($('#firstname').val() == "") fieldname = "firstname";
        if ($('#lastname').val() == "") fieldname = "lastname";
        if ($('#login').val() == "") fieldname = "login";
        if ($('#email').val() == "") fieldname = "email";
        if ($('#txtManager').val() == "") fieldname = "manager";
        if ($('#contract').val() == "") fieldname = "contract";
        if ($('#password').val() == "") fieldname = "password";
        if (fieldname == "") {
            return true;
        } else {
            bootbox.alert(<?php echo lang('users_create_mandatory_js_msg');?>);
            return false;
        }
    }

    //Before submitting the form, encrypt the password and don't send the clear value
    function submit_form() {
        var encrypter = new CryptoTools();
        encrypter.encrypt($('#pubkey').val(), $('#password').val()).then((encrypted) => {
            $('#CipheredValue').val(encrypted);
            $('#target').submit();
        });
    }

    /**
     * Generate a password of the specified length
     * @param int len Length of password to be generated
     * @returns string generated password
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    function password_generator(len) {
        var length = (len)?(len):(10);
        var string = "abcdefghijklnopqrstuvwxyz";
        var numeric = '0123456789';
        var punctuation = '!@?/=';
        var password = "";
        var character = "";
        while(password.length < length) {
            entity1 = Math.ceil(string.length * Math.random() * Math.random());
            entity2 = Math.ceil(numeric.length * Math.random() * Math.random());
            entity3 = Math.ceil(punctuation.length * Math.random() * Math.random());
            hold = string.charAt(entity1);
            hold = (entity1 % 2 == 0)?(hold.toUpperCase()):(hold);
            character += hold;
            character += numeric.charAt( entity2 );
            character += punctuation.charAt( entity3 );
            password = character;
        }
        return password;
    }

    /**
     * Generate a login according to a pattern
     * @param string User's firstname
     * @param string User's lastname
     * @param string pattern of the combination
     * @param int max Maximum length of the generated login (default 32)
     * @returns string Combination of firstname and lastname
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    function generateLogin(firstname, lastname, pattern, max) {
        max = typeof max !== 'undefined' ? max : 32;
        var login = '';
        switch (pattern) {
            case 'jdoe':
                login = firstname.charAt(0).toLowerCase() + lastname.toLowerCase();
                break;
            case 'john.doe':
                login = firstname.toLowerCase() + '.' + lastname.toLowerCase();
                break;
            case 'john_doe':
                login = firstname.toLowerCase() + '_' + lastname.toLowerCase();
                break;
            default:
                if (pattern.indexOf('#') != -1) {
                    login = $(pattern).val();
                }
                break;
        }
        return login.substring(0, max);
    }

    //Check if the login is valid or not
    function checkLogin() {
        if ($("#login").val() != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>users/check/login",
                data: { login: $("#login").val() }
                })
                .done(function( msg ) {
                    if (msg == "true") {
                        $("#lblLoginAlert").hide();
                    } else {
                        $("#lblLoginAlert").show();
                    }
                });
        }
    }

    $(function () {
<?php if ($this->config->item('csrf_protection') == TRUE) {?>
    $.ajaxSetup({
        data: {
            <?php echo $this->security->get_csrf_token_name();?>: "<?php echo $this->security->get_csrf_hash();?>",
        }
    });
<?php }?>
        $("#lblLoginAlert").alert();

        $("#viz_datehired").datepicker({
          format: '<?php echo lang('global_date_js_format');?>',
          language: "<?php echo $language_code;?>",
          startDate: "01/01/1970",
          autoclose: true
        }).on('changeDate', function(e){
          $('#datehired').val(e.format('yyyy-mm-dd'));
        });

        //Transform SELECT tags in richer controls
        $('#timezone').select2();
        $('#contract').select2();

        $("#cmdGeneratePassword").click(function() {
            $("#password").val(password_generator(<?php echo $this->config->item('password_length');?>));
        });

        //On any change on firstname or lastname fields, automatically build the
        //login identifier with first character of firstname and the 31 first characters of lastname
        $("#firstname").change(function() {
            var login = generateLogin($("#firstname").val(), $("#lastname").val(), '<?php echo $this->config->item('login_pattern')!==FALSE?$this->config->item('login_pattern'):'jdoe';?>',32);
            $("#login").val(login);
        });
        $("#lastname").change(function() {
            <?php if ($this->config->item('disable_capitalization') === FALSE) {?>
            $("#lastname").val($("#lastname").val().toUpperCase());
            <?php }?>
            var login = generateLogin($("#firstname").val(), $("#lastname").val(), '<?php echo $this->config->item('login_pattern')!==FALSE?$this->config->item('login_pattern'):'jdoe';?>',32);
            $("#login").val(login);
        });

        //
        $('#cmdRefreshLogin').click(function() {
            var login = generateLogin($("#firstname").val(), $("#lastname").val(), '<?php echo $this->config->item('login_pattern')!==FALSE?$this->config->item('login_pattern'):'jdoe';?>',32);
            $("#login").val(login);
            checkLogin();
        });

        //Check if the user has not exceed the number of entitled days
        $("#login").change(function() {
            checkLogin();
        });

        $('#send').click(function() {
            if (validate_form() == false) {
                //Error of validation
            } else {
                $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>users/check/login",
                data: { login: $("#login").val() }
                })
                .done(function( msg ) {
                    if (msg == "true") {
                        if ($('#contract').val() == "") {
                            bootbox.confirm("<?php echo lang('users_create_no_contract_confirm');?>", function(result) {
                                if (result == true) {
                                    submit_form();
                                }
                            });
                        } else {
                            submit_form()
                        }
                    } else {
                        bootbox.alert("<?php echo lang('users_create_login_check');?>");
                    }
                });
            }
        });

        //Popup select manager
        $("#cmdSelectManager").click(function() {
            $("#frmSelectManager").modal('show');
            $("#frmSelectManagerBody").load('<?php echo base_url(); ?>users/employees');
        });

        //Popup select position
        $("#cmdSelectPosition").click(function() {
            $("#frmSelectPosition").modal('show');
            $("#frmSelectPositionBody").load('<?php echo base_url(); ?>positions/select');
        });

        //Popup select entity
        $("#cmdSelectEntity").click(function() {
            $("#frmSelectEntity").modal('show');
            $("#frmSelectEntityBody").load('<?php echo base_url(); ?>organization/select');
        });

        //Load alert forms
        $("#frmSelectEntity").alert();
        //Prevent to load always the same content (refreshed each time)
        $('#frmSelectEntity').on('hidden', function() {
            $(this).removeData('modal');
        });

        //Self manager button
        $("#cmdSelfManager").click(function() {
            $("#manager").val('-1');
            $('#txtManager').val('<?php echo lang('users_create_field_manager_alt');?>');
        });

        //Init all tooltips
        $('[data-toggle="tooltip"]').tooltip({ placement: 'top'});
    });

</script>

<textarea id="pubkey" style="visibility:hidden;"><?php echo $public_key; ?></textarea>
