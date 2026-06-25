<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_committees', function (Blueprint $table) {

            $table->id('id_comm');

            $table->uuid('id_event');
            $table->uuid('id_user');
            $table->foreignId('id_divisi');

            $table->enum('position', [
                'ketua',
                'wakil_ketua',
                'sekretaris',
                'bendahara',
                'coordinator',
                'member',
            ]);

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

            $table->foreign('id_divisi')
                ->references('id_divisi')
                ->on('divisions');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_committees');
    }
};