<?php
// psalm-bootstrap.php

// Define CodeIgniter 3 constants
define('BASEPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/application/');
define('VIEWPATH', __DIR__ . '/application/views/');
define('ENVIRONMENT', 'development');

// Load CodeIgniter 3 global functions
require_once BASEPATH . 'core/Common.php';
require_once BASEPATH . 'helpers/url_helper.php';
require_once BASEPATH . 'helpers/form_helper.php';
