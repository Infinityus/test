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
        Schema::create('tbl_banks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('mobile', 20)->nullable()->index();
            $table->string('bank_name')->nullable();
            $table->string('ifsc', 20)->nullable()->index();
            $table->string('upi', 50)->nullable()->index();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('account_type')->nullable(); // savings, current, etc.
            //$table->boolean('is_primary')->default(false);
            //$table->boolean('is_verified')->default(false);
            $table->string('status')->default('active'); // active, inactive, blocked
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('user_id')
                  ->references('id')
                  ->on('tbl_user')
                  ->onDelete('set null');
                  
            // Indexes for faster queries
            $table->index('user_id');
            $table->index('bank_name');
            $table->index('account_number');
            $table->index('is_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_banks');
    }
};