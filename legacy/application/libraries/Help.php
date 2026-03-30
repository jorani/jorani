<?php
/**
 * This library helps us to deal with links to documentation
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.3.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * This class helps to build links to documentation pages
 */
class Help
{

    /**
     * Access to CI framework so as to use other libraries
     * @var CI_Controller Code Igniter framework
     */
    private $CI;

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->helper('language');
        $this->CI->load->library('session');
        $this->CI->lang->load('global', $this->CI->session->userdata('language'));
    }

    /**
     * Test if a help page is available and returns a help link if so
     * @param string $page name of a page of the application
     * @return string link to Help page or empty string
     */
    public function create_help_link($page)
    {
        if (lang($page) != "") {
            return '&nbsp;' .
                '<a href="' . lang($page) . '"' .
                ' title="' . lang('global_link_tooltip_documentation') . '"' .
                ' target="_blank" rel="nofollow"><sup><i class="mdi mdi-help-circle-outline mdi-18px nolink"></i></sup></a>';
        } else {
            return '';
        }
    }
}
