<?php
/**
 * This view displays the list of telework requests created by an employee.
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>

<h2><?php echo lang('teleworks_index_title');?> &nbsp;<?php echo $help;?></h2>

<?php echo $flash_partial_view;?>

<div class="row">
	<div class="span3">
        <label for="cboTeleworkType">
            <?php echo lang('teleworks_index_thead_type');?>
            <select name="cboTeleworkType" id="cboTeleworkType">
                <option value="" selected></option>
                <option value="Campaign"><?php echo lang('Campaign'); ?></option>
                <option value="Floating"><?php echo lang('Floating'); ?></option>
            </select>
        </label>
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
            <th><?php echo lang('teleworks_index_thead_id');?></th>
            <th><?php echo lang('teleworks_index_thead_start_date');?></th>
            <th><?php echo lang('teleworks_index_thead_end_date');?></th>
            <th><?php echo lang('teleworks_index_thead_cause');?></th>
            <th><?php echo lang('teleworks_index_thead_duration');?></th>
            <th><?php echo lang('teleworks_index_thead_type');?></th>
            <th><?php echo lang('teleworks_index_thead_campaign');?></th>
            <th><?php echo lang('teleworks_index_thead_status');?></th>
            <?php
            if ($this->config->item('enable_teleworks_history') == TRUE){
              echo "<th>".lang('teleworks_index_thead_requested_date')."</th>";
              echo "<th>".lang('teleworks_index_thead_last_change')."</th>";
            }
            ?>
        </tr>
    </thead>
    <tbody>
<?php foreach ($teleworks as $telework):
    //echo $telework['startdate'];
    $datetimeStart = new DateTime($telework['startdate']);
    $tmpStartDate = $datetimeStart->getTimestamp();
    $startdate = $datetimeStart->format(lang('global_date_format'));
    $datetimeEnd = new DateTime($telework['enddate']);
    $tmpEndDate = $datetimeEnd->getTimestamp();
    $enddate = $datetimeEnd->format(lang('global_date_format'));
    if ($this->config->item('enable_teleworks_history') == TRUE){
      if($telework['request_date'] == NULL){
        $tmpRequestDate = "";
        $requestdate = "";
      }else{
        $datetimeRequested = new DateTime($telework['request_date']);
        $tmpRequestDate = $datetimeRequested->getTimestamp();
        $requestdate = $datetimeRequested->format(lang('global_date_format'));
      }
      if($telework['change_date'] == NULL){
        $tmpLastChangeDate = "";
        $lastchangedate = "";
      }else{
        $datetimelastChanged = new DateTime($telework['change_date']);
        $tmpLastChangeDate = $datetimelastChanged->getTimestamp();
        $lastchangedate = $datetimelastChanged->format(lang('global_date_format'));
      }
    }?>
    <tr>
        <td data-order="<?php echo $telework['id']; ?>">
            <a href="<?php echo base_url();?>teleworks/teleworks/<?php echo $telework['id']; ?>" title="<?php echo lang('teleworks_index_thead_tip_view');?>"><?php echo $telework['id']; ?></a>
            &nbsp;
            <div class="pull-right">
                <?php
                $showDelete = FALSE;
                $showCancel = FALSE;
                $showCancelByUser = FALSE;
                $showEdit = FALSE;
                $showReminder = FALSE;
                //Edit rules
                if (($telework['status'] == LMS_PLANNED)) {
                    $showEdit = TRUE;
                }
                if (($telework['status'] == LMS_REJECTED) &&
                        ($this->config->item('edit_rejected_requests') === TRUE)) {
                    $showEdit = TRUE;
                }
                //Cancellation rules
                if ($telework['status'] == LMS_ACCEPTED) {
                    $showCancel = TRUE;
                }
                //Delete rules
                if ($telework['status'] == LMS_PLANNED) {
                    $showDelete = TRUE;
                }
                if (($telework['status'] == LMS_REJECTED) &&
                        ($this->config->item('delete_rejected_requests') === TRUE)) {
                    $showDelete = TRUE;
                }
                //Reminder rules
                if (($telework['status'] == LMS_REQUESTED) ||
                        ($telework['status'] == LMS_CANCELLATION)) {
                    $showReminder = TRUE;
                }
                //Direct cancelation by the employee
                if (($telework['status'] == LMS_REQUESTED && $telework['type'] == 'Floating')) {
                    $showCancelByUser = TRUE;
                }
                ?>
                <?php if ($showEdit == TRUE) { ?>
                <a href="<?php echo base_url();?>teleworks/edit/<?php echo $telework['id']; ?>" title="<?php echo lang('teleworks_index_thead_tip_edit');?>"><i class="mdi mdi-pencil nolink"></i></a>
                &nbsp;
                <?php } ?>
                <?php if ($showDelete == TRUE) { ?>
                <a href="#" class="confirm-delete" data-id="<?php echo $telework['id'];?>" title="<?php echo lang('teleworks_index_thead_tip_delete');?>"><i class="mdi mdi-delete nolink"></i></a>
                &nbsp;
                <?php } ?>
                <?php if ($showCancel == TRUE) { ?>
                    <a href="<?php echo base_url();?>teleworks/cancellation/<?php echo $telework['id']; ?>" title="<?php echo lang('teleworks_index_thead_tip_cancel');?>"><i class="mdi mdi-undo nolink"></i></a>
                    &nbsp;
                <?php } ?>
                <?php if ($showCancelByUser == TRUE) { ?>
                    <a href="<?php echo base_url();?>teleworks/cancel/<?php echo $telework['id']; ?>" title="<?php echo lang('teleworks_index_thead_tip_cancel');?>"><i class="mdi mdi-undo nolink"></i></a>
                    &nbsp;
                <?php } ?>
                <?php if ($showReminder == TRUE) { ?>
                    <a href="<?php echo base_url();?>teleworks/reminder/<?php echo $telework['id']; ?>" title="<?php echo lang('teleworks_button_send_reminder');?>"><i class="mdi mdi-email nolink"></i></a>
                    &nbsp;
                <?php } ?>
                <a href="<?php echo base_url();?>teleworks/teleworks/<?php echo $telework['id']; ?>" title="<?php echo lang('teleworks_index_thead_tip_view');?>"><i class="mdi mdi-eye nolink"></i></a>
                <?php if ($this->config->item('enable_teleworks_history') === TRUE) { ?>
                &nbsp;
                <a href="#" class="show-history" data-id="<?php echo $telework['id'];?>" title="<?php echo lang('teleworks_index_thead_tip_history');?>"><i class="mdi mdi-history nolink"></i></a>
                <?php } ?>
            </div>
        </td>
        <td data-order="<?php echo $tmpStartDate; ?>"><?php echo $startdate . ' (' . lang($telework['startdatetype']). ')'; ?></td>
        <td data-order="<?php echo $tmpEndDate; ?>"><?php echo $enddate . ' (' . lang($telework['enddatetype']) . ')'; ?></td>
        <td><?php echo $telework['cause']; ?></td>
        <td><?php echo $telework['duration']; ?></td>
        <td><?php echo lang($telework['type']); ?></td>
        <td><?php echo $telework['campaign_name']; ?></td>
        <?php
        switch ($telework['status']) {
            case 1: echo "<td><span class='label'>" . lang($telework['status_name']) . "</span></td>"; break;
            case 2: echo "<td><span class='label label-warning'>" . lang($telework['status_name']) . "</span></td>"; break;
            case 3: echo "<td><span class='label label-info'>" . lang($telework['status_name']) . "</span></td>"; break;
            default: echo "<td><span class='label label-important' style='background-color: #ff0000;'>" . lang($telework['status_name']) . "</span></td>"; break;
        }?>
        <?php
        if ($this->config->item('enable_teleworks_history') == TRUE){
          echo "<td data-order='".$tmpRequestDate."'>" . $requestdate . "</td>";
          echo "<td data-order='".$tmpLastChangeDate."'>" . $lastchangedate . "</td>";
        }
        ?>
    </tr>
<?php endforeach ?>
    </tbody>
</table>


<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div class="row-fluid">
    <div class="span12">
      <a href="<?php echo base_url();?>teleworks/export" class="btn btn-primary"><i class="mdi mdi-download"></i>&nbsp; <?php echo lang('teleworks_index_button_export');?></a>
      &nbsp;&nbsp;
      <a href="<?php echo base_url();?>teleworks/create" class="btn btn-primary"><i class="mdi mdi-plus-circle"></i>&nbsp; <?php echo lang('teleworks_index_button_create');?></a>
      &nbsp;&nbsp;
        <?php if ($this->config->item('ics_enabled') == TRUE) {?>
        <a id="lnkICS" href="#"><i class="mdi mdi-earth nolink"></i> ICS</a>
        <?php }?>
    </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div id="frmDeleteTeleworkRequest" class="modal hide fade">
    <div class="modal-header">
        <a href="#" class="close">&times;</a>
         <h3><?php echo lang('teleworks_index_popup_delete_title');?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo lang('teleworks_index_popup_delete_message');?></p>
        <p><?php echo lang('teleworks_index_popup_delete_question');?></p>
    </div>
    <div class="modal-footer">
        <a href="#" id="lnkDeleteUser" class="btn btn-danger"><?php echo lang('teleworks_index_popup_delete_button_yes');?></a>
        <a href="#" onclick="$('#frmDeleteTeleworkRequest').modal('hide');" class="btn"><?php echo lang('teleworks_index_popup_delete_button_no');?></a>
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

<div id="frmLinkICS" class="modal hide fade">
    <div class="modal-header">
        <h3>ICS<a href="#" onclick="$('#frmLinkICS').modal('hide');" class="close">&times;</a></h3>
    </div>
    <div class="modal-body" id="frmSelectDelegateBody">
        <div class='input-append'>
        <?php $icsUrl = base_url() . 'ics/individual/' . $user_id . '?token=' . $this->session->userdata('random_hash');?>
            <input type="text" class="input-xlarge" id="txtIcsUrl" onfocus="this.select();" onmouseup="return false;"
                value="<?php echo $icsUrl;?>" />
                <button id="cmdCopy" class="btn" data-clipboard-text="<?php echo $icsUrl;?>">
                    <i class="mdi mdi-content-copy"></i>
                </button>
            <a href="#" id="tipCopied" data-toggle="tooltip" title="<?php echo lang('copied');?>" data-placement="right" data-container="#cmdCopy"></a>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#" onclick="$('#frmLinkICS').modal('hide');" class="btn btn-primary"><?php echo lang('OK');?></a>
    </div>
</div>

<link href="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>

<script type="text/javascript">
var teleworkTable = null;

//Return a URL parameter identified by 'name'
function getURLParameter(name) {
  return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [null, ''])[1].replace(/\+/g, '%20')) || null;
}

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
    teleworkTable.columns( 7 ).search( filter, true, false ).draw();
}

$(document).ready(function() {
    $('#frmDeleteTeleworkRequest').alert();

    //Transform the HTML table in a fancy datatable
    teleworkTable = $('#teleworks').DataTable({
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
    $('#frmDeleteTeleworkRequest').on('show', function() {
        var link = "<?php echo base_url();?>teleworks/delete/" + $(this).data('id');
        $("#lnkDeleteUser").attr('href', link);
    })

    //Display a modal pop-up so as to confirm if a telework request has to be deleted or not
    //We build a complex selector because datatable does horrible things on DOM...
    //a simplier selector doesn't work when the delete is on page >1
    $("#teleworks tbody").on('click', '.confirm-delete',  function(){
        var id = $(this).data('id');
        $('#frmDeleteTeleworkRequest').data('id', id).modal('show');
    });

    //Prevent to load always the same content (refreshed each time)
    $('#frmDeleteTeleworkRequest').on('hidden', function() {
        $(this).removeData('modal');
    });
    <?php if ($this->config->item('enable_teleworks_history') === TRUE) { ?>
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

    //Copy/Paste ICS Feed
    var client = new ClipboardJS("#cmdCopy");
    $('#lnkICS').click(function () {
        $("#frmLinkICS").modal('show');
    });
    client.on( "success", function() {
        $('#tipCopied').tooltip('show');
        setTimeout(function() {$('#tipCopied').tooltip('hide')}, 1000);
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

    //Analyze URL to get the filter on one type
    if (getURLParameter('type') != null) {
        var teleworkType = $("#cboTeleworkType option[value='" + getURLParameter('type') + "']").text();
        $("#cboTeleworkType option[value='" + getURLParameter('type') + "']").prop("selected", true);
        teleworkTable.columns( 5 ).search( "^" + teleworkType + "$", true, false ).draw();
    }

    //Filter on statuses is a list of inclusion
    var statuses = getURLParameter('statuses');
    if (statuses != null) {
        //Unselect all statuses and select only the statuses passed by URL
        $(".filterStatus").prop("checked", false);
        statuses.split(/\|/).forEach(function(status) {
            switch (status) {
                case '1': $("#chkPlanned").prop("checked", true); break;
                case '2': $("#chkRequested").prop("checked", true); break;
                case '3': $("#chkAccepted").prop("checked", true); break;
                case '4': $("#chkRejected").prop("checked", true); break;
                case '5': $("#chkCancellation").prop("checked", true); break;
                case '6': $("#chkCanceled").prop("checked", true); break;
            }
        });
        //$("#cboTeleworkType option[value='" + getURLParameter('type') + "']").prop("selected", true);
        filterStatusColumn();
    }
    $('.filterStatus').on('change',function(){
        filterStatusColumn();
    });
});
</script>
