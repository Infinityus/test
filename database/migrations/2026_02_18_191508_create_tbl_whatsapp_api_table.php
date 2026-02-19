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
        Schema::create('tbl_whatsapp_api', function (Blueprint $table) {
            $table->id();
            $table->string('user', 100)->nullable();
            $table->string('pass', 255)->nullable(); // Store encrypted/hashed password
            $table->string('sender', 100)->nullable();
            $table->string('text', 255)->nullable();
            $table->string('priority', 50)->nullable()->default('wa');
            $table->string('stype', 50)->nullable()->default('auth');
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('user');
            $table->index('stype');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_whatsapp_api');
    }
};