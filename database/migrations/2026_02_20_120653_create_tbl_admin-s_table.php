<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_admin-s', function (Blueprint $table) {
            $table->id();
            $table->string('admin_name')->nullable();
            $table->integer('role')->default(2); // 1=super admin, 2=admin, 3=employee
            $table->string('ip_address', 45)->nullable();
            $table->text('token')->nullable();
            $table->boolean('status')->default(true);
            $table->string('mobile', 20)->nullable()->index();
            $table->string('gmail')->nullable()->unique();
            //$table->string('password')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->rememberToken();
            $table->timestamps();
            
            // Indexes
            $table->index('role');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_admin-s');
    }
};