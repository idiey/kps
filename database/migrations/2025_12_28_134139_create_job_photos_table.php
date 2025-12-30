<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workshop_job_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inspection_report_id')->nullable()->constrained()->cascadeOnDelete();

            // Photo classification
            $table->string('photo_stage');
            $table->string('category')->nullable();

            // File metadata
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->bigInteger('file_size');

            // Photo metadata
            $table->text('description')->nullable();
            $table->string('location_context')->nullable();
            $table->boolean('is_public')->default(false);

            // Timestamps
            $table->timestamp('taken_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('workshop_job_id');
            $table->index('photo_stage');
            $table->index('is_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_photos');
    }
};
