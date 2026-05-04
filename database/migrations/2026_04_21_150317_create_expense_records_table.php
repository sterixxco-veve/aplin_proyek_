<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expense_reports', function (Blueprint $table) {
    $table->id('id_expense');

    $table->uuid('id_event');
    $table->foreign('id_event')->references('id_event')->on('events')->cascadeOnDelete();

    $table->uuid('id_user');
    $table->foreign('id_user')->references('id_user')->on('users')->cascadeOnDelete();

    $table->foreignId('id_expense_category')->constrained('expense_categories', 'id_expense_category');

    $table->string('nama_pengeluaran');
    $table->decimal('nominal', 15, 2);
    $table->integer('qty');
    $table->decimal('sub_total', 15, 2);

    $table->string('nomor_rekening')->nullable()->change();
    $table->string('bukti_nota_path')->nullable();

    $table->boolean('is_reimbursed')->default(false);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_records');
    }
};
