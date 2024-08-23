<?php 
/**
 * This view displays the list of leave types.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>

<h2><?php echo lang('leavetypes_type_title');?><?php echo $help;?></h2>

<p><?php echo lang('leavetypes_type_description');?></p>

<?php echo $flash_partial_view;?>

<table class="table table-bordered table-hover">
<thead>
    <tr>
      <th><?php echo lang('leavetypes_type_thead_id');?></th>
      <th><?php echo lang('leavetypes_type_thead_acronym');?></th>
      <th><?php echo lang('leavetypes_type_thead_name');?></th>
     <th><?php echo lang('leavetypes_type_thead_deduct');?></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($leavetypes as $type) { ?>
    <tr>
      <td><?php echo $type['id'] ?> &nbsp; 
          <?php if ($type['id'] !=0 ) { ?>
          <a href="#" class="confirm-delete" data-id="<?php echo $type['id'];?>" title="<?php echo lang('leavetypes_type_thead_tip_delete');?>"><i class="mdi mdi-delete nolink"></i></a>
          <?php } ?>
      </td>
      <td>
          <?php echo $type['acronym']; ?>
      </td>
      <td>
          <a href="<?php echo base_url();?>leavetypes/edit/<?php echo $type['id'] ?>" data-target="#frmEditLeaveType" data-toggle="modal" title="<?php echo lang('leavetypes_type_thead_tip_edit');?>"><i class="mdi mdi-pencil nolink"></i></a>
          &nbsp; <?php echo $type['name']; ?>
      </td>
      <td>
        <?php if ($type['deduct_days_off'] == TRUE ) { ?>
        <i class="mdi mdi-checkbox-marked-outline"></i>
        <?php } else { ?>
        <i class="mdi mdi-checkbox-blank-outline"></i>
        <?php } ?>
      </td>
    </tr>
  <?php } ?>
  <?php if (count($leavetypes) == 0) { ?>
    <tr>
        <td colspan="5"><?php echo lang('leavetypes_type_not_found');?></td>
    </tr>
  <?php } ?>
  </tbody>
</table>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div class="row-fluid">
    <div class="span12">
        <a href="<?php echo base_url();?>leavetypes/export" class="btn btn-primary"><i class="mdi mdi-download"></i>&nbsp; <?php echo lang('leavetypes_type_button_export');?></a>
        &nbsp;
        <a href="<?php echo base_url();?>leavetypes/create" class="btn btn-primary" data-target="#frmAddLeaveType" data-toggle="modal"><i class="mdi mdi-plus-circle"></i>&nbsp; <?php echo lang('leavetypes_type_button_create');?></a>
    </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div id="frmAddLeaveType" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmAddLeaveType').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('leavetypes_popup_create_title');?></h3>
    </div>
    <div class="modal-body">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="$('#frmAddLeaveType').modal('hide');" class="btn btn-danger"><?php echo lang('leavetypes_popup_create_button_cancel');?></a>
    </div>
</div>

<div id="frmEditLeaveType" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmEditLeaveType').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('leavetypes_popup_update_title');?></h3>
    </div>
    <div class="modal-body">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="$('#frmEditLeaveType').modal('hide');" class="btn"><?php echo lang('leavetypes_popup_update_button_cancel');?></a>
    </div>
</div>

<div id="frmDeleteLeaveType" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmDeleteLeaveType').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('leavetypes_popup_delete_title');?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo lang('leavetypes_popup_delete_description');?></p>
        <p><?php echo lang('leavetypes_popup_delete_confirm');?></p>
    </div>
    <div class="modal-footer">
        <a href="#" id="lnkDeleteLeaveType" class="btn btn-danger"><?php echo lang('leavetypes_popup_delete_button_yes');?></a>
        <a href="#" onclick="$('#frmDeleteLeaveType').modal('hide');" class="btn"><?php echo lang('leavetypes_popup_delete_button_no');?></a>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $("#frmAddLeaveType").alert();
    $("#frmEditLeaveType").alert();
    $("#frmDeleteLeaveType").alert();
	
    //On showing the confirmation pop-up, add the type id at the end of the delete url action
    $('#frmDeleteLeaveType').on('show', function() {
        var link = "<?php echo base_url();?>leavetypes/delete/" + $(this).data('id');
        $("#lnkDeleteLeaveType").attr('href', link);
    })

    //Display a modal pop-up so as to confirm if a type has to be deleted or not
    $('.confirm-delete').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#frmDeleteLeaveType').data('id', id).modal('show');
    });
    
    //Prevent to load always the same content (refreshed each time)
    $('#frmAddLeaveType').on('hidden', function() {
        $(this).removeData('modal');
    });
    $('#frmEditLeaveType').on('hidden', function() {
        $(this).removeData('modal');
    });
    $('#frmDeleteLeaveType').on('hidden', function() {
        $(this).removeData('modal');
    });
    
    //Give focus on first field on opening modal forms
    $('#frmAddLeaveType').on('shown', function () {
        $('input:text:visible:first', this).focus();
    });
    $('#frmEditLeaveType').on('shown', function () {
        $('input:text:visible:first', this).focus();
    });
});
</script>

