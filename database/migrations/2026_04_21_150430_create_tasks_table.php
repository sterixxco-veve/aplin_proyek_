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
        Schema::create('tasks', function (Blueprint $table) {
    $table->id('id_task');

    $table->uuid('id_event');
    $table->foreign('id_event')->references('id_event')->on('events')->cascadeOnDelete();

    $table->foreignId('id_divisi')->constrained('divisions', 'id_divisi');

    $table->string('nama_tugas');
    $table->text('brief')->nullable();

    $table->uuid('assigned_to')->nullable();
    $table->foreign('assigned_to')->references('id_user')->on('users')->nullOnDelete();

    $table->enum('status', ['todo', 'progress', 'done']);
    $table->dateTime('deadline')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
