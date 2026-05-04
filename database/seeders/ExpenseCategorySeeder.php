<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('expense_categories')->insert([
            ['nama_kategori' => 'Konsumsi'],
            ['nama_kategori' => 'Transport'],
            ['nama_kategori' => 'Logistik'],
            ['nama_kategori' => 'Akomodasi'],
        ]);
    }
}