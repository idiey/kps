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
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('subdomain')->unique()->nullable();
            $table->enum('tier', ['free', 'professional', 'enterprise'])->default('free');
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_active');
            $table->index('subdomain');
        });

        Schema::create('workshops', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->json('operating_hours')->nullable()->comment('JSON: {mon: {open: "08:00", close: "17:00"}, ...}');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('code');
            $table->index('is_active');
        });

        // Add workshop_id to existing tables if needed
        if (Schema::hasTable('workshop_jobs') && !Schema::hasColumn('workshop_jobs', 'workshop_id')) {
            Schema::table('workshop_jobs', function (Blueprint $table) {
                $table->foreignUuid('workshop_id')->nullable()->after('id')->constrained()->onDelete('cascade');
                $table->index('workshop_id');
            });
        }

        if (Schema::hasTable('customers') && !Schema::hasColumn('customers', 'workshop_id')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->foreignUuid('workshop_id')->nullable()->after('id')->constrained()->onDelete('cascade');
                $table->index('workshop_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys first
        if (Schema::hasColumn('workshop_jobs', 'workshop_id')) {
            Schema::table('workshop_jobs', function (Blueprint $table) {
                $table->dropForeign(['workshop_id']);
                $table->dropColumn('workshop_id');
            });
        }

        if (Schema::hasColumn('customers', 'workshop_id')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropForeign(['workshop_id']);
                $table->dropColumn('workshop_id');
            });
        }

        Schema::dropIfExists('workshops');
        Schema::dropIfExists('companies');
    }
};
