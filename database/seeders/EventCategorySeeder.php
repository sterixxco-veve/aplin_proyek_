<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('event_categories')->insert([
            ['nama_kategori' => 'Seminar', 'slug' => 'seminar'],
            ['nama_kategori' => 'Workshop', 'slug' => 'workshop'],
            ['nama_kategori' => 'Competition', 'slug' => 'competition'],
            ['nama_kategori' => 'Bootcamp', 'slug' => 'bootcamp'],
        ]);
    }
}