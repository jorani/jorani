<?php
/**
 * Customized CI_Loader class (loading custom views)
 * @copyright Copyright (c) 2014-2023 Benjamin BALET
 * @license   http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link      https://github.com/bbalet/jorani
 * @since     0.4.3
 */

/**
 * This class extends the capacity of CodeIgniter view loader
 */
class MY_Loader extends CI_Loader {
   
  /**
   * Load a view that can be anywhere into the installation (outside application/views)
   * @param string $folder path where we can find the extended views
   * @param string $view name of the extended view
   * @param array $vars data to be passed to the extended view
   * @param bool $return TRUE if we want to eval the view and to return it as a  string
   * @return string (optionnal) content of the parsed view
   */
  function customView($folder, $view, $vars = array(), $return = FALSE) {
    //We don't use $this->_ci_view_paths
    $this->_ci_view_paths = array_merge(array($folder . '/' => TRUE), $this->_ci_view_paths);
    return $this->_ci_load(array(
                '_ci_view' => $view,
                '_ci_vars' => $this->_ci_prepare_view_vars($vars),
                '_ci_return' => $return
            ));
  }
  
}
