<?php
/**
 * This view displays the list of telework requests submitted to a manager by an employee
 * (from HR menu).
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.2.0
 */
?>

<h2><?php echo lang('campaign_teleworks_index_html_title');?>&nbsp;<?php echo $user_id; ?>&nbsp;<span class="muted">(<?php echo $name ?>)</span></h2>

<?php echo $flash_partial_view;?>

<div class="row">
	<div class="span4">
        <?php echo lang('teleworks_index_thead_campaign');?>
        <select name="cboTeleworkCampaign" id="cboTeleworkCampaign">
            <option value="" selected></option>
        <?php foreach ($campaigns as $campaign): ?>
            <option value="<?php echo $campaign['name']; ?>"><?php echo $campaign['name']; ?></option>
        <?php endforeach ?>
        </select>&nbsp;&nbsp;
    </div>
    <div class="span1">&nbsp;</div>
    <div class="span7">
    <span class="label"><input type="checkbox" checked id="chkPlanned" class="filterStatus"> &nbsp;<?php echo lang('Planned');?></span> &nbsp;
    <span class="label label-info"><input type="checkbox" checked id="chkAccepted" class="filterStatus"> &nbsp;<?php echo lang('Accepted');?></span> &nbsp;
    <span class="label label-warning"><input type="checkbox" checked id="chkRequested" class="filterStatus"> &nbsp;<?php echo lang('Requested');?></span> &nbsp;
    <span class="label label-important" style="background-color: #ff0000;"><input type="checkbox" checked id="chkRejected" class="filterStatus"> &nbsp;<?php echo lang('Rejected');?></span> &nbsp;
    <span class="label label-important" style="background-color: #ff0000;"><input type="checkbox" checked id="chkCancellation" class="filterStatus"> &nbsp;<?php echo lang('Cancellation');?></span> &nbsp;
    <span class="label label-important" style="background-color: #ff0000;"><input type="checkbox" checked id="chkCanceled" class="filterStatus"> &nbsp;<?php echo lang('Canceled');?></span>
    </div>
</div>
<br />
<table cellpadding="0" cellspacing="0" border="0" class="display" id="teleworks" width="100%">
    <thead>
        <tr>
            <th><?php echo lang('teleworks_index_thead_id');?></th>            
            <th><?php echo lang('teleworks_index_thead_start_date');?></th>
            <th><?php echo lang('teleworks_index_thead_end_date');?></th>
            <th><?php echo lang('teleworks_index_thead_duration');?></th>
            <th><?php echo lang('teleworks_index_thead_campaign');?></th>
            <th><?php echo lang('teleworks_index_thead_status');?></th>
        <?php if ($this->config->item('enable_teleworks_history') == TRUE){?>
            <th><?php echo lang('teleworkrequests_index_thead_requested_date');?></th>
            <th><?php echo lang('teleworkrequests_index_thead_last_change');?></th>
        <?php } ?>
																	  
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
    if ($this->config->item('enable_teleworks_history') == TRUE){
        if($telework['request_date'] == NULL){
            $tmpRequestDate = "";
            $teleworkdate = "";
        }else{
            $datetimeRequested = new DateTime($telework['request_date']);
            $tmpRequestDate = $datetimeRequested->getTimestamp();
            $teleworkdate = $datetimeRequested->format(lang('global_date_format'));
        }
        if($telework['change_date'] == NULL){
            $tmpLastChangeDate = "";
            $lastchangedate = "";
        }else{
            $datetimelastChanged = new DateTime($telework['change_date']);
            $tmpLastChangeDate = $datetimelastChanged->getTimestamp();
            $lastchangedate = $datetimelastChanged->format(lang('global_date_format'));
        }
    }
    ?>
    <tr>
        <td data-order="<?php echo $telework['id']; ?>">
            <a href="<?php echo base_url();?>teleworks/edit/<?php echo $telework['id']; ?>?source=teleworkrequests%2Fcampaignteleworks%2F<?php echo $user_id; ?>" title="<?php echo lang('teleworks_index_thead_tip_edit');?>"><?php echo $telework['id'] ?></a>
        	&nbsp;
            <div class="pull-right">
              <?php if ($telework['status'] == LMS_CANCELLATION) { ?>
              <a href="#" class="lnkCancellationAccept" data-id="<?php echo $telework['id']; ?>" title="<?php echo lang('teleworkrequests_index_thead_tip_accept');?>"><i class="mdi mdi-check nolink"></i></a>
              &nbsp;
              <a href="#" class="lnkCancellationReject" data-id="<?php echo $telework['id']; ?>" title="<?php echo lang('teleworkrequests_index_thead_tip_reject');?>"><i class="mdi mdi-close nolink"></i></a>
              <?php } ?>
              <?php if ($this->config->item('enable_teleworks_history') === TRUE) { ?>
              &nbsp;
              <a href="<?php echo base_url();?>teleworks/teleworks/<?php echo $telework['id']; ?>" title="<?php echo lang('teleworkrequests_index_thead_tip_view');?>"><i class="mdi mdi-eye nolink"></i></a>
              &nbsp;
              <a href="#" class="show-history" data-id="<?php echo $telework['id'];?>" title="<?php echo lang('teleworkrequests_index_thead_tip_history');?>"><i class="mdi mdi-history nolink"></i></a>
              <?php } ?>
            </div>
        </td>        
        <td data-order="<?php echo $tmpStartDate; ?>"><?php echo $startdate . ' (' . lang($telework['startdatetype']). ')'; ?></td>
        <td data-order="<?php echo $tmpEndDate; ?>"><?php echo $enddate . ' (' . lang($telework['enddatetype']) . ')'; ?></td>
        <td><?php echo $telework['duration']; ?></td>
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
          echo "<td data-order='".$tmpRequestDate."'>" . $teleworkdate . "</td>";
          echo "<td data-order='".$tmpLastChangeDate."'>" . $lastchangedate . "</td>";
        }
        ?>
														  
    </tr>
<?php endforeach ?>
	</tbody>
</table>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div id="frmShowHistory" class="modal hide fade">
    <div class="modal-body" id="frmShowHistoryBody">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="$('#frmShowHistory').modal('hide');" class="btn"><?php echo lang('OK');?></a>
    </div>
</div>

<div id="sendComment">
  <?php
    echo form_open("teleworkrequests/", array('id' => 'frmRejectTeleworkForm'))
  ?>
  <input id="comment" type="hidden" name="comment" value="">
</form>
</div>

<div class="row-fluid">
    <div class="span12">
        <a href="<?php echo base_url();?>teleworkrequests/exportforcampaign/<?php echo $filter; ?>/<?php echo $user_id; ?>" class="btn btn-primary"><i class="mdi mdi-download"></i>&nbsp; <?php echo lang('teleworkrequests_index_button_export');?></a>
        &nbsp;&nbsp;
        <a href="<?php echo base_url();?>teleworkrequests/campaignteleworks/all/<?php echo $user_id; ?>" class="btn btn-primary"><i class="mdi mdi-filter-remove"></i>&nbsp; <?php echo lang('teleworkrequests_index_button_show_all');?></a>
        &nbsp;&nbsp;
        <a href="<?php echo base_url();?>teleworkrequests/campaignteleworks/requested/<?php echo $user_id; ?>" class="btn btn-primary"><i class="mdi mdi-filter"></i>&nbsp; <?php echo lang('teleworkrequests_index_button_show_pending');?></a>
    	&nbsp;&nbsp;
    	<a href="<?php echo base_url();?>teleworkrequests/rejectall/<?php echo $user_id; ?>?source=requests%2Fcollaborators" class="btn btn-primary"><i class="mdi mdi-playlist-remove"></i>&nbsp;<?php echo lang('teleworkrequests_collaborators_thead_link_reject_all');?></a>
		&nbsp;&nbsp;
		<a href="<?php echo base_url();?>teleworkrequests/acceptall/<?php echo $user_id; ?>?source=requests%2Fcollaborators" class="btn btn-primary"><i class="mdi mdi-playlist-check"></i>&nbsp;<?php echo lang('teleworkrequests_collaborators_thead_link_accept_all');?></a>
    </div>
</div>

<link href="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>

<script type="text/javascript">
var clicked = false;
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
    teleworkTable.columns( 5 ).search( filter, true, false ).draw();
}

$(document).ready(function() {

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
        
    $('#teleworks').on('click', '.lnkCancellationAccept', function (event) {
        event.preventDefault();
        if (!clicked) {
            clicked = true;
            window.location.href = "<?php echo base_url();?>teleworkrequests/cancellation/accept/" + $(this).data("id");
        }
     });
     $("#teleworks").on('click', '.lnkCancellationReject', function (event) {
        event.preventDefault();
        if (!clicked) {
            clicked = true;
            var id = $(this).data("id");
            var validateUrl = "<?php echo base_url();?>teleworkrequests/cancellation/reject/" + $(this).data("id");
            bootbox.prompt('<?php echo (($this->config->item('mandatory_comment_on_reject') === TRUE)?'<i class="mdi mdi-alert"></i>&nbsp;':'') .
                    lang('teleworkrequests_comment_reject_request_title');?>',
              '<?php echo lang('teleworkrequests_comment_reject_request_button_cancel');?>',
              '<?php echo lang('teleworkrequests_comment_reject_request_button_reject');?>',
              function (result) {
                if (result !== null){
                    <?php if ($this->config->item('mandatory_comment_on_reject') === TRUE) { ?>
                    if (result === "") return false;
                    <?php } ?>
                  $("#sendComment #frmRejectTeleworkForm").attr("action", validateUrl);
                  $("#sendComment #frmRejectTeleworkForm input#comment").attr("value", result);
                  $("#sendComment #frmRejectTeleworkForm").submit();

                } else {
                  clicked = false;
                }
              });
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
        $("#frmShowHistoryBody").load('<?php echo base_url();?>teleworks/' + $(this).data('id') +'/history');
    });
    <?php } ?>
    
    var cancelRejected = getURLParameter('cancel_rejected');
    var idCancel = parseInt(cancelRejected)
    if (idCancel != null && !isNaN(idCancel)) {
      var validateUrl = "<?php echo base_url();?>teleworkrequests/cancellation/reject/" + idCancel;
      bootbox.prompt('<?php echo lang('teleworkrequests_comment_reject_request_title');?>',
        '<?php echo lang('teleworkrequests_comment_reject_request_button_cancel');?>',
        '<?php echo lang('teleworkrequests_comment_reject_request_button_reject');?>',
      function (result) {
        if (result !== null){
            <?php if ($this->config->item('mandatory_comment_on_reject') === TRUE) { ?>
            if (result === "") return false;
            <?php } ?>
          $("#sendComment #frmRejectTeleworkForm").attr("action", validateUrl);
          $("#sendComment #frmRejectTeleworkForm input#comment").attr("value", result);
          $("#sendComment #frmRejectTeleworkForm").submit();
        }
      });
    }

    $('#cboTeleworkCampaign').on('change',function(){
        var teleworkCampaign = $("#cboTeleworkCampaign option:selected").text();
        if (teleworkCampaign != '') {
            teleworkTable.columns( 4 ).search( "^" + teleworkCampaign + "$", true, false ).draw();
        } else {
            teleworkTable.columns( 4 ).search( "", true, false ).draw();
        }
    });

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