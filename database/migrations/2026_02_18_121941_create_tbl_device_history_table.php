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
        Schema::create('tbl_device_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('device_name')->nullable();
            $table->string('mobile', 20)->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->string('device_type')->nullable();
            $table->string('device_os')->nullable();
            $table->string('app_version')->nullable();
            $table->timestamp('login_at')->nullable();
            $table->string('action')->nullable(); // 'new_device', 'device_change', 'existing_device'
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')
                  ->references('id')
                  ->on('tbl_user')
                  ->onDelete('set null');
                  
            // Index for faster queries
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_device_history');
    }
};