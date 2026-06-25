<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_members', function (Blueprint $table) {
        $table->id();

        $table->foreignId('organization_id')
            ->constrained('organizations', 'id_org')
            ->cascadeOnDelete();

        $table->uuid('user_id');

        $table->unsignedBigInteger('id_divisi')->nullable();

        $table->enum('position', [
            'ketua',
            'wakil_ketua',
            'sekretaris',
            'bendahara',
            'coordinator',
            'member',
        ]);

        $table->timestamps();

        $table->foreign('user_id')
            ->references('id_user')
            ->on('users')
            ->cascadeOnDelete();

        $table->foreign('id_divisi')
            ->references('id_divisi')
            ->on('divisions')
            ->nullOnDelete();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_members');
    }
};