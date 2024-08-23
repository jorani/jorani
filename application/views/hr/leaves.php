<?php
/**
 * This view lists the list leave requests created and deleted by an employee
 * (from HR menu).
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.2.0
 */
?>

<h2><?php echo lang('hr_leaves_html_title');?><?php echo $user_id; ?>&nbsp;<span class="muted">(<?php echo $name ?>)</span></h2>

<?php echo $flash_partial_view;?>

<div class="row">
    <div class="span3">
        <?php echo lang('leaves_index_thead_type');?>
        <select name="cboLeaveType" id="cboLeaveType">
            <option value="" selected></option>
        <?php foreach ($types as $type): ?>
            <option value="<?php echo $type['id']; ?>"><?php echo $type['name']; ?></option>
        <?php endforeach ?>
        </select>&nbsp;&nbsp;
    </div>
    <div class="span1">&nbsp;</div>
    <div class="span8">
    <span class="label"><input type="checkbox" checked id="chkPlanned" class="filterStatus"> &nbsp;<?php echo lang('Planned');?></span> &nbsp;
    <span class="label label-success"><input type="checkbox" checked id="chkAccepted" class="filterStatus"> &nbsp;<?php echo lang('Accepted');?></span> &nbsp;
    <span class="label label-warning"><input type="checkbox" checked id="chkRequested" class="filterStatus"> &nbsp;<?php echo lang('Requested');?></span> &nbsp;
    <span class="label label-important" style="background-color: #ff0000;"><input type="checkbox" checked id="chkRejected" class="filterStatus"> &nbsp;<?php echo lang('Rejected');?></span> &nbsp;
    <span class="label label-important" style="background-color: #ff0000;"><input type="checkbox" checked id="chkCancellation" class="filterStatus"> &nbsp;<?php echo lang('Cancellation');?></span> &nbsp;
    <span class="label label-important" style="background-color: #ff0000;"><input type="checkbox" checked id="chkCanceled" class="filterStatus"> &nbsp;<?php echo lang('Canceled');?></span>
    </div>
</div>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="leaves" width="100%">
    <thead>
        <tr>
            <th><?php echo lang('hr_leaves_thead_id');?></th>
            <th><?php echo lang('hr_leaves_thead_status');?></th>
            <th><?php echo lang('hr_leaves_thead_start');?></th>
            <th><?php echo lang('hr_leaves_thead_end');?></th>
            <th><?php echo lang('hr_leaves_thead_duration');?></th>
            <th><?php echo lang('hr_leaves_thead_type');?></th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($leaves as $leave):
    $date = new DateTime($leave['startdate']);
    $tmpStartDate = $date->getTimestamp();
    $startdate = $date->format(lang('global_date_format'));
    $date = new DateTime($leave['enddate']);
    $tmpEndDate = $date->getTimestamp();
    $enddate = $date->format(lang('global_date_format'));
    $showReminder = FALSE;
    if (($leave['status'] == LMS_REQUESTED) || ($leave['status'] == LMS_CANCELLATION)) {
        $showReminder = TRUE;
    }
    ?>
    <tr>
        <td data-order="<?php echo $leave['id']; ?>">
            <a href="<?php echo base_url();?>leaves/edit/<?php echo $leave['id']; ?>?source=hr%2Fleaves%2F<?php echo $user_id; ?>" title="<?php echo lang('hr_leaves_thead_tip_edit');?>"><?php echo $leave['id'] ?></a>
            <div class="pull-right">
                <?php if ($showReminder == TRUE) { ?>
                    <a href="<?php echo base_url();?>leaves/reminder/<?php echo $leave['id']; ?>?source=hr%2Fleaves%2F<?php echo $user_id; ?>" title="<?php echo lang('leaves_button_send_reminder');?>"><i class="mdi mdi-mail nolink"></i></a>
                    &nbsp;
                <?php } ?>
                &nbsp;
                <a href="<?php echo base_url();?>requests/accept/<?php echo $leave['id']; ?>?source=hr%2Fleaves%2F<?php echo $user_id; ?>" title="<?php echo lang('hr_leaves_thead_tip_accept');?>"><i class="mdi mdi-check nolink"></i></a>
                &nbsp;
                <a href="<?php echo base_url();?>requests/reject/<?php echo $leave['id']; ?>?source=hr%2Fleaves%2F<?php echo $user_id; ?>" title="<?php echo lang('hr_leaves_thead_tip_reject');?>"><i class="mdi mdi-close nolink"></i></a>
                &nbsp;
                <a href="#" class="confirm-delete" data-id="<?php echo $leave['id'];?>" title="<?php echo lang('hr_leaves_thead_tip_delete');?>"><i class="mdi mdi-delete nolink"></i></a>
                <?php if ($this->config->item('enable_history') === TRUE) { ?>
                &nbsp;
                <a href="#" class="show-history" data-id="<?php echo $leave['id'];?>" title="<?php echo lang('leaves_index_thead_tip_history');?>"><i class="mdi mdi-history nolink"></i></a>
                <?php } ?>
            </div>
        </td>
        <?php
        switch ($leave['status']) {
            case 1: echo "<td><span class='label'>" . lang($leave['status_name']) . "</span></td>"; break;
            case 2: echo "<td><span class='label label-warning'>" . lang($leave['status_name']) . "</span></td>"; break;
            case 3: echo "<td><span class='label label-success'>" . lang($leave['status_name']) . "</span></td>"; break;
            default: echo "<td><span class='label label-important' style='background-color: #ff0000;'>" . lang($leave['status_name']) . "</span></td>"; break;
        }?>
        <td data-order="<?php echo $tmpStartDate; ?>"><?php echo $startdate . ' (' . lang($leave['startdatetype']). ')'; ?></td>
        <td data-order="<?php echo $tmpEndDate; ?>"><?php echo $enddate . ' (' . lang($leave['enddatetype']) . ')'; ?></td>
        <td><?php echo $leave['duration']; ?></td>
        <td><?php echo $leave['type_name']; ?></td>
    </tr>
<?php endforeach ?>
	</tbody>
</table>

<br />

<a href="<?php echo base_url();?>hr/leaves/export/<?php echo $user_id; ?>" class="btn btn-primary"><i class="mdi mdi-download"></i>&nbsp;<?php echo lang('hr_leaves_button_export');?></a>
&nbsp;&nbsp;
<a href="<?php echo base_url();?>hr/employees" class="btn btn-primary"><i class="mdi mdi-arrow-left-bold"></i>&nbsp;<?php echo lang('hr_leaves_button_list');?></a>

<br />

<?php if ($this->config->item('enable_history') === TRUE) { ?>

<h2><?php echo lang('hr_leaves_deleted_title');?></h2>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="deleted_leaves" width="100%">
    <thead>
        <tr>
            <th><?php echo lang('hr_leaves_thead_id');?></th>
            <th><?php echo lang('hr_leaves_thead_status');?></th>
            <th><?php echo lang('hr_leaves_thead_start');?></th>
            <th><?php echo lang('hr_leaves_thead_end');?></th>
            <th><?php echo lang('hr_leaves_thead_duration');?></th>
            <th><?php echo lang('hr_leaves_thead_type');?></th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($deletedLeaves as $leave):
    $date = new DateTime($leave['startdate']);
    $tmpStartDate = $date->getTimestamp();
    $startdate = $date->format(lang('global_date_format'));
    $date = new DateTime($leave['enddate']);
    $tmpEndDate = $date->getTimestamp();
    $enddate = $date->format(lang('global_date_format'));?>
    <tr>
        <td data-order="<?php echo $leave['id']; ?>">
            <?php echo $leave['id'];?>
            <div class="pull-right">
                &nbsp;
                <a href="#" class="show-history" data-id="<?php echo $leave['id'];?>" title="<?php echo lang('hr_leaves_thead_tip_history');?>"><i class="mdi mdi-history nolink"></i></a>
            </div>
        </td>
        <td><?php echo lang($leave['status_name']); ?></td>
        <td data-order="<?php echo $tmpStartDate; ?>"><?php echo $startdate . ' (' . lang($leave['startdatetype']). ')'; ?></td>
        <td data-order="<?php echo $tmpEndDate; ?>"><?php echo $enddate . ' (' . lang($leave['enddatetype']) . ')'; ?></td>
        <td><?php echo $leave['duration']; ?></td>
        <td><?php echo $leave['type_name']; ?></td>
    </tr>
<?php endforeach ?>
        </tbody>
</table>

<br />

<?php } ?>

<div id="frmDeleteLeaveRequest" class="modal hide fade">
    <div class="modal-header">
        <a href="#" class="close">&times;</a>
         <h3><?php echo lang('hr_leaves_popup_delete_title');?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo lang('hr_leaves_popup_delete_message');?></p>
        <p><?php echo lang('hr_leaves_popup_delete_question');?></p>
    </div>
    <div class="modal-footer">
        <a href="#" id="lnkDeleteUser" class="btn btn-danger"><?php echo lang('hr_leaves_popup_delete_button_yes');?></a>
        <a href="#" onclick="$('#frmDeleteLeaveRequest').modal('hide');" class="btn"><?php echo lang('hr_leaves_popup_delete_button_no');?></a>
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
var leaveTable = null;

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
    leaveTable.columns( 1 ).search( filter, true, false ).draw();
}

$(function () {
    //Transform the HTML table in a fancy datatable
    leaveTable = $('#leaves').DataTable({
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

    //On showing the confirmation pop-up, add the user id at the end of the delete url action
    $('#frmDeleteLeaveRequest').on('show', function() {
        var link = "<?php echo base_url();?>leaves/delete/" + $(this).data('id');
        link += "?source=hr%2Fleaves%2F<?php echo $user_id; ?>";
        $("#lnkDeleteUser").attr('href', link);
    })

    //Display a modal pop-up so as to confirm if a leave request has to be deleted or not
    //We build a complex selector because datatable does horrible things on DOM...
    //a simplier selector doesn't work when the delete is on page >1
    $("#leaves tbody").on('click', '.confirm-delete',  function(){
        var id = $(this).data('id');
        $('#frmDeleteLeaveRequest').data('id', id).modal('show');
    });

    $('#frmDeleteLeaveRequest').on('hidden', function() {
        $(this).removeData('modal');
    });
    <?php if ($this->config->item('enable_history') === TRUE) { ?>
    //Prevent to load always the same content (refreshed each time)
    $('#frmShowHistory').on('hidden', function() {
        $("#frmShowHistoryBody").html('<img src="<?php echo base_url();?>assets/images/loading.gif">');
    });

    //Popup show history
    $("#leaves tbody").on('click', '.show-history',  function(){
        $("#frmShowHistory").modal('show');
        $("#frmShowHistoryBody").load('<?php echo base_url();?>leaves/' + $(this).data('id') +'/history', function(response, status, xhr) {
            if (xhr.status == 401) {
                $("#frmShowHistory").modal('hide');
                bootbox.alert("<?php echo lang('global_ajax_timeout');?>", function() {
                    //After the login page, we'll be redirected to the current page
                   location.reload();
                });
            }
          });
    });
    $("#deleted_leaves tbody").on('click', '.show-history',  function(){
        $("#frmShowHistory").modal('show');
        $("#frmShowHistoryBody").load('<?php echo base_url();?>leaves/' + $(this).data('id') +'/history', function(response, status, xhr) {
            if (xhr.status == 401) {
                $("#frmShowHistory").modal('hide');
                bootbox.alert("<?php echo lang('global_ajax_timeout');?>", function() {
                    //After the login page, we'll be redirected to the current page
                   location.reload();
                });
            }
          });
    });

    $('#deleted_leaves').dataTable({
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
    <?php } ?>

    //Detect change of filter check boxes
    $('.filterStatus').on('change',function(){
        filterStatusColumn();
    });

    //Dynamic filter on leave type
    $('#cboLeaveType').on('change',function(){
        var leaveType = $("#cboLeaveType option:selected").text();
        if (leaveType != '') {
            leaveTable.columns( 5 ).search( "^" + leaveType + "$", true, false ).draw();
        } else {
            leaveTable.columns( 5 ).search( "", true, false ).draw();
        }
    });
});
</script>
