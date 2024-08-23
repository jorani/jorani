<?php
/**
 * This view displays the list of employees.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>

<div class="row-fluid">
    <div class="span12">

<h2><?php echo lang('hr_employees_title');?>&nbsp;<?php echo $help;?></h2>

<?php echo $flash_partial_view;?>

        <div class="row-fluid">
            <div class="span6">
                <input type="hidden" name="entity" id="entity" />
                 <label for="txtEntity"><?php echo lang('hr_employees_field_entity');?>
                    <div class="input-append">
                        <input type="text" id="txtEntity" name="txtEntity" readonly />
                        <a id="cmdSelectEntity" class="btn btn-primary"><?php echo lang('hr_employees_button_select');?></a>
                    </div>
                 </label>
                </div>
            <div class="span6">
                <div class="pull-left">
                    <input type="checkbox" id="chkIncludeChildren" /> <?php echo lang('hr_employees_field_subdepts');?>
                </div>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="btn-group" data-toggle="buttons-radio">
                  <button id="cmdAll" type="button" class="btn"><?php echo lang('hr_employees_button_all');?></button>
                  <button id="cmdActive" type="button" class="btn"><?php echo lang('hr_employees_button_active');?></button>
                  <button id="cmdInactive" type="button" class="btn"><?php echo lang('hr_employees_button_inactive');?></button>
                </div>
                &nbsp;
                <?php echo lang('hr_employees_thead_datehired');?>
                <div class="input-prepend input-append">
                    <div class="btn-group">
                        <div class="btn-group" data-toggle="buttons-radio">
                            <button id="cmdGreater1" type="button" class="btn active"><i class="mdi mdi-chevron-right"></i></button>
                            <button id="cmdLesser1" type="button" class="btn"><i class="mdi mdi-chevron-left"></i></button>
                        </div>
                        <input type="text" id="viz_datehired1" class="input-small" readonly />
                        <button id="cmdResetDate1" type="button" class="btn"><i class="mdi mdi-close"></i></button>
                    </div>
                </div>
                &nbsp;&mdash;&nbsp;
                <div class="input-prepend input-append">
                    <div class="btn-group">
                        <div class="btn-group" data-toggle="buttons-radio">
                            <button id="cmdGreater2" type="button" class="btn"><i class="mdi mdi-chevron-right"></i></button>
                            <button id="cmdLesser2" type="button" class="btn active"><i class="mdi mdi-chevron-left"></i></button>
                        </div>
                        <input type="text" id="viz_datehired2" class="input-small" readonly />
                        <button id="cmdResetDate2" type="button" class="btn"><i class="mdi mdi-close"></i></button>
                    </div>
                </div>
                <input type="hidden" name="datehired1" id="datehired1" />
                <input type="hidden" name="datehired2" id="datehired2" />
            </div>
        </div>
        <div class="row-fluid">
            <div class="span6">
              <?php echo lang('hr_employees_description');?>
            </div>
            <div class="span6">
                <div class="pull-right">
                    <div class="btn-group">
                        <button id="cmdSelection" class="btn dropdown-toggle btn-primary" data-toggle="dropdown">
                          <i class="mdi mdi-pencil"></i>&nbsp;<?php echo lang('hr_employees_button_selection');?>&nbsp;<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#" id="cmdCreateRequest"><i class="mdi mdi-file-plus nolink"></i>&nbsp;<?php echo lang('hr_employees_button_create_request');?></a></li>
                            <li><a href="#" id="cmdSelectManager"><i class="mdi mdi-account-supervisor"></i>&nbsp;<?php echo lang('hr_employees_button_select_manager');?></a></li>
                            <li><a href="#" id="cmdAddEntitlments"><i class="mdi mdi-pencil-box-outline"></i>&nbsp;<?php echo lang('hr_employees_button_entitleddays');?></a></li>
                            <li><a href="#" id="cmdSelectContract"><i class="mdi mdi-file-document-outline"></i>&nbsp;<?php echo lang('hr_employees_button_select_contract');?></a></li>
                            <li><a href="#" id="cmdChangeEntity"><i class="mdi mdi-sitemap"></i>&nbsp;<?php echo lang('hr_employees_button_select_entity');?></a></li>
                            <li class="divider"></li>
                            <li><a href="#" id="cmdSelectAll"><i class="mdi mdi-checkbox-blank-circle"></i>&nbsp;<?php echo lang('hr_employees_button_select_all');?></a></li>
                            <li><a href="#" id="cmdDeselectAll"><i class="mdi mdi-checkbox-blank-circle-outline"></i>&nbsp;<?php echo lang('hr_employees_button_deselect_all');?></a></li>
                        </ul>
                    </div>
                  &nbsp;<a href="#" id="cmdExportEmployees" class="btn btn-primary"><i class="mdi mdi-download"></i>&nbsp;<?php echo lang('hr_employees_button_export');?></a>
                  &nbsp;<a href="<?php echo base_url();?>users/create" class="btn btn-primary"><i class="mdi mdi-account-plus"></i>&nbsp;<?php echo lang('hr_employees_button_create_user');?></a>
              </div>
            </div>
        </div>

        <div class="row-fluid"><div class="span12">&nbsp;</div></div>

        <div class="row-fluid">
            <div class="span12">
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="users" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo lang('hr_employees_thead_id');?></th>
                            <th><?php echo lang('hr_employees_thead_firstname');?></th>
                            <th><?php echo lang('hr_employees_thead_lastname');?></th>
                            <th><?php echo lang('hr_employees_thead_email');?></th>
                            <th><?php echo lang('hr_employees_thead_entity');?></th>
                            <th><?php echo lang('hr_employees_thead_contract');?></th>
                            <th><?php echo lang('hr_employees_thead_manager');?></th>
                            <th><?php echo lang('hr_employees_thead_identifier');?></th>
                            <th><?php echo lang('hr_employees_thead_datehired');?></th>
                            <th><?php echo lang('hr_employees_thead_position');?></th>
                        </tr>
                    </thead>
                    <tbody class="employees-area">
                    </tbody>
                </table>
            </div>
         </div>

        </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div id="frmSelectManager" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmSelectManager').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('hr_employees_popup_manager_title');?></h3>
    </div>
    <div class="modal-body" id="frmSelectManagerBody">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="select_manager();" class="btn btn-primary"><?php echo lang('OK');?></a>
        <a href="#" onclick="$('#frmSelectManager').modal('hide');" class="btn btn-danger"><?php echo lang('Cancel');?></a>
    </div>
</div>

<div id="frmSelectEntity" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmSelectEntity').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('hr_employees_popup_entity_title');?></h3>
    </div>
    <div class="modal-body" id="frmSelectEntityBody">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="select_entity();" class="btn"><?php echo lang('OK');?></a>
        <a href="#" onclick="$('#frmSelectEntity').modal('hide');" class="btn"><?php echo lang('Cancel');?></a>
    </div>
</div>

<div id="frmSelectContract" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmSelectContract').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('hr_employees_popup_contract_title');?></h3>
    </div>
    <div class="modal-body">
        <select id="cboContract" class="selectized input-xlarge">
        <?php $index = 0;
             foreach ($contracts as $contract) { ?>
            <option value="<?php echo $contract['id'] ?>" <?php if ($index == 0) echo "selected"; ?>><?php echo $contract['name']; ?></option>
        <?php
                $index++;
            } ?>
        </select>
    </div>
    <div class="modal-footer">
        <a href="#" onclick="select_contract();" class="btn"><?php echo lang('OK');?></a>
        <a href="#" onclick="$('#frmSelectContract').modal('hide');" class="btn"><?php echo lang('Cancel');?></a>
    </div>
</div>

<div id="frmAddEntitledDays" class="modal hide fade">
        <div class="modal-header">
            <a href="#" class="close" onclick="$('#frmAddEntitledDays').modal('hide');">&times;</a>
            <h3 id="frmAddEntitledDaysTitle"><?php echo lang('entitleddays_contract_popup_title');?></h3>
        </div>
        <div class="modal-body">
            <label for="viz_startentdate"><?php echo lang('entitleddays_user_index_field_start');?></label>
            <input type="text" id="viz_startentdate" name="viz_startentdate" required />
            <br />
            <input type="hidden" name="startentdate" id="startentdate" />
            <label for="viz_endentdate"><?php echo lang('entitleddays_user_index_field_end');?></label>
            <input type="text" id="viz_endentdate" name="viz_endentdate" required /><br />
            <input type="hidden" name="endentdate" id="endentdate" />
            <label for="typeEnt"><?php echo lang('entitleddays_user_index_field_type');?></label>
            <select name="typeEnt" id="typeEnt" required>
            <?php foreach ($types as $types_item): ?>
                <option value="<?php echo $types_item['id'] ?>" <?php if ($types_item['id'] == 1) echo "selected" ?>><?php echo $types_item['name'] ?></option>
            <?php endforeach ?>
            </select>
            <label for="daysEnt" required><?php echo lang('entitleddays_user_index_field_days');?></label>
            <input type="text" class="input-mini" name="daysEnt" id="daysEnt" />
            <label for="descriptionEnt"><?php echo lang('entitleddays_user_index_field_description');?></label>
            <input type="text" class="input-xlarge" name="descriptionEnt" id="descriptionEnt" />
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" onclick="add_entitleddays();" ><?php echo lang('OK');?></button>
            <button class="btn btn-danger" onclick="$('#frmAddEntitledDays').modal('hide');"><?php echo lang('entitleddays_user_index_button_cancel');?></button>
        </div>
 </div>

<div id="frmCreateLeaveRequest" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmCreateLeaveRequest').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('hr_employees_button_create_request');?></h3>
    </div>
    <div class="modal-body">
        <label for="leaveType"><?php echo lang('leaves_create_field_type');?></label>
        <select name="leaveType" id="leaveType">
        <?php
        $default_type = $this->config->item('default_leave_type');
        $default_type = $default_type == FALSE ? 0 : $default_type;
        foreach ($types as $types_item): ?>
            <option value="<?php echo $types_item['id'] ?>" <?php if ($types_item['id'] == $default_type) echo "selected" ?>><?php echo $types_item['name'] ?></option>
        <?php endforeach ?>
        </select>

        <label for="viz_leaveStartdate"><?php echo lang('leaves_create_field_start');?></label>
        <input type="text" name="viz_leaveStartdate" id="viz_leaveStartdate" autocomplete="off" />
        <input type="hidden" name="leaveStartdate" id="leaveStartdate" />
        <select name="leaveStartdatetype" id="leaveStartdatetype">
            <option value="Morning" selected><?php echo lang('Morning');?></option>
            <option value="Afternoon"><?php echo lang('Afternoon');?></option>
        </select><br />

        <label for="viz_leaveEnddate"><?php echo lang('leaves_create_field_end');?></label>
        <input type="text" name="viz_leaveEnddate" id="viz_leaveEnddate" autocomplete="off" />
        <input type="hidden" name="leaveEnddate" id="leaveEnddate" />
        <select name="leaveEnddatetype" id="leaveEnddatetype">
            <option value="Morning"><?php echo lang('Morning');?></option>
            <option value="Afternoon" selected><?php echo lang('Afternoon');?></option>
        </select><br />

        <label for="leaveDuration"><?php echo lang('leaves_create_field_duration');?></label>
        <input type="text" name="leaveDuration" id="leaveDuration" />

        <div class="alert hide alert-error" id="lblOverlappingAlert" onclick="$('#lblOverlappingAlert').hide();">
            <button type="button" class="close">&times;</button>
            <?php echo lang('leaves_create_field_overlapping_message');?>
        </div>

        <label for="leaveCause"><?php echo lang('leaves_create_field_cause');?></label>
        <textarea name="leaveCause" id="leaveCause"></textarea>

        <label for="leaveStatus"><?php echo lang('leaves_create_field_status');?></label>
        <select name="leaveStatus" id ="leaveStatus">
            <option value="1"><?php echo lang('Planned');?></option>
            <option value="2"><?php echo lang('Requested');?></option>
            <option value="3"><?php echo lang('Accepted');?></option>
            <option value="4"><?php echo lang('Rejected');?></option>
        </select><br />

    </div>
    <div class="modal-footer">
        <a href="#" onclick="createLeaveRequest();" class="btn"><?php echo lang('OK');?></a>
        <a href="#" onclick="$('#frmCreateLeaveRequest').modal('hide');" class="btn"><?php echo lang('Cancel');?></a>
    </div>
</div>

<div class="modal hide fade" id="frmContextMenu">
    <div class="modal-body">
        <a class="context" href="<?php echo base_url();?>hr/leaves/create/{id}"><i class="mdi mdi-file-plus nolink"></i>&nbsp;<?php echo lang('hr_employees_thead_link_create_leave');?></a><br />
        <a class="context" href="<?php echo base_url();?>users/edit/{id}?source=hr%2Femployees"><i class="mdi mdi-account-edit nolink"></i>&nbsp;<?php echo lang('hr_employees_thead_tip_edit');?></a><br />
        <a class="context" href="<?php echo base_url();?>entitleddays/user/{id}"><i class="mdi mdi-pencil-box-outline nolink"></i>&nbsp;<?php echo lang('hr_employees_thead_tip_entitlment');?></a><br />
        <a class="context" href="<?php echo base_url();?>hr/leaves/{id}"><i class="mdi mdi-format-list-bulleted nolink"></i>&nbsp;<?php echo lang('hr_employees_thead_link_leaves');?></a><br />
        <?php if ($this->config->item('disable_overtime') == FALSE) { ?>
        <a class="context" href="<?php echo base_url();?>hr/overtime/{id}"><i class="mdi mdi-format-list-bulleted nolink"></i>&nbsp;<?php echo lang('hr_employees_thead_link_extra');?></a><br />
        <?php } ?>
        <?php if ($this->config->item('disable_telework') == FALSE) { ?>
        <a class="context" href="<?php echo base_url();?>hr/teleworks/create/{id}"><i class="mdi mdi-file-plus nolink"></i>&nbsp;<?php echo lang('hr_employees_thead_link_create_telework');?></a><br />
        <a class="context" href="<?php echo base_url();?>hr/teleworks/{id}"><i class="mdi mdi-format-list-numbered-rtl nolink"></i>&nbsp;<?php echo lang('hr_employees_thead_link_teleworks');?></a><br />
        <?php if ($this->config->item('disable_campaign_telework') == FALSE) { ?>
        <a class="context" href="<?php echo base_url();?>hr/campaignteleworks/create/{id}"><i class="mdi mdi-file-plus nolink"></i>&nbsp;<?php echo lang('hr_employees_thead_link_create_campaign_telework');?></a><br />
        <?php } ?>
        <?php } ?>
        <a class="context" href="<?php echo base_url();?>hr/counters/employees/{id}"><i class="mdi mdi-information-outline nolink"></i>&nbsp;<?php echo lang('hr_employees_thead_link_balance');?></a><br />
        <a class="context" href="<?php echo base_url();?>hr/presence/employees/{id}"><i class="mdi mdi-chart-pie nolink"></i>&nbsp;<?php echo lang('hr_employees_thead_link_presence');?></a><br />
        <a class="context" href="<?php echo base_url();?>calendar/year/{id}"><i class="mdi mdi-calendar-text nolink"></i>&nbsp;<?php echo lang('hr_employees_thead_link_calendar');?></a><br />
        <a class="context" href="<?php echo base_url();?>requests/delegations/{id}"><i class="mdi mdi-share nolink"></i>&nbsp;<?php echo lang('hr_employees_thead_link_delegation');?></a>
  </div>
</div>

<div class="modal hide" id="frmModalAjaxWait" data-backdrop="static" data-keyboard="false">
        <div class="modal-header">
            <h1><?php echo lang('global_msg_wait');?></h1>
        </div>
        <div class="modal-body">
            <img src="<?php echo base_url();?>assets/images/loading.gif"  align="middle">
        </div>
 </div>

<link href="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/datatable/Buttons-1.1.2/css/buttons.dataTables.min.css" rel="stylesheet"/>
<link href="<?php echo base_url();?>assets/datatable/ColReorder-1.3.1/css/colReorder.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/datatable/Select-1.1.2/css/select.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/flick/jquery-ui.custom.min.css">
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/Buttons-1.1.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/Buttons-1.1.2/js/buttons.colVis.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/ColReorder-1.3.1/js/dataTables.colReorder.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/Select-1.1.2/js/dataTables.select.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery-ui.custom.min.js"></script>
<?php //Prevent HTTP-404 when localization isn't needed
if ($language_code != 'en') { ?>
<script src="<?php echo base_url();?>assets/js/i18n/jquery.ui.datepicker-<?php echo $language_code;?>.js"></script>
<?php } ?>

<script type="text/javascript">
var entity = 0; //Root of the tree by default
var entityName = '';
var includeChildren = true;
var contextSelectEntity = "select";
var filterActive = "all"; //active (only), inactive (only), all
var oTable;
var filterDate = "greater/empty/greater/empty";
var state1="greater";
var state2="lesser";

//Handle choose of entity with the modal form "select an entity". Update cookie with selected values
function select_entity() {
    entity = $('#organization').jstree('get_selected')[0];
    entityName = $('#organization').jstree().get_text(entity);
    $("#frmSelectEntity").modal('hide');
    if (contextSelectEntity == "select") {
        //"select": Filter the content of datatable for a given entity
        includeChildren = $('#chkIncludeChildren').is(':checked');
        $('#entity').val(entity);
        $('#txtEntity').val(entityName);
        Cookies.set('entity', entity);
        Cookies.set('entityName', entityName);
        Cookies.set('includeChildren', includeChildren);
        refreshDataTable();
    } else {
        //"change": Move selected employees to another entity
        var employeeIds = getSelectedEmployees();
        //Call a web service that changes the entity of a list of employees
        $('#frmModalAjaxWait').modal('show');
        $.ajax({
            url: "<?php echo base_url();?>hr/employees/edit/entity",
            type: "POST",
            data: {
                    entity: entity,
                    employees: employeeIds
                }
          }).done(function() {
              oTable.ajax.reload(function ( json ) {
                  $('#frmModalAjaxWait').modal('hide');
              });
        });
    }
}

function refreshDataTable() {
    date1 = $("#datehired1").val()!=""?$("#datehired1").val():"empty";
    date2 = $("#datehired2").val()!=""?$("#datehired2").val():"empty";
    filterDate = state1 + "/" + date1 + "/" + state2 + "/" + date2;
    $('#frmModalAjaxWait').modal('show');
    oTable.ajax.url('<?php echo base_url();?>hr/employees/entity/' + entity + '/' + includeChildren + '/' + filterActive + '/' + filterDate)
        .load(function() {
            $("#frmModalAjaxWait").modal('hide');
        }, true);
}

//Get the list of selected employees into the datatable
//Return a JSON representation of an array of identifiers (integer)
function getSelectedEmployees() {
    var employeeIds = [];
    oTable.rows({selected: true}).every( function () {
        employeeIds.push(this.data().id);
     });
     return JSON.stringify(employeeIds);
}

//Popup create leave request: on click OK:
// - Find the id of all selected employees.
// - Validate the input form.
function createLeaveRequest() {
    //Validate the form
    var fieldname = "";
    var startType = $('#leaveStartdatetype option:selected').val();
    var endType = $('#leaveEnddatetype option:selected').val();
    var start = moment($('#leaveStartdate').val());
    var end = moment($('#leaveEnddate').val());
    if (start.isSame(end)) {
        if (startType == "Afternoon" && endType == "Morning") {
            bootbox.alert("<img src='<?php echo base_url();?>assets/images/date_error.png' />" +
                    "&nbsp;<?php echo lang('leaves_create_field_end');?> >" +
                    "&nbsp;<?php echo lang('leaves_create_field_start');?>");
            return;
        }
    }
    if ($('#viz_leaveStartdate').val() == "") fieldname = "<?php echo lang('leaves_create_field_start');?>";
    if ($('#viz_leaveEnddate').val() == "") fieldname = "<?php echo lang('leaves_create_field_end');?>";
    if ($('#leaveDuration').val() == "" || $('#leaveDuration').val() == 0) fieldname = "<?php echo lang('leaves_create_field_duration');?>";
    if (fieldname != "") {
        bootbox.alert(<?php echo lang('leaves_validate_mandatory_js_msg');?>);
        return;
    }

    //Call a web service to batch insert all the leave requests
    var employeeIds = getSelectedEmployees();
    $('#frmModalAjaxWait').modal('show');
    $.ajax({
        url: "<?php echo base_url();?>hr/employees/create/leave",
        type: "POST",
        data: {
                type: $('#leaveType').val(),
                duration: $('#leaveDuration').val(),
                startdate: $('#leaveStartdate').val(),
                enddate: $('#leaveEnddate').val(),
                startdatetype: startType,
                enddatetype: endType,
                cause: $('#leaveCause').val(),
                status: $('#leaveStatus option:selected').val(),
                employees: employeeIds
            }
      }).done(function() {
        $('#frmModalAjaxWait').modal('hide');
    });
    $("#frmCreateLeaveRequest").modal('hide');
}

//Popup select manager: on click OK, find the id of all selected employees and update their manager.
function select_manager() {
    var employees = $('#employees').DataTable();
    if ( employees.rows({ selected: true }).any() ) {
        var manager_id = employees.rows({selected: true}).data()[0][0];
        var employeeIds = getSelectedEmployees();
        //Call a web service that changes the manager of a list of employees
        $('#frmModalAjaxWait').modal('show');
        $.ajax({
            url: "<?php echo base_url();?>hr/employees/edit/manager",
            type: "POST",
            data: {
                    manager: manager_id,
                    employees: employeeIds
                }
          }).done(function() {
              oTable.ajax.reload(function ( json ) {
                  $('#frmModalAjaxWait').modal('hide');
              });
        });
    }
    $("#frmSelectManager").modal('hide');
}

//Popup select contract: on click OK, find the id of all selected employees and update their contract.
function select_contract() {
    var contract_id = $('#cboContract').val();
    var employeeIds = getSelectedEmployees();
    //Call a web service that changes the contract of a list of employees
    $('#frmModalAjaxWait').modal('show');
    $.ajax({
        url: "<?php echo base_url();?>hr/employees/edit/contract",
        type: "POST",
        data: {
                contract: contract_id,
                employees: employeeIds
            }
      }).done(function() {
          oTable.ajax.reload(function ( json ) {
              $('#frmModalAjaxWait').modal('hide');
          });
    });
    $("#frmSelectContract").modal('hide');
}

//Popup add entitled days: on click OK, find the id of all selected employees and update their contract.
function add_entitleddays() {
    var employeeIds = getSelectedEmployees();
    //Validate the form
    var fieldname = "";
    if ($('#startentdate').val() == "") fieldname = "<?php echo lang('entitleddays_user_index_field_start');?>";
    if ($('#endentdate').val() == "") fieldname = "<?php echo lang('entitleddays_user_index_field_end');?>";
    if ($('#typeEnt').val() == "") fieldname = "<?php echo lang('entitleddays_user_index_field_type');?>";
    if ($('#daysEnt').val() == "") fieldname = "<?php echo lang('entitleddays_user_index_field_days');?>";
    if (fieldname != "") {
        bootbox.alert(<?php echo lang('entitleddays_user_mandatory_js_msg');?>);
        return;
    }

    //Call a web service that changes the entitlements of a list of employees
    $('#frmModalAjaxWait').modal('show');
    $.ajax({
        url: "<?php echo base_url();?>hr/employees/edit/entitlements",
        type: "POST",
        data: {
                startdate: $('#startentdate').val(),
                enddate: $('#endentdate').val(),
                days: $('#daysEnt').val(),
                type: $('#typeEnt').val(),
                description: $('#descriptionEnt').val(),
                employees: employeeIds
            }
      }).done(function() {
          oTable.ajax.reload(function ( json ) {
              $('#frmModalAjaxWait').modal('hide');
          });
    });
    $("#frmAddEntitledDays").modal('hide');
}

$(function () {
<?php if ($this->config->item('csrf_protection') == TRUE) {?>
    $.ajaxSetup({
        data: {
            <?php echo $this->security->get_csrf_token_name();?>: "<?php echo $this->security->get_csrf_hash();?>",
        }
    });
<?php }?>

    //Global Ajax error handling mainly used for session expiration
    $( document ).ajaxError(function(event, jqXHR, settings, errorThrown) {
        $('#frmModalAjaxWait').modal('hide');
        if (jqXHR.status == 401) {
            bootbox.alert("<?php echo lang('global_ajax_timeout');?>", function() {
                //After the login page, we'll be redirected to the current page
               location.reload();
            });
        } else { //Oups
            bootbox.alert("<?php echo lang('global_ajax_error');?>");
        }
      });

    //Handle links clicked into the context menu popup
    function handleLinksInContextMenu(e) {
        id = $(e.target).closest("tr").find('td:eq(0)').text();
        $("#frmContextMenu").modal('show');
        $('.context').each(function() {
            action =  $(this).attr( 'href');
            var url = action.replace("{id}", id.trim());
            $(this).attr( 'href', url);
        }); 
    }
    
    //Long press on mobile, display contextual menu as a popup
    $(document).on('contextmenu', '.employees-area', function (e) {
        e.preventDefault();
        handleLinksInContextMenu(e);
    });

    //Long press on mobile, display contextual menu as a popup
    var hammer = new Hammer(document, {time: 600});
    hammer.on('press', function(e) {
        handleLinksInContextMenu(e);
    });

    //On double click, display contextual menu as a popup
    $(document).on('dblclick', '.employees-area', function (e) {
        //Prevent text selection after double click
        if(document.selection && document.selection.empty) {
            document.selection.empty();
        } else if(window.getSelection) {
            var sel = window.getSelection();
            sel.removeAllRanges();
        }
        //Handle links
        handleLinksInContextMenu(e);
    });

    //On keying ESC, hide context menu
    $("body").on("keyup", function(e){
        if (e.keyCode == 27) {
            if ($('#frmContextMenu').hasClass('in')) {
                $('#frmContextMenu').modal('hide');
            }
        }
    });

    //Cookie has value ? take -1 by default
    if(Cookies.get('entity') !== undefined) {
        entity = Cookies.get('entity');
        entityName = Cookies.get('entityName');
        includeChildren = Cookies.get('includeChildren');
        if (Cookies.get('filterActive') !== undefined) {
            filterActive = Cookies.get('filterActive');
        }
        //Parse boolean value contained into the string
        includeChildren = $.parseJSON(includeChildren.toLowerCase());
        $('#txtEntity').val(entityName);
        $('#chkIncludeChildren').prop('checked', includeChildren);
        switch (filterActive) {
            case "active": $("#cmdActive").addClass("active"); break;
            case "inactive": $("#cmdInactive").addClass("active"); break;
            case "all": $("#cmdAll").addClass("active"); break;
        }
    } else { //Set default value
        Cookies.set('entity', entity);
        Cookies.set('entityName', entityName);
        Cookies.set('includeChildren', includeChildren);
        Cookies.set('filterActive', filterActive);
    }

    //Transform the HTML table in a fancy datatable:
    // * Column ID cannot be moved or hidden because it is used for contextual actions
    oTable = $('#users').DataTable({
            "ajax": '<?php echo base_url();?>hr/employees/entity/' + entity + '/' + includeChildren + '/' + filterActive + '/' + filterDate,
            columns: [
                { data: "id" },
                { data: "firstname" },
                { data: "lastname" },
                { data: "email" },
                { data: "entity" },
                { data: "contract" },
                { data: "manager_name" },
                { data: "identifier" },
                { data: 'datehired',
                    render: {
                        display: "display",
                        sort: "timestamp"
                    }, type: "numeric"
                },
                { data: "position" }
            ],
            stateSave: true,
            select: 'multiple',
            dom: 'Bfrtip',
            buttons: [
                            {
                                extend: 'pageLength',
                                text: '<?php echo lang('datatable_pagination');?>'
                            },
                            {
                                extend: 'colvis',
                                columns: ':not(:first-child)',
                                postfixButtons: [
                                    {
                                        extend: 'colvisRestore',
                                        text: '<?php echo lang('datatable_colvisRestore');?>'
                                    }
                                ]
                            }
            ],
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [
                    '<?php echo lang('datatable_10_rows');?>',
                    '<?php echo lang('datatable_25_rows');?>',
                    '<?php echo lang('datatable_50_rows');?>',
                    '<?php echo lang('datatable_all_rows');?>'
                ]
            ],
            colReorder: {
                fixedColumnsLeft: 1
            },
        language: {
            buttons: {
                colvis: '<?php echo lang('datatable_colvis');?>'
            },
            decimal:            "<?php echo lang('datatable_sInfoThousands');?>",
            processing:       "<?php echo lang('datatable_sProcessing');?>",
            search:              "<?php echo lang('datatable_sSearch');?>",
            lengthMenu:     "<?php echo lang('datatable_sLengthMenu');?>",
            info:                   "<?php echo lang('datatable_sInfo');?>",
            infoEmpty:          "<?php echo lang('datatable_sInfoEmpty');?>",
            infoFiltered:       "<?php echo lang('datatable_sInfoFiltered');?>",
            infoPostFix:        "<?php echo lang('datatable_sInfoPostFix');?>",
            loadingRecords: "<?php echo lang('datatable_sLoadingRecords');?>",
            zeroRecords:    "<?php echo lang('datatable_sZeroRecords');?>",
            emptyTable:     "<?php echo lang('datatable_sEmptyTable');?>",
            paginate: {
                first:          "<?php echo lang('datatable_sFirst');?>",
                previous:   "<?php echo lang('datatable_sPrevious');?>",
                next:           "<?php echo lang('datatable_sNext');?>",
                last:           "<?php echo lang('datatable_sLast');?>"
            },
            aria: {
                sortAscending:  "<?php echo lang('datatable_sSortAscending');?>",
                sortDescending: "<?php echo lang('datatable_sSortDescending');?>"
            }
        },
    });

    //Popup select entity
    $("#cmdSelectEntity").click(function() {
        contextSelectEntity = "select";
        $("#frmSelectEntity").modal('show');
        $("#frmSelectEntityBody").load('<?php echo base_url(); ?>organization/select');
    });

    //Force decimal separator whatever the locale is
    $( "#days" ).keyup(function() {
        var value = $("#days").val();
        value = value.replace(",", ".");
        $("#days").val(value);
    });
    //Link the two dates of the entitled days popup
    $("#viz_startentdate").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: '<?php echo lang('global_date_js_format');?>',
        altFormat: "yy-mm-dd",
        altField: "#startentdate",
        numberOfMonths: 3,
              onClose: function( selectedDate ) {
                $( "#viz_endentdate" ).datepicker( "option", "minDate", selectedDate );
              }
    }, $.datepicker.regional['<?php echo $language_code;?>']);
    $("#viz_endentdate").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: '<?php echo lang('global_date_js_format');?>',
        altFormat: "yy-mm-dd",
        altField: "#endentdate",
        numberOfMonths: 3,
              onClose: function( selectedDate ) {
                $( "#viz_startentdate" ).datepicker( "option", "maxDate", selectedDate );
              }
    }, $.datepicker.regional['<?php echo $language_code;?>']);

    //Change the manager of a group of employees
    $("#cmdSelectManager").click(function() {
        if (oTable.rows({selected: true}).any()) {
            $("#frmSelectManager").modal('show');
            $("#frmSelectManagerBody").load('<?php echo base_url(); ?>users/employees');
        }
        else {
            bootbox.alert("<?php echo lang('hr_employees_multiple_edit_selection_msg');?>");
        }
    });

    //Add entitled days to a group of employees
    $("#cmdAddEntitlments").click(function() {
        if (oTable.rows({selected: true}).any()) {
            $("#frmAddEntitledDays").modal('show');
        }
        else {
            bootbox.alert("<?php echo lang('hr_employees_multiple_edit_selection_msg');?>");
        }
    });

    //Change the contract of a group of employees
    $("#cmdSelectContract").click(function() {
        if (oTable.rows({selected: true}).any()) {
            $("#frmSelectContract").modal('show');
        }
        else {
            bootbox.alert("<?php echo lang('hr_employees_multiple_edit_selection_msg');?>");
        }
    });

    //Move the entity of a group of employees
    $("#cmdChangeEntity").click(function() {
        if (oTable.rows({selected: true}).any()) {
            contextSelectEntity = "change";
            $("#frmSelectEntity").modal('show');
            $("#frmSelectEntityBody").load('<?php echo base_url(); ?>organization/select');
        }
        else {
            bootbox.alert("<?php echo lang('hr_employees_multiple_edit_selection_msg');?>");
        }
    });

    //Create a leave request for a group of employees
    $("#cmdCreateRequest").click(function() {
        if (oTable.rows({selected: true}).any()) {
            $("#frmCreateLeaveRequest").modal('show');
            //Init the start and end date picker and link them (end>=date)
            $("#viz_leaveStartdate").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: '<?php echo lang('global_date_js_format');?>',
                altFormat: "yy-mm-dd",
                altField: "#leaveStartdate",
                numberOfMonths: 1,
                      onClose: function( selectedDate ) {
                        $( "#viz_leaveEnddate" ).datepicker( "option", "minDate", selectedDate );
                      }
            }, $.datepicker.regional['<?php echo $language_code;?>']);
            $("#viz_leaveEnddate").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: '<?php echo lang('global_date_js_format');?>',
                altFormat: "yy-mm-dd",
                altField: "#leaveEnddate",
                numberOfMonths: 1,
                      onClose: function( selectedDate ) {
                        $( "#viz_leaveStartdate" ).datepicker( "option", "maxDate", selectedDate );
                      }
            }, $.datepicker.regional['<?php echo $language_code;?>']);
        }
        else {
            bootbox.alert("<?php echo lang('hr_employees_multiple_edit_selection_msg');?>");
        }
    });

    //Select or deselect all rows
    $("#cmdSelectAll").click(function() {
        oTable.rows({filter: 'applied'}).select();
    });
    $("#cmdDeselectAll").click(function() {
        oTable.rows().deselect();
    });

    //If we opt-in the include children box, we'll recursively include the children of the selected entity
    //and the attached employees
    $("#chkIncludeChildren").on('change', function() {
        $('#frmModalAjaxWait').modal('show');
        includeChildren = $('#chkIncludeChildren').is(':checked');
        Cookies.set('includeChildren', includeChildren);
        //Refresh datatable
        oTable.ajax.url('<?php echo base_url();?>hr/employees/entity/' + entity + '/' + includeChildren + '/' + filterActive + '/' + filterDate)
            .load(function() {
                $("#frmModalAjaxWait").modal('hide');
            }, true);
    });

    //Manage radio buttons for the filtre active/inactive
    $("#cmdAll").click(function() {
        $('#frmModalAjaxWait').modal('show');
        filterActive = "all";
        Cookies.set('filterActive', filterActive);
        oTable.ajax.url('<?php echo base_url();?>hr/employees/entity/' + entity + '/' + includeChildren + '/' + filterActive + '/' + filterDate)
            .load(function() {
                $("#frmModalAjaxWait").modal('hide');
            }, true);
    });
    $("#cmdActive").click(function() {
        $('#frmModalAjaxWait').modal('show');
        filterActive = "active";
        Cookies.set('filterActive', filterActive);
        oTable.ajax.url('<?php echo base_url();?>hr/employees/entity/' + entity + '/' + includeChildren + '/' + filterActive + '/' + filterDate)
            .load(function() {
                $("#frmModalAjaxWait").modal('hide');
            }, true);
    });
    $("#cmdInactive").click(function() {
        $('#frmModalAjaxWait').modal('show');
        filterActive = "inactive";
        Cookies.set('filterActive', filterActive);
        oTable.ajax.url('<?php echo base_url();?>hr/employees/entity/' + entity + '/' + includeChildren + '/' + filterActive + '/' + filterDate)
            .load(function() {
                $("#frmModalAjaxWait").modal('hide');
            }, true);
    });

    //On click button export, call the export to Excel view
    $("#cmdExportEmployees").click(function() {
        window.location = '<?php echo base_url();?>hr/employees/export/' + entity + '/' + includeChildren + '/' + filterActive + '/' + filterDate;
    });

    //Filter on date hired
    $("#viz_datehired1").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: '<?php echo lang('global_date_js_format');?>',
        altFormat: "yy-mm-dd",
        altField: "#datehired1",
        onSelect: function() {
            refreshDataTable();
        }
    }, $.datepicker.regional['<?php echo $language_code;?>']);
    $("#viz_datehired2").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: '<?php echo lang('global_date_js_format');?>',
        altFormat: "yy-mm-dd",
        altField: "#datehired2",
        onSelect: function() {
            refreshDataTable();
        }
    }, $.datepicker.regional['<?php echo $language_code;?>']);

    //Handle filters on date hired field
    $("#cmdLesser1").click(function() {
        state1="lesser";
        refreshDataTable();
    });
    $("#cmdGreater1").click(function() {
        state1="greater";
        refreshDataTable();
    });
    $("#cmdLesser2").click(function() {
        state2="lesser";
        refreshDataTable();
    });
    $("#cmdGreater2").click(function() {
        state2="greater";
        refreshDataTable();
    });
    $("#cmdResetDate1").click(function() {
        $("#viz_datehired1").val("");
        $("#datehired1").val("");
        refreshDataTable();
    });
    $("#cmdResetDate2").click(function() {
        $("#viz_datehired2").val("");
        $("#datehired2").val("");
        refreshDataTable();
    });
});
</script>
