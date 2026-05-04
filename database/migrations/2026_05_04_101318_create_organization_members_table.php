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

            $table->enum('role', ['admin_org','member']);

            $table->timestamps();

            // FK manual karena UUID
            $table->foreign('user_id')
                ->references('id_user')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_members');
    }
};