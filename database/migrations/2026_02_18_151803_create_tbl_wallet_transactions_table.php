<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id');
            $table->unsignedBigInteger('user_id');
            $table->string('transaction_id')->unique(); // Unique transaction ID
            $table->string('type'); // credit, debit, withdrawal, referral_bonus, etc.
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2);
            $table->decimal('balance_after', 15, 2);
            $table->string('status')->default('completed'); // pending, completed, failed
            $table->string('description')->nullable();
            $table->string('reference_type')->nullable(); // e.g., 'withdrawal', 'referral', 'payout'
            $table->unsignedBigInteger('reference_id')->nullable(); // ID of related record
            $table->json('metadata')->nullable(); // Additional data
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            $table->foreign('wallet_id')->references('id')->on('tbl_wallet')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('tbl_user')->onDelete('cascade');
            
            $table->index(['wallet_id', 'created_at']);
            $table->index(['user_id', 'type']);
            $table->index('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_wallet_transactions');
    }
};