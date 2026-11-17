<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Auto-ensure SQLite database & storage permissions for shared hosting
$dbPath = __DIR__.'/../database';
if (file_exists($dbPath.'/database.sqlite')) {
    @chmod($dbPath.'/database.sqlite', 0666);
    @chmod($dbPath, 0777);
}
$storagePath = __DIR__.'/../storage';
if (is_dir($storagePath)) {
    @chmod($storagePath, 0777);
    @chmod($storagePath.'/framework', 0777);
    @chmod($storagePath.'/framework/views', 0777);
    @chmod($storagePath.'/framework/sessions', 0777);
    @chmod($storagePath.'/logs', 0777);
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
