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
        Schema::create('tbl_publish', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('blog_id')->nullable();
            $table->string('status')->default('draft'); // draft, published, archived, pending
            $table->bigInteger('views')->default(0);
            $table->decimal('conversion_rate', 5, 2)->default(0.00); // e.g., 15.50%
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->string('category')->nullable();
            $table->json('tags')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('user_id')
                  ->references('id')
                  ->on('tbl_user')
                  ->onDelete('set null');
            
            // Indexes for faster queries
            $table->index('user_id');
            $table->index('blog_id');
            $table->index('status');
            $table->index('published_at');
            $table->index('views');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_publish');
    }
};