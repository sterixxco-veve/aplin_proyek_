<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

echo "STEP 1<br>";

$app = require_once __DIR__.'/../bootstrap/app.php';

echo "STEP 2<br>";

$kernel = $app->make(Kernel::class);

echo "STEP 3<br>";

$request = Request::capture();

echo "STEP 4<br>";

$response = $kernel->handle($request);

echo "STEP 5<br>";

$response->send();