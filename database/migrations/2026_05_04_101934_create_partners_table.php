<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {

            $table->id('id_partner');

            $table->uuid('id_event');

            $table->string('nama_partner');

            $table->enum('jenis_partner', ['sponsor','medpar','comrel']);

            $table->uuid('assigned_pic')->nullable();

            $table->enum('status', ['approach','follow_up','deal','rejected'])
                  ->default('approach');

            $table->timestamps();

            // FK
            $table->foreign('id_event')
                ->references('id_event')
                ->on('events')
                ->cascadeOnDelete();

            $table->foreign('assigned_pic')
                ->references('id_user')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};