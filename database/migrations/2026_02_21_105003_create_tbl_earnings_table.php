<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_earnings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('blog_id')->nullable();
            $table->string('blog_name')->nullable();
            $table->string('blog_status')->default('pending'); // pending, paid, cancelled
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->string('currency')->default('INR');
            $table->string('transaction_id')->nullable()->unique();
            $table->timestamp('earned_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Foreign key
            $table->foreign('user_id')
                  ->references('id')
                  ->on('tbl_user')
                  ->onDelete('set null');
                  
            // Indexes
            $table->index('user_id');
            $table->index('blog_id');
            $table->index('blog_status');
            $table->index('earned_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_earnings');
    }
};