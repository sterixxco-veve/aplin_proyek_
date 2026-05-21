<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            if (!Schema::hasColumn('partners', 'assigned_pic')) {
                $table->uuid('assigned_pic')->nullable();
            }

            if (!Schema::hasColumn('partners', 'status')) {
                $table->enum('status', [
                    'approach',
                    'prospect',
                    'contacted',
                    'follow_up',
                    'negotiation',
                    'deal',
                    'rejected',
                    'cancelled',
                ])->default('approach');
            }

            if (!Schema::hasColumn('partners', 'notes')) {
                $table->text('notes')->nullable();
            }

            if (!Schema::hasColumn('partners', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (!Schema::hasColumn('partners', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }

            if (!Schema::hasColumn('partners', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            foreach (['assigned_pic', 'status', 'notes', 'created_at', 'updated_at', 'deleted_at'] as $column) {
                if (Schema::hasColumn('partners', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
