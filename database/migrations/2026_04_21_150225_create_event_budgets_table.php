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
        Schema::create('event_budgets', function (Blueprint $table) {
    $table->id('id_budget');
    $table->uuid('id_event');
    $table->foreign('id_event')->references('id_event')->on('events')->cascadeOnDelete();
    
    $table->foreignId('id_category')->constrained('budget_categories', 'id_category');
    
    $table->string('keterangan');
    $table->integer('qty');
    $table->decimal('nominal_rencana', 15, 2);
    $table->decimal('sub_total', 15, 2);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_budgets');
    }
};
