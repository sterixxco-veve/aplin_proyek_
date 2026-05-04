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
        Schema::create('invitation_letters', function (Blueprint $table) {
    $table->id('id_surat');

    $table->uuid('id_event');
    $table->foreign('id_event')->references('id_event')->on('events')->cascadeOnDelete();

    $table->enum('tipe_surat', ['pembicara', 'undangan_umum', 'mou_partner', 'peminjaman_venue']);

    $table->string('nama_penerima');
    $table->string('talk_title')->nullable();

    $table->string('hari_acara');
    $table->time('waktu_acara');
    $table->string('tempat_acara');

    $table->text('file_url')->nullable();

    $table->uuid('dibuat_oleh');
    $table->foreign('dibuat_oleh')->references('id_user')->on('users')->cascadeOnDelete();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitation_letters');
    }
};
