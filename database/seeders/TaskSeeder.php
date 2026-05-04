<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $event = DB::table('events')->first();
        $user = DB::table('users')->first();
        $div = DB::table('divisions')->first();

        DB::table('tasks')->insert([
            [
                'id_event' => $event->id_event,
                'id_divisi' => $div->id_divisi,
                'nama_tugas' => 'Booking Venue',
                'brief' => 'Cari dan booking venue acara',
                'assigned_to' => $user->id_user,
                'priority' => 'high',
                'status' => 'todo',
                'deadline' => now()->addDays(3)
            ],
            [
                'id_event' => $event->id_event,
                'id_divisi' => $div->id_divisi,
                'nama_tugas' => 'Design Poster',
                'brief' => 'Buat poster promosi',
                'assigned_to' => $user->id_user,
                'priority' => 'medium',
                'status' => 'progress',
                'deadline' => now()->addDays(5)
            ]
        ]);
    }
}