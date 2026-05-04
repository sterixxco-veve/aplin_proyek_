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
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id_event')->primary();

            // 🔑 FK ke organization (explicit, jangan pakai constrained default)
            $table->unsignedBigInteger('id_org');

            $table->string('nama_event');

            $table->enum('kategori', ['study_jam', 'seminar', 'lomba', 'workshop'])
                ->default('seminar'); // biar nggak error waktu create

            $table->dateTime('tgl_mulai')->nullable(); // biar fleksibel

            $table->enum('status', ['planning', 'ongoing', 'done'])
                ->default('planning');

            $table->timestamps();

            // 🔥 FK manual (lebih aman untuk custom key)
            $table->foreign('id_org')
                ->references('id_org')
                ->on('organizations')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
