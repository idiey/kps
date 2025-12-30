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
        Schema::create('government_departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('department_code')->unique();
            $table->string('ministry')->nullable();
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->string('city', 100);
            $table->string('state', 50);
            $table->string('postcode', 10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('department_code');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('government_departments');
    }
};
