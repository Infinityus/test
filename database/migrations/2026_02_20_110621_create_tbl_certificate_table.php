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
        Schema::create('tbl_certificate', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('course_name')->nullable();
            $table->boolean('certificate')->default(false); // true or false
            $table->string('mobile', 20)->nullable()->index();
            $table->string('certificate_id')->nullable()->unique(); // Unique certificate ID
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('issued_by')->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // For additional data
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('user_id')
                  ->references('id')
                  ->on('tbl_user')
                  ->onDelete('set null');
                  
            // Indexes for faster queries
            $table->index('user_id');
            $table->index('course_name');
            $table->index('certificate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_certificate');
    }
};