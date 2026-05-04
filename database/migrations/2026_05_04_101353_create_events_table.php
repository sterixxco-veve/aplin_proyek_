<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {

            $table->uuid('id_event')->primary();

            $table->foreignId('id_org')
                ->constrained('organizations', 'id_org')
                ->cascadeOnDelete();

            $table->foreignId('id_event_category')
                ->constrained('event_categories', 'id_event_category');

            $table->string('nama_event');
            $table->string('tema_acara')->nullable();

            $table->dateTime('tgl_mulai');
            $table->dateTime('tgl_selesai')->nullable();

            $table->enum('status', ['planning','ongoing','done'])
                  ->default('planning');

            $table->text('latar_belakang')->nullable();
            $table->text('tujuan')->nullable();
            $table->text('manfaat')->nullable();
            $table->string('sasaran_peserta')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};