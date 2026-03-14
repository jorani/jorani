<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }
/* 
 * This code is included into create and edit leave views
 */
//You can get access to the controller instance or use $this
//$ci = get_instance(); 
//Or to any item of the configuration file
//$this->config->item();
//The language helper is accessible as well
//echo lang('leaves_create_field_overlapping_message');
//This last part will be display at the bottom of the form
?>

<script type="text/javascript">
//This code is called from the function (validate_form) that validates user input
//If you return false, this will invalidate the form
function triggerValidateEditForm() {
    //bootbox.alert("no");
    //return false;    //The user inputs are not valid
    return true;    //The user inputs are valid but the other mandatory fields are checked
}

//This code is called from the function (validate_form) that validates user input
//If you return false, this will invalidate the form
function triggerValidateCreateForm() {
    //bootbox.alert("no");
    //return false;    //The user inputs are not valid
    return true;    //The user inputs are valid but the other mandatory fields are checked
}

//If you want to trigger something when the page is loaded, use JQuery as in this example :
$(function () {
    //bootbox.alert("On load");
});
</script>
