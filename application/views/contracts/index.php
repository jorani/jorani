<?php
/**
 * This view lists the contracts created into the application
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>

<h2><?php echo lang('contract_index_title');?> &nbsp;<?php echo $help;?></h2>

<?php echo $flash_partial_view;?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="contracts" width="100%">
    <thead>
        <tr>
            <th><?php echo lang('contract_index_thead_id');?></th>
            <th><?php echo lang('contract_index_thead_name');?></th>
            <th><?php echo lang('contract_index_thead_start');?></th>
            <th><?php echo lang('contract_index_thead_end');?></th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($contracts as $contracts_item): ?>
    <tr>
        <td data-order="<?php echo $contracts_item['id']; ?>">
            <?php echo $contracts_item['id'] ?>
            &nbsp;
            <div class="pull-right">
                <a href="#" class="confirm-delete" data-id="<?php echo $contracts_item['id'];?>" title="<?php echo lang('contract_index_tip_delete');?>"><i class="mdi mdi-delete nolink"></i></a>
                &nbsp;
                <a href="<?php echo base_url();?>contracts/edit/<?php echo $contracts_item['id'] ?>" title="<?php echo lang('contract_index_tip_edit');?>"><i class="mdi mdi-pencil nolink"></i></a>
                &nbsp;
                <a href="<?php echo base_url();?>entitleddays/contract/<?php echo $contracts_item['id'] ?>" title="<?php echo lang('contract_index_tip_entitled');?>"><i class="mdi mdi-pencil-box-outline nolink"></i></a>
                &nbsp;
                <a href="<?php echo base_url();?>contracts/<?php echo $contracts_item['id'] ?>/calendar" title="<?php echo lang('contract_index_tip_dayoffs');?>"><i class="mdi mdi-calendar nolink"></i></a>
                &nbsp;
                <a href="<?php echo base_url();?>contracts/<?php echo $contracts_item['id'] ?>/excludetypes" title="<?php echo lang('contract_index_tip_exclude_types');?>"><i class="mdi mdi-cancel nolink"></i></a>
            </div>
        </td>
        <td><?php echo $contracts_item['name']; ?></td>
        <?php
        $startentdate = $contracts_item['startentdate'];
        $endentdate = $contracts_item['endentdate'];
        if (strpos(lang('global_date_format'), 'd') < strpos(lang('global_date_format'), 'm')) {
            $pieces = explode("/", $startentdate);
            $startentdate = $pieces[1] . '/' . $pieces[0];
            $pieces = explode("/", $endentdate);
            $endentdate = $pieces[1] . '/' . $pieces[0];
        }?>
        <td><?php echo $startentdate; ?></td>
        <td><?php echo $endentdate; ?></td>
    </tr>
<?php endforeach ?>
	</tbody>
</table>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div class="row-fluid">
    <div class="span12">
        <a href="<?php echo base_url();?>contracts/export" class="btn btn-primary"><i class="mdi mdi-download"></i>&nbsp; <?php echo lang('contract_index_button_export');?></a>
        &nbsp;
        <a href="<?php echo base_url();?>contracts/create" class="btn btn-primary"><i class="mdi mdi-plus-circle"></i>&nbsp; <?php echo lang('contract_index_button_create');?></a>
    </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div id="frmDeleteContract" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmDeleteContract').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('contract_index_popup_delete_title');?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo lang('contract_index_popup_delete_description');?></p>
        <p><?php echo lang('contract_index_popup_delete_confirm');?></p>
    </div>
    <div class="modal-footer">
        <a href="#" id="lnkDeleteContract" class="btn btn-danger"><?php echo lang('contract_index_popup_delete_button_yes');?></a>
        <a href="#" onclick="$('#frmDeleteContract').modal('hide');" class="btn"><?php echo lang('contract_index_popup_delete_button_no');?></a>
    </div>
</div>

<div id="frmEntitledDays" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmEntitledDays').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('contract_index_popup_entitled_title');?></h3>
    </div>
    <div class="modal-body">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="$('#frmEntitledDays').modal('hide');" class="btn"><?php echo lang('contract_index_popup_entitled_button_close');?></a>
    </div>
</div>

<link href="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    //Transform the HTML table in a fancy datatable
    $('#contracts').dataTable({
        stateSave: true,
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
    $("#frmChangePwd").alert();
    $("#frmEntitledDays").alert();

    //On showing the confirmation pop-up, add the contract id at the end of the delete url action
    $('#frmDeleteContract').on('show', function() {
        var link = "<?php echo base_url();?>contracts/delete/" + $(this).data('id');
        $("#lnkDeleteContract").attr('href', link);
    });

    //Display a modal pop-up so as to confirm if a contract has to be deleted or not
    //We build a complex selector because datatable does horrible things on DOM...
    //a simplier selector doesn't work when the delete is on page >1
    $("#contracts tbody").on('click', '.confirm-delete',  function(){
        var id = $(this).data('id');
        $('#frmDeleteContract').data('id', id).modal('show');
    });

    $('#frmEntitledDays').on('hidden', function() {
        $(this).removeData('modal');
    });
});
</script>
