<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This class is a hook for DebugBar
 * @license https://opensource.org/licenses/MIT MIT
 */
class DebugBarHook
{
    /**
     * Bootstrap the DebugBar
     * @return void
     */
    public function bootstrap(): void
    {
        $CI =& get_instance();
        $CI->load->config('debugbar', TRUE);
        $CI->load->library('DebugBarService');
        $CI->debugbarservice->bootstrap();
    }

    /**
     * Finalize the DebugBar
     * @return void
     */
    public function finalize(): void
    {
        $CI =& get_instance();
        if (isset($CI->debugbarservice)) {
            $CI->debugbarservice->finish();
        }
    }
}
