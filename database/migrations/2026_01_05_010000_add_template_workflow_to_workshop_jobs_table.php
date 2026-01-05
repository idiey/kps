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
        Schema::table('workshop_jobs', function (Blueprint $table) {
            $table->foreignId('template_id')->nullable()->after('id')->constrained('job_templates')->onDelete('set null');
            $table->foreignId('workflow_id')->nullable()->after('template_id')->constrained('workflows')->onDelete('set null');
            $table->foreignId('current_workflow_status_id')->nullable()->after('workflow_id')->constrained('workflow_statuses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workshop_jobs', function (Blueprint $table) {
            $table->dropForeign(['template_id']);
            $table->dropForeign(['workflow_id']);
            $table->dropForeign(['current_workflow_status_id']);
            $table->dropColumn(['template_id', 'workflow_id', 'current_workflow_status_id']);
        });
    }
};
