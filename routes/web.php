<?php
use Illuminate\Support\Facades\DB;


Route::get('/container-test', function () {
    return [
        'view' => app()->bound('view'),
        'router' => app()->bound('router'),
        'config' => app()->bound('config'),
        'events' => app()->bound('events'),
    ];
});

Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return 'Database Connected';
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});

Route::get('/diag', function () {
    return [
        'view_exists' => app()->bound('view'),
        'config_exists' => app()->bound('config'),
        'router_exists' => app()->bound('router'),
    ];
});