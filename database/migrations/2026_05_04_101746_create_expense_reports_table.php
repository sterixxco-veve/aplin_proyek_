<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_reports', function (Blueprint $table) {

            $table->id('id_expense');

            $table->uuid('id_event');
            $table->uuid('id_user');

            $table->foreignId('id_expense_category');

            $table->string('nama_pengeluaran');

            $table->decimal('nominal', 12, 2);
            $table->integer('qty');
            $table->decimal('sub_total', 12, 2);

            $table->string('nomor_rekening');
            $table->string('bukti_nota_path')->nullable();

            // 🔥 WORKFLOW
            $table->enum('approval_status', ['pending','accepted','rejected'])
                  ->default('pending');

            $table->text('rejection_reason')->nullable();

            $table->uuid('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();

            // 🔥 REIMBURSE
            $table->boolean('is_reimbursed')->default(false);
            $table->dateTime('reimbursed_at')->nullable();

            $table->timestamps();

            // FK
            $table->foreign('id_event')
                ->references('id_event')
                ->on('events')
                ->cascadeOnDelete();

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('id_expense_category')
                ->references('id_expense_category')
                ->on('expense_categories');

            $table->foreign('approved_by')
                ->references('id_user')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_reports');
    }
};