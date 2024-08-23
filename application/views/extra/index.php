<?php
/**
 * This view displays the list of overtime request created by the connected user.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.2.0
 */
?>

<div class="row-fluid">
    <div class="span12">

<h2><?php echo lang('extra_index_title');?><?php echo $help;?></h2>

<?php echo $flash_partial_view;?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="extras" width="100%">
    <thead>
        <tr>
            <th><?php echo lang('extra_index_thead_id');?></th>
            <th><?php echo lang('extra_index_thead_date');?></th>
            <th><?php echo lang('extra_index_thead_duration');?></th>
            <th><?php echo lang('extra_index_thead_cause');?></th>
            <th><?php echo lang('extra_index_thead_status');?></th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($extras as $extra_item): ?>
    <tr>
        <td data-order="<?php echo $extra_item['id']; ?>">
            <a href="<?php echo base_url();?>extra/extra/<?php echo $extra_item['id']; ?>" title="<?php echo lang('extra_index_thead_tip_view');?>"><?php echo $extra_item['id']; ?></a>
            &nbsp;
            <div class="pull-right">
                <a href="<?php echo base_url();?>extra/extra/<?php echo $extra_item['id']; ?>" title="<?php echo lang('extra_index_thead_tip_view');?>"><i class="mdi mdi-eye nolink"></i></a>
                &nbsp;
                <?php if ($extra_item['status'] == 1) { ?>
                <a href="<?php echo base_url();?>extra/edit/<?php echo $extra_item['id']; ?>" title="<?php echo lang('extra_index_thead_tip_edit');?>"><i class="mdi mdi-pencil nolink"></i></a>
                &nbsp;
                <a href="#" class="confirm-delete" data-id="<?php echo $extra_item['id'];?>" title="<?php echo lang('extra_index_thead_tip_delete');?>"><i class="mdi mdi-delete nolink"></i></a>
                <?php } ?>
            </div>
        </td>
<?php $date = new DateTime($extra_item['date']);
$tmpDate = $date->getTimestamp();?>
        <td data-order="<?php echo $tmpDate; ?>"><?php echo $date->format(lang('global_date_format'));?></td>
        <td><?php echo $extra_item['duration']; ?></td>
        <td><?php echo $extra_item['cause']; ?></td>
        <td><?php echo lang($extra_item['status_name']); ?></td>
    </tr>
<?php endforeach ?>
	</tbody>
</table>
    </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div class="row-fluid">
    <div class="span12">
      <a href="<?php echo base_url();?>extra/export" class="btn btn-primary"><i class="mdi mdi-download"></i>&nbsp; <?php echo lang('extra_index_button_export');?></a>
      &nbsp;
      <a href="<?php echo base_url();?>extra/create" class="btn btn-primary"><i class="mdi mdi-plus-circle"></i>&nbsp; <?php echo lang('extra_index_button_create');?></a>
    </div>
</div>

<div id="frmDeleteExtraRequest" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmDeleteExtraRequest').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('extra_index_popup_delete_title');?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo lang('extra_index_popup_delete_message');?></p>
        <p><?php echo lang('extra_index_popup_delete_question');?></p>
    </div>
    <div class="modal-footer">
        <a href="#" id="lnkDeleteUser" class="btn btn-danger"><?php echo lang('extra_index_popup_delete_button_yes');?></a>
        <a href="#" onclick="$('#frmDeleteExtraRequest').modal('hide');" class="btn"><?php echo lang('extra_index_popup_delete_button_no');?></a>
    </div>
</div>

<link href="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    //Transform the HTML table in a fancy datatable
    $('#extras').dataTable({
        stateSave: true,
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

    //On showing the confirmation pop-up, add the user id at the end of the delete url action
    $('#frmDeleteExtraRequest').on('show', function() {
        var link = "<?php echo base_url();?>extra/delete/" + $(this).data('id');
        $("#lnkDeleteUser").attr('href', link);
    })

    //Display a modal pop-up so as to confirm if a leave request has to be deleted or not
    //We build a complex selector because datatable does horrible things on DOM...
    //a simplier selector doesn't work when the delete is on page >1
    $("#extras tbody").on('click', '.confirm-delete',  function(){
        var id = $(this).data('id');
        $('#frmDeleteExtraRequest').data('id', id).modal('show');
    });

    $('#frmDeleteExtraRequest').on('hidden', function() {
        $(this).removeData('modal');
    });
});
</script>
