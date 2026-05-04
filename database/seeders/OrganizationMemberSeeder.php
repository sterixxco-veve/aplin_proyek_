<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationMemberSeeder extends Seeder
{
    public function run(): void
    {
        $user = DB::table('users')->first();
        $org = DB::table('organizations')->first();

        DB::table('organization_members')->insert([
            [
                'organization_id' => $org->id_org,
                'user_id' => $user->id_user,
                'role' => 'admin_org'
            ]
        ]);
    }
}