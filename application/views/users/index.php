<?php
/**
 * This view displays the list of users.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.1.0
 */
?>

<div class="row-fluid">
    <div class="span12">

<h2><?php echo lang('users_index_title');?><?php echo $help;?></h2>

<?php echo $flash_partial_view;?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="users" width="100%">
    <thead>
        <tr>
            <th><?php echo lang('users_index_thead_id');?></th>
            <th><?php echo lang('users_index_thead_firstname');?></th>
            <th><?php echo lang('users_index_thead_lastname');?></th>
            <th><?php echo lang('users_index_thead_login');?></th>
            <th><?php echo lang('users_index_thead_email');?></th>
            <th><?php echo lang('users_index_thead_role');?></th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($users as $users_item): ?>
    <tr data-id="<?php echo $users_item['id']; ?>">
        <td data-order="<?php echo $users_item['id']; ?>">
            <?php echo $users_item['id'] ?>&nbsp;
            <div class="pull-right">
            <?php if ($users_item['id'] != $this->session->userdata('id')) { ?>
                <?php if ($users_item['active']) { ?>
                <a href="#" class="action-disable" data-id="<?php echo $users_item['id'];?>" title="<?php echo lang('users_index_thead_tip_active');?>"><i class="mdi mdi-account-off nolink"></i></a>
                <?php } else { ?>
                <a href="#" class="action-enable" data-id="<?php echo $users_item['id'];?>" title="<?php echo lang('users_index_thead_tip_inactive');?>"><i class="mdi mdi-account mdi-dark mdi-inactive"></i></a>
                <?php } ?>
            <?php } ?>
                &nbsp;
                <a href="<?php echo base_url();?>users/edit/<?php echo $users_item['id'] ?>" title="<?php echo lang('users_index_thead_tip_edit');?>"><i class="mdi mdi-account-edit nolink"></i></a>
                &nbsp;
                <?php if ($users_item['id'] != $this->session->userdata('id')) { ?>
                <a href="#" class="confirm-delete" data-id="<?php echo $users_item['id'];?>" title="<?php echo lang('users_index_thead_tip_delete');?>"><i class="mdi mdi-delete nolink"></i></a>
                &nbsp;
                <?php } ?>
                <a href="<?php echo base_url();?>users/reset/<?php echo $users_item['id'] ?>" title="<?php echo lang('users_index_thead_tip_reset');?>" data-target="#frmResetPwd" data-toggle="modal"><i class="mdi mdi-lock nolink"></i></a>
            </div>
        </td>
        <td><?php echo $users_item['firstname']; ?></td>
        <td><?php echo $users_item['lastname']; ?></td>
        <td><?php echo $users_item['login']; ?></td>
        <td><a href="mailto:<?php echo $users_item['email']; ?>"><?php echo $users_item['email']; ?></a></td>
        <td><?php echo $users_item['roles_list']; ?></td>
    </tr>
<?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div class="row-fluid">
    <div class="span12">
      <a href="<?php echo base_url();?>users/export" class="btn btn-primary"><i class="mdi mdi-download"></i>&nbsp;<?php echo lang('users_index_button_export');?></a>
      &nbsp;
      <a href="<?php echo base_url();?>users/create" class="btn btn-primary"><i class="mdi mdi-account-plus"></i>&nbsp;<?php echo lang('users_index_button_create_user');?></a>
    </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div id="frmConfirmDelete" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmConfirmDelete').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('users_index_popup_delete_title');?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo lang('users_index_popup_delete_message');?></p>
        <p><?php echo lang('users_index_popup_delete_question');?></p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-danger" id="action-delete"><?php echo lang('users_index_popup_delete_button_yes');?></a>
        <a href="#" onclick="$('#frmConfirmDelete').modal('hide');" class="btn"><?php echo lang('users_index_popup_delete_button_no');?></a>
    </div>
</div>

<div id="frmResetPwd" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
         <h3><?php echo lang('users_index_popup_password_title');?></h3>
    </div>
    <div class="modal-body">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal"><?php echo lang('users_index_popup_password_button_cancel');?></button>
    </div>
</div>

<div id="frmImportUsers" class="modal hide fade">

    <div class="modal-header">
        <a href="#" onclick="$('#frmImportUsers').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('users_index_popup_import_title');?></h3>
    </div>
    <div class="modal-body">
        <?php echo form_open_multipart('users/import');?>
            <label for="importFile"><?php echo lang('users_index_popup_field_filename');?>
            <input type="file" name="importFile" size="20" />
            </label>
            <input class="btn btn-primary" type="submit" value="<?php echo lang('OK');?>" />
        </form>
    </div>
    <div class="modal-footer">
        <a href="#" onclick="$('#frmImportUsers').modal('hide');" class="btn btn-danger"><?php echo lang('Cancel');?></a>
    </div>

</div>

<link href="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {

    <?php if ($this->config->item('csrf_protection') == TRUE) {?>
    $.ajaxSetup({
        data: {
            <?php echo $this->security->get_csrf_token_name();?>: "<?php echo $this->security->get_csrf_hash();?>",
        }
    });
    <?php }?>

    //Global Ajax error handling mainly used for session expiration
    $( document ).ajaxError(function(event, jqXHR, settings, errorThrown) {
        $('#frmModalAjaxWait').modal('hide');
        if (jqXHR.status == 401) {
            bootbox.alert("<?php echo lang('global_ajax_timeout');?>", function() {
                //After the login page, we'll be redirected to the current page
                location.reload();
            });
        } else { //Oups
            bootbox.alert("<?php echo lang('global_ajax_error');?>");
        }
    });

    //Transform the HTML table in a fancy datatable
    oTable = $('#users').DataTable({
        stateSave: true,
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
        },
    });
    $("#frmResetPwd").alert();
    $("#frmImportUsers").alert();

    //On showing the confirmation pop-up, add the user id as an attribute of the delete url link
    $('#frmConfirmDelete').on('show', function() {
        $("#action-delete").attr('data-id', $(this).data('id'));
    });

    //Display a modal pop-up so as to confirm if a user has to be deleted or not
    //We build a complex selector because datatable does horrible things on DOM...
    //a simplier selector doesn't work when the delete is on page >1
    $("#users tbody").on('click', '.confirm-delete',  function(){
        var id = $(this).data('id');
        $('#frmConfirmDelete').data('id', id).modal('show');
    });

    //Enable a user
    $("#users tbody").on('click', '.action-enable',  function(){
        var id = $(this).data('id');
        var ref = $(this);
        $.post( "<?php echo base_url();?>users/account", { operation: "enable", id: id }).done(function() {
            ref.attr('class', 'action-disable');
            ref.attr('title', '<?php echo lang('users_index_thead_tip_active');?>');
            ref.children(":first").attr('class', 'mdi mdi-account-off nolink');
        });
    });

    //Disable a user
    $("#users tbody").on('click', '.action-disable',  function(){
        var id = $(this).data('id');
        var ref = $(this);
        $.post( "<?php echo base_url();?>users/account", { operation: "disable", id: id }).done(function() {
            ref.attr('class', 'action-enable');
            ref.attr('title', '<?php echo lang('users_index_thead_tip_inactive');?>');
            ref.children(":first").attr('class', 'mdi mdi-account mdi-dark mdi-inactive');
        });
    });

    //Delete a user
    $("#action-delete").on('click',  function(){
        var id = $(this).data('id');
        $.post( "<?php echo base_url();?>users/account", { operation: "delete", id: id }).done(function() {
            oTable.rows('tr[data-id="' + id + '"]').remove().draw();
            $('#frmConfirmDelete').modal('hide');
        });
    });

    //Prevent to load always the same content (refreshed each time)
    $('#frmConfirmDelete').on('hidden', function() {
        $(this).removeData('modal');
    });
    $('#frmResetPwd').on('hidden', function() {
        $(this).removeData('modal');
    });
});
</script>
