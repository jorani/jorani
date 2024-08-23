<?php
/**
 * This view allows to manage the service accounts (OAuth clients) and sessions.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.6.0
 */
?>

        <h2><?php echo $title;?><?php echo $help;?></h2>

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#clients"><?php echo lang('admin_oauthclients_tab_clients');?></a></li>
    <li><a data-toggle="tab" href="#sessions"><?php echo lang('admin_oauthclients_tab_sessions');?></a></li>
</ul>

<div class="tab-content">

  <div class="tab-pane active" id="clients">
      <div class="row-fluid">
    <div class="span12">
        <p><?php echo lang('admin_oauthclients_tab_clients_description');?></p>

        <table cellpadding="0" cellspacing="0" border="0" class="display" id="clientsRest" width="100%">
            <thead>
                <tr>
                    <th>client_id</th>
                    <th>client_secret</th>
                    <th>redirect_uri</th>
                    <th>grant_types</th>
                    <th>scope</th>
                    <th>user_id</th>
                </tr>
            </thead>
          <tbody>
            <?php foreach ($clients as $client): ?>
            <tr data-id="<?php echo $client['client_id']; ?>">
                <td>
                    <a href="#" class="confirm-delete" data-id="<?php echo $client['client_id'];?>" title="<?php echo lang('admin_oauthclients_thead_tip_delete');?>"><i class="mdi mdi-delete nolink"></i></a>
                    &nbsp;<?php echo $client['client_id']; ?>
                </td>
                <td><?php echo $client['client_secret']; ?></td>
                <td><?php echo $client['redirect_uri']; ?></td>
                <td><?php echo $client['grant_types']; ?></td>
                <td><?php echo $client['scope']; ?></td>
                <td><?php echo $client['user_id']; ?></td>
            </tr>
            <?php endforeach ?>
          </tbody>
        </table>

         </div>
        </div>
        <div class="row-fluid"><div class="span12">&nbsp;</div></div>

        <div class="row-fluid">
            <div class="span12">
              <button onclick="$('#frmAddClient').modal('show');" class="btn btn-primary"><i class="mdi mdi-plus-circle"></i>&nbsp;<?php echo lang('admin_oauthclients_button_add');?></button>
              &nbsp;
            </div>
        </div>
  </div>

   <div class="tab-pane" id="sessions">
        <p><?php echo lang('admin_oauthclients_tab_sessions_description');?></p>

        <table cellpadding="0" cellspacing="0" border="0" class="display" id="sessionsRest" width="100%">
            <thead>
                <tr>
                    <th>access_tokens</th>
                    <th>client_id</th>
                    <th>user_id</th>
                    <th>expires</th>
                    <th>scope</th>
                </tr>
            </thead>
          <tbody>
            <?php foreach ($tokens as $token): ?>
            <tr>
                <td><?php echo $token['access_token']; ?></td>
                <td><?php echo $token['client_id']; ?></td>
                <td><?php echo $token['user_id']; ?></td>
                <td><?php echo $token['expires']; ?></td>
                <td><?php echo $token['scope']; ?></td>
            </tr>
            <?php endforeach ?>
          </tbody>
        </table>

        <div class="row-fluid"><div class="span12">&nbsp;</div></div>

        <div class="row-fluid">
            <div class="span12">
              <!--<button onclick="purgeTokens();" class="btn btn-danger"><i class="mdi mdi-delete"></i>&nbsp;<?php echo lang('admin_oauthclients_button_purge');?></button>//-->
            </div>
        </div>
   </div>
  </div>

<div id="frmAddClient" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmAddClient').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('admin_oauthclients_popup_add_title');?></h3>
    </div>
    <div class="modal-body">
        <div class="row-fluid">
            <div class="span6">
                <label for="client_id">client_id</label>
                <input type="text" name="client_id" id="client_id" required /><br />
                <label for="client_secret">client_secret</label>
                <input type="text" name="client_secret" id="client_secret" required /><br />
                <label for="redirect_uri">redirect_uri</label>
                <input type="text" name="redirect_uri" id="redirect_uri" /><br />
                <label for="grant_types">grant_types</label>
                <input type="text" name="grant_types" id="grant_types" /><br />
                <label for="scope">scope</label>
                <input type="text" name="scope" id="scope" /><br />
                <label for="user_id">user_id</label>
                <div class="input-append">
                    <input type="text" name="user_id" id="user_id" />
                    <button id="cmdSelectUser" class="btn btn-primary"><i class="mdi mdi-account-search"></i></button>
                </div>
                <br />
            </div>
            <div class="span6">
                <div class="well">
                    Scope (comma separated):
                    <ul id="scopeListItems">
                        <li>users</li>
                        <li>entitlements</li>
                        <li>contracts</li>
                        <li>leaves</li>
                        <li>selfservice</li>
                        <li>etc.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button onclick="createClient();" class="btn"><?php echo lang('OK');?></button>
        <button onclick="$('#frmAddClient').modal('hide');" class="btn btn-danger"><?php echo lang('Cancel');?></button>
    </div>
</div>

<div id="frmSelectUser" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmSelectUser').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('admin_oauthclients_popup_select_user_title');?></h3>
    </div>
    <div class="modal-body" id="frmSelectUserBody">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="select_user();" class="btn"><?php echo lang('OK');?></a>
        <a href="#" onclick="$('#frmSelectUser').modal('hide');" class="btn"><?php echo lang('Cancel');?></a>
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

<link href="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>

<script type="text/javascript">
var tableClients = null;

function createClient() {
    $('#frmModalAjaxWait').modal('show');
    $.ajax({
        url: "<?php echo base_url(); ?>admin/oauthclients/create",
            type: "POST",
            data: {
                client_id: $('#client_id').val(),
                client_secret: $('#client_secret').val(),
                redirect_uri: $('#redirect_uri').val(),
                grant_types: $('#grant_types').val(),
                scope: $('#scope').val(),
                user_id: $('#user_id').val()
            }
      }).done(function(result) {
        $('#frmModalAjaxWait').modal('hide');
        if (result == "DUPLICATE") {
            bootbox.alert("<?php echo lang('admin_oauthclients_error_exists');?>");
        } else {
          //Add into the datatable
          contentHTML = "<tr data-id='" + $('#client_id').val() + "' >" +
                "<td>" +
                    "<a href='#' class='confirm-delete' data-id='" + $('#client_id').val() + "' title='<?php echo lang('admin_oauthclients_thead_tip_delete');?>'><i class='icon-trash'></i></a>" +
                    "&nbsp;" + $('#client_id').val() + "" +
                "</td>" +
                "<td>" + $('#client_secret').val() + "</td>" +
                "<td>" + $('#redirect_uri').val() + "</td>" +
                "<td>" + $('#grant_types').val() + "</td>" +
                "<td>" + $('#scope').val() + "</td>" +
                "<td>" + $('#user_id').val() + "</td>" +
            "<tr>";
            oRow = $(contentHTML);
            tableClients.row.add( oRow ).draw();
        }

      });
}

//If confirmed, purge all sessions wether they are active or not
function purgeTokens() {
    bootbox.confirm("<?php echo lang('admin_oauthclients_confirm_delete');?>", function(result) {
        if (result) {
            window.location.href = "<?php echo base_url(); ?>admin/oauthtokens/purge";
        }
    });
}

//Popup select user: on click OK, find the user id for the selected line
function select_user() {
    var employees = $('#employees').DataTable();
    if ( employees.rows({ selected: true }).any() ) {
        var userId = employees.rows({selected: true}).data()[0][0];
        $('#user_id').val(userId);
    }
    $("#frmSelectUser").modal('hide');
}

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
            bootbox.alert("Unexpected Ajax Error");
        }
    });

    //Append sope items to scope list on double click
    $("#scopeListItems li").dblclick(function(){
        if ($("#scope").val() == "") {
            $("#scope").val($(this).text());
        } else {
            $("#scope").val($("#scope").val() + "," + $(this).text());
        }
    });

    //open a tab if a hash was passed by URL
    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
    }

    //Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    })

    //Transform the HTML table in a fancy datatable
    tableClients = $('#clientsRest').DataTable({
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

    //sessions (OAuth2 tokens)
    tableSessions = $('#sessionsRest').DataTable({
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

    //Transform Add Client modal
    $("#frmAddClient").alert();

    //Popup select user
    $("#cmdSelectUser").click(function() {
        $("#frmSelectUser").modal('show');
        $("#frmSelectUserBody").load('<?php echo base_url(); ?>users/employees');
    });

    //Display a modal pop-up so as to confirm if a user has to be deleted or not
    //We build a complex selector because datatable does horrible things on DOM...
    //a simplier selector doesn't work when the delete is on page >1
    $("#clientsRest tbody").on('click', '.confirm-delete',  function(){
        var id = $(this).data('id');
        bootbox.confirm("<?php echo lang('admin_oauthclients_confirm_delete');?>", function(result) {
            if (result) {
                $('#frmModalAjaxWait').modal('show');
                $.ajax({
                    url: "<?php echo base_url(); ?>admin/oauthclients/delete",
                        type: "POST",
                        data: {
                            client_id: id
                        }
                  }).done(function() {
                      //Delete the row in the datatable
                      tableClients.rows('tr[data-id="' + id + '"]').remove().draw();
                      $('#frmModalAjaxWait').modal('hide');
                  });
            }
        });
    });
});
</script>
