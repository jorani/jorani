<?php
/**
 * This view displays the leave balance of the collaborators of the connected employee (manager).
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.4.5
 */
?>

<div class="row-fluid">
    <div class="span12">

        <h2><?php echo lang('requests_balance_title');?>  &nbsp;<?php echo $help;?></h2>

        <p><?php echo lang('requests_balance_description');?></p>

        <p><?php echo lang('requests_balance_date_field');?>&nbsp;<input type="text" id="refdate" /></p>

        <table cellpadding="0" cellspacing="0" border="0" class="cell-border" id="balance" width="100%">
            <thead>
                <tr>
                    <th><?php echo lang('identifier');?></th>
                    <th><?php echo lang('firstname');?></th>
                    <th><?php echo lang('lastname');?></th>
                    <th><?php echo lang('datehired');?></th>
                    <th><?php echo lang('position');?></th>
                <?php foreach ($types as $type): ?>
                    <th><?php echo $type['name'];?></th>
                <?php endforeach ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($result as $row): ?>
                <tr>
                    <?php foreach ($row as $key => $value): ?>
                    <td><?php echo $value == '' ? '&nbsp;' : $value; ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>

    </div>
</div>

<link href="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/datatable/Buttons-1.1.2/css/buttons.dataTables.min.css" rel="stylesheet"/>
<link href="<?php echo base_url();?>assets/datatable/ColReorder-1.3.1/css/colReorder.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/Buttons-1.1.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/Buttons-1.1.2/js/buttons.colVis.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/Buttons-1.1.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/ColReorder-1.3.1/js/dataTables.colReorder.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jszip.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/flick/jquery-ui.custom.min.css">
<script src="<?php echo base_url();?>assets/js/jquery-ui.custom.min.js"></script>
<?php //Prevent HTTP-404 when localization isn't needed
if ($language_code != 'en') { ?>
<script src="<?php echo base_url();?>assets/js/i18n/jquery.ui.datepicker-<?php echo $language_code;?>.js"></script>
<?php } ?>

<script type="text/javascript">
$(function () {
    //Transform the HTML table in a fancy datatable
    var table = $('#balance').DataTable({
            stateSave: true,
            dom: 'Bfrtip',
            'colReorder' : true,
            buttons: [
                    {
                        extend: 'pageLength',
                        text: '<?php echo lang('datatable_pagination');?>'
                    },
                    {
                        extend: 'colvis',
                        postfixButtons: [
                            {
                                extend: 'colvisRestore',
                                text: '<?php echo lang('datatable_colvisRestore');?>'
                            }
                        ]
                    },
                    {
                        extend:    'excelHtml5',
                        text:      '<i class="mdi mdi-download"></i>',
                        titleAttr: 'Excel'
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

    //Init datepicker widget (it is complicated because we cannot based it on UTC)
    isDefault = <?php echo $isDefault;?>;
    moment.locale('<?php echo $language_code;?>', {longDateFormat : {L : '<?php echo lang('global_date_momentjs_format');?>'}});
    reportDate = '<?php $date = new DateTime($refDate); echo $date->format(lang('global_date_format'));?>';
    todayDate = moment().format('L');
    if (isDefault == 1) {
        $("#refdate").val(todayDate);
    } else {
        $("#refdate").val(reportDate);
    }
    $('#refdate').datepicker({
        dateFormat: '<?php echo lang('global_date_js_format');?>',
        onSelect: function(dateText, inst) {
                tmpUnix = moment($("#refdate").datepicker("getDate")).unix();
                url = "<?php echo base_url();?>requests/balance/" + tmpUnix;
                window.location = url;
        }
    });
});
</script>
