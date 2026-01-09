<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Makes customer_id and title nullable to support dynamic workflow job creation
     * where a customer may not be immediately associated with the job.
     */
    public function up(): void
    {
        Schema::table('workshop_jobs', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['customer_id']);
        });

        Schema::table('workshop_jobs', function (Blueprint $table) {
            // Make customer_id nullable
            $table->foreignId('customer_id')
                ->nullable()
                ->change();

            // Re-add foreign key with SET NULL on delete
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('set null');
        });

        Schema::table('workshop_jobs', function (Blueprint $table) {
            // Make title nullable (was required before)
            $table->string('title')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workshop_jobs', function (Blueprint $table) {
            // Drop the modified foreign key
            $table->dropForeign(['customer_id']);
        });

        Schema::table('workshop_jobs', function (Blueprint $table) {
            // Restore customer_id as NOT NULL (will fail if nulls exist)
            $table->foreignId('customer_id')
                ->nullable(false)
                ->change();

            // Restore original foreign key with CASCADE on delete
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');
        });

        Schema::table('workshop_jobs', function (Blueprint $table) {
            // Make title required again
            $table->string('title')->nullable(false)->change();
        });
    }
};
