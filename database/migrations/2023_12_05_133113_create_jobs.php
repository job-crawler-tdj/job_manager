<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('source');
            $table->string('company')->unique();
            $table->string('job_name');
            $table->string('url');
            $table->unsignedInteger('min_monthly_salary')->nullable();
            $table->unsignedInteger('max_monthly_salary')->nullable();
            $table->unsignedInteger('min_annual_salary')->nullable();
            $table->unsignedInteger('max_annual_salary')->nullable();
            $table->float('rating')->nullable();
            $table->boolean('illegal')->default(false);
            $table->text('comment')->nullable();
            $table->timestamp('last_check_time')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->timestamp('delivery_time')->nullable();
            $table->boolean('starred')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
