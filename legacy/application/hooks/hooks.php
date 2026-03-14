<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hook['post_controller_constructor'][] = [
    'class' => 'DebugBarHook',
    'function' => 'bootstrap',
    'filename' => 'DebugBarHook.php',
    'filepath' => 'hooks',
    'params' => []
];

$hook['post_system'][] = [
    'class' => 'DebugBarHook',
    'function' => 'finalize',
    'filename' => 'DebugBarHook.php',
    'filepath' => 'hooks',
    'params' => []
];
