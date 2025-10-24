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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->string('qr_id', 255);
            $table->string('organization_name', 100);
            $table->string('membership_type', 100);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('membership_status', 100)->nullable();
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
        Schema::dropIfExists('memberships');
    }
};