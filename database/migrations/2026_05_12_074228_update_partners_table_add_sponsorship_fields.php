<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partners', function (Blueprint $table) {

            if (!Schema::hasColumn('partners', 'contact_person_name')) {
                $table->string('contact_person_name')->nullable();
            }

            if (!Schema::hasColumn('partners', 'contact_person_position')) {
                $table->string('contact_person_position')->nullable();
            }

            if (!Schema::hasColumn('partners', 'contact_person_email')) {
                $table->string('contact_person_email')->nullable();
            }

            if (!Schema::hasColumn('partners', 'contact_person_phone')) {
                $table->string('contact_person_phone')->nullable();
            }

            if (!Schema::hasColumn('partners', 'website')) {
                $table->string('website')->nullable();
            }

            if (!Schema::hasColumn('partners', 'instagram')) {
                $table->string('instagram')->nullable();
            }

            if (!Schema::hasColumn('partners', 'alamat')) {
                $table->text('alamat')->nullable();
            }

            if (!Schema::hasColumn('partners', 'logo_path')) {
                $table->string('logo_path')->nullable();
            }

            if (!Schema::hasColumn('partners', 'sponsor_level')) {
                $table->string('sponsor_level')->nullable();
            }

            if (!Schema::hasColumn('partners', 'contribution_type')) {
                $table->string('contribution_type')->nullable();
            }

            if (!Schema::hasColumn('partners', 'contribution_value')) {
                $table->decimal('contribution_value', 15, 2)->nullable();
            }

            if (!Schema::hasColumn('partners', 'contribution_description')) {
                $table->text('contribution_description')->nullable();
            }

            if (!Schema::hasColumn('partners', 'benefit')) {
                $table->text('benefit')->nullable();
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
    }
};