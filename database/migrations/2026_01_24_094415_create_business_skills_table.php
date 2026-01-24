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
        
        if (Schema::hasTable('business_skills')) {
            return;
        }
        
        Schema::create('business_skills', function (Blueprint $table) {
            $table->id();
            $table->string('qr_id', 255);
            $table->string('skill_name', 100);
            $table->unsignedBigInteger('category_id');
            $table->timestamps();
            
            $table->foreign('qr_id')->references('qr_id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('business_skill_categories')->onDelete('cascade');
            $table->index('qr_id');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_skills');
    }
};
