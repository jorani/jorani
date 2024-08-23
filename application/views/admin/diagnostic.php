<?php
/**
 * This view displays the diagnotic of confiuration and what was entered by employees (requests, etc.)
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.4.6
 */
?>

<div class="row-fluid">
    <div class="span12">
        <h2><?php echo $title;?><?php echo $help;?></h2>

        <div class="alert fade in">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <img id='checkVersionLoading' src="<?php echo base_url();?>assets/images/loading.gif" align="middle">
          <span id='checkVersion'><?php echo lang('global_msg_wait');?></span>
        </div>

        <p><?php echo lang('admin_diagnostic_description');?></p>

<?php
$daysOffYears_badge = ($daysOffYears_count == 0)?'':'<span class="badge badge-info">' . $daysOffYears_count . '</span>&nbsp;';
$duplicatedLeaves_badge = ($duplicatedLeaves_count == 0)?'':'<span class="badge badge-info">' . $duplicatedLeaves_count . '</span>&nbsp;';
$wrongDateType_badge = ($wrongDateType_count == 0)?'':'<span class="badge badge-info">' . $wrongDateType_count . '</span>&nbsp;';
$entitlmentOverflow_badge = ($entitlmentOverflow_count == 0)?'':'<span class="badge badge-info">' . $entitlmentOverflow_count . '</span>&nbsp;';
$negativeOvertime_badge = ($negativeOvertime_count == 0)?'':'<span class="badge badge-info">' . $negativeOvertime_count . '</span>&nbsp;';
$unusedContracts_badge = ($unusedContracts_count == 0)?'':'<span class="badge badge-info">' . $unusedContracts_count . '</span>&nbsp;';
$leaveBalance_badge = ($leaveBalance_count == 0)?'':'<span class="badge badge-info">' . $leaveBalance_count . '</span>&nbsp;';
$overlappingLeaves_badge = ($overlappingLeaves_count == 0)?'':'<span class="badge badge-info">' . $overlappingLeaves_count . '</span>&nbsp;';
if ($this->config->item('disable_telework') == FALSE) {
    $duplicatedTeleworks_badge = ($duplicatedTeleworks_count == 0)?'':'<span class="badge badge-info">' . $duplicatedTeleworks_count . '</span>&nbsp;';
    $wrongDateTypeTeleworks_badge = ($wrongDateTypeTeleworks_count == 0)?'':'<span class="badge badge-info">' . $wrongDateTypeTeleworks_count . '</span>&nbsp;';
    $overlappingTeleworks_badge = ($overlappingTeleworks_count == 0)?'':'<span class="badge badge-info">' . $overlappingTeleworks_count . '</span>&nbsp;';
}

?>
<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#daysoff"><?php echo $daysOffYears_badge . lang('admin_diagnostic_daysoff_tab');?></a></li>
    <li><a data-toggle="tab" href="#requests"><?php echo $duplicatedLeaves_badge . lang('admin_diagnostic_requests_tab');?></a></li>
    <li><a data-toggle="tab" href="#datetypes"><?php echo $wrongDateType_badge . lang('admin_diagnostic_datetype_tab');?></a></li>
    <li><a data-toggle="tab" href="#entitlements"><?php echo $entitlmentOverflow_badge . lang('admin_diagnostic_entitlements_tab');?></a></li>
    <li><a data-toggle="tab" href="#overtime"><?php echo $negativeOvertime_badge . lang('admin_diagnostic_overtime_tab');?></a></li>
    <li><a data-toggle="tab" href="#contracts"><?php echo $unusedContracts_badge . lang('admin_diagnostic_contract_tab');?></a></li>
    <li><a data-toggle="tab" href="#balance"><?php echo $leaveBalance_badge . lang('admin_diagnostic_balance_tab');?></a></li>
    <li><a data-toggle="tab" href="#overlapping"><?php echo $overlappingLeaves_badge . lang('admin_diagnostic_overlapping_tab');?>&nbsp;<i class="mdi mdi-test-tube mdi-rotate-45" title="Beta"></i></a></li>
	<?php if ($this->config->item('disable_telework') == FALSE) {?>
    	<li><a data-toggle="tab" href="#teleworkrequests"><?php echo $duplicatedTeleworks_badge . lang('admin_diagnostic_teleworks_requests_tab');?></a></li>
        <li><a data-toggle="tab" href="#teleworkdatetypes"><?php echo $wrongDateTypeTeleworks_badge . lang('admin_diagnostic_teleworks_datetype_tab');?></a></li>
        <li><a data-toggle="tab" href="#teleworkoverlapping"><?php echo $overlappingTeleworks_badge . lang('admin_diagnostic_teleworks_overlapping_tab');?>&nbsp;<i class="mdi mdi-test-tube mdi-rotate-45" title="Beta"></i></a></li>
	<?php }?>
</ul>

<div class="tab-content">

  <div class="tab-pane active" id="daysoff">

    <p><?php echo lang('admin_diagnostic_daysoff_description');?></p>

    <table class="table table-bordered table-hover table-condensed">
      <thead>
        <tr>
            <th><?php echo lang('admin_diagnostic_daysoff_thead_id');?></th>
            <th><?php echo lang('admin_diagnostic_daysoff_thead_name');?></th>
            <th><?php echo lang('admin_diagnostic_daysoff_thead_ym1');?></th>
            <th><?php echo lang('admin_diagnostic_daysoff_thead_y');?></th>
            <th><?php echo lang('admin_diagnostic_daysoff_thead_yp1');?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($daysOffYears as $yearDef): ?>
        <tr>
            <td><a target="_blank" href="<?php echo base_url();?>contracts/<?php echo $yearDef['contract'];?>/calendar"><?php echo $yearDef['contract'];?></a></td>
            <td><?php echo $yearDef['name'];?></td>
            <td><?php echo $yearDef['ym1'];?></td>
            <?php if ($yearDef['y'] == 0) {?>
            <td style="background:#f5811e;color:white;"><b><?php echo $yearDef['y'];?></b></td>
            <?php } else {?>
            <td><?php echo $yearDef['y'];?></td>
            <?php } ?>
            <td><?php echo $yearDef['yp1'];?></td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>

  <div class="tab-pane" id="requests">

        <p><?php echo lang('admin_diagnostic_requests_description');?></p>

        <?php if ($duplicatedLeaves_count==0) {?>
        <p><b><?php echo lang('admin_diagnostic_no_error');?></b></p>
        <?php } else {?>
        <table class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
                <th><?php echo lang('admin_diagnostic_requests_thead_id');?></th>
                <th><?php echo lang('admin_diagnostic_requests_thead_employee');?></th>
                <th><?php echo lang('admin_diagnostic_requests_thead_start_date');?></th>
                <th><?php echo lang('admin_diagnostic_requests_thead_type');?></th>
            </tr>
          </thead>
          <tbody>
        <?php foreach ($duplicatedLeaves as $leave):
            $date = new DateTime($leave['startdate']);
            $startdate = $date->format(lang('global_date_format'));?>
            <tr>
                <td><a target="_blank" href="<?php echo base_url();?>leaves/edit/<?php echo $leave['id'];?>"><?php echo $leave['id'];?></a></td>
                <td><?php echo $leave['user_label'];?></td>
                <td><?php echo $startdate;?></td>
                <td><?php echo $leave['type_label'];?></td>
            </tr>
            <?php endforeach ?>
          </tbody>
        </table>
        <?php }?>
  </div>

  <div class="tab-pane" id="datetypes">

        <p><?php echo lang('admin_diagnostic_datetype_description');?></p>

        <?php if ($wrongDateType_count==0) {?>
        <p><b><?php echo lang('admin_diagnostic_no_error');?></b></p>
        <?php } else {?>
        <table class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
                <th><?php echo lang('admin_diagnostic_datetype_thead_id');?></th>
                <th><?php echo lang('admin_diagnostic_datetype_thead_employee');?></th>
                <th><?php echo lang('admin_diagnostic_datetype_thead_start_date');?></th>
                <th><?php echo lang('admin_diagnostic_datetype_thead_start_type');?></th>
                <th><?php echo lang('admin_diagnostic_datetype_thead_end_type');?></th>
                <th><?php echo lang('admin_diagnostic_datetype_thead_status');?></th>
            </tr>
          </thead>
          <tbody>
        <?php foreach ($wrongDateType as $dateType):
            $date = new DateTime($dateType['startdate']);
            $startdate = $date->format(lang('global_date_format'));?>
            <tr>
                <td><a target="_blank" href="<?php echo base_url();?>leaves/edit/<?php echo $dateType['id'];?>"><?php echo $dateType['id'];?></a></td>
                <td><?php echo $dateType['user_label'];?></td>
                <td><?php echo $startdate;?></td>
                <td><?php echo lang($dateType['startdatetype']);?></td>
                <td><?php echo lang($dateType['enddatetype']);?></td>
                <td><?php echo lang($dateType['status_label']);?></td>
            </tr>
            <?php endforeach ?>
          </tbody>
        </table>
        <?php }?>
  </div>

  <div class="tab-pane" id="entitlements">

      <p><?php echo lang('admin_diagnostic_entitlements_description');?></p>

        <?php if ($entitlmentOverflow_count==0) {?>
        <p><b><?php echo lang('admin_diagnostic_no_error');?></b></p>
        <?php } else {?>
        <table class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
                <th><?php echo lang('admin_diagnostic_entitlements_thead_id');?></th>
                <th><?php echo lang('admin_diagnostic_entitlements_thead_type');?></th>
                <th><?php echo lang('admin_diagnostic_entitlements_thead_name');?></th>
                <th><?php echo lang('admin_diagnostic_entitlements_thead_start_date');?></th>
                <th><?php echo lang('admin_diagnostic_entitlements_thead_end_date');?></th>
            </tr>
          </thead>
          <tbody>
        <?php foreach ($entitlmentOverflow as $entitlment):
            $date = new DateTime($entitlment['startdate']);
            $startdate = $date->format(lang('global_date_format'));
            $date = new DateTime($entitlment['enddate']);
            $enddate = $date->format(lang('global_date_format'));
            if (!is_null($entitlment['contract'])) {?>
            <tr>
                <td><a target="_blank" href="<?php echo base_url();?>entitleddays/contract/<?php echo $entitlment['contract'];?>"><?php echo $entitlment['id'];?></a></td>
                <td><?php echo lang('admin_diagnostic_entitlements_type_contract');?></td>
                <td><?php echo (is_null($entitlment['contract_label'])?lang('admin_diagnostic_entitlements_deletion_problem'):$entitlment['contract_label']);?></td>
                <td><?php echo $startdate;?></td>
                <td><?php echo $enddate;?></td>
            </tr>
            <?php } else {?>
            <tr>
                <td><a target="_blank" href="<?php echo base_url();?>entitleddays/user/<?php echo $entitlment['employee'];?>"><?php echo $entitlment['id'];?></a></td>
                <td><?php echo lang('admin_diagnostic_entitlements_type_employee');?></td>
                <td><?php echo (is_null($entitlment['user_label'])?lang('admin_diagnostic_entitlements_deletion_problem'):$entitlment['user_label']);?></td>
                <td><?php echo $startdate;?></td>
                <td><?php echo $enddate;?></td>
            </tr>
            <?php
                }
                endforeach ?>
          </tbody>
        </table>
        <?php }?>
  </div>

  <div class="tab-pane" id="overtime">

        <p><?php echo lang('admin_diagnostic_overtime_description');?></p>

        <?php if ($negativeOvertime_count==0) {?>
        <p><b><?php echo lang('admin_diagnostic_no_error');?></b></p>
        <?php } else {?>
        <table class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
                <th><?php echo lang('admin_diagnostic_daysoff_thead_id');?></th>
                <th><?php echo lang('admin_diagnostic_daysoff_thead_employee');?></th>
                <th><?php echo lang('admin_diagnostic_daysoff_thead_date');?></th>
                <th><?php echo lang('admin_diagnostic_daysoff_thead_duration');?></th>
                <th><?php echo lang('admin_diagnostic_daysoff_thead_status');?></th>
            </tr>
          </thead>
          <tbody>
        <?php foreach ($negativeOvertime as $overtime):
            $date = new DateTime($overtime['date']);
            $date = $date->format(lang('global_date_format'));?>
            <tr>
                <td><a target="_blank" href="<?php echo base_url();?>extra/edit/<?php echo $overtime['id'];?>"><?php echo $overtime['id'];?></a></td>
                <td><?php echo $overtime['user_label'];?></td>
                <td><?php echo $date;?></td>
                <td><?php echo $overtime['duration'];?></td>
                <td><?php echo lang($overtime['status_label']);?></td>
            </tr>
            <?php endforeach ?>
          </tbody>
        </table>
        <?php }?>
  </div>

  <div class="tab-pane" id="contracts">
    <p><a target="_blank" href="<?php echo base_url();?>contracts"><?php echo lang('admin_diagnostic_contract_description');?></a></p>

    <?php if ($unusedContracts_count==0) {?>
    <p><b><?php echo lang('admin_diagnostic_no_error');?></b></p>
    <?php } else {?>
    <table class="table table-bordered table-hover table-condensed">
      <thead>
        <tr>
            <th><?php echo lang('admin_diagnostic_contract_thead_id');?></th>
            <th><?php echo lang('admin_diagnostic_contract_thead_name');?></th>
        </tr>
      </thead>
      <tbody>
    <?php foreach ($unusedContracts as $contract):?>
        <tr>
            <td><?php echo $contract['id'];?></td>
            <td><?php echo $contract['name'];?></td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <?php }?>
  </div><!-- Unused contracts //-->

  <div class="tab-pane" id="balance">
    <p><?php echo lang('admin_diagnostic_balance_description');?></a></p>

    <?php if ($leaveBalance_count==0) {?>
    <p><b><?php echo lang('admin_diagnostic_no_error');?></b></p>
    <?php } else {?>
    <table class="table table-bordered table-hover table-condensed">
      <thead>
        <tr>
            <th><?php echo lang('admin_diagnostic_balance_thead_id');?></th>
            <th><?php echo lang('admin_diagnostic_balance_thead_employee');?></th>
            <th><?php echo lang('admin_diagnostic_balance_thead_contract');?></th>
            <th><?php echo lang('admin_diagnostic_balance_thead_start_date');?></th>
            <th><?php echo lang('admin_diagnostic_balance_thead_status');?></th>
        </tr>
      </thead>
      <tbody>
    <?php foreach ($leaveBalance as $balance):
        $date = new DateTime($balance['startdate']);
        $startdate = $date->format(lang('global_date_format'));?>
        <tr>
            <td><a target="_blank" href="<?php echo base_url();?>leaves/edit/<?php echo $balance['id'];?>"><?php echo $balance['id'];?></a></td>
            <td>
                <a target="_blank" href="<?php echo base_url();?>hr/counters/employees/<?php echo $balance['employee'];?>"><i class="mdi mdi-information-outline nolink"></i></a>
                <a target="_blank" href="<?php echo base_url();?>entitleddays/user/<?php echo $balance['employee'];?>"><i class="mdi mdi-pencil-box-outline nolink"></i></a>
                <?php echo $balance['user_label'];?>
            </td>
            <td>
                <a target="_blank" href="<?php echo base_url();?>entitleddays/contract/<?php echo $balance['employee'];?>"><i class="mdi mdi-pencil-box-outline nolink"></i></a>
                <?php echo $balance['contract_label'];?>
            </td>
            <td><?php echo $startdate;?></td>
            <td><?php echo $balance['status_label'];?></td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <?php }?>
  </div><!-- Leave Balance //-->

  <div class="tab-pane" id="overlapping">
    <p><?php echo lang('admin_diagnostic_overlapping_description');?></a></p>

    <?php if ($overlappingLeaves_count==0) {?>
    <p><b><?php echo lang('admin_diagnostic_no_error');?></b></p>
    <?php } else {?>
    <table class="table table-bordered table-hover table-condensed">
      <thead>
        <tr>
            <th><?php echo lang('admin_diagnostic_overlapping_thead_id');?></th>
            <th><?php echo lang('admin_diagnostic_overlapping_thead_employee');?></th>
            <th><?php echo lang('admin_diagnostic_overlapping_thead_contract');?></th>
            <th><?php echo lang('admin_diagnostic_overlapping_thead_start_date');?></th>
            <th><?php echo lang('admin_diagnostic_overlapping_thead_end_date');?></th>
            <th><?php echo lang('admin_diagnostic_overlapping_thead_status');?></th>
        </tr>
      </thead>
      <tbody>
    <?php foreach ($overlappingLeaves as $leave):
        $date = new DateTime($leave['startdate']);
        $startdate = $date->format(lang('global_date_format'));
        $date = new DateTime($leave['enddate']);
        $enddate = $date->format(lang('global_date_format'));?>
        <tr>
            <td><a target="_blank" href="<?php echo base_url();?>leaves/edit/<?php echo $leave['id'];?>"><?php echo $leave['id'];?></a></td>
            <td><?php echo $leave['user_label'];?></td>
            <td>
                <a target="_blank" href="<?php echo base_url();?>contracts/edit/<?php echo $leave['contract_id'];?>"><?php echo $leave['contract_label'];?></a>
            </td>
            <td><?php echo $startdate;?></td>
            <td><?php echo $enddate;?></td>            
            <td><?php echo $leave['status_label'];?></td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <?php }?>
  </div><!-- Overlapping Leave Requests //-->
  
  <?php if ($this->config->item('disable_telework') == FALSE) {?>
      <div class="tab-pane" id="teleworkrequests">
    
            <p><?php echo lang('admin_diagnostic_teleworks_requests_description');?></p>
    
            <?php if ($duplicatedTeleworks_count==0) {?>
            <p><b><?php echo lang('admin_diagnostic_no_error');?></b></p>
            <?php } else {?>
            <table class="table table-bordered table-hover table-condensed">
              <thead>
                <tr>
                    <th><?php echo lang('admin_diagnostic_teleworks_requests_thead_id');?></th>
                    <th><?php echo lang('admin_diagnostic_teleworks_requests_thead_employee');?></th>
                    <th><?php echo lang('admin_diagnostic_teleworks_requests_thead_start_date');?></th>
                    <th><?php echo lang('admin_diagnostic_teleworks_requests_thead_type');?></th>
                </tr>
              </thead>
              <tbody>
            <?php foreach ($duplicatedTeleworks as $telework):
            $date = new DateTime($telework['startdate']);
                $startdate = $date->format(lang('global_date_format'));?>
                <tr>
                    <td><a target="_blank" href="<?php echo base_url();?>teleworks/edit/<?php echo $telework['id'];?>"><?php echo $telework['id'];?></a></td>
                    <td><?php echo $telework['user_label'];?></td>
                    <td><?php echo $startdate;?></td>
                    <td><?php echo $telework['type_label'];?></td>
                </tr>
                <?php endforeach ?>
              </tbody>
            </table>
            <?php }?>
      </div>
      
      <div class="tab-pane" id="teleworkdatetypes">
    
            <p><?php echo lang('admin_diagnostic_teleworks_datetype_description');?></p>
    
            <?php if ($wrongDateTypeTeleworks_count==0) {?>
            <p><b><?php echo lang('admin_diagnostic_no_error');?></b></p>
            <?php } else {?>
            <table class="table table-bordered table-hover table-condensed">
              <thead>
                <tr>
                    <th><?php echo lang('admin_diagnostic_teleworks_datetype_thead_id');?></th>
                    <th><?php echo lang('admin_diagnostic_teleworks_datetype_thead_employee');?></th>
                    <th><?php echo lang('admin_diagnostic_teleworks_datetype_thead_start_date');?></th>
                    <th><?php echo lang('admin_diagnostic_teleworks_datetype_thead_start_type');?></th>
                    <th><?php echo lang('admin_diagnostic_teleworks_datetype_thead_end_type');?></th>
                    <th><?php echo lang('admin_diagnostic_teleworks_datetype_thead_status');?></th>
                </tr>
              </thead>
              <tbody>
            <?php foreach ($wrongDateTypeTeleworks as $dateTypeTelework):
            $date = new DateTime($dateTypeTelework['startdate']);
                $startdate = $date->format(lang('global_date_format'));?>
                <tr>
                    <td><a target="_blank" href="<?php echo base_url();?>teleworks/edit/<?php echo $dateTypeTelework['id'];?>"><?php echo $dateTypeTelework['id'];?></a></td>
                    <td><?php echo $dateTypeTelework['user_label'];?></td>
                    <td><?php echo $startdate;?></td>
                    <td><?php echo lang($dateTypeTelework['startdatetype']);?></td>
                    <td><?php echo lang($dateTypeTelework['enddatetype']);?></td>
                    <td><?php echo lang($dateTypeTelework['status_label']);?></td>
                </tr>
                <?php endforeach ?>
              </tbody>
            </table>
            <?php }?>
      </div>
      
      <div class="tab-pane" id="teleworkoverlapping">
        <p><?php echo lang('admin_diagnostic_teleworks_overlapping_description');?></a></p>
    
        <?php if ($overlappingTeleworks_count==0) {?>
        <p><b><?php echo lang('admin_diagnostic_no_error');?></b></p>
        <?php } else {?>
        <table class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
                <th><?php echo lang('admin_diagnostic_teleworks_overlapping_thead_id');?></th>
                <th><?php echo lang('admin_diagnostic_teleworks_overlapping_thead_employee');?></th>
                <th><?php echo lang('admin_diagnostic_teleworks_overlapping_thead_contract');?></th>
                <th><?php echo lang('admin_diagnostic_teleworks_overlapping_thead_start_date');?></th>
                <th><?php echo lang('admin_diagnostic_teleworks_overlapping_thead_end_date');?></th>
                <th><?php echo lang('admin_diagnostic_teleworks_overlapping_thead_status');?></th>
            </tr>
          </thead>
          <tbody>
        <?php foreach ($overlappingTeleworks as $telework):
        $date = new DateTime($telework['startdate']);
            $startdate = $date->format(lang('global_date_format'));
            $date = new DateTime($telework['enddate']);
            $enddate = $date->format(lang('global_date_format'));?>
            <tr>
                <td><a target="_blank" href="<?php echo base_url();?>teleworks/edit/<?php echo $telework['id'];?>"><?php echo $telework['id'];?></a></td>
                <td><?php echo $telework['user_label'];?></td>
                <td>
                    <a target="_blank" href="<?php echo base_url();?>contracts/edit/<?php echo $telework['contract_id'];?>"><?php echo $telework['contract_label'];?></a>
                </td>
                <td><?php echo $startdate;?></td>
                <td><?php echo $enddate;?></td>            
                <td><?php echo $telework['status_label'];?></td>
            </tr>
            <?php endforeach ?>
          </tbody>
        </table>
        <?php }?>
      </div><!-- Overlapping Telework Requests //-->
  <?php }?>
  

</div>
    </div>
</div>

<script type="text/javascript">
$(function () {
    //open a tab if a hash was passed by URL
    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
    }

    //Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

    //Check if we are using the latest version
    $.get( "https://jorani.org/version.php", function( data ) {
      comparison = versionCompare('<?php echo $GLOBALS['versionOfJorani'];?>', data);
      $("#checkVersionLoading").remove();
      if (comparison < 0) {
        $("#checkVersion").html('<?php echo lang('global_msg_new_version_available');?>');
      }
      if (comparison > 0) {
        $("#checkVersion").html('<?php echo lang('global_msg_your_version_is_dev');?>');
      }
      if (comparison == 0) {
        $("#checkVersion").html('<?php echo lang('global_msg_your_version_is_up_to_date');?>');
      }
    });
});

/**
 * Compares two software version numbers (e.g. "1.7.1" or "1.2b").
 *
 * This function was born in http://stackoverflow.com/a/6832721.
 *
 * @param {string} v1 The first version to be compared.
 * @param {string} v2 The second version to be compared.
 * @param {object} [options] Optional flags that affect comparison behavior:
 * <ul>
 *     <li>
 *         <tt>lexicographical: true</tt> compares each part of the version strings lexicographically instead of
 *         naturally; this allows suffixes such as "b" or "dev" but will cause "1.10" to be considered smaller than
 *         "1.2".
 *     </li>
 *     <li>
 *         <tt>zeroExtend: true</tt> changes the result if one version string has less parts than the other. In
 *         this case the shorter string will be padded with "zero" parts instead of being considered smaller.
 *     </li>
 * </ul>
 * @returns {number|NaN}
 * <ul>
 *    <li>0 if the versions are equal</li>
 *    <li>a negative integer iff v1 < v2</li>
 *    <li>a positive integer iff v1 > v2</li>
 *    <li>NaN if either version string is in the wrong format</li>
 * </ul>
 *
 * @copyright by Jon Papaioannou (["john", "papaioannou"].join(".") + "@gmail.com")
 * @license This function is in the public domain. Do what you want with it, no strings attached.
 */
function versionCompare(v1, v2, options) {
    var lexicographical = options && options.lexicographical,
        zeroExtend = options && options.zeroExtend,
        v1parts = v1.split('.'),
        v2parts = v2.split('.');

    function isValidPart(x) {
        return (lexicographical ? /^\d+[A-Za-z]*$/ : /^\d+$/).test(x);
    }

    if (!v1parts.every(isValidPart) || !v2parts.every(isValidPart)) {
        return NaN;
    }

    if (zeroExtend) {
        while (v1parts.length < v2parts.length) v1parts.push("0");
        while (v2parts.length < v1parts.length) v2parts.push("0");
    }

    if (!lexicographical) {
        v1parts = v1parts.map(Number);
        v2parts = v2parts.map(Number);
    }

    for (var i = 0; i < v1parts.length; ++i) {
        if (v2parts.length == i) {
            return 1;
        }

        if (v1parts[i] == v2parts[i]) {
            continue;
        }
        else if (v1parts[i] > v2parts[i]) {
            return 1;
        }
        else {
            return -1;
        }
    }

    if (v1parts.length != v2parts.length) {
        return -1;
    }

    return 0;
}
</script>
