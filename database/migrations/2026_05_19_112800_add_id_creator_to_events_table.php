<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->uuid('id_creator')->nullable()->after('id_org');

            $table->foreign('id_creator')
                ->references('id_user')
                ->on('users')
                ->nullOnDelete();
        });

        DB::statement("
            UPDATE events
            SET id_creator = (
                SELECT id_user
                FROM event_committees
                WHERE event_committees.id_event = events.id_event
                ORDER BY id_comm ASC
                LIMIT 1
            )
            WHERE id_creator IS NULL
        ");
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['id_creator']);
            $table->dropColumn('id_creator');
        });
    }
};
