<?php
/**
 * This view displays the leave balance report.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.2.0
 */
?>

<h2><?php echo lang('reports_balance_title');?> &nbsp;<?php echo $help;?></h2>

<div class="row-fluid">
    <div class="span4">
        <label for="refdate"><?php echo lang('reports_balance_date_field');?></label>
        <div class="input-append">
        <input type="text" name="refdate" id="refdate" />
        </div>
    </div>
    <div class="span4">
        <label for="txtEntity"><?php echo lang('reports_balance_field_entity');?></label>
        <div class="input-append">
        <input type="text" id="txtEntity" name="txtEntity" readonly />
        <button id="cmdSelectEntity" class="btn btn-primary"><?php echo lang('reports_balance_button_entity');?></button>
        </div>
    </div>
    <div class="span4">
        <div class="pull-right">
            <label for="chkIncludeChildren">
                <input type="checkbox" id="chkIncludeChildren" name="chkIncludeChildren" checked /> <?php echo lang('reports_balance_field_subdepts');?>
            </label>
            &nbsp;
            <button class="btn btn-primary" id="cmdLaunchReport"><i class="mdi mdi-file-chart"></i>&nbsp; <?php echo lang('reports_balance_button_launch');?></button>
            <button class="btn btn-primary" id="cmdExportReport"><i class="mdi mdi-download"></i>&nbsp; <?php echo lang('reports_balance_button_export');?></button>
        </div>
    </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div id="reportResult"></div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div id="frmSelectEntity" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmSelectEntity').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('reports_balance_popup_entity_title');?></h3>
    </div>
    <div class="modal-body" id="frmSelectEntityBody">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="select_entity();" class="btn"><?php echo lang('reports_balance_popup_entity_button_ok');?></a>
        <a href="#" onclick="$('#frmSelectEntity').modal('hide');" class="btn"><?php echo lang('reports_balance_popup_entity_button_cancel');?></a>
    </div>
</div>

<link rel="stylesheet" href="<?php echo base_url();?>assets/css/flick/jquery-ui.custom.min.css">
<script src="<?php echo base_url();?>assets/js/jquery-ui.custom.min.js"></script>
<?php //Prevent HTTP-404 when localization isn't needed
if ($language_code != 'en') { ?>
<script src="<?php echo base_url();?>assets/js/i18n/jquery.ui.datepicker-<?php echo $language_code;?>.js"></script>
<?php } ?>
<script type="text/javascript">

var entity = -1; //Id of the selected entity
var entityName = ''; //Label of the selected entity
var includeChildren = true;

function select_entity() {
    entity = $('#organization').jstree('get_selected')[0];
    entityName = $('#organization').jstree().get_text(entity);
    $('#txtEntity').val(entityName);
    $("#frmSelectEntity").modal('hide');
    Cookies.set('rep_entity', entity);
    Cookies.set('rep_entityName', entityName);
    Cookies.set('rep_includeChildren', includeChildren);
}

$(document).ready(function() {

    //Init datepicker widget
    moment.locale('<?php echo $language_code;?>');
    $("#refdate").val(moment().format('L'));
    $('#refdate').datepicker();

    $("#frmSelectEntity").alert();

    $("#cmdSelectEntity").click(function() {
        $("#frmSelectEntity").modal('show');
        $("#frmSelectEntityBody").load('<?php echo base_url(); ?>organization/select');
    });

    $('#cmdExportReport').click(function() {
        var rtpQuery = '<?php echo base_url();?>reports/balance/export';
        var tmpUnix = moment($("#refdate").datepicker("getDate")).utc().unix();
        if (entity != -1) {
            rtpQuery += '?entity=' + entity;
        } else {
            rtpQuery += '?entity=0';
        }
        rtpQuery += '&refDate=' + tmpUnix;
        if ($('#chkIncludeChildren').prop('checked') == true) {
            rtpQuery += '&children=true';
        } else {
            rtpQuery += '&children=false';
        }
        document.location.href = rtpQuery;
    });

    $('#cmdLaunchReport').click(function() {
        var ajaxQuery = '<?php echo base_url();?>reports/balance/execute';
        var tmpUnix = moment($("#refdate").datepicker("getDate")).utc().unix();
        if (entity != -1) {
            ajaxQuery += '?entity=' + entity;
        } else {
            ajaxQuery += '?entity=0';
        }
        ajaxQuery += '&refDate=' + tmpUnix;
        if ($('#chkIncludeChildren').prop('checked') == true) {
            ajaxQuery += '&children=true';
        } else {
            ajaxQuery += '&children=false';
        }
        $('#reportResult').html("<img src='<?php echo base_url();?>assets/images/loading.gif' />");

        $.ajax({
          url: ajaxQuery
        })
        .done(function( data ) {
              $('#reportResult').html(data);
        });

    });

    //Toggle day offs displays
    $('#chkIncludeChildren').on('change', function() {
        includeChildren = $('#chkIncludeChildren').prop('checked');
        Cookies.set('rep_includeChildren', includeChildren);
    });

    //Cookie has value ? take -1 by default
    if(Cookies.get('rep_entity') !== undefined) {
        entity = Cookies.get('rep_entity');
        entityName = Cookies.get('rep_entityName');
        includeChildren = Cookies.get('rep_includeChildren');
        //Parse boolean values
        includeChildren = $.parseJSON(includeChildren.toLowerCase());
        $('#txtEntity').val(entityName);
        $('#chkIncludeChildren').prop('checked', includeChildren);
    } else { //Set default value
        Cookies.set('rep_entity', entity);
        Cookies.set('rep_entityName', entityName);
        Cookies.set('rep_includeChildren', includeChildren);
    }
});
</script>
