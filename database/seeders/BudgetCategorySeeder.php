<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BudgetCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('budget_categories')->insert([
            ['nama_kategori' => 'Operasional'],
            ['nama_kategori' => 'Marketing'],
            ['nama_kategori' => 'Produksi'],
            ['nama_kategori' => 'Transport'],
        ]);
    }
}