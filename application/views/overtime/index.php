<?php
/**
 * This view displays the list of overtime requests submitted to the connected manager.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>

<div class="row-fluid">
    <div class="span12">

<h2><?php echo lang('overtime_index_title');?><?php echo $help;?></h2>

<?php echo $flash_partial_view;?>

<p><?php echo lang('overtime_index_description');?></p>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="overtime" width="100%">
    <thead>
        <tr>
            <th><?php echo lang('overtime_index_thead_id');?></th>
            <th><?php echo lang('overtime_index_thead_fullname');?></th>
            <th><?php echo lang('overtime_index_thead_date');?></th>
            <th><?php echo lang('overtime_index_thead_duration');?></th>
            <th><?php echo lang('overtime_index_thead_status');?></th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($requests as $requests_item): ?>
    <tr>
        <td data-order="<?php echo $requests_item['id'] ?>">
          <a href="<?php echo base_url();?>extra/overtime/<?php echo $requests_item['id']; ?>" title="<?php echo lang('overtime_index_thead_tip_view');?>"><?php echo $requests_item['id']; ?></a>
          <div class="pull-right">
            <a href="<?php echo base_url();?>extra/overtime/<?php echo $requests_item['id']; ?>" title="<?php echo lang('overtime_index_thead_tip_view');?>"><i class="mdi mdi-eye nolink"></i></a>
            &nbsp;
            <a href="<?php echo base_url();?>overtime/accept/<?php echo $requests_item['id']; ?>" title="<?php echo lang('overtime_index_thead_tip_accept');?>"><i class="mdi mdi-check nolink"></i></a>
            &nbsp;
            <a href="<?php echo base_url();?>overtime/reject/<?php echo $requests_item['id']; ?>" title="<?php echo lang('overtime_index_thead_tip_reject');?>"><i class="mdi mdi-close nolink"></i></a>
          </div>
        </td>
        <td><?php echo $requests_item['firstname'] . ' ' . $requests_item['lastname']; ?></td>
<?php $date = new DateTime($requests_item['date']);
$tmpDate = $date->getTimestamp();?>
        <td data-order="<?php echo $tmpDate; ?>"><?php echo $date->format(lang('global_date_format'));?></td>
        <td><?php echo $requests_item['duration']; ?></td>
        <td><?php echo lang($requests_item['status_name']); ?></td>

    </tr>
<?php endforeach ?>
	</tbody>
</table>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">&nbsp;</div>
</div>

<div class="row-fluid">
    <div class="span12">
      <a href="<?php echo base_url();?>overtime/export/<?php echo $filter; ?>" class="btn btn-primary"><i class="mdi mdi-download"></i>&nbsp; <?php echo lang('overtime_index_button_export');?></a>
      &nbsp;&nbsp;
      <a href="<?php echo base_url();?>overtime/all" class="btn btn-primary"><i class="mdi mdi-filter-remove"></i>&nbsp; <?php echo lang('overtime_index_button_show_all');?></a>
      &nbsp;&nbsp;
      <a href="<?php echo base_url();?>overtime/requested" class="btn btn-primary"><i class="mdi mdi-filter"></i>&nbsp; <?php echo lang('overtime_index_button_show_pending');?></a>
    </div>
</div>

<link href="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    //Transform the HTML table in a fancy datatable
    $('#overtime').dataTable({
        order: [[ 2, "desc" ]],
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
});
</script>
