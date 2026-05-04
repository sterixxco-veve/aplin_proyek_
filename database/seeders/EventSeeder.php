<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $org = DB::table('organizations')->first();
        $cat = DB::table('event_categories')->first();

        DB::table('events')->insert([
            [
                'id_event' => Str::uuid(),
                'id_org' => $org->id_org,
                'id_event_category' => $cat->id_event_category,
                'nama_event' => 'Tech Conference 2026',
                'tema_acara' => 'Future of AI',
                'tgl_mulai' => now(),
                'tgl_selesai' => now()->addDays(1),
                'status' => 'planning'
            ]
        ]);
    }
}