<?php
/**
 * This controller loads the static and custom pages of the application
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.4.0
 */

if (!defined('BASEPATH')) {exit('No direct script access allowed');}

/**
 * This class serve default and cutom pages.
 * Please note that a page can be the implementation of a custom report (see Controller Report)
 */
class Pages extends CI_Controller {
   
    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {
        parent::__construct();
        setUserContext($this);
    }

    /**
     * Display a simple view indicating that the business object was not found.
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function notfound() {
        $data = getUserContext($this);
        $data['title'] = 'Error';
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('pages/notfound', $data);
        $this->load->view('templates/footer', $data);
    }
    
    /**
     * Display a page with this order of priority (based on the provided page name) :
     *  1. Does the page exist into local/pages/{lang}/ (this allows you to overwrite default pages)?
     *  2. Does the page exist into the views available in views/pages/ folder?
     * Pages are not public and we take into account the language of the connected user.
     * If the page name contains the keyword export, then we don't output the default template.
     * @param string $page Name of the view (and of the corresponding PHP file)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function view($page = 'home') {
        $data = getUserContext($this);
        $trans = array("-" => " ", "_" => " ", "." => " ");
        $data['title'] = ucfirst(strtr($page, $trans)); // Capitalize the first letter
        //The page containing export in their name are returning another MIMETYPE
        if (strpos($page, 'export') === FALSE) {//Don't include header and menu
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
        }
        $view = 'pages/' . $this->language_code . '/' . $page . '.php';
        $pathCI = APPPATH . 'views/';
        $pathLocal = FCPATH . 'local/';
        //Check if we have a user-defined view
        if (file_exists($pathLocal . $view)) {
            $this->load->customView($pathLocal, $view, $data);
        } else {//Load the page from the default location (CI views folder)
            if (!file_exists($pathCI . $view)) {
                log_message('error', '{controllers/pages/view} Not found=' . $pathCI . $view);
                redirect('notfound');
            }
            $this->load->view($view, $data);
        }
        if (strpos($page, 'export') === FALSE) {
            $this->load->view('templates/footer', $data);
        }
    }

}
