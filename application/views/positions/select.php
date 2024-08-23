<?php 
/**
 * This partial view (embedded into a modal form) allows a user to select a position.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.2.0
 */
?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="positions" width="100%">
    <thead>
        <tr>
            <th><?php echo lang('positions_select_thead_id');?></th>
            <th><?php echo lang('positions_select_thead_name');?></th>
            <th><?php echo lang('positions_select_thead_description');?></th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($positions as $position): ?>
    <tr>
        <td><?php echo $position['id'] ?></td>
        <td><?php echo $position['name'] ?></td>
        <td><?php echo $position['description'] ?></td>
    </tr>
<?php endforeach ?>
	</tbody>
</table>

<link href="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/datatable/Select-1.1.2/css/select.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/Select-1.1.2/js/dataTables.select.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    //Transform the HTML table in a fancy datatable
    $('#positions').dataTable({
        pageLength: 5,
        select: 'single',
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
    //Hide pagination select box in order to save space
    $('.dataTables_length').css("display", "none");
});
</script>
