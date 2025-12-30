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
            $table->foreignId('kew_pa_10_id')->nullable()->after('customer_id')->constrained('kew_pa_10s')->nullOnDelete();
            $table->foreignId('government_department_id')->nullable()->after('kew_pa_10_id')->constrained()->nullOnDelete();
            $table->foreignId('asset_id')->nullable()->after('government_department_id')->constrained()->nullOnDelete();
            $table->boolean('inspection_required')->default(true)->after('asset_id');
            $table->boolean('inspection_approved')->nullable()->after('inspection_required');
            $table->date('estimated_completion_date')->nullable()->after('due_date');
            $table->timestamp('kew_pa_10_returned_at')->nullable()->after('invoiced_at');
            $table->decimal('estimated_hours', 8, 2)->nullable()->after('actual_cost');
            $table->decimal('actual_hours', 8, 2)->nullable()->after('estimated_hours');

            $table->index('kew_pa_10_id');
            $table->index('government_department_id');
            $table->index('asset_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workshop_jobs', function (Blueprint $table) {
            $table->dropForeign(['kew_pa_10_id']);
            $table->dropForeign(['government_department_id']);
            $table->dropForeign(['asset_id']);
            $table->dropIndex(['kew_pa_10_id']);
            $table->dropIndex(['government_department_id']);
            $table->dropIndex(['asset_id']);
            $table->dropColumn([
                'kew_pa_10_id',
                'government_department_id',
                'asset_id',
                'inspection_required',
                'inspection_approved',
                'estimated_completion_date',
                'kew_pa_10_returned_at',
                'estimated_hours',
                'actual_hours',
            ]);
        });
    }
};
