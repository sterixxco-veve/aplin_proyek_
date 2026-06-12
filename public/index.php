<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
echo '<pre>';
echo 'view bound = ';
var_dump($app->bound('view'));

echo 'router bound = ';
var_dump($app->bound('router'));

echo 'config bound = ';
var_dump($app->bound('config'));

exit;
$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
);

$response->send();

$kernel->terminate($request, $response);