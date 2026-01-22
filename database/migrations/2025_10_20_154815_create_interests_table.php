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
        
        if (Schema::hasTable('interests')) {
            return;
        }
        
        Schema::create('interests', function (Blueprint $table) {
            $table->id();
            $table->string('qr_id', 255);
            $table->string('interest_name', 255);
            $table->timestamps();
            
            $table->foreign('qr_id')->references('qr_id')->on('users')->onDelete('cascade');
            $table->index('qr_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interests');
    }
};