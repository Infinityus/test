<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_wallet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // One wallet per user
            $table->decimal('balance', 15, 2)->default(0.00); // Total balance
            $table->decimal('total_earned', 15, 2)->default(0.00); // Lifetime earnings
            $table->decimal('total_withdrawn', 15, 2)->default(0.00); // Total withdrawn
            $table->decimal('pending_withdrawal', 15, 2)->default(0.00); // Processing withdrawals
            $table->decimal('available_balance', 15, 2)->default(0.00); // Available to withdraw
            $table->string('currency', 10)->default('USD');
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('user_id')
                  ->references('id')
                  ->on('tbl_user')
                  ->onDelete('cascade');
                  
            // Index for faster queries
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_wallet');
    }
};