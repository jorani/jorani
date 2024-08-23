<?php
/**
 * This view allows to exclude some leave types from a contract
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.5.0
 */
?>

<h2><?php echo lang('contract_exclude_title');?>&nbsp;<span class="muted">(<?php echo $contract['name'];?>)</span>&nbsp;<?php echo $help;?></h2>

<p><?php echo lang('contract_exclude_description');?></p>

<div class="row-fluid">
    <div class="span6">
        <h3><?php echo lang('contract_exclude_title_included');?></h3>
        <div class="well">
             <table class="table" id="included">
              <thead>
                <tr>
                    <th>&nbsp;</th>
                  <th>Type</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($includedTypes as $typeId => $TypeName): ?>
                <tr id="leave_row_<?php echo $typeId; ?>">
                    <td>
                        <div class="pull-right">

                            <?php if ($typesUsage[$typeId] == 0 && $defaultType != $typeId) { ?>
                            <a href="#" class="exclude" data-id="<?php echo $typeId; ?>" title="<?php echo lang('contract_exclude_tip_exclude_type');?>"><i class="mdi mdi-close nolink"></i></a>
                            <?php } else { ?>
                            <span class="badge badge-info"><?php echo $typesUsage[$typeId]; ?></span>
                            <?php       if ($defaultType == $typeId) { ?>
                            <a href="#" title="<?php echo lang('contract_exclude_tip_default_type');?>"><i class="mdi mdi-star nolink"></i></a>
                            <?php       } else { ?>
                            <a href="#" title="<?php echo lang('contract_exclude_tip_already_used');?>"><i class="mdi mdi-alert-circle nolink"></i></a>
                            <?php       } ?>
                            <?php } ?>
                        </div>
                    </td>
                    <td><?php echo $typeId; ?> &mdash;  <span id="leave_name_<?php echo $typeId; ?>"><?php echo $TypeName; ?></span></td>
                </tr>
                <?php endforeach ?>
              </tbody>
            </table>
            </div>
    </div>

    <div class="span6">
        <h3><?php echo lang('contract_exclude_title_excluded');?></h3>
        <div class="well">
             <table class="table" id="excluded">
              <thead>
                <tr>
                  <th>&nbsp;</th>
                  <th>Type</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($excludedTypes as $typeId => $TypeName): ?>
                <tr id="leave_row_<?php echo $typeId; ?>">
                    <td>
                        <div class="pull-right">
                            <a href="#" class="include" data-id="<?php echo $typeId; ?>" title="<?php echo lang('contract_exclude_tip_include_type');?>"><i class="mdi mdi-undo nolink"></i></a>
                        </div>
                    </td>
                    <td><?php echo $typeId; ?> &mdash; <span id="leave_name_<?php echo $typeId; ?>"><?php echo $TypeName; ?></span></td>
                </tr>
                <?php endforeach ?>
              </tbody>
            </table>
           </div>
    </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div class="row-fluid">
    <div class="span12">
        <a href="<?php echo base_url() . 'contracts';?>" class="btn btn-primary"><i class="mdi mdi-arrow-left-bold"></i>&nbsp; <?php echo lang('contract_calendar_button_back');?></a>
    </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div class="modal hide" id="frmModalAjaxWait" data-backdrop="static" data-keyboard="false">
        <div class="modal-header">
            <h1><?php echo lang('global_msg_wait');?></h1>
        </div>
        <div class="modal-body">
            <img src="<?php echo base_url();?>assets/images/loading.gif"  align="middle">
        </div>
 </div>

<script type="text/javascript">
<?php if ($this->config->item('csrf_protection') == TRUE) {?>
/*$(function () {
    $.ajaxSetup({
        data: {
            <?php echo $this->security->get_csrf_token_name();?>: "<?php echo $this->security->get_csrf_hash();?>",
        }
    });
});*/
<?php }?>
/**
 * Include or Exclude a leave type from a contract (depending on the operation parameter)
 * @param {int} contractId Identifier the contract
 * @param {int} typeId Identifier of the leave type
 * @param {String} operation "include" or "exclude" the leave type from the contract
 * @returns {undefined}
 */
function includeExclude(contractId, typeId, operation) {
    $('#frmModalAjaxWait').modal('show');
    $.ajax({
        type: "GET",
        url: "<?php echo base_url();?>contracts/" + contractId + "/types/" + typeId + "/" + operation,
        }).done(function() {
            $('#frmModalAjaxWait').modal('hide');
        });
}

$(function () {

    //On click on a valid element of the table of the included types
    //Remove into 'included', add into 'excluded' table
    $("#included").on('click', '.exclude', function() {
        var contractId = <?php echo $contract['id'];?>;
        var TypeId = $(this).data('id');
        var leaveName = $("#leave_name_" + TypeId).text();
        var newRow = '<tr id="leave_row_' + TypeId + '">' +
                                '<td>' +
                                    '<div class="pull-right">' +
                                        '<a href="#" class="include" data-id="' + TypeId + '" title="Include this leave type"><i class="mdi mdi-undo nolink"></i></a>' +
                                   '</div>' +
                                 '</td>' +
                                '<td>' + TypeId + ' &mdash; <span id="leave_name_' + TypeId + '">' + leaveName + '</span></td>' +
                                '</tr>';
        $('#leave_row_' + TypeId).remove();
        $('#excluded tr:last').after(newRow);
        includeExclude(contractId, TypeId, 'exclude');
    });

    //On click on an element of the table of the included types
    //Remove into 'included', add into 'excluded'
    $("#excluded").on('click', '.include', function() {
        var contractId = <?php echo $contract['id'];?>;
        var TypeId = $(this).data('id');
        var leaveName = $("#leave_name_" + TypeId).text();
        var newRow = '<tr id="leave_row_' + TypeId + '">' +
                                '<td>' +
                                    '<div class="pull-right">' +
                                        '<a href="#" class="exclude" data-id="' + TypeId + '" title="Exclude this leave type"><i class="mdi mdi-close nolink"></i></a>' +
                                   '</div>' +
                                 '</td>' +
                                '<td>' + TypeId + ' &mdash; <span id="leave_name_' + TypeId + '">' + leaveName + '</span></td>' +
                                '</tr>';
        $('#leave_row_' + TypeId).remove();
        $('#included tr:last').after(newRow);
        includeExclude(contractId, TypeId, 'include');
    });
});
</script>
