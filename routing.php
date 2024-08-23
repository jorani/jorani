<?php
/**
 * Routing file for PHP built-in server. (not suitable for production).
 * This is for a quick test of the application. You still need a MySQL DB.
 * From the root of this repository, launch:
 * php -S localhost:8888 routing.php
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.5.0
 */

$disallow = FALSE;
if (strpos($_SERVER['REQUEST_URI'], '.htaccess') !== false) { $disallow = TRUE; }
if (strpos($_SERVER['REQUEST_URI'], '/tests/') !== false) { $disallow = TRUE; }
if (strpos($_SERVER['REQUEST_URI'], '/tests/') !== false) { $disallow = TRUE; }
if (strpos($_SERVER['REQUEST_URI'], '/keys/') !== false) { $disallow = TRUE; }
if (strpos($_SERVER['REQUEST_URI'], '/system/') !== false) { $disallow = TRUE; }
if (strpos($_SERVER['REQUEST_URI'], '/docs/') !== false) { $disallow = TRUE; }
if (strpos($_SERVER['REQUEST_URI'], '/local/') !== false) { $disallow = TRUE; }
if (strpos($_SERVER['REQUEST_URI'], '/sql/') !== false) { $disallow = TRUE; }

if ($disallow) {
    //Forbidden files
    header("HTTP/1.1 403 Forbidden");
    echo "403 Forbidden"; 
} else {
    $filename = $_SERVER['SCRIPT_NAME'];
    if (file_exists(__DIR__ . '/' . $filename)) {
        return FALSE; // serve the requested resource as-is.
    } else {
        // this is the missing piece!
        $_SERVER['SCRIPT_NAME'] = '/index.php'; 
        include_once (__DIR__ . '/index.php');
    }
}
