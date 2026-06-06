<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('divisions')->insert([
            ['nama_divisi' => 'BPH', 'is_default' => true],
            ['nama_divisi' => 'Sekretaris', 'is_default' => true],
            ['nama_divisi' => 'Bendahara', 'is_default' => true],  
            ['nama_divisi' => 'Acara', 'is_default' => true],
            ['nama_divisi' => 'Konsumsi', 'is_default' => true],
            ['nama_divisi' => 'Publikasi', 'is_default' => true],
            ['nama_divisi' => 'Perlengkapan', 'is_default' => true],
        ]);
    }
}