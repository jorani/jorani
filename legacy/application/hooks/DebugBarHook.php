<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * DebugBarHook
 * 
 * This class is a hook for DebugBar
 */
class DebugBarHook
{
    /**
     * Bootstrap the DebugBar
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
     */
    public function finalize(): void
    {
        $CI =& get_instance();

        if (isset($CI->debugbarservice)) {
            $CI->debugbarservice->finish();
        }
    }
}
