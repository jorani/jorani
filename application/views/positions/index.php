<?php 
/**
 * This view displays the list of positions.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>

<div class="row-fluid">
    <div class="span12">
      
<h2><?php echo lang('positions_index_title');?> &nbsp;<?php echo $help;?></h2>

<?php echo $flash_partial_view;?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="positions" width="100%">
    <thead>
        <tr>
            <th><?php echo lang('positions_index_thead_id');?></th>
            <th><?php echo lang('positions_index_thead_name');?></th>
            <th><?php echo lang('positions_index_thead_description');?></th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($positions as $position): ?>
    <tr>
        <td data-order="<?php echo $position['id']; ?>">
            <?php echo $position['id'];?>
            <div class="pull-right">
                <a href="#" class="confirm-delete" data-id="<?php echo $position['id'];?>" title="<?php echo lang('positions_index_thead_tip_delete');?>"><i class="mdi mdi-delete nolink"></i></a>&nbsp; 
                <a href="<?php echo base_url();?>positions/edit/<?php echo $position['id']; ?>" title="<?php echo lang('positions_index_thead_tip_edit');?>"><i class="mdi mdi-pencil nolink"></i></a>
            </div>
        </td>
        <td><?php echo $position['name']; ?></td>
        <td><?php echo $position['description']; ?></td>
    </tr>
<?php endforeach ?>
	</tbody>
</table>
	</div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div class="row-fluid">
    <div class="span12">
      <a href="<?php echo base_url();?>positions/export" class="btn btn-primary"><i class="mdi mdi-download"></i>&nbsp;<?php echo lang('positions_index_button_export');?></a>
        &nbsp;
      <a href="<?php echo base_url();?>positions/create" class="btn btn-primary"><i class="mdi mdi-plus-circle"></i>&nbsp;<?php echo lang('positions_index_button_create');?></a>
    </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div id="frmDeletePosition" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmDeletePosition').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('positions_index_popup_title');?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo lang('positions_index_popup_description');?></p>
        <p><?php echo lang('positions_index_popup_confirm');?></p>
    </div>
    <div class="modal-footer">
        <a href="#" id="lnkDeletePosition" class="btn btn-danger"><?php echo lang('positions_index_popup_button_yes');?></a>
        <a href="#" onclick="$('#frmDeletePosition').modal('hide');" class="btn"><?php echo lang('positions_index_popup_button_no');?></a>
    </div>
</div>

<link href="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
$(function () {
    //Transform the HTML table in a fancy datatable
    $('#positions').dataTable({
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
    
    $("#frmDeletePosition").alert();
    
    //On showing the confirmation pop-up, add the contract id at the end of the delete url action
    $('#frmDeletePosition').on('show', function() {
        var link = "<?php echo base_url();?>positions/delete/" + $(this).data('id');
        $("#lnkDeletePosition").attr('href', link);
    });

    //Display a modal pop-up so as to confirm if a contract has to be deleted or not
    //We build a complex selector because datatable does horrible things on DOM...
    //a simplier selector doesn't work when the delete is on page >1 
    $("#positions tbody").on('click', '.confirm-delete',  function(){
            var id = $(this).data('id');
            $('#frmDeletePosition').data('id', id).modal('show');
    });
});
</script>
