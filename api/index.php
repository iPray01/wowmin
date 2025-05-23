<?php

// Set the base path for the Laravel application
$basePath = realpath(__DIR__ . '/..');

// Set the public path
$publicPath = $basePath . '/dist';

// Load Composer's autoloader
require $basePath . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once $basePath . '/bootstrap/app.php';

// Run the application
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Set the public path
$app->usePublicPath($publicPath);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
