<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id_user')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('avatar_path')->nullable();
            $table->enum('role', ['super_admin','global_coordinator','leader','member']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};