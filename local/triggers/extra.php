<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * This function is called just before saving the overtime request.
 * A reference to the instance of the CI Controller is passed so you can change everything.
 * @param reference to CI Controller object
 * 
 */
function triggerCreateExtraRequest(CI_Controller $controller)
{
    //log_message('error', 'Test accessing posted value : ' . $controller->input->post('date'));
}
