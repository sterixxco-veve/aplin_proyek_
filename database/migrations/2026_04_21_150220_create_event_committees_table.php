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
        Schema::create('event_committees', function (Blueprint $table) {
            $table->id('id_comm');

            $table->uuid('id_event');
            $table->uuid('id_user');
            $table->unsignedBigInteger('id_divisi');

            $table->string('jabatan');
            $table->timestamps();

            $table->unique(['id_event', 'id_user']);

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
                ->on('divisions')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_committees');
    }
};
