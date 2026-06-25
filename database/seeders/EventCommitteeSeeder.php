<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventCommitteeSeeder extends Seeder
{
    public function run(): void
    {
        $event = DB::table('events')->first();
        $user = DB::table('users')->first();
        $div = DB::table('divisions')->first();

        DB::table('event_committees')->insert([
            [
                'id_event' => $event->id_event,
                'id_user' => $user->id_user,
                'id_divisi' => $div->id_divisi,
                'position' => 'Ketua'
            ]
        ]);
    }
}