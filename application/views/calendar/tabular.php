<?php
/**
 * This view builds a monthly tabular calendar for a group of employees.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.3.0
 */
?>

<h2><?php echo lang('calendar_tabular_title');?> &nbsp;<?php echo $help;?></h2>

<div class="row-fluid">
    <div class="span4">
        <label for="chkIncludeChildren">
            <?php echo lang('calendar_organization_field_select_entity');?>
            &nbsp;(<input type="checkbox" class="input-centered" checked id="chkIncludeChildren" name="chkIncludeChildren"> <?php echo lang('calendar_tabular_check_include_subdept');?>)
        </label>
        <div class="input-prepend input-append">
            <span class="add-on" id="spnAddOn"><i class="mdi mdi-sitemap"></i></span>
            <input type="text" id="txtEntity" name="txtEntity" value="<?php echo $department;?>" readonly />
            <button id="cmdSelectEntity" class="btn btn-primary" title="<?php echo lang('calendar_tabular_button_select_entity');?>"><i class="mdi mdi-sitemap"></i></button>
            <?php if ($mode == 'connected') { ?>
           <button id="cmdSelectList" class="btn btn-primary" title="<?php echo lang('calendar_tabular_button_select_list');?>"><i class="mdi mdi-account-multiple"></i></button>
            <?php } ?>
        </div>
    </div>
    <div class="span3">
        <label for="txtMonthYear">
        <?php echo lang('calendar_tabular_field_month');?> / <?php echo lang('calendar_tabular_field_year');?>
        </label>
        <div class="input-prepend input-append">
            <button id="cmdPrevious" class="btn btn-primary" title="<?php echo lang('calendar_tabular_button_previous');?>"><i class="mdi mdi-chevron-left"></i></button>
            <input type="text" style="cursor:pointer;" id="txtMonthYear" name="txtMonthYear" value="<?php echo $monthName . ' ' . $year;?>" class="input-medium" readonly />
            <button id="cmdNext" class="btn btn-primary" title="<?php echo lang('calendar_tabular_button_next');?>"><i class="mdi mdi-chevron-right"></i></button>
        </div>
    </div>
    <div class="span3">
        <label for="chkDisplayTypes">
            <input type="checkbox" class="input-centered" checked id="chkDisplayTypes" name="chkDisplayTypes"><?php echo lang('calendar_tabular_check_display_types');?>
        </label>
    </div>
    <div class="span2">
        <span class="pull-right">
            <button id="cmdExport" class="btn btn-primary"><i class="mdi mdi-download"></i>&nbsp;<?php echo lang('calendar_tabular_button_export');?></button>
        </span>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <span class="label"><input type="checkbox" checked id="chkPlanned" class="filterStatus"> &nbsp;<?php echo lang('Planned');?></span> &nbsp;
        <span class="label label-success"><input type="checkbox" checked id="chkAccepted" class="filterStatus"> &nbsp;<?php echo lang('Accepted');?></span> &nbsp;
        <span class="label label-warning"><input type="checkbox" checked id="chkRequested" class="filterStatus"> &nbsp;<?php echo lang('Requested');?></span> &nbsp;
        <span class="label label-important" style="background-color: #ff0000;"><input type="checkbox" checked id="chkCancellation" class="filterStatus"> &nbsp;<?php echo lang('Cancellation');?></span> &nbsp;
    </div>
</div>


<div class="row-fluid">
    <div class="span12" id="spnTabularView"><?php echo $tabularPartialView; ?></div>
</div>

<div id="frmSelectEntity" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3><?php echo lang('calendar_tabular_popup_entity_title');?></h3>
    </div>
    <div class="modal-body" id="frmSelectEntityBody">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <button onclick="select_entity();" class="btn" data-dismiss="modal"><?php echo lang('OK');?></button>
        <button data-dismiss="modal" class="btn"><?php echo lang('Cancel');?></button>
    </div>
</div>

<div id="frmSelectList" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3><?php echo lang('calendar_tabular_button_select_list');?></h3>
    </div>
    <div class="modal-body" id="frmSelectListBody">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <button onclick="select_list();" data-dismiss="modal" class="btn"><?php echo lang('OK');?></button>
        <button data-dismiss="modal" class="btn"><?php echo lang('Cancel');?></button>
    </div>
</div>

<link rel="stylesheet" href="<?php echo base_url();?>assets/bootstrap-datepicker-1.8.0/css/bootstrap-datepicker.min.css">
<script src="<?php echo base_url();?>assets/bootstrap-datepicker-1.8.0/js/bootstrap-datepicker.min.js"></script>
<?php if ($language_code != 'en') {?>
<script src="<?php echo base_url();?>assets/bootstrap-datepicker-1.8.0/locales/bootstrap-datepicker.<?php echo $language_code;?>.min.js"></script>
<?php }?>

<style>
#frmSelectList
{
    width: 700px;
    margin-left:  -350px !important;
}
</style>

<script src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>
<script type="text/javascript">
    //Global locale for moment objects
    moment.locale('<?php echo $language_code;?>', {longDateFormat : {L : '<?php echo lang('global_date_momentjs_format');?>'}});

    var entity = -1; //Id of the selected entity
    var text; //Label of the selected entity
    var entity = <?php echo $entity;?>;
    var month = (<?php echo $month;?> - 1); //Monent.js uses 0 based numbers!
    var year = <?php echo $year;?>;
    var children = '<?php echo $children;?>';
    var displayTypes = '<?php echo $displayTypes;?>';
    var currentDate = moment().year(year).month(month).date(1);
    var listId;
    var listName = '';
    var source = 'treeview';    //treeview or list

    // After selection of an entity from the modal dialog, refresh the partial
    // view if the entity is diferent
    function select_entity() {
        source = 'treeview';
        $('#spnAddOn').html('<i class="mdi mdi-sitemap"></i>');
        entity = $('#organization').jstree('get_selected')[0];
        text = $('#organization').jstree().get_text(entity);
        $('#txtEntity').val(text);
        $("#frmSelectEntity").modal('hide');
        reloadTabularView();
    }

    // After selection of a list from the modal dialog, refresh the partial
    // view if the entity is diferent
    function select_list() {
        //$('#frmSelectList').modal('hide');
        //Reload the partial view
        listId = $('#cboList').val();
        if (listId != -1 ) {
            source = 'list';
            $('#spnAddOn').html('<i class="mdi mdi-account-multiple"></i>');
            listName = $('#cboList option:selected').text();
            $('#txtEntity').val(listName);
            reloadTabularView();
        }
    }

    // Return a boolean value representing the value of checkbox "include children"
    function includeChildren() {
        if ($('#chkIncludeChildren').prop('checked') == true) {
            return '1';
        } else {
            return '0';
        }
    }

    // Return a boolean value representing the value of checkbox "display types"
    function displayLeaveTypes() {
        if ($('#chkDisplayTypes').prop('checked') == true) {
            return '1';
        } else {
            return '0';
        }
    }
    
    // Build the status filter based on the selected options
    function buildStatusesFilter() {
        statuses = "";
        if ($("#chkPlanned").prop("checked")) statuses+="1|";
        if ($("#chkRequested").prop("checked")) statuses+="2|";
        if ($("#chkAccepted").prop("checked")) statuses+="3|";
        if ($("#chkCancellation").prop("checked")) statuses+="5|";
        statuses = statuses.replace(/\|*$/, "");
        if (statuses!="") statuses = '?statuses=' + statuses;
        return statuses;
    }

    // Reload the partial view containing the tabular calendar
    function reloadTabularView() {
        var url ='';
        children = includeChildren();
        displayTypes = displayLeaveTypes();
        statuses = buildStatusesFilter();
        
        if (source == 'treeview') {
            url = '<?php echo base_url();?>calendar/tabular/partial/' +
                entity + '/' + (month + 1) + '/' + year + '/' + children + '/' +
                displayTypes + statuses;
        } else {
            url = '<?php echo base_url();?>calendar/tabular/list/partial/' +
                    listId + '/' + (month + 1) + '/' + year + '/' +
                    displayTypes + statuses;
        }

        $("#spnTabularView").html('<img src="<?php echo base_url();?>assets/images/loading.gif">');
        //Month number needs to be converted between monment.js and PHP
        $("#spnTabularView").load(url,
            function(response, status, xhr) {
            if (xhr.status == 401) {
                $("#frmShowHistory").modal('hide');
                bootbox.alert("<?php echo lang('global_ajax_timeout');?>", function() {
                    //After the login page, we'll be redirected to the current page
                   location.reload();
                });
            }
        });
        // Update the text view containing the date
        var fullDate = moment().date(1).month(month).year(year).format("MMMM");
        fullDate = fullDate + ' ' + year;
        $("#txtMonthYear").val(fullDate);
    }
    
    //Return a URL parameter identified by 'name'
    function getURLParameter(name) {
      return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [null, ''])[1].replace(/\+/g, '%20')) || null;
    }

    $(document).ready(function() {
        //Select checkboxes depending on URL
        if (children == '1') {
            $("#chkIncludeChildren").prop("checked", true);
        } else {
            $("#chkIncludeChildren").prop("checked", false);
        }
        if (displayTypes == '1') {
            $("#chkDisplayTypes").prop("checked", true);
        } else {
            $("#chkDisplayTypes").prop("checked", false);
        }

        // On changing 'include children' / 'include types' checkboxes,
        // reload the partial view
        $('#chkIncludeChildren').change(function() {
            reloadTabularView();
        });
        $('#chkDisplayTypes').change(function() {
            reloadTabularView();
        });

        //Intialize Month/Year selection
        $("#txtMonthYear").datepicker({
            format: "MM yyyy",
            startView: 1,
            minViewMode: 1,
            todayBtn: 'linked',
            todayHighlight: true,
            language: "<?php echo $language_code;?>",
            autoclose: true
        }).on("changeDate", function(e) {
            month = new Date(e.date).getMonth();
            //Doesn't work : year = new Date(e.date).getYear();
            year = parseInt(String(e.date).split(" ")[3]);
            currentDate = moment().year(year).month(month).date(1);
            reloadTabularView();
        });

        //Popup select entity
        $("#cmdSelectEntity").click(function() {
            $("#frmSelectEntity").modal('show');
            $("#frmSelectEntityBody").load('<?php echo base_url(); ?>organization/select');
        });
        //Popup select list
        $("#cmdSelectList").click(function() {
            $("#frmSelectList").modal('show');
            $("#frmSelectListBody").load('<?php echo base_url(); ?>organization/lists');
        });

        //Export the report into Excel
        $("#cmdExport").click(function() {
            var exportUrl = '';
            var displayTypes = displayLeaveTypes();
            if (source == 'treeview') {
                children = includeChildren();
                if (entity != -1) {
                    exportUrl = '<?php echo base_url();?>calendar/tabular/export/' +
                            entity + '/' + (month+1) + '/' + year + '/' + children +
                            '/' + displayTypes;
                    document.location.href = exportUrl;
                }
            } else {
                if (listId != -1) {
                    exportUrl = '<?php echo base_url();?>calendar/tabular/list/export/' +
                            listId + '/' + (month+1) + '/' + year + '/' + displayTypes;
                    document.location.href = exportUrl;
                }
            }
        });

        //Previous/Next
        $('#cmdPrevious').click(function() {
            currentDate = currentDate.add(-1, 'M');
            month = currentDate.month();
            year = currentDate.year();
            reloadTabularView();
        });
        $('#cmdNext').click(function() {
            currentDate = currentDate.add(1, 'M');
            month = currentDate.month();
            year = currentDate.year();
            reloadTabularView();
        });

        //Load alert forms
        $(".alert").alert();
        $('.alert').on('hidden', function() {
            $(this).removeData('modal');
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
            reloadTabularView();
        }
        $('.filterStatus').on('change',function(){
            reloadTabularView();
        });
    });
</script>
