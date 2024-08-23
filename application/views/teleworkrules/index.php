<?php
/**
 * This view lists the telework rules created into the application
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>

<h2><?php echo lang('telework_rule_index_title');?> &nbsp;<?php echo $help;?></h2>

<?php echo $flash_partial_view;?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="teleworkrules" width="100%">
    <thead>
        <tr>
            <th><?php echo lang('telework_rule_index_thead_id');?></th>
            <th><?php echo lang('telework_rule_index_thead_organization');?></th>
            <th><?php echo lang('telework_rule_index_thead_limit');?></th>
            <th><?php echo lang('telework_rule_index_thead_delay');?></th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($rules as $rules_item): ?>
    <tr>
        <td data-order="<?php echo $rules_item['id']; ?>">
            <?php echo $rules_item['id'] ?>
            &nbsp;
            <div class="pull-right">
                <a href="#" class="confirm-delete" data-id="<?php echo $rules_item['id'];?>" title="<?php echo lang('telework_rule_index_tip_delete');?>"><i class="mdi mdi-delete nolink"></i></a>
                &nbsp;
                <a href="<?php echo base_url();?>teleworkrules/edit/<?php echo $rules_item['id'] ?>" title="<?php echo lang('telework_rule_index_tip_edit');?>"><i class="mdi mdi-pencil nolink"></i></a>
          </div>
        </td>
        <td><?php echo $rules_item['organization_name']; ?></td>
        <td><?php echo $rules_item['limit']; ?></td>
        <td><?php echo $rules_item['delay']; ?></td>
    </tr>
<?php endforeach ?>
	</tbody>
</table>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div class="row-fluid">
    <div class="span12">
        <a href="<?php echo base_url();?>teleworkrules/create" class="btn btn-primary"><i class="mdi mdi-plus-circle"></i>&nbsp; <?php echo lang('telework_rule_index_button_create');?></a>
   		&nbsp;&nbsp;
   		<a href="<?php echo base_url();?>teleworkrules/export" class="btn btn-primary"><i class="mdi mdi-download"></i>&nbsp; <?php echo lang('telework_rule_index_button_export');?></a>
        &nbsp;&nbsp;
        <a href="<?php echo base_url();?>teleworkrules/import" class="btn btn-primary"><i class="mdi mdi-import"></i>&nbsp; <?php echo lang('telework_rule_index_button_import');?></a>
    </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div id="frmDeleteRule" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmDeleteRule').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('telework_rule_index_popup_delete_title');?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo lang('telework_rule_index_popup_delete_description');?></p>
        <p><?php echo lang('telework_rule_index_popup_delete_confirm');?></p>
    </div>
    <div class="modal-footer">
        <a href="#" id="lnkDeleteRule" class="btn btn-danger"><?php echo lang('telework_rule_index_popup_delete_button_yes');?></a>
        <a href="#" onclick="$('#frmDeleteRule').modal('hide');" class="btn"><?php echo lang('telework_rule_index_popup_delete_button_no');?></a>
    </div>
</div>

<link href="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    //Transform the HTML table in a fancy datatable
    $('#teleworkrules').dataTable({
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

    //On showing the confirmation pop-up, add the telework rule id at the end of the delete url action
    $('#frmDeleteRule').on('show', function() {
        var link = "<?php echo base_url();?>teleworkrules/delete/" + $(this).data('id');
        $("#lnkDeleteRule").attr('href', link);
    });

    //Display a modal pop-up so as to confirm if a telework rule has to be deleted or not
    //We build a complex selector because datatable does horrible things on DOM...
    //a simplier selector doesn't work when the delete is on page >1
    $("#teleworkrules tbody").on('click', '.confirm-delete',  function(){
        var id = $(this).data('id');
        $('#frmDeleteRule').data('id', id).modal('show');
    });
});
</script>
