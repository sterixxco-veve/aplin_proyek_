<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {

            $table->id('id_cert');

            $table->uuid('id_event');

            $table->string('nama_penerima');
            $table->string('email_penerima');
            $table->string('nrp_penerima')->nullable();

            $table->string('qr_token')->unique();

            $table->text('file_url')->nullable();

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
        Schema::dropIfExists('certificates');
    }
};