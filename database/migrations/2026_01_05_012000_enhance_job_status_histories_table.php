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
        Schema::table('job_status_histories', function (Blueprint $table) {
            $table->foreignId('workflow_status_id')->nullable()->after('to_status')->constrained('workflow_statuses')->onDelete('set null');
            $table->foreignId('transition_id')->nullable()->after('workflow_status_id')->constrained('workflow_transitions')->onDelete('set null');
            $table->json('metadata')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_status_histories', function (Blueprint $table) {
            $table->dropForeign(['workflow_status_id']);
            $table->dropForeign(['transition_id']);
            $table->dropColumn(['workflow_status_id', 'transition_id', 'metadata']);
        });
    }
};
