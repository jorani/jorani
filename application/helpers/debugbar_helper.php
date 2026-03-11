<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('debugbar_head')) {
    function debugbar_head(): string
    {
        $CI =& get_instance();

        if (!isset($CI->debugbarservice)) {
            $CI->load->library('DebugBarService');
            $CI->debugbarservice->bootstrap();
        }

        return $CI->debugbarservice->renderHead();
    }
}

if (!function_exists('debugbar_footer')) {
    function debugbar_footer(): string
    {
        $CI =& get_instance();

        if (!isset($CI->debugbarservice)) {
            return '';
        }

        return $CI->debugbarservice->renderFooter();
    }
}
