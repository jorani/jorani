<?php
/**
 * This view lists the list telework requests created by an employee
 * (from HR menu).
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.2.0
 */
?>

<h2><?php echo lang('teleworkrequests_export_title');?>&nbsp;<?php echo $user_id; ?>&nbsp;<span class="muted">(<?php echo $name ?>)</span></h2>

<?php echo $flash_partial_view;?>

<div class="row">
	<div class="span3">
        <?php echo lang('teleworks_index_thead_type');?>
        <select name="cboTeleworkType" id="cboTeleworkType">
            <option value="" selected></option>
        	<option value="Campaign"><?php echo lang('Campaign'); ?></option>
            <option value="Floating"><?php echo lang('Floating'); ?></option>
        </select>&nbsp;&nbsp;
    </div>
    <div class="span1">&nbsp;</div>
    <div class="span8">
    <span class="label"><input type="checkbox" checked id="chkPlanned" class="filterStatus"> &nbsp;<?php echo lang('Planned');?></span> &nbsp;
    <span class="label label-info"><input type="checkbox" checked id="chkAccepted" class="filterStatus"> &nbsp;<?php echo lang('Accepted');?></span> &nbsp;
    <span class="label label-warning"><input type="checkbox" checked id="chkRequested" class="filterStatus"> &nbsp;<?php echo lang('Requested');?></span> &nbsp;
    <span class="label label-important" style="background-color: #ff0000;"><input type="checkbox" checked id="chkRejected" class="filterStatus"> &nbsp;<?php echo lang('Rejected');?></span> &nbsp;
    <span class="label label-important" style="background-color: #ff0000;"><input type="checkbox" checked id="chkCancellation" class="filterStatus"> &nbsp;<?php echo lang('Cancellation');?></span> &nbsp;
    <span class="label label-important" style="background-color: #ff0000;"><input type="checkbox" checked id="chkCanceled" class="filterStatus"> &nbsp;<?php echo lang('Canceled');?></span>
    </div>
</div>
<br />
<div class="row">
	<div class="span6">
        <?php echo lang('teleworks_index_thead_campaign');?>
        <select name="cboTeleworkCampaign" id="cboTeleworkCampaign">
            <option value="" selected></option>
        	<?php foreach ($campaigns as $campaign): ?>
            <option value="<?php echo $campaign['name']; ?>"><?php echo $campaign['name']; ?></option>
        	<?php endforeach ?>
        </select>&nbsp;&nbsp;
    </div>
</div>
<br />
<table cellpadding="0" cellspacing="0" border="0" class="display" id="teleworks" width="100%">
    <thead>
        <tr>
            <th><?php echo lang('hr_teleworks_thead_id');?></th>
            <th><?php echo lang('hr_teleworks_thead_status');?></th>
            <th><?php echo lang('hr_teleworks_thead_start');?></th>
            <th><?php echo lang('hr_teleworks_thead_end');?></th>
            <th><?php echo lang('hr_teleworks_thead_duration');?></th>
            <th><?php echo lang('hr_teleworks_thead_type');?></th>
            <th><?php echo lang('hr_teleworks_thead_campaign');?></th>
																	  
        </tr>
    </thead>
    <tbody>
<?php foreach ($teleworks as $telework):
    $date = new DateTime($telework['startdate']);
    $tmpStartDate = $date->getTimestamp();
    $startdate = $date->format(lang('global_date_format'));
    $date = new DateTime($telework['enddate']);
    $tmpEndDate = $date->getTimestamp();
    $enddate = $date->format(lang('global_date_format'));
    $showReminder = FALSE;
    if (($telework['status'] == LMS_REQUESTED) || ($telework['status'] == LMS_CANCELLATION)) {
        $showReminder = TRUE;
    }
    ?>
    <tr>
        <td data-order="<?php echo $telework['id']; ?>">
            <a href="<?php echo base_url();?>teleworks/edit/<?php echo $telework['id']; ?>?source=teleworkrequests%2Fteleworks%2Fall%2F<?php echo $user_id; ?>" title="<?php echo lang('hr_teleworks_thead_tip_edit');?>"><?php echo $telework['id'] ?></a>
            <div class="pull-right">
                <?php if ($showReminder == TRUE) { ?>
                    <a href="<?php echo base_url();?>teleworks/reminder/<?php echo $telework['id']; ?>?source=teleworkrequests%2Fteleworks%2Fall%2F<?php echo $user_id; ?>" title="<?php echo lang('teleworks_button_send_reminder');?>"><i class="mdi mdi-mail nolink"></i></a>
                    &nbsp;
                <?php } ?>
                &nbsp;
                <a href="<?php echo base_url();?>teleworkrequests/accept/<?php echo $telework['id']; ?>?source=teleworkrequests%2Fteleworks%2Fall%2F<?php echo $user_id; ?>" title="<?php echo lang('hr_teleworks_thead_tip_accept');?>"><i class="mdi mdi-check nolink"></i></a>
                &nbsp;
                <a href="<?php echo base_url();?>teleworkrequests/reject/<?php echo $telework['id']; ?>?source=teleworkrequests%2Fteleworks%2Fall%2F<?php echo $user_id; ?>" title="<?php echo lang('hr_teleworks_thead_tip_reject');?>"><i class="mdi mdi-close nolink"></i></a>
                <?php if ($this->config->item('enable_teleworks_history') === TRUE) { ?>
                &nbsp;
                <a href="<?php echo base_url();?>teleworks/teleworks/<?php echo $telework['id']; ?>" title="<?php echo lang('teleworkrequests_index_thead_tip_view');?>"><i class="mdi mdi-eye nolink"></i></a>
              	&nbsp;
                <a href="#" class="show-history" data-id="<?php echo $telework['id'];?>" title="<?php echo lang('teleworks_index_thead_tip_history');?>"><i class="mdi mdi-history nolink"></i></a>
                <?php } ?>
            </div>
        </td>
        <?php
        switch ($telework['status']) {
            case 1: echo "<td><span class='label'>" . lang($telework['status_name']) . "</span></td>"; break;
            case 2: echo "<td><span class='label label-warning'>" . lang($telework['status_name']) . "</span></td>"; break;
            case 3: echo "<td><span class='label label-info'>" . lang($telework['status_name']) . "</span></td>"; break;
            default: echo "<td><span class='label label-important' style='background-color: #ff0000;'>" . lang($telework['status_name']) . "</span></td>"; break;
        }?>
        <td data-order="<?php echo $tmpStartDate; ?>"><?php echo $startdate . ' (' . lang($telework['startdatetype']). ')'; ?></td>
        <td data-order="<?php echo $tmpEndDate; ?>"><?php echo $enddate . ' (' . lang($telework['enddatetype']) . ')'; ?></td>
        <td><?php echo $telework['duration']; ?></td>
        <td><?php echo lang($telework['type']); ?></td>
        <td><?php echo $telework['campaign_name']; ?></td>
														  
    </tr>
<?php endforeach ?>
	</tbody>
</table>							  

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div class="row-fluid">
    <div class="span12">
        <a href="<?php echo base_url();?>teleworkrequests/exportall/<?php echo $filter . "/" . $user_id; ?>" class="btn btn-primary"><i class="mdi mdi-download"></i>&nbsp; <?php echo lang('teleworkrequests_index_button_export');?></a>
        &nbsp;&nbsp;
        <a href="<?php echo base_url();?>teleworkrequests/teleworks/all/<?php echo $user_id; ?>" class="btn btn-primary"><i class="mdi mdi-filter-remove"></i>&nbsp; <?php echo lang('teleworkrequests_index_button_show_all');?></a>
        &nbsp;&nbsp;
        <a href="<?php echo base_url();?>teleworkrequests/teleworks/requested/<?php echo $user_id; ?>" class="btn btn-primary"><i class="mdi mdi-filter"></i>&nbsp; <?php echo lang('teleworkrequests_index_button_show_pending');?></a>
        &nbsp;&nbsp;
        <?php if ($this->config->item('ics_enabled') == TRUE) {?>
        <a id="lnkICS" href="#"><i class="mdi mdi-earth nolink"></i> ICS</a>
        <?php }?>
    </div>
</div>

<div id="frmShowHistory" class="modal hide fade">
    <div class="modal-body" id="frmShowHistoryBody">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="$('#frmShowHistory').modal('hide');" class="btn"><?php echo lang('OK');?></a>
    </div>
</div>

<link href="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>

<script type="text/javascript">
var teleworkTable = null;

//Apply a filter on the status column
function filterStatusColumn() {
    var filter = "^(";
    if ($('#chkPlanned').prop('checked')) filter += "<?php echo lang('Planned');?>|";
    if ($('#chkAccepted').prop('checked')) filter += "<?php echo lang('Accepted');?>|";
    if ($('#chkRequested').prop('checked')) filter += "<?php echo lang('Requested');?>|";
    if ($('#chkRejected').prop('checked')) filter += "<?php echo lang('Rejected');?>|";
    if ($('#chkCancellation').prop('checked')) filter += "<?php echo lang('Cancellation');?>|";
    if ($('#chkCanceled').prop('checked')) filter += "<?php echo lang('Canceled');?>|";
    filter = filter.slice(0,-1) + ")$";
    if (filter.indexOf('(') == -1) filter = 'nothing is selected';
    teleworkTable.columns( 1 ).search( filter, true, false ).draw();
}

$(function () {
    //Transform the HTML table in a fancy datatable
    teleworkTable = $('#teleworks').DataTable({
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

    <?php if ($this->config->item('enable_teleworks_history') === TRUE) { ?>
    //Prevent to load always the same content (refreshed each time)
    $('#frmShowHistory').on('hidden', function() {
        $("#frmShowHistoryBody").html('<img src="<?php echo base_url();?>assets/images/loading.gif">');
    });

    //Popup show history
    $("#teleworks tbody").on('click', '.show-history',  function(){
        $("#frmShowHistory").modal('show');
        $("#frmShowHistoryBody").load('<?php echo base_url();?>teleworks/' + $(this).data('id') +'/history', function(response, status, xhr) {
            if (xhr.status == 401) {
                $("#frmShowHistory").modal('hide');
                bootbox.alert("<?php echo lang('global_ajax_timeout');?>", function() {
                    //After the login page, we'll be redirected to the current page
                   location.reload();
                });
            }
          });
    });
    <?php } ?>

    //Detect change of filter check boxes
    $('.filterStatus').on('change',function(){
        filterStatusColumn();
    });
    
    //Dynamic filter on telework type
    $('#cboTeleworkType').on('change',function(){
        var teleworkType = $("#cboTeleworkType option:selected").text();
        if (teleworkType != '') {
            teleworkTable.columns( 5 ).search( "^" + teleworkType + "$", true, false ).draw();
        } else {
            teleworkTable.columns( 5 ).search( "", true, false ).draw();
        }
    });
    
    //Dynamic filter on telework campaign
    $('#cboTeleworkCampaign').on('change',function(){
        var teleworkCampaign = $("#cboTeleworkCampaign option:selected").text();
        if (teleworkCampaign != '') {
            teleworkTable.columns( 6 ).search( "^" + teleworkCampaign + "$", true, false ).draw();
        } else {
            teleworkTable.columns( 6 ).search( "", true, false ).draw();
        }
    });
});
</script>