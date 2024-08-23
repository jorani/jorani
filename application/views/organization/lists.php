<?php
/**
 * This partial view is intended to be used in a modal. It allows to manage
 * custom lists of employees created by a user. An example of use is into the
 * tabular calendar as an alternative selection (instead of an entity, we can
 * choose a list).
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.6.0
 */
?>

<div class="row-fluid">
    <div class="span12">

<div class="input-prepend input-append">
    <button id="cmdDeleteList" class="btn btn-danger" title="<?php echo lang('organization_lists_button_delete_list');?>"><i class="mdi mdi-trash-can-outline"></i></button>
    <button id="cmdRenameList" class="btn btn-primary" title="<?php echo lang('organization_lists_button_edit_list');?>"><i class="mdi mdi-pencil"></i></button>
    <button id="cmdCreateList" class="btn btn-primary" title="<?php echo lang('organization_lists_button_add_list');?>"><i class="mdi mdi-plus"></i></button>
    <select id="cboList" name="cboList">
        <option value="-1" selected="true"></option>
<?php foreach ($lists as $listItem): ?>
        <option value="<?php echo $listItem['id'];?>"><?php echo $listItem['name'];?></option>
<?php endforeach ?>
    </select>
    <button id="cmdAddEmployees" class="btn btn-primary" title="<?php echo lang('organization_lists_button_add_users');?>"><i class="mdi mdi-account-plus"></i></button>
    <button id="cmdRemoveEmployees" class="btn btn-primary" title="<?php echo lang('organization_lists_button_remove_users');?>"><i class="mdi mdi-account-remove"></i></button>
</div>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="employeesOrgList" width="100%">
    <thead>
        <tr>
            <th id='id'><?php echo lang('organization_lists_employees_thead_id');?></th>
            <th id='firstname'><?php echo lang('organization_lists_employees_thead_firstname');?></th>
            <th id='lastname'><?php echo lang('organization_lists_employees_thead_lastname');?></th>
            <th id ='entity'><?php echo lang('organization_lists_employees_thead_entity');?></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
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

<div id="frmSelectEmployees" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmSelectEmployees').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('organization_lists_button_add_users');?></h3>
    </div>
    <div class="modal-body" id="frmSelectEmployeesBody">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="select_employees();" class="btn"><?php echo lang('OK');?></a>
        <a href="#" onclick="$('#frmSelectEmployees').modal('hide');" class="btn"><?php echo lang('Cancel');?></a>
    </div>
</div>


<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootbox-4.4.0.min.js"></script>
<link href="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/datatable/RowReorder-1.1.1/css/rowReorder.dataTables.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/datatable/Select-1.1.2/css/select.dataTables.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/Select-1.1.2/js/dataTables.select.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/datatable/RowReorder-1.1.1/js/dataTables.rowReorder.min.js"></script>
<script type="text/javascript">
var listId;
var listName;
var employeesOrgList;   //DataTable object
//var orgTable;
var urlListEmployees;

//If a list is selected, activate the controls and load the employees
function toggleCommands() {
    if ($('#cboList').val() == -1) {
        $('#cmdDeleteList').prop("disabled", true);
        $('#cmdRenameList').prop("disabled", true);
        $('#cmdAddEmployees').prop("disabled", true);
        $('#cmdRemoveEmployees').prop("disabled", true);
    } else {
        $('#cmdDeleteList').prop("disabled", false);
        $('#cmdRenameList').prop("disabled", false);
        $('#cmdAddEmployees').prop("disabled", false);
        $('#cmdRemoveEmployees').prop("disabled", false);
    }
        //Reload the list of employees
        listId = $('#cboList').val();
        urlListEmployees = '<?php echo base_url();?>organization/lists/employees?list=' + listId;
        $('#frmModalAjaxWait').modal('show');
        employeesOrgList.ajax.url(urlListEmployees)
            .load(function() {
                $("#frmModalAjaxWait").modal('hide');
            }, true);
}

//Pick up employees to be added into the selected list
function select_employees() {
    var oTable = $('#employeesMultiSelect').DataTable();
    var employeeIds = [];
    oTable.rows({selected: true}).every( function () {
        employeeIds.push(this.data()[0]);
     });
    employeeIds = JSON.stringify(employeeIds);
    if(employeeIds != "[]"){
    listId = $('#cboList').val();
    $('#frmModalAjaxWait').modal('show');
    $.ajax({
        url: "<?php echo base_url();?>organization/lists/addemployees",
        type: "POST",
        data: {
                list: listId,
                employees: employeeIds
            }
      }).done(function(message) {
        toggleCommands();
        $('#frmModalAjaxWait').modal('hide');
      });
    }
    $("#frmSelectEmployees").modal('hide');
}

$(function () {
    //Setup Ajax/CSRF
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

    //Transform the HTML table in a fancy datatable
    employeesOrgList = $('#employeesOrgList').DataTable({
        select: 'multiple',
        rowReorder: true,
        colReorder: true,
        pageLength: 10,
        order: [],
        columns: [
          { data: "id" },
          { data: "firstname" },
          { data: "lastname" },
          { data: "entity" }
        ],
        columnDefs: [
            { orderable: true, className: 'reorder', targets: 0 },
            { orderable: false, targets: '_all' }
        ],
        language: {
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
        }
    });

    employeesOrgList.on( 'row-reorder', function ( e, diff, edit ) {
        var retour = [];
        for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
            retour.push({newPos: diff[i].newPosition + 1,
                        user: diff[i].node.outerText.split("	")[0]});
        }
        retour = JSON.stringify(retour);
        if(retour != "[]"){
          $('#frmModalAjaxWait').modal('show');
          $.ajax({
            url: "<?php echo base_url();?>organization/lists/reorder",
            type: "POST",
            data: {
              id: listId,
              moves: retour
            }
          }).done(function(message) {
            toggleCommands();
            $('#frmModalAjaxWait').modal('hide');
          });
        }

    });
    var columnId = -1;
    var orderColumn = "";
    $('#employeesOrgList').on( 'order.dt', function () {
      // This will show: "Ordering on column 1 (asc)", for example
      var order = employeesOrgList.order();
      if(order.length > 0){
          columnId = order[0][0];
          orderColumn = order[0][1];
        var retour = [];
        for ( var i=0, ien=employeesOrgList.rows()[0].length ; i<ien ; i++ ) {
            retour.push({newPos: i + 1,
                        user: employeesOrgList.rows().data()[i].id});
        }
        retour = JSON.stringify(retour);
        if(retour != "[]"){
          $('#frmModalAjaxWait').modal('show');
          $.ajax({
            url: "<?php echo base_url();?>organization/lists/reorder",
            type: "POST",
            data: {
              id: listId,
              moves: retour
            }
          }).done(function(message) {
            $('#frmModalAjaxWait').modal('hide');
          });
        }
      }
    });

    $("#cboList").on('change', function() {
      //console.log($('#cboList').val());
      toggleCommands();
    });

    //Add a list of employees into the selected list
    $("#cmdAddEmployees").click(function() {
      if($('#cboList').val() != -1){
        $("#frmSelectEmployees").modal('show');
        $("#frmSelectEmployeesBody").load('<?php echo base_url(); ?>users/employeesMultiSelect');
      }
    });
    $("#cmdRemoveEmployees").click(function() {

      var employeeDeleteIds = [];
      employeesOrgList.rows({selected: true}).every( function () {
          employeeDeleteIds.push(this.index() + 1);
       });
      if(employeeDeleteIds != ""){
        employeeDeleteIds = JSON.stringify(employeeDeleteIds);
        listId = $('#cboList').val();
        $('#frmModalAjaxWait').modal('show');
        $.ajax({
          url: "<?php echo base_url();?>organization/lists/removeemployees",
          type: "POST",
          data: {
            id: listId,
            employees: employeeDeleteIds
          }
        }).done(function(message) {
          employeesOrgList.rows({selected: true}).every( function () {
            this.deselect();
           });
          toggleCommands();
          $('#frmModalAjaxWait').modal('hide');
        });
        $("#frmSelectEmployees").modal('hide');
      }

    });

    //Create a new list by ajax. Add the new option into select control
    $("#cmdCreateList").click(function() {
        bootbox.prompt({
            title: "<?php echo lang('organization_lists_employees_prompt_new');?>",
            buttons: {
                confirm: {
                    label: "<?php echo lang('OK');?>"
                },
                cancel: {
                    label: "<?php echo lang('Cancel');?>"
                }
            },
            callback: function(result) {
          if ((result !== null) && (result != '')) {
            listName = result;
            //Call ajax endpoint
            $('#frmModalAjaxWait').modal('show');
            $.ajax({
                url: "<?php echo base_url();?>organization/lists/create",
                type: "POST",
                data: {
                    name: listName
                }
              }).done(function( data ) {
                  $('#frmModalAjaxWait').modal('hide');
                  if ($.isNumeric(data)) {
                    $('#cboList').append($('<option>', {value: data, text: listName}));
                    $('#cboList option[value="' + data + '"]').attr('selected','selected');
                  } else {
                      bootbox.alert(data);
                  }
                  toggleCommands();
            });//ajax
          }//have prompt
        }
      });//bootbox
    });

    //Delete a list by ajax. Remove the option from the select control
    $("#cmdDeleteList").click(function() {
        bootbox.confirm({
            title: "<?php echo lang('organization_lists_employees_confirm_delete');?>",
            message: "<?php echo lang('organization_lists_employees_confirm_delete');?>",
            buttons: {
                confirm: {
                    label: "<?php echo lang('OK');?>"
                },
                cancel: {
                    label: "<?php echo lang('Cancel');?>"
                }
            },
            callback: function(result) {
          if (result === true) {
            listId = $('#cboList').val();
            //Call ajax endpoint
            $('#frmModalAjaxWait').modal('show');
            $.ajax({
                url: "<?php echo base_url();?>organization/lists/delete",
                type: "POST",
                data: {
                    id: listId
                }
              }).done(function( msg ) {
                  $('#frmModalAjaxWait').modal('hide');
                  if (msg == "") {
                    $('#cboList option:selected').remove();
                    $('#cboList').val(-1);
                  } else {
                      bootbox.alert(data);
                  }
                  toggleCommands();
            });//ajax
          }//have prompt
        }
      });//bootbox
    });

    //Rename a list by ajax. Change the option from the select control
    $("#cmdRenameList").click(function() {
        listId = $('#cboList').val();
        listName = $('#cboList option:selected').text();
        bootbox.prompt({
            title: "<?php echo lang('organization_lists_employees_prompt_rename');?>",
            value: listName,
            buttons: {
                confirm: {
                    label: "<?php echo lang('OK');?>"
                },
                cancel: {
                    label: "<?php echo lang('Cancel');?>"
                }
            },
            callback: function(result) {
                if ((result !== null) && (result != '')) {
                    listName = result;
                    //Call ajax endpoint
                    $('#frmModalAjaxWait').modal('show');
                    $.ajax({
                        url: "<?php echo base_url();?>organization/lists/rename",
                        type: "POST",
                        data: {
                            id: listId,
                            name: listName
                        }
                      }).done(function( msg ) {
                          $('#frmModalAjaxWait').modal('hide');
                          if (msg == "") {
                            $('#cboList option:selected').text(listName);
                          } else {
                              bootbox.alert(data);
                          }
                    });//ajax
                }//have prompt
            }//function
        });//bootbox
    });

    toggleCommands();
});

</script>
