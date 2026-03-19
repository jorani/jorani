<?php

declare(strict_types=1);

/*
 * Main front controller for the strangler fig pattern.
 * It routes incoming HTTP requests either to the legacy CodeIgniter app
 * or to the new application based on URL rules.
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
 * Forbidden access to root files
 */
$requestedPath = __DIR__ . DIRECTORY_SEPARATOR . ltrim($uri, '/');
if (is_file($requestedPath)) {
    $realRequested = realpath($requestedPath);
    $realCurrentDir = realpath(__DIR__);
    if ($realRequested && dirname($realRequested) === $realCurrentDir && basename($realRequested) !== 'index.php') {
        header('HTTP/1.1 403 Forbidden');
        exit('Direct access to root files is forbidden.');
    }
}

/*
 * Routing strategy:
 * - New app handles new APIs and new features
 * - Legacy CI app handles the existing UI and remaining routes
 */
$useNewApp = false;

switch ($uri) {
    case '/requirements.php':
    case '/testapi.php':
    case '/testldap.php':
    case '/testmail.php':
        require __DIR__ . '/legacy/' . $uri;
        exit;
    default:
        break;
}

if (
    str_starts_with($uri, '/api/v2/')
) {
    $useNewApp = true;
}

if ($useNewApp) {
    require __DIR__ . '/public/index.php';
    exit;
}

require __DIR__ . '/legacy/index.php';
exit;
