<?php

declare(strict_types=1);

/*
 * Main front controller for the strangler fig pattern.
 * It routes incoming HTTP requests either to the legacy CodeIgniter app
 * or to the Laravel application based on URL rules.
 */

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

/*
 * Normalize script base path if the application is not deployed at domain root.
 */
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
if ($basePath !== '' && $basePath !== '/' && str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath)) ?: '/';
}

/*
 * Routing strategy:
 * - Laravel handles new APIs and new features
 * - Legacy handles the existing UI and remaining routes
 */
$useLaravel = false;

if (
    str_starts_with($uri, '/api/v2/')
) {
    $useLaravel = true;
}

if ($useLaravel) {
    require __DIR__ . '/public/index.php';
    exit;
}

require __DIR__ . '/legacy/index.php';
exit;
