<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {

            $table->id('id_task');

            $table->uuid('id_event');
            $table->foreignId('id_divisi');

            $table->string('nama_tugas');
            $table->text('brief')->nullable();

            $table->uuid('assigned_to')->nullable();

            $table->enum('priority', ['low','medium','high'])->default('medium');

            $table->enum('status', ['todo','progress','done'])->default('todo');

            $table->dateTime('deadline')->nullable();

            $table->timestamps();

            // FK
            $table->foreign('id_event')
                ->references('id_event')
                ->on('events')
                ->cascadeOnDelete();

            $table->foreign('id_divisi')
                ->references('id_divisi')
                ->on('divisions');

            $table->foreign('assigned_to')
                ->references('id_user')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};