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
        Schema::table('users', function (Blueprint $table) {
            // Make nullable fields that can be optional
            $table->string('phone', 60)->nullable()->change();
            $table->string('city', 60)->nullable()->change();
            $table->text('profile_summary')->nullable()->change();
            $table->string('email', 255)->nullable()->change();
            $table->string('linkedin_profile', 255)->nullable()->change();
            $table->string('github_profile', 255)->nullable()->change();
            $table->string('profile_website', 255)->nullable()->change();
            $table->string('profile_image', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert to NOT NULL (but we'll use empty strings as default)
            $table->string('phone', 60)->nullable(false)->default('')->change();
            $table->string('city', 60)->nullable(false)->default('')->change();
            $table->text('profile_summary')->nullable(false)->default('')->change();
            $table->string('email', 255)->nullable(false)->default('')->change();
            $table->string('linkedin_profile', 255)->nullable(false)->default('')->change();
            $table->string('github_profile', 255)->nullable(false)->default('')->change();
            $table->string('profile_website', 255)->nullable(false)->default('')->change();
            $table->string('profile_image', 255)->nullable(false)->default('')->change();
        });
    }
};
