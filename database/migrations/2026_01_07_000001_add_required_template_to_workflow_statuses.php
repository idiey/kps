<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds form template support to workflow statuses (steps).
     * When a workflow step has a required_template_id, users must
     * fill out that form template before transitioning.
     */
    public function up(): void
    {
        Schema::table('workflow_statuses', function (Blueprint $table) {
            $table->foreignId('required_template_id')
                  ->nullable()
                  ->after('display_order')
                  ->constrained('job_templates')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workflow_statuses', function (Blueprint $table) {
            $table->dropForeign(['required_template_id']);
            $table->dropColumn('required_template_id');
        });
    }
};
