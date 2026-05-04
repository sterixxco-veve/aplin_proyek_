<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_budgets', function (Blueprint $table) {

            $table->id('id_budget');

            $table->uuid('id_event');

            $table->foreignId('id_category');

            $table->string('keterangan');

            $table->integer('qty');
            $table->decimal('nominal_rencana', 12, 2);
            $table->decimal('sub_total', 12, 2);

            $table->timestamps();

            // FK
            $table->foreign('id_event')
                ->references('id_event')
                ->on('events')
                ->cascadeOnDelete();

            $table->foreign('id_category')
                ->references('id_category')
                ->on('budget_categories');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_budgets');
    }
};