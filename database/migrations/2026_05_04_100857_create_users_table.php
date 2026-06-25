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
            
            // 🌟 SINKRONISASI ROLE: Mengganti ENUM dengan FK ke tabel divisions
            $table->unsignedBigInteger('id_divisi')->nullable();
            
            $table->timestamps();

            // Relasi Foreign Key ke tabel divisions
            $table->foreign('id_divisi')
                ->references('id_divisi')
                ->on('divisions')
                ->nullOnDelete(); // Jika divisi dihapus, role user diset NULL (tidak ikut terhapus)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
