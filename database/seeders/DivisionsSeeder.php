<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DivisionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    DB::table('divisions')->insert([
        ['nama_divisi' => 'Acara', 'is_default' => true],
        ['nama_divisi' => 'Humas', 'is_default' => true],
        ['nama_divisi' => 'Publikasi', 'is_default' => true],
        ['nama_divisi' => 'Perkap', 'is_default' => true],
    ]);
}
}
