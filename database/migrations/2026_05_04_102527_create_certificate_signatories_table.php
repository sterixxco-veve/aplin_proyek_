<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_signatories', function (Blueprint $table) {

            $table->id('id_signatory');

            $table->uuid('id_event');

            $table->string('nama_penandatangan');
            $table->string('jabatan');

            $table->string('tanda_tangan_path')->nullable();

            $table->timestamps();

            // FK
            $table->foreign('id_event')
                ->references('id_event')
                ->on('events')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_signatories');
    }
};