<?php
// psalm-bootstrap.php

// Définition des constantes vitales de CodeIgniter 3 pour que Psalm ne panique pas
define('BASEPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/application/');
define('VIEWPATH', __DIR__ . '/application/views/');
define('ENVIRONMENT', 'development');
