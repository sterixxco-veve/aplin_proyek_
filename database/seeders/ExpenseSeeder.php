<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $event = DB::table('events')->first();
        $user = DB::table('users')->first();
        $cat = DB::table('expense_categories')->first();

       DB::table('expense_reports')->insert([
        [
            'id_event' => $event->id_event,
            'id_user' => $user->id_user,
            'id_expense_category' => $cat->id_expense_category,
            'nama_pengeluaran' => 'Konsumsi Panitia',
            'nominal' => 15000,
            'qty' => 10,
            'sub_total' => 150000,
            'nomor_rekening' => '1234567890',
            'approval_status' => 'pending',
            'rejection_reason' => null,
            'is_reimbursed' => false,
            'approved_by' => null,
            'approved_at' => null,
            'reimbursed_at' => null,
            'bukti_nota_path' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'id_event' => $event->id_event,
            'id_user' => $user->id_user,
            'id_expense_category' => $cat->id_expense_category,
            'nama_pengeluaran' => 'Transport panitia',
            'nominal' => 50000,
            'qty' => 2,
            'sub_total' => 100000,
            'nomor_rekening' => '1234567890',
            'approval_status' => 'rejected',
            'rejection_reason' => 'Bukti nota kurang jelas dan nominal tidak sesuai dengan laporan',
            'is_reimbursed' => false,
            'approved_by' => null,
            'approved_at' => null,
            'reimbursed_at' => null,
            'bukti_nota_path' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]
    ]);
    }
}