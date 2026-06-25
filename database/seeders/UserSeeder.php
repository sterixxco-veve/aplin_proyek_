<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id_user' => (string) Str::uuid(),
                'name' => 'Super Admin',
                'email' => 'admin@mail.com',
                'password' => Hash::make('password'),
                'id_divisi' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => (string) Str::uuid(),
                'name' => 'Coordinator',
                'email' => 'coord@mail.com',
                'password' => Hash::make('password'),
                'id_divisi' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}