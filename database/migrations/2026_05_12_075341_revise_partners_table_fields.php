<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * Revisi tipe partner.
         * 
         * Jika kolom lama masih bernama jenis_partner,
         * maka kita ubah menjadi tipe_partner.
         */
        if (Schema::hasColumn('partners', 'jenis_partner')) {
            DB::statement("
                ALTER TABLE partners
                MODIFY jenis_partner ENUM(
                    'sponsor',
                    'medpar',
                    'comrel',
                    'media_partner',
                    'community_partner',
                    'support',
                    'other'
                ) NOT NULL DEFAULT 'sponsor'
            ");

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
                DB::statement("
                    ALTER TABLE partners
                    CHANGE jenis_partner tipe_partner ENUM(
                        'media_partner',
                        'sponsor',
                        'community_partner',
                        'support'
                    ) NOT NULL DEFAULT 'sponsor'
                ");
            }
        }

        /**
         * Jika kolom tipe_partner sudah ada,
         * pastikan enum-nya sesuai revisi terbaru.
         */
        if (Schema::hasColumn('partners', 'tipe_partner')) {
            DB::statement("
                ALTER TABLE partners
                MODIFY tipe_partner ENUM(
                    'media_partner',
                    'sponsor',
                    'community_partner',
                    'support'
                ) NOT NULL DEFAULT 'sponsor'
            ");
        }

        /**
         * Tambahkan kolom tipe_partner jika sebelumnya belum ada sama sekali.
         */
        if (!Schema::hasColumn('partners', 'tipe_partner')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->enum('tipe_partner', [
                    'media_partner',
                    'sponsor',
                    'community_partner',
                    'support'
                ])->default('sponsor')->after('nama_partner');
            });
        }

        /**
         * Rename kolom dari migration sebelumnya jika sudah terlanjur dibuat.
         */
        if (Schema::hasColumn('partners', 'contact_person_email') && !Schema::hasColumn('partners', 'email_partner')) {
            DB::statement("
                ALTER TABLE partners
                CHANGE contact_person_email email_partner VARCHAR(255) NULL
            ");
        }

        if (Schema::hasColumn('partners', 'contact_person_phone') && !Schema::hasColumn('partners', 'no_telepon_partner')) {
            DB::statement("
                ALTER TABLE partners
                CHANGE contact_person_phone no_telepon_partner VARCHAR(255) NULL
            ");
        }

        if (Schema::hasColumn('partners', 'contribution_value') && !Schema::hasColumn('partners', 'contribution')) {
            DB::statement("
                ALTER TABLE partners
                CHANGE contribution_value contribution DECIMAL(15, 2) NULL DEFAULT 0
            ");
        }

        /**
         * Tambahkan kolom baru jika belum ada.
         */
        Schema::table('partners', function (Blueprint $table) {
            if (!Schema::hasColumn('partners', 'email_partner')) {
                $table->string('email_partner')->nullable()->after('tipe_partner');
            }

            if (!Schema::hasColumn('partners', 'no_telepon_partner')) {
                $table->string('no_telepon_partner')->nullable()->after('email_partner');
            }

            if (!Schema::hasColumn('partners', 'contribution')) {
                $table->decimal('contribution', 15, 2)->nullable()->default(0)->after('no_telepon_partner');
            }
        });
    }

    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            if (Schema::hasColumn('partners', 'email_partner')) {
                $table->dropColumn('email_partner');
            }

            if (Schema::hasColumn('partners', 'no_telepon_partner')) {
                $table->dropColumn('no_telepon_partner');
            }

            if (Schema::hasColumn('partners', 'contribution')) {
                $table->dropColumn('contribution');
            }
        });

        if (Schema::hasColumn('partners', 'tipe_partner')) {
            DB::statement("
                ALTER TABLE partners
                CHANGE tipe_partner jenis_partner ENUM(
                    'sponsor',
                    'medpar',
                    'comrel'
                ) NOT NULL DEFAULT 'sponsor'
            ");
        }
    }
};