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
        
        if (Schema::hasTable('education')) {
            return;
        }
        
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->string('qr_id', 255);
            $table->string('degree_name', 100);
            $table->string('field_of_study', 100)->nullable();
            $table->string('university_name', 100)->nullable();
            $table->date('start_year')->nullable();
            $table->date('end_year')->nullable();
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
        Schema::dropIfExists('education');
    }
};

