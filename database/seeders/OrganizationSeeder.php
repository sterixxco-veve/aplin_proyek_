<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('organizations')->insert([
            [
                'nama_org' => 'GDGOC Indonesia',
                'logo_path' => null
            ]
        ]);
    }
}