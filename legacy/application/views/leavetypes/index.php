<?php
/**
 * This view displays the list of leave types.
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.1.0
 */
?>

<h2><?= t('Leave types') ?></h2>

<p><?= t('Leave type #0 is a system type reserved for overtime management. You should not use it for other requests.') ?>
</p>

<?php echo $flash_partial_view; ?>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th><?= t('ID') ?></th>
            <th><?= t('Acronym') ?></th>
            <th><?= t('Name') ?></th>
            <th><?= t('Deduct non working days') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($leavetypes as $type) { ?>
            <tr>
                <td><?php echo $type->getId(); ?> &nbsp;
                    <?php if ($type->getId() !== 0) { ?>
                        <a href="#" class="confirm-delete" data-id="<?php echo $type->getId(); ?>" title="<?= t('delete') ?>"><i
                                class="mdi mdi-delete nolink"></i></a>
                    <?php } ?>
                </td>
                <td>
                    <?php echo $type->getAcronym(); ?>
                </td>
                <td>
                    <a href="<?php echo base_url(); ?>leavetypes/edit/<?php echo $type->getId(); ?>"
                        data-target="#frmEditLeaveType" data-toggle="modal" title="<?= t('edit') ?>"><i
                            class="mdi mdi-pencil nolink"></i></a>
                    &nbsp; <?php echo $type->getName(); ?>
                </td>
                <td>
                    <?php if ($type->isDeductDaysOff()) { ?>
                        <i class="mdi mdi-checkbox-marked-outline"></i>
                    <?php } else { ?>
                        <i class="mdi mdi-checkbox-blank-outline"></i>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        <?php if (count($leavetypes) == 0) { ?>
            <tr>
                <td colspan="5"><?= t('No leave type found into the database.') ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<div class="row-fluid">
    <div class="span12">&nbsp;</div>
</div>

<div class="row-fluid">
    <div class="span12">
        <a href="<?php echo base_url(); ?>leavetypes/export" class="btn btn-primary"><i
                class="mdi mdi-download"></i>&nbsp; <?= t('Export this list') ?></a>
        &nbsp;
        <a href="<?php echo base_url(); ?>leavetypes/create" class="btn btn-primary" data-target="#frmAddLeaveType"
            data-toggle="modal"><i class="mdi mdi-plus-circle"></i>&nbsp;
            <?= t('Create a new type') ?></a>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">&nbsp;</div>
</div>

<div id="frmAddLeaveType" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmAddLeaveType').modal('hide');" class="close">&times;</a>
        <h3><?= t('Add a leave type') ?></h3>
    </div>
    <div class="modal-body">
        <img src="<?php echo base_url(); ?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="$('#frmAddLeaveType').modal('hide');" class="btn btn-danger"><?= t('Cancel') ?></a>
    </div>
</div>

<div id="frmEditLeaveType" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmEditLeaveType').modal('hide');" class="close">&times;</a>
        <h3><?= t('Edit a Leave type') ?></h3>
    </div>
    <div class="modal-body">
        <img src="<?php echo base_url(); ?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="$('#frmEditLeaveType').modal('hide');" class="btn"><?= t('Cancel') ?></a>
    </div>
</div>

<div id="frmDeleteLeaveType" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmDeleteLeaveType').modal('hide');" class="close">&times;</a>
        <h3><?= t('Delete Leave Type') ?></h3>
    </div>
    <div class="modal-body">
        <p><?= t('You are about to delete one leave type, this procedure is irreversible.') ?></p>
        <p><?= t('Do you want to proceed?') ?></p>
    </div>
    <div class="modal-footer">
        <a href="#" id="lnkDeleteLeaveType" class="btn btn-danger"><?= t('Yes') ?></a>
        <a href="#" onclick="$('#frmDeleteLeaveType').modal('hide');" class="btn"><?= t('No') ?></a>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#frmAddLeaveType").alert();
        $("#frmEditLeaveType").alert();
        $("#frmDeleteLeaveType").alert();

        //On showing the confirmation pop-up, add the type id at the end of the delete url action
        $('#frmDeleteLeaveType').on('show', function () {
            var link = "<?php echo base_url(); ?>leavetypes/delete/" + $(this).data('id');
            $("#lnkDeleteLeaveType").attr('href', link);
        })

        //Display a modal pop-up so as to confirm if a type has to be deleted or not
        $('.confirm-delete').on('click', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            $('#frmDeleteLeaveType').data('id', id).modal('show');
        });

        //Prevent to load always the same content (refreshed each time)
        $('#frmAddLeaveType').on('hidden', function () {
            $(this).removeData('modal');
        });
        $('#frmEditLeaveType').on('hidden', function () {
            $(this).removeData('modal');
        });
        $('#frmDeleteLeaveType').on('hidden', function () {
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