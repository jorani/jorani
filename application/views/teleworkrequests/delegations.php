<?php
/**
 * This view allows a manager to designate a list of employees as delegates.
 * A user being the delegate of a manager can validate telework requests sumitted to this manager.
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.4.0
 */
?>

<div class="row-fluid">
    <div class="span12">

<h2><?php echo lang('teleworkrequests_delegations_title');?> <span class="muted">(<?php echo $name; ?>)</span>  &nbsp;<?php echo $help;?></h2>

<div class="row-fluid"><div class="span12"><?php echo lang('teleworkrequests_delegations_description');?></div></div>

<table id="delegations">
<thead>
    <tr>
      <th>&nbsp;</th>
      <th><?php echo lang('teleworkrequests_delegations_thead_employee');?></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($delegations as $delegation) { ?>
    <tr data-id="<?php echo $delegation['id']; ?>">
      <td><a href="#" onclick="delete_delegation(<?php echo $delegation['id'] ?>);" title="<?php echo lang('teleworkrequests_delegations_thead_tip_delete');?>"><i class="mdi mdi-close nolink"></i></a></td>
      <td><?php echo $delegation['delegate_name']; ?></td>
    </tr>
  <?php } ?>
  </tbody>
</table>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>
<button id="cmdAddDelegate" class="btn btn-primary" onclick="$('#frmSelectDelegate').modal('show');"><i class="mdi mdi-account-search"></i>&nbsp;<?php echo lang('teleworkrequests_delegations_button_add');?></button>
<div class="row-fluid"><div class="span12">&nbsp;</div></div>

    </div>
</div>

<div id="frmSelectDelegate" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmSelectDelegate').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('teleworkrequests_delegations_popup_delegate_title');?></h3>
    </div>
    <div class="modal-body" id="frmSelectDelegateBody">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="select_delegate();" class="btn"><?php echo lang('teleworkrequests_delegations_popup_delegate_button_ok');?></a>
        <a href="#" onclick="$('#frmSelectDelegate').modal('hide');" class="btn"><?php echo lang('teleworkrequests_delegations_popup_delegate_button_cancel');?></a>
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
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>

<script type="text/javascript">
    var oTable;     //datatable

    function delete_delegation(id) {
        bootbox.confirm("<?php echo lang('teleworkrequests_delegations_confirm_delete_message');?>",
            "<?php echo lang('teleworkrequests_delegations_confirm_delete_cancel');?>",
            "<?php echo lang('teleworkrequests_delegations_confirm_delete_yes');?>", function(result) {
            if (result) {
                $('#frmModalAjaxWait').modal('show');
                $.ajax({
                    url: "<?php echo base_url();?>requests/ajax/delegations/delete",
                    type: "POST",
                    data: { manager_id: <?php echo $id; ?>,
                        delegation_id: id
                    }
                  }).done(function() {
                      oTable.rows('tr[data-id="' + id + '"]').remove().draw();
                      $('#frmModalAjaxWait').modal('hide');
                  });
                }
        });
    }

    function select_delegate() {
        var employees = $('#employees').DataTable();
        if ( employees.rows({ selected: true }).any() ) {
            var employee = employees.rows({selected: true}).data()[0][0];
            name = employees.rows({selected: true}).data()[0][1] + ' ' + employees.rows({selected: true}).data()[0][2];
            $('#frmSelectDelegate').modal('hide');
            if (parseInt(employee) != parseInt('<?php echo $id; ?>')) {
                $('#frmModalAjaxWait').modal('show');
                $.ajax({
                    url: "<?php echo base_url();?>requests/ajax/delegations/add",
                    type: "POST",
                    data: { manager_id: <?php echo $id; ?>,
                            delegate_id: employee
                        }
                  }).done(function(id) {
                      if (id != 'null') {
                        htmlRow = '<tr data-id="' + id + '">' +
                                  '<td><a href="#" onclick="delete_delegation(' + id + ');" title="<?php echo lang('teleworkrequests_delegations_thead_tip_delete');?>"><i class="mdi mdi-close nolink"></i></a></td>' +
                                  '<td>' + name + '</td>' +
                              '</tr>';
                          objRow=$(htmlRow);
                          oTable.row.add(objRow).draw();
                      }
                      $('#frmModalAjaxWait').modal('hide');
                });
            }
        } else {
            $('#frmSelectDelegate').modal('hide');
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

        //Transform the HTML table in a fancy datatable
        oTable = $('#delegations').DataTable({
            order: [[ 1, "desc" ]],
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

        //Popup select delegate
        $("#cmdAddDelegate").click(function() {
            $("#frmSelectDelegate").modal('show');
            $("#frmSelectDelegateBody").load('<?php echo base_url(); ?>users/employees');
        });
    });
</script>
