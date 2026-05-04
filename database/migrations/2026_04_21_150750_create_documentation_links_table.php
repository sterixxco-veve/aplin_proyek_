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
        Schema::create('documentation_links', function (Blueprint $table) {
    $table->id('id_doc');

    $table->uuid('id_event');
    $table->foreign('id_event')->references('id_event')->on('events')->cascadeOnDelete();

    $table->text('file_path');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentation_links');
    }
};
