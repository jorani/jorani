<?php
/**
 * This view allows an HR admin to credit entitled days to a contract
 * This will affect employees having this contract
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.2.0
 */
?>

<div class="row-fluid">
    <div class="span12">

<h2><?php echo lang('entitleddays_contract_index_title');?> <span class="muted"><?php echo $contract_name; ?></span>&nbsp;<?php echo $help;?></h2>

<table id="entitleddayscontract">
<thead>
    <tr>
      <th>&nbsp;</th>
      <th><?php echo lang('entitleddays_contract_index_thead_start');?></th>
      <th><?php echo lang('entitleddays_contract_index_thead_end');?></th>
      <th><?php echo lang('entitleddays_contract_index_thead_days');?></th>
      <th><?php echo lang('entitleddays_contract_index_thead_type');?></th>
      <th><?php echo lang('entitleddays_contract_index_thead_description');?></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($entitleddays as $days) { ?>
    <tr data-id="<?php echo $days['id']; ?>">
        <td>
          <a href="#" onclick="delete_entitleddays(<?php echo $days['id'] ?>);" title="<?php echo lang('entitleddays_contract_index_thead_tip_delete');?>"><i class="mdi mdi-close nolink"></i></a>
          &nbsp;<a href="#" onclick="copy_entitleddays(<?php echo $days['id'] ?>);" title="<?php echo lang('entitleddays_contract_index_thead_tip_copy');?>"><i class="mdi mdi-content-copy nolink"></i></a>
          &nbsp;<a href="#" onclick="show_edit_entitleddays(<?php echo $days['id'] ?>);" title="<?php echo lang('entitleddays_contract_index_thead_tip_edit');?>"><i class="mdi mdi-pencil nolink"></i></a>
        </td>
<?php $startDate = new DateTime($days['startdate']);
$endDate = new DateTime($days['enddate']);?>
      <td data-order="<?php echo $startDate->getTimestamp(); ?>"><?php echo $startDate->format(lang('global_date_format'));?></td>
      <td data-order="<?php echo $endDate->getTimestamp(); ?>"><?php echo $endDate->format(lang('global_date_format'));?></td>
      <td data-order="<?php echo $days['days']; ?>">
        <span id="days<?php echo $days['id']; ?>"><?php echo $days['days']; ?></span>
        &nbsp;<a href="#" onclick="Javascript:incdec(<?php echo $days['id']; ?>, 'decrease');"><i class="mdi mdi-minus nolink"></i></a>
        &nbsp;<a href="#" onclick="Javascript:incdec(<?php echo $days['id']; ?>, 'increase');"><i class="mdi mdi-plus nolink"></i></a>
      </td>
      <td data-id="<?php echo $days['type']; ?>"><?php echo $days['type_name']; ?></td>
      <td><?php echo $days['description']; ?></td>
    </tr>
  <?php } ?>
  </tbody>
</table>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div class="row-fluid">
    <div class="span6">
        <a href="<?php echo base_url();?>contracts" class="btn btn-danger"><i class="mdi mdi-arrow-left-bold"></i>&nbsp;<?php echo lang('entitleddays_contract_index_button_back');?></a>
        <button id="cmdAddEntitledDays" class="btn btn-primary" onclick="show_add_entitleddays();"><i class="mdi mdi-plus-circle"></i>&nbsp;<?php echo lang('entitleddays_contract_index_button_add');?></button>
    </div>
    <div class="span6">
        <div class="pull-right">
            <label for="txtStep"><?php echo lang('entitleddays_contract_index_field_step');?></label>
            <input type="text" class="input-mini" id="txtStep" name="txtStep" value="1">
        </div>
    </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

    </div>
</div>

<div id="frmAddEntitledDays" class="modal hide fade">
        <div class="modal-header">
            <a href="#" class="close" onclick="$('#frmAddEntitledDays').modal('hide');">&times;</a>
            <h3 id="frmAddEntitledDaysTitle"><?php echo lang('entitleddays_contract_popup_title');?></h3>
        </div>
        <div class="modal-body">
            <label for="viz_startdate"><?php echo lang('entitleddays_contract_index_field_start');?></label>
            <div class="input-append">
                <input type="text" id="viz_startdate" name="viz_startdate" required />
                <button class="btn" onclick="set_current_period();"><?php echo lang('entitleddays_contract_index_button_current');?></button>
            </div><br />
            <input type="hidden" name="startdate" id="startdate" />
            <label for="viz_enddate"><?php echo lang('entitleddays_contract_index_field_end');?></label>
            <input type="text" id="viz_enddate" name="viz_enddate" required /><br />
            <input type="hidden" name="enddate" id="enddate" />
            <label for="type"><?php echo lang('entitleddays_contract_index_field_type');?></label>
            <select name="type" id="type" required>
            <?php foreach ($types as $types_item): ?>
                <option value="<?php echo $types_item['id'] ?>" <?php if ($types_item['id'] == 1) echo "selected" ?>><?php echo $types_item['name'] ?></option>
            <?php endforeach ?>
            </select>
            <label for="days" required><?php echo lang('entitleddays_contract_index_field_days');?></label>
            <input type="text" class="input-mini" name="days" id="days" />
            <label for="description"><?php echo lang('entitleddays_contract_index_field_description');?></label>
            <input type="text" class="input-xlarge" name="description" id="description" />
        </div>
        <div class="modal-footer">
            <button id="cmdFrmSaveEntitledDays" class="btn btn-primary" onclick="edit_entitleddays();" ><?php echo lang('OK');?></button>
            <button id="cmdFrmAddEntitledDays" class="btn btn-primary" onclick="add_entitleddays();"><?php echo lang('entitleddays_contract_index_button_add');?></button>
            <button class="btn btn-danger" onclick="$('#frmAddEntitledDays').modal('hide');"><?php echo lang('entitleddays_contract_index_button_cancel');?></button>
        </div>
 </div>

<div class="modal hide" id="frmModalAjaxWait" data-backdrop="static" data-keyboard="false">
        <div class="modal-header">
            <h1><?php echo lang('global_msg_wait');?></h1>
        </div>
        <div class="modal-body">
            <img src="<?php echo base_url();?>assets/images/loading.gif"  align="middle">
        </div>
 </div>

<link rel="stylesheet" href="<?php echo base_url();?>assets/css/flick/jquery-ui.custom.min.css">
<script src="<?php echo base_url();?>assets/js/jquery-ui.custom.min.js"></script>
<?php //Prevent HTTP-404 when localization isn't needed
if ($language_code != 'en') { ?>
<script src="<?php echo base_url();?>assets/js/i18n/jquery.ui.datepicker-<?php echo $language_code;?>.js"></script>
<?php } ?>
<script src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>
<link href="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
    //Current cell transformed in input box
    var current_input = null;
    var credit = 0;
    var startMonth = <?php echo $contract_start_month;?>;
    var startDay = <?php echo $contract_start_day;?>;
    var endMonth = <?php echo $contract_end_month;?>;
    var endDay = <?php echo $contract_end_day;?>;
    var locale = '<?php echo $language_code;?>';
    var oTable;     //datatable
    var step = 1;
    var current_id = 1;
    //Global locale for moment objects
    moment.locale('<?php echo $language_code;?>', {longDateFormat : {L : '<?php echo lang('global_date_momentjs_format');?>'}});

    //Compute the end and start dates with the contract periods
    function set_current_period() {
        var now = moment();
        var startEntDate = moment();//now
        var endEntDate = moment();//now

        //Compute boundaries
        startEntDate.month(startMonth - 1);
        startEntDate.date(startDay);
        endEntDate.month(endMonth - 1);
        endEntDate.date(endDay);
        if (startMonth != 1 ) {
                if (now.month() < 5) {//zero-based => june
                        startEntDate.subtract(1, 'years');
                } else {
                        endEntDate.add(1, 'years');
                }
        }

        //Presentation for DB and Human
        startEntDate.locale(locale);
        endEntDate.locale(locale);
        $("#startdate").val(startEntDate.format("YYYY-MM-DD"));
        $("#enddate").val(endEntDate.format("YYYY-MM-DD"));
        $("#viz_startdate").val(startEntDate.format("L"));
        $("#viz_enddate").val(endEntDate.format("L"));
        }

    function validate_form() {
        result = false;
        var fieldname = "";
        if ($('#startdate').val() == "") fieldname = "<?php echo lang('entitleddays_contract_index_field_start');?>";
        if ($('#enddate').val() == "") fieldname = "<?php echo lang('entitleddays_contract_index_field_end');?>";
        if ($('#type').val() == "") fieldname = "<?php echo lang('entitleddays_contract_index_field_type');?>";
        if ($('#days').val() == "") fieldname = "<?php echo lang('entitleddays_contract_index_field_days');?>";
        if (fieldname == "") {
            return true;
        } else {
            bootbox.alert(<?php echo lang('entitleddays_contract_mandatory_js_msg');?>);
            return false;
        }
    }

    function delete_entitleddays(id) {
        bootbox.confirm("<?php echo lang('entitleddays_contract_confirm_delete_message');?>",
            "<?php echo lang('entitleddays_contract_confirm_delete_cancel');?>",
            "<?php echo lang('entitleddays_contract_confirm_delete_yes');?>", function(result) {
            if (result) {
                $('#frmModalAjaxWait').modal('show');
                $.ajax({
                    url: "<?php echo base_url();?>entitleddays/contractdelete/" + id
                  }).done(function() {
                      oTable.rows('tr[data-id="' + id + '"]').remove().draw();
                      $('#frmModalAjaxWait').modal('hide');
                  });
                }
        });
    }

    //Open the pop-up (edit mode), load its fields with the content of the fields
    //of the selected row in the datatable
    function show_edit_entitleddays(id) {
        $('#frmAddEntitledDays').modal('show');
        $("#cmdFrmAddEntitledDays").hide();
        $("#cmdFrmSaveEntitledDays").show();
        $("#frmAddEntitledDaysTitle").html("<?php echo lang('entitleddays_contract_index_title');?>");

        startdate = $("tr[data-id='" + id + "']>td:eq(1)").data('order');
        startdate = moment(startdate, 'X').format("YYYY-MM-DD");
        enddate = $("tr[data-id='" + id + "']>td:eq(2)").data('order');
        enddate = moment(enddate, 'X').format("YYYY-MM-DD");
        days = parseFloat($("tr[data-id='" + id + "']>td:eq(3)").text());
        type = $("tr[data-id='" + id + "']>td:eq(4)").data('id');
        type_name = $("tr[data-id='" + id + "']>td:eq(4)").text();
        description = $("tr[data-id='" + id + "']>td:eq(5)").text();

        $('#viz_startdate').datepicker('setDate', new Date(startdate));
        $('#viz_enddate').datepicker('setDate', new Date(enddate));
        $("#type").val(type);
        $("#days").val(days);
        $("#description").val(description);
        current_id = id;
    }

    //Remote Ajax call for saving the modified values, update of the datatable
    function edit_entitleddays() {
        $('#frmAddEntitledDays').modal('hide');
        $('#frmModalAjaxWait').modal('show');
        $.ajax({
            url: "<?php echo base_url();?>entitleddays/ajax/update",
                            type: "POST",
                data: { id: current_id,
                        operation: "update",
                        startdate: $("#startdate").val(),
                        enddate: $("#enddate").val(),
                        days: parseFloat($('#days').val()),
                        type: $("#type").val(),
                        description: $("#description").val(),
                    }
          })

        //Start date
        startdate = moment($("#startdate").val(), "YYYY-MM-DD").unix();
        $("tr[data-id='" + current_id + "'] td:eq(1)").data('order', startdate);
        cell = oTable.cell("tr[data-id='" + current_id + "'] td:eq(1)");
        cell.data($("#viz_startdate").val()).draw();
        //End date
        enddate = moment($("#enddate").val(), "YYYY-MM-DD").unix();
        $("tr[data-id='" + current_id + "'] td:eq(2)").data('order', enddate);
        cell = oTable.cell("tr[data-id='" + current_id + "'] td:eq(2)");
        cell.data($("#viz_enddate").val()).draw();
        //Days (mind +/- icons)
        days = parseFloat($('#days').val());
        cell_val = '<td data-order="' + days.toFixed(2) + '"><span id="days' + current_id + '">' + days.toFixed(2) + '</span> &nbsp; ' +
                        '<a href="#" onclick="Javascript:incdec(' + current_id + ', \'decrease\');"><i class="mdi mdi-minus nolink"></i></a>' +
                        '&nbsp;<a href="#" onclick="Javascript:incdec(' + current_id + ', \'increase\');"><i class="mdi mdi-plus nolink"></i></a></td>';
        cell = oTable.cell("tr[data-id='" + current_id + "'] td:eq(3)");
        cell.data(cell_val).draw();
        //Type of leave
        $("tr[data-id='" + current_id + "'] td:eq(4)").data('id', $("#type").val());
        cell = oTable.cell("tr[data-id='" + current_id + "'] td:eq(4)");
        cell.data($("#type option:selected").text()).draw();
        //Description
        cell = oTable.cell("tr[data-id='" + current_id + "'] td:eq(5)");
        cell.data($("#description").val()).draw();
        $('#frmModalAjaxWait').modal('hide');
    }

    //"increase" or "decrease" the number of entitled days of a given row
    function incdec(id, operation) {
        $('#frmModalAjaxWait').modal('show');
        $.ajax({
            url: "<?php echo base_url();?>entitleddays/ajax/update",
                            type: "POST",
                data: { id: id,
                        operation: operation,
                        days: step
                    }
          }).done(function() {
              var days = parseFloat($('#days' + id).text());
              switch(operation) {
                  case "increase": days+=step; $('#days' + id).text(days.toFixed(2)); break;
                  case "decrease": days-=step; $('#days' + id).text(days.toFixed(2)); break;
              }
              $('#frmModalAjaxWait').modal('hide');
          });
    }

    //copy a line of entitleddays
    function copy_entitleddays(id) {
        startdate = $("tr[data-id='" + id + "']>td:eq(1)").data('order');
        startdate = moment(startdate, 'X').format("YYYY-MM-DD");
        viz_startdate = $("tr[data-id='" + id + "']>td:eq(1)").text();
        enddate = $("tr[data-id='" + id + "']>td:eq(2)").data('order');
        enddate = moment(enddate, 'X').format("YYYY-MM-DD");
        viz_enddate = $("tr[data-id='" + id + "']>td:eq(2)").text();
        days = parseFloat($("tr[data-id='" + id + "']>td:eq(3)").text());
        type = $("tr[data-id='" + id + "']>td:eq(4)").data('id');
        type_name = $("tr[data-id='" + id + "']>td:eq(4)").text();
        description = $("tr[data-id='" + id + "']>td:eq(5)").text();
        create_entitleddays(startdate, viz_startdate, enddate, viz_enddate, days, type, type_name, description);
    }

    function add_entitleddays() {
        $('#frmAddEntitledDays').modal('hide');
        if (validate_form()) {
            create_entitleddays($('#startdate').val(), $('#viz_startdate').val(),
                                        $('#enddate').val(), $('#viz_enddate').val(),
                                        parseFloat($('#days').val()), $('#type').val(),
                                        $('#type option:selected').text(),
                                        $("#description").val());
        }
    }

    function show_add_entitleddays() {
        $('#frmAddEntitledDays').modal('show');
        $("#cmdFrmAddEntitledDays").show();
        $("#cmdFrmSaveEntitledDays").hide();
        $("#frmAddEntitledDaysTitle").html('<?php echo lang('entitleddays_contract_popup_title');?>');
    }

    function create_entitleddays(startdate, viz_startdate, enddate, viz_enddate, days, type, type_name, description) {
        $('#frmModalAjaxWait').modal('show');
        $.ajax({
            url: "<?php echo base_url();?>entitleddays/ajax/contract",
            type: "POST",
            data: { contract_id: <?php echo $id; ?>,
                    startdate: startdate,
                    enddate: enddate,
                    days: days,
                    type: type,
                    description: description
                }
          }).done(function( msg ) {
              id = parseInt(msg);
              htmlRow = '<tr data-id="' + id + '">' +
                        '<td><a href="#" onclick="delete_entitleddays(' + id + ');" title="<?php echo lang('entitleddays_contract_index_thead_tip_delete');?>"><i class="mdi mdi-close nolink"></i></a>' +
                        '&nbsp;&nbsp;<a href="#" onclick="copy_entitleddays(' + id + ');" title="<?php echo lang('entitleddays_contract_index_thead_tip_copy');?>"><i class="mdi mdi-content-copy nolink"></i></a>' +
                        '&nbsp;&nbsp;<a href="#" onclick="show_edit_entitleddays(' + id + ');" title="<?php echo lang('entitleddays_contract_index_thead_tip_edit');?>"><i class="mdi mdi-pencil nolink"></i></a></td>' +
                        '<td data-order="' + moment.utc(startdate, "YYYY-MM-DD").unix() + '">' + viz_startdate + '</td>' +
                        '<td data-order="' + moment.utc(enddate, "YYYY-MM-DD").unix() + '">' + viz_enddate + '</td>' +
                        '<td data-order="' + days.toFixed(2) + '"><span id="days' + id + '" class="credit">' + days.toFixed(2) + '</span> &nbsp; ' +
                        '<a href="#" onclick="Javascript:incdec(' + id + ', \'decrease\');"><i class="mdi mdi-minus nolink"></i></a>' +
                        '&nbsp; <a href="#" onclick="Javascript:incdec(' + id + ', \'increase\');"><i class="mdi mdi-plus nolink"></i></a></td>' +
                        '<td data-id="' + type + '">' + type_name + '</td>' +
                        '<td>' + description + '</td>' +
                    '</tr>';
                objRow=$(htmlRow);
                oTable.row.add(objRow).draw();
              $('#frmModalAjaxWait').modal('hide');
        });
    }

    $(function () {
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

        $("#viz_startdate").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: '<?php echo lang('global_date_js_format');?>',
            altFormat: "yy-mm-dd",
            altField: "#startdate",
            numberOfMonths: 3,
                  onClose: function( selectedDate ) {
                    $( "#viz_enddate" ).datepicker( "option", "minDate", selectedDate );
                  }
        }, $.datepicker.regional['<?php echo $language_code;?>']);

        $("#viz_enddate").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: '<?php echo lang('global_date_js_format');?>',
            altFormat: "yy-mm-dd",
            altField: "#enddate",
            numberOfMonths: 3,
                  onClose: function( selectedDate ) {
                    $( "#viz_startdate" ).datepicker( "option", "maxDate", selectedDate );
                  }
        }, $.datepicker.regional['<?php echo $language_code;?>']);

        //Force decimal separator whatever the locale is
        $( "#days" ).keyup(function() {
            var value = $("#days").val();
            value = value.replace(",", ".");
            $("#days").val(value);
        });

        $("body").on("keyup", function(e){
            if (e.keyCode == 27) {
                if ($('#frmAddEntitledDays').hasClass('in')) {
                    $('#frmAddEntitledDays').modal('hide');
                }
            }
        });

        //On load, try to get stepping value from a cookie
        if(Cookies.get('ent_contract_step') !== undefined) {
            step = parseFloat(Cookies.get('ent_contract_step'));
            $("#txtStep").val(step);
        }//Default to 1
        //Update step value if it is a number
        $("#txtStep").change(function() {
            if (!isNaN($("#txtStep").val())) {
                Cookies.set('ent_contract_step', $("#txtStep").val());
                step = parseFloat($("#txtStep").val());
            }
        });

        //Transform the HTML table in a fancy datatable
        oTable = $('#entitleddayscontract').DataTable({
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
    });
</script>
