<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            if (!Schema::hasColumn('partners', 'jenis_partner')) {
                $table->enum('jenis_partner', ['sponsor', 'medpar', 'comrel'])
                    ->default('sponsor')
                    ->after('nama_partner');
            }
        });

        if (Schema::hasColumn('partners', 'tipe_partner')) {
            DB::statement("
                UPDATE partners
                SET jenis_partner = CASE tipe_partner
                    WHEN 'media_partner' THEN 'medpar'
                    WHEN 'community_partner' THEN 'comrel'
                    WHEN 'support' THEN 'sponsor'
                    ELSE tipe_partner
                END
                WHERE jenis_partner IS NULL OR jenis_partner = ''
            ");
        }
    }

    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            if (Schema::hasColumn('partners', 'jenis_partner')) {
                $table->dropColumn('jenis_partner');
            }
        });
    }
};
