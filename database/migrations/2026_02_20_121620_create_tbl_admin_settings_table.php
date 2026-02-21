<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_admin_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->string('currency')->default('INR');
            $table->string('setting_name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_admin_settings');
    }
};