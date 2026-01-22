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
        
        if (Schema::hasTable('users')) {
            return;
        }
        
        Schema::create('users', function (Blueprint $table) {
            $table->string('qr_id')->primary();
            $table->string('name', 60);
            $table->string('phone', 60);
            $table->string('city', 60);
            $table->string('job_title', 60);
            $table->text('profile_summary');
            $table->string('email', 255);
            $table->string('linkedin_profile', 255);
            $table->string('github_profile', 255);
            $table->string('profile_website', 255);
            $table->string('profile_image', 255);
            $table->enum('major', ['IT', 'Medicine', 'Engineering', 'Business']);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            
            $table->unique('email');
            $table->index('qr_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};