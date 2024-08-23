<?php
/**
 * This view displays the native report listing the approved telework requests of employees attached to an entity.
 * User can change the week and year of execution (set by default to the previous week).
 * The content of this page is partially loaded by Ajax.
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.4.3
 */
?>

<h2><?php echo lang('teleworkreports_title');?> &nbsp;<?php echo $help;?></h2>

<div class="row-fluid">
    <div class="span4">
        <label for="cboWeek"><?php echo lang('teleworkreports_week_field');?>
            <select name="cboWeek" id="cboWeek">
                <?php for ($ii=1; $ii<=53;$ii++) {
                    if ($ii == date('W')) {
                        echo "<option val='" . $ii ."' selected>" . $ii ."</option>";
                    } else {
                        echo "<option val='" . $ii ."'>" . $ii ."</option>";
                    }
                }?>
                <!--<option val='0'><?php echo lang('All');?></option>//-->
            </select>
        </label>
        <label for="cboYear"><?php echo lang('teleworkreports_year_field');?>
            <select name="cboYear" id="cboYear">
                <?php $len =  date('Y');
                for ($ii=date('Y', strtotime('-6 year')); $ii<= $len; $ii++) {
                    if ($ii == date('Y')) {
                        echo "<option val='" . $ii ."' selected>" . $ii ."</option>";
                    } else {
                        echo "<option val='" . $ii ."'>" . $ii ."</option>";
                    }
                }?>
            </select>
        </label>
        <br />
    </div>
    <div class="span4">
        <label for="txtEntity"><?php echo lang('teleworkreports_field_entity');?></label>
        <div class="input-append">
        <input type="text" id="txtEntity" name="txtEntity" readonly />
        <button id="cmdSelectEntity" class="btn btn-primary"><?php echo lang('teleworkreports_button_entity');?></button>
        </div>
        <label for="chkIncludeChildren">
                <input type="checkbox" id="chkIncludeChildren" name="chkIncludeChildren" checked /> <?php echo lang('teleworkreports_field_subdepts');?>
        </label>
    </div>
    <div class="span4">
        <div class="pull-right">
            <button class="btn btn-primary" id="cmdLaunchReport"><i class="mdi mdi-file-chart"></i>&nbsp; <?php echo lang('teleworkreports_button_launch');?></button>
            <button class="btn btn-primary" id="cmdExportReport"><i class="mdi mdi-download"></i>&nbsp; <?php echo lang('teleworkreports_button_export');?></button>
        </div>
    </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div id="teleworkreportResult"></div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div id="frmSelectEntity" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmSelectEntity').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('teleworkreports_popup_entity_title');?></h3>
    </div>
    <div class="modal-body" id="frmSelectEntityBody">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="select_entity();" class="btn"><?php echo lang('teleworkreports_popup_entity_button_ok');?></a>
        <a href="#" onclick="$('#frmSelectEntity').modal('hide');" class="btn"><?php echo lang('teleworkreports_popup_entity_button_cancel');?></a>
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
var teleworksDetails = false;
var week = <?php echo date('W');?>;
var year = <?php echo date('Y');?>;

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

    $("#cboWeek").click(function() {
        week = $("#cboWeek").val();
    });

    $("#cboYear").click(function() {
        year = $("#cboYear").val();
    });

    $('#cmdExportReport').click(function() {
        var rtpQuery = '<?php echo base_url();?>teleworkreports/byweek/export';
        var tmpUnix = moment($("#refdate").datepicker("getDate")).utc().unix();
        if (entity != -1) {
            rtpQuery += '?entity=' + entity;
        } else {
            rtpQuery += '?entity=0';
        }
        rtpQuery += '&week=' + week;
        rtpQuery += '&year=' + year;
        if ($('#chkIncludeChildren').prop('checked') == true) {
            rtpQuery += '&children=true';
        } else {
            rtpQuery += '&children=false';
        }
        document.location.href = rtpQuery;
    });

    $('#cmdLaunchReport').click(function() {
        var ajaxQuery = '<?php echo base_url();?>teleworkreports/byweek/execute';
        var tmpUnix = moment($("#refdate").datepicker("getDate")).utc().unix();
        if (entity != -1) {
            ajaxQuery += '?entity=' + entity;
        } else {
            ajaxQuery += '?entity=0';
        }
        ajaxQuery += '&week=' + week;
        ajaxQuery += '&year=' + year;
        if ($('#chkIncludeChildren').prop('checked') == true) {
            ajaxQuery += '&children=true';
        } else {
            ajaxQuery += '&children=false';
        }
        $('#teleworkreportResult').html("<img src='<?php echo base_url();?>assets/images/loading.gif' />");

        $.ajax({
          url: ajaxQuery
        })
        .done(function( data ) {
              $('#teleworkreportResult').html(data);
        });

    });

    //Toggle include sub-entities option
    $('#chkIncludeChildren').on('change', function() {
        includeChildren = $('#chkIncludeChildren').prop('checked');
        Cookies.set('rep_includeChildren', includeChildren);
    });

    //Cookie has value ? take -1 by default
    if(Cookies.get('rep_entity') !== undefined) {
        entity = Cookies.get('rep_entity');
        entityName = Cookies.get('rep_entityName');
        includeChildren = Cookies.get('rep_includeChildren');
        teleworksDetails = (Cookies.get('rep_teleworksDetails') === undefined) ? "false" : Cookies.get('rep_teleworksDetails');
        //Parse boolean values
        includeChildren = $.parseJSON(includeChildren.toLowerCase());
        teleworksDetails = $.parseJSON(teleworksDetails.toLowerCase());
        $('#txtEntity').val(entityName);
        $('#chkIncludeChildren').prop('checked', includeChildren);
    } else { //Set default value
        Cookies.set('rep_entity', entity);
        Cookies.set('rep_entityName', entityName);
        Cookies.set('rep_includeChildren', includeChildren);
        Cookies.set('rep_teleworksDetails', teleworksDetails);        
    }
});
</script>
