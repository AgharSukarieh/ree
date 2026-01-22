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
        
        if (Schema::hasTable('experiences')) {
            return;
        }
        
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('qr_id', 255);
            $table->string('title', 60);
            $table->string('company', 60);
            $table->string('location', 60);
            $table->string('start_date', 60);
            $table->date('end_date')->nullable();
            $table->text('description');
            $table->tinyInteger('is_internship')->default(0);
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
        Schema::dropIfExists('experiences');
    }
};