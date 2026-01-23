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
        Schema::table('projects', function (Blueprint $table) {
            // Make description nullable
            $table->text('description')->nullable()->change();
            // Fix technologies_used to be string instead of year (if it's still year type)
            if (Schema::hasColumn('projects', 'technologies_used')) {
                $table->string('technologies_used', 255)->nullable()->change();
            }
            // Make link nullable as well
            $table->string('link', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
            if (Schema::hasColumn('projects', 'technologies_used')) {
                $table->string('technologies_used', 255)->nullable(false)->default('')->change();
            }
            $table->string('link', 255)->nullable(false)->default('')->change();
        });
    }
};
