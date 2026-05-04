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
                DivisionSeeder::class,
                EventCategorySeeder::class,
                BudgetCategorySeeder::class,
                ExpenseCategorySeeder::class,
                UserSeeder::class,

                OrganizationSeeder::class,
                OrganizationMemberSeeder::class,
                EventSeeder::class,
                EventCommitteeSeeder::class,
                TaskSeeder::class,
                ExpenseSeeder::class,
            ]);
        }
}
