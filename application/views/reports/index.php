<?php
/**
 * This view displays the list of the custom reports.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.4.3
 */
?>

<div class="row-fluid">
    <div class="span12">
        
        <h2><?php echo lang('reports_index_title');?></h2>

        <table cellpadding="0" cellspacing="0" border="0" class="display" id="reports" width="100%">
            <thead>
                <tr>
                    <th><?php echo lang('reports_index_thead_report');?></th>
                    <th><?php echo lang('reports_index_thead_description');?></th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($reports as $name => $report): ?>
            <tr>
                <td><a href="<?php echo base_url() . $report[2]; ?>"><?php echo $name; ?></a></td>
                <td><?php echo $report[1]; ?></td>
            </tr>
        <?php endforeach ?>
            </tbody>
        </table>
        
    </div>
</div>

<link href="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
$(function () {
    //Transform the HTML table in a fancy datatable
    $('#reports').dataTable({
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
