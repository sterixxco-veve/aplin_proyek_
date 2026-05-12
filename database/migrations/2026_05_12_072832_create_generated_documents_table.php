<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generated_documents', function (Blueprint $table) {
            $table->id('id_document');

            $table->uuid('id_event');

            $table->enum('document_type', [
                'proposal',
                'lpj',
                'invitation_letter',
                'mou_partner',
                'certificate',
                'other'
            ]);

            $table->string('title');

            $table->text('file_url')->nullable();

            $table->unsignedInteger('version_number')->default(1);

            $table->enum('status', [
                'draft',
                'generated',
                'final',
                'archived',
                'failed'
            ])->default('generated');

            $table->uuid('generated_by')->nullable();

            $table->dateTime('generated_at')->nullable();

            $table->string('template_name')->nullable();
            $table->string('template_version')->nullable();

            $table->string('reference_type')->nullable();
            $table->string('reference_id')->nullable();

            $table->json('snapshot_data')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_event')
                ->references('id_event')
                ->on('events')
                ->cascadeOnDelete();

            $table->foreign('generated_by')
                ->references('id_user')
                ->on('users')
                ->nullOnDelete();

            $table->index(['id_event', 'document_type']);
            $table->index(['reference_type', 'reference_id']);
            $table->index('status');
            $table->index('generated_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generated_documents');
    }
};