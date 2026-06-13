<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Jika masih ada kolom jenis_partner lama
        if (Schema::hasColumn('partners', 'jenis_partner')) {

            DB::table('partners')
                ->where('jenis_partner', 'medpar')
                ->update(['jenis_partner' => 'media_partner']);

            DB::table('partners')
                ->where('jenis_partner', 'comrel')
                ->update(['jenis_partner' => 'community_partner']);

            DB::table('partners')
                ->where('jenis_partner', 'other')
                ->update(['jenis_partner' => 'support']);

            if (!Schema::hasColumn('partners', 'tipe_partner')) {

                Schema::table('partners', function (Blueprint $table) {
                    $table->string('tipe_partner')
                        ->default('sponsor')
                        ->nullable();
                });

                DB::statement("
                    UPDATE partners
                    SET tipe_partner = CASE jenis_partner
                        WHEN 'medpar' THEN 'media_partner'
                        WHEN 'comrel' THEN 'community_partner'
                        WHEN 'other' THEN 'support'
                        ELSE jenis_partner
                    END
                ");
            }
        }

        Schema::table('partners', function (Blueprint $table) {

            if (!Schema::hasColumn('partners', 'email_partner')) {
                $table->string('email_partner')->nullable();
            }

            if (!Schema::hasColumn('partners', 'no_telepon_partner')) {
                $table->string('no_telepon_partner')->nullable();
            }

            if (!Schema::hasColumn('partners', 'contribution')) {
                $table->decimal('contribution', 15, 2)
                    ->nullable()
                    ->default(0);
            }
        });

        if (
            Schema::hasColumn('partners', 'contact_person_email')
            && Schema::hasColumn('partners', 'email_partner')
        ) {
            DB::statement("
                UPDATE partners
                SET email_partner = contact_person_email
                WHERE email_partner IS NULL
            ");
        }

        if (
            Schema::hasColumn('partners', 'contact_person_phone')
            && Schema::hasColumn('partners', 'no_telepon_partner')
        ) {
            DB::statement("
                UPDATE partners
                SET no_telepon_partner = contact_person_phone
                WHERE no_telepon_partner IS NULL
            ");
        }

        if (
            Schema::hasColumn('partners', 'contribution_value')
            && Schema::hasColumn('partners', 'contribution')
        ) {
            DB::statement("
                UPDATE partners
                SET contribution = contribution_value
                WHERE contribution IS NULL
            ");
        }
    }

    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {

            foreach ([
                'email_partner',
                'no_telepon_partner',
                'contribution',
                'tipe_partner'
            ] as $column) {

                if (Schema::hasColumn('partners', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};