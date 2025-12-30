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
        Schema::create('job_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workshop_job_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_to')->constrained('users')->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamp('assigned_at');
            $table->timestamp('unassigned_at')->nullable();
            $table->boolean('is_current')->default(true); // Marks the current active assignment
            $table->timestamps();

            // Indexes
            $table->index('workshop_job_id');
            $table->index('assigned_by');
            $table->index('assigned_to');
            $table->index('assigned_at');
            $table->index('is_current');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_assignments');
    }
};
