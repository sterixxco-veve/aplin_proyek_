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
        Schema::create('organization_members', function (Blueprint $table) {
            $table->id();

            // 🔑 FK ke organizations (pakai id_org)
            $table->unsignedBigInteger('organization_id');

            // 🔑 FK ke users (UUID sesuai schema kamu)
            $table->uuid('user_id');

            $table->string('role')->default('member');

            $table->timestamps();

            // ✅ Foreign key manual (PENTING)
            $table->foreign('organization_id')
                ->references('id_org')
                ->on('organizations')
                ->cascadeOnDelete();

            $table->foreign('user_id')
                ->references('id_user')
                ->on('users')
                ->cascadeOnDelete();

            // ❗ biar gak duplicate member
            $table->unique(['organization_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_members');
    }
};
