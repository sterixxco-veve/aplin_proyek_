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
        Schema::create('event_rundown_items', function (Blueprint $table) {
    $table->id('id_rundown');

    $table->uuid('id_event');
    $table->foreign('id_event')->references('id_event')->on('events')->cascadeOnDelete();

    $table->integer('day_number');
    $table->string('session_group')->nullable();

    $table->time('waktu_mulai');
    $table->time('waktu_selesai');

    $table->string('kegiatan');

    $table->unsignedBigInteger('assigned_to')->nullable(); // event_committees
    $table->foreign('assigned_to')->references('id_comm')->on('event_committees')->nullOnDelete();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_rundown_items');
    }
};
