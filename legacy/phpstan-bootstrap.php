<?php
define('BASEPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/application/');
define('VIEWPATH', APPPATH . 'views/');
define('ENVIRONMENT', 'development');

require_once 'system/core/Controller.php';
require_once 'system/core/Model.php';
require_once 'system/core/Loader.php';
require_once 'application/core/MY_Loader.php';

require_once 'system/database/DB_driver.php';
require_once 'system/database/DB_query_builder.php';
require_once 'system/database/DB_result.php';

// Create aliases to reduce the number of false positives
if (!class_exists('CI_DB_result')) {
    class_alias('CI_DB_mysqli_result', 'CI_DB_result');
}
if (!class_exists('CI_DB_query_builder')) {
    class_alias('CI_DB_query_builder', 'CI_DB');
}

