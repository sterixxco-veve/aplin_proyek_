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

        if (!$user || !$org) {
            return;
        }

        DB::table('organization_members')->insert([
            [
                'organization_id' => $org->id_org,
                'user_id'         => $user->id_user,
                'id_divisi'       => $user->id_divisi, // atau 1
                'position'=> 'member',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);
    }
}