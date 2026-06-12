<?php

// 1. Jalankan migrasi otomatis secara aman di cloud Vercel
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->call('migrate', ['--force' => true]);

// 2. Lanjutkan render halaman Laravel seperti biasa
require __DIR__ . '/../public/index.php';