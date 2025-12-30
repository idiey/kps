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
        Schema::create('repair_completion_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workshop_job_id')->constrained()->cascadeOnDelete();
            $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete();

            // Completion details
            $table->boolean('work_completed')->default(false);
            $table->json('parts_used')->nullable();
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->decimal('time_spent_hours', 8, 2);
            $table->text('work_description');
            $table->text('issues_encountered')->nullable();
            $table->text('recommendations')->nullable();
            $table->integer('quality_rating')->nullable();

            // Signatures
            $table->string('technician_signature')->nullable();
            $table->timestamp('technician_signed_at')->nullable();

            $table->timestamps();

            $table->index('workshop_job_id');
            $table->index('technician_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_completion_reports');
    }
};
