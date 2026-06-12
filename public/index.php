<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

echo "STEP 1<br>";

$app = require_once __DIR__.'/../bootstrap/app.php';
echo "<pre>";
echo "VIEW=";
var_dump($app->bound('view'));

echo " CONFIG=";
var_dump($app->bound('config'));

echo " ROUTER=";
var_dump($app->bound('router'));
exit;

echo "STEP 2<br>";

$kernel = $app->make(Kernel::class);

echo "STEP 3<br>";

$request = Request::capture();

try {

    echo "STEP 4<br>";

    $response = $kernel->handle($request);

    echo "STEP 5<br>";

    $response->send();

} catch (\Throwable $e) {

    echo "<pre>";
    echo "ERROR CLASS:\n";
    echo get_class($e);

    echo "\n\nMESSAGE:\n";
    echo $e->getMessage();

    echo "\n\nFILE:\n";
    echo $e->getFile().':'.$e->getLine();

    echo "\n\nTRACE:\n";
    echo $e->getTraceAsString();

    exit;
}