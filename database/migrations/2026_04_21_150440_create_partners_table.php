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
       Schema::create('partners', function (Blueprint $table) {
    $table->id('id_partner');

    $table->uuid('id_event');
    $table->foreign('id_event')->references('id_event')->on('events')->cascadeOnDelete();

    $table->string('nama_partner');
    $table->enum('jenis_partner', ['MedPar', 'Sponsor', 'ComPar']);

    $table->uuid('assigned_pic')->nullable();
    $table->foreign('assigned_pic')->references('id_user')->on('users')->nullOnDelete();

    $table->enum('status', ['approach', 'follow_up', 'deal', 'rejected']);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
