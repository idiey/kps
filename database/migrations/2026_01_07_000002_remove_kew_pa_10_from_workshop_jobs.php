<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Removes KEW.PA-10 foreign key from workshop_jobs.
     * KEW.PA-10 is now a form template attached to workflow steps.
     */
    public function up(): void
    {
        Schema::table('workshop_jobs', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['kew_pa_10_id']);
            $table->dropIndex(['kew_pa_10_id']);
            
            // Then drop the column
            $table->dropColumn('kew_pa_10_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workshop_jobs', function (Blueprint $table) {
            $table->foreignId('kew_pa_10_id')
                  ->nullable()
                  ->after('customer_id')
                  ->constrained('kew_pa_10s')
                  ->nullOnDelete();
        });
    }
};
