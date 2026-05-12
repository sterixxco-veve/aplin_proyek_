<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update enum status supaya lebih lengkap untuk proses sponsorship
        DB::statement("
            ALTER TABLE partners
            MODIFY status ENUM(
                'approach',
                'prospect',
                'contacted',
                'follow_up',
                'negotiation',
                'deal',
                'rejected',
                'cancelled'
            ) NOT NULL DEFAULT 'prospect'
        ");

        Schema::table('partners', function (Blueprint $table) {
            if (!Schema::hasColumn('partners', 'contact_person_name')) {
                $table->string('contact_person_name')->nullable()->after('jenis_partner');
            }

            if (!Schema::hasColumn('partners', 'contact_person_position')) {
                $table->string('contact_person_position')->nullable()->after('contact_person_name');
            }

            if (!Schema::hasColumn('partners', 'contact_person_email')) {
                $table->string('contact_person_email')->nullable()->after('contact_person_position');
            }

            if (!Schema::hasColumn('partners', 'contact_person_phone')) {
                $table->string('contact_person_phone')->nullable()->after('contact_person_email');
            }

            if (!Schema::hasColumn('partners', 'website')) {
                $table->string('website')->nullable()->after('contact_person_phone');
            }

            if (!Schema::hasColumn('partners', 'instagram')) {
                $table->string('instagram')->nullable()->after('website');
            }

            if (!Schema::hasColumn('partners', 'alamat')) {
                $table->text('alamat')->nullable()->after('instagram');
            }

            if (!Schema::hasColumn('partners', 'logo_path')) {
                $table->string('logo_path')->nullable()->after('alamat');
            }

            if (!Schema::hasColumn('partners', 'sponsor_level')) {
                $table->enum('sponsor_level', [
                    'platinum',
                    'gold',
                    'silver',
                    'bronze',
                    'custom'
                ])->nullable()->after('logo_path');
            }

            if (!Schema::hasColumn('partners', 'contribution_type')) {
                $table->enum('contribution_type', [
                    'cash',
                    'goods',
                    'service',
                    'media_support',
                    'other'
                ])->nullable()->after('sponsor_level');
            }

            if (!Schema::hasColumn('partners', 'contribution_value')) {
                $table->decimal('contribution_value', 15, 2)->nullable()->after('contribution_type');
            }

            if (!Schema::hasColumn('partners', 'contribution_description')) {
                $table->text('contribution_description')->nullable()->after('contribution_value');
            }

            if (!Schema::hasColumn('partners', 'benefit')) {
                $table->text('benefit')->nullable()->after('contribution_description');
            }

            if (!Schema::hasColumn('partners', 'proposal_sent_at')) {
                $table->dateTime('proposal_sent_at')->nullable()->after('assigned_pic');
            }

            if (!Schema::hasColumn('partners', 'deal_at')) {
                $table->dateTime('deal_at')->nullable()->after('proposal_sent_at');
            }

            if (!Schema::hasColumn('partners', 'rejected_at')) {
                $table->dateTime('rejected_at')->nullable()->after('deal_at');
            }

            if (!Schema::hasColumn('partners', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected_at');
            }

            if (!Schema::hasColumn('partners', 'proposal_file_url')) {
                $table->text('proposal_file_url')->nullable()->after('rejection_reason');
            }

            if (!Schema::hasColumn('partners', 'mou_file_url')) {
                $table->text('mou_file_url')->nullable()->after('proposal_file_url');
            }

            if (!Schema::hasColumn('partners', 'agreement_file_url')) {
                $table->text('agreement_file_url')->nullable()->after('mou_file_url');
            }

            if (!Schema::hasColumn('partners', 'notes')) {
                $table->text('notes')->nullable()->after('agreement_file_url');
            }

            if (!Schema::hasColumn('partners', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $columns = [
                'contact_person_name',
                'contact_person_position',
                'contact_person_email',
                'contact_person_phone',
                'website',
                'instagram',
                'alamat',
                'logo_path',
                'sponsor_level',
                'contribution_type',
                'contribution_value',
                'contribution_description',
                'benefit',
                'proposal_sent_at',
                'deal_at',
                'rejected_at',
                'rejection_reason',
                'proposal_file_url',
                'mou_file_url',
                'agreement_file_url',
                'notes',
                'deleted_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('partners', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        DB::statement("
            ALTER TABLE partners
            MODIFY status ENUM(
                'approach',
                'follow_up',
                'deal',
                'rejected'
            ) NOT NULL DEFAULT 'approach'
        ");
    }
};