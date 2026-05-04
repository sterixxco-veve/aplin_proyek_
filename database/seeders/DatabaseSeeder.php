<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
        DivisionsSeeder::class,
        BudgetCategoriesSeeder::class,
        ExpenseCategoriesSeeder::class,
        UsersSeeder::class,
    ]);
    }
}
