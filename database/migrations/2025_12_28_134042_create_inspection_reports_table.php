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
        Schema::create('inspection_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workshop_job_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inspector_id')->constrained('users')->cascadeOnDelete();

            // Inspection findings
            $table->text('asset_condition_current');
            $table->text('visual_damage_assessment');
            $table->text('functional_testing_results')->nullable();
            $table->text('safety_hazards_identified')->nullable();
            $table->text('additional_issues_discovered')->nullable();
            $table->text('recommended_repairs');

            // Approval
            $table->string('approval_status')->default('pending');
            $table->text('approval_notes')->nullable();
            $table->string('digital_signature')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->timestamp('inspection_completed_at')->nullable();

            $table->timestamps();

            $table->index('workshop_job_id');
            $table->index('inspector_id');
            $table->index('approval_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_reports');
    }
};
