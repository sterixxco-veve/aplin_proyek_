<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id_user' => Str::uuid(),
                'name' => 'Super Admin',
                'email' => 'admin@mail.com',
                'password' => Hash::make('password'),
                'role' => 'super_admin'
            ],
            [
                'id_user' => Str::uuid(),
                'name' => 'Coordinator',
                'email' => 'coord@mail.com',
                'password' => Hash::make('password'),
                'role' => 'global_coordinator'
            ]
        ]);
    }
}