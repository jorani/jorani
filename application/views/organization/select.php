<?php 
/**
 * This partial view (embedded into a modal form) allows a user to select an entity of the organization.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.2.0
 */
?>

<div class="input-append">
<input type="text" placeholder="<?php echo lang('organization_select_field_search_placeholder');?>" id="txtSearch" />
<button id="cmdSearchOrg" class="btn btn-primary"><?php echo lang('organization_select_button_search');?></button>
</div>

<div style="text-align: left;" id="organization"></div>

<script type="text/javascript">
    $(function () {
        //Search in the treeview
        $("#cmdSearchOrg").click(function () {
            $("#organization").jstree("search", $("#txtSearch").val());
        });
        
        $('#organization').jstree({
            rules : {
                deletable  : false,
                creatable  : false,
                draggable  : false,
                dragrules  : false,
                renameable : false
              },
            core : {
              multiple : false,
              data : {
                url : function (node) {
                  return node.id === '#' ? 
                    '<?php echo base_url(); ?>organization/root' : 
                    '<?php echo base_url(); ?>organization/children';
                },
                'data' : function (node) {
                  return { 'id' : node.id };
                }
              },
              'check_callback' : true
            },
            plugins: [ "search", "state", "sort" ]
        });
    });
</script>
