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
        Schema::create('tbl_device', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID (primary key)
            $table->unsignedBigInteger('user_id')->nullable(); // Foreign key to users table
            $table->string('device_name')->nullable();
            $table->string('mobile', 20)->nullable()->index();
            $table->text('token')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('device_type')->nullable(); // Android, iOS, Web, etc.
            $table->string('device_os')->nullable(); // OS version
            $table->string('app_version')->nullable(); // App version
            $table->timestamp('last_used_at')->nullable();
            
            // Waiting time column - for device change restrictions (24 hour lock)
            $table->timestamp('waiting_time')->nullable(); // When device change restriction ends
            
            $table->timestamps();
            
            // Foreign key constraint (if you have users table)
            $table->foreign('user_id')
                  ->references('id')
                  ->on('tbl_user')
                  ->onDelete('set null'); // If user deleted, keep device record but set user_id to null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_device');
    }
};