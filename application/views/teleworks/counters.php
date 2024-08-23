<?php
/**
 * This view displays the counters (number of available telework) for an employee.
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.2.0
 */
?>

<div class="row-fluid">
    <div class="span12">

        <h2><?php echo lang('teleworks_summary_title');?><?php echo $help;?></h2>

        <p><?php echo lang('teleworks_summary_date_field');?>&nbsp;
            <input type="text" id="refdate" />
        </p>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                <th rowspan="2"><?php echo lang('teleworks_summary_thead_type');?></th>
                <th rowspan="2"><i class="mdi mdi-minus-circle" aria-hidden="true"></i>&nbsp;<?php echo lang('teleworks_summary_thead_taken');?>&nbsp;<i class="mdi mdi-help-circle" data-toggle="tooltip" title="<?php echo lang('Accepted');?> + <?php echo lang('Cancellation');?>"></i></th>
                <th rowspan="2"><i class="mdi mdi-information" aria-hidden="true"></i>&nbsp;<span class="label"><?php echo lang('Planned');?></span></th>
                <th rowspan="2"><i class="mdi mdi-information" aria-hidden="true"></i>&nbsp;<span class="label label-warning"><?php echo lang('Requested');?></span></th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($summary) > 0) {
            foreach ($summary as $key => $value) {
                    ?>
                <tr>
                <td><?php echo lang($key); ?></td>
                <td><a href="<?php echo base_url();?>teleworks?statuses=3|5&type=<?php echo $value[3]; ?>" target="_blank"><?php echo ((float) $value[0]); ?></a></td>
                <?php if (empty($value[4])) { ?>
                <td>&nbsp;</td>
                <?php } else { ?>
                <td><a href="<?php echo base_url();?>teleworks?statuses=1&type=<?php echo $value[3]; ?>" target="_blank"><?php echo ((float) $value[4]); ?></a></td>
                <?php } ?>
                <?php if (empty($value[5])) { ?>
                <td>&nbsp;</td>
                <?php } else { ?>
                <td><a href="<?php echo base_url();?>teleworks?statuses=2&type=<?php echo $value[3]; ?>" target="_blank"><?php echo ((float) $value[5]); ?></a></td>
                <?php } ?>
                </tr>
            <?php }
            } else {?>
                <tr>
                <td colspan="4"><?php echo lang('teleworks_summary_tbody_empty');; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<link rel="stylesheet" href="<?php echo base_url();?>assets/bootstrap-datepicker-1.8.0/css/bootstrap-datepicker.min.css">
<script src="<?php echo base_url();?>assets/bootstrap-datepicker-1.8.0/js/bootstrap-datepicker.min.js"></script>
<?php //Prevent HTTP-404 when localization isn't needed
if ($language_code != 'en') { ?>
<script src="<?php echo base_url();?>assets/bootstrap-datepicker-1.8.0/locales/bootstrap-datepicker.<?php echo $language_code;?>.min.js"></script>
<?php } ?>

<script type="text/javascript">
/**
 * Converts a local date to an ISO compliant string
 * Because toISOString converts to UTC causing one day
 * of shift in some zones
 * @param Date $d JavaScript native date object
 */
function toISODateLocal(d) {
  var z = n => (n<10? '0':'')+n;
  return d.getFullYear() + '-' + z(d.getMonth()+1) + '-' + z(d.getDate()); 
}

$(function () {
    //Init datepicker widget (it is complicated because we cannot based it on UTC)
    var isDefault = <?php echo $isDefault;?>;
    var reportDate = '<?php $date = new DateTime($refDate); echo $date->format(lang('global_date_format'));?>';
    var dateFormat = { year: 'numeric', month: 'numeric', day: 'numeric' };
    var now = new Date();
    var todayDate = now.toLocaleDateString('<?php echo $language_code;?>', dateFormat);
    if (isDefault == 1) {
        $("#refdate").val(todayDate);
    } else {
        $("#refdate").val(reportDate);
    }

    $("#refdate").datepicker({
        language: "<?php echo $language_code;?>",
        autoclose: true
    }).on('changeDate', function(e){
        isoDate = toISODateLocal(e.date);
        url = "<?php echo base_url();?>teleworks/counters/" + isoDate;
        window.location = url;
    });
        
    //Display tooltips
    $("[ data-toggle=tooltip]").tooltip({ placement: 'top'});
});
</script>
