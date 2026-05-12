<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * Hapus foreign key assigned_pic dulu.
         * Karena assigned_pic sebelumnya kemungkinan punya relasi ke users.id_user.
         */
        try {
            Schema::table('partners', function (Blueprint $table) {
                if (Schema::hasColumn('partners', 'assigned_pic')) {
                    $table->dropForeign(['assigned_pic']);
                }
            });
        } catch (\Throwable $e) {
            // Diabaikan kalau foreign key assigned_pic ternyata tidak ada
        }

        Schema::table('partners', function (Blueprint $table) {
            $columns = [
                'assigned_pic',
                'proposal_sent_at',
                'deal_at',
                'rejected_at',
                'rejection_reason',
                'proposal_file_url',
                'mou_file_url',
                'agreement_file_url',
                'notes',
                'status',
                'created_at',
                'updated_at',
                'deleted_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('partners', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            if (!Schema::hasColumn('partners', 'assigned_pic')) {
                $table->uuid('assigned_pic')->nullable();
            }

            if (!Schema::hasColumn('partners', 'proposal_sent_at')) {
                $table->dateTime('proposal_sent_at')->nullable();
            }

            if (!Schema::hasColumn('partners', 'deal_at')) {
                $table->dateTime('deal_at')->nullable();
            }

            if (!Schema::hasColumn('partners', 'rejected_at')) {
                $table->dateTime('rejected_at')->nullable();
            }

            if (!Schema::hasColumn('partners', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }

            if (!Schema::hasColumn('partners', 'proposal_file_url')) {
                $table->text('proposal_file_url')->nullable();
            }

            if (!Schema::hasColumn('partners', 'mou_file_url')) {
                $table->text('mou_file_url')->nullable();
            }

            if (!Schema::hasColumn('partners', 'agreement_file_url')) {
                $table->text('agreement_file_url')->nullable();
            }

            if (!Schema::hasColumn('partners', 'notes')) {
                $table->text('notes')->nullable();
            }

            if (!Schema::hasColumn('partners', 'status')) {
                $table->enum('status', [
                    'prospect',
                    'contacted',
                    'follow_up',
                    'negotiation',
                    'deal',
                    'rejected',
                    'cancelled'
                ])->default('prospect');
            }

            if (!Schema::hasColumn('partners', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (!Schema::hasColumn('partners', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }

            if (!Schema::hasColumn('partners', 'deleted_at')) {
                $table->timestamp('deleted_at')->nullable();
            }
        });

        /*
         * Restore foreign key assigned_pic saat rollback.
         */
        try {
            Schema::table('partners', function (Blueprint $table) {
                if (Schema::hasColumn('partners', 'assigned_pic')) {
                    $table->foreign('assigned_pic')
                        ->references('id_user')
                        ->on('users')
                        ->nullOnDelete();
                }
            });
        } catch (\Throwable $e) {
            // Diabaikan kalau constraint sudah ada atau tabel users tidak sesuai
        }
    }
};