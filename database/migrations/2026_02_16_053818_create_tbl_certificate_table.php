<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // protected $connection = 'vidya';   // ← uncomment when running for vidya_db

    public function up(): void
    {
        Schema::create('tbl_certificate', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('tbl_user')     // references tbl_user.id
                ->onDelete('cascade');
            $table->string('course_name', 150);
            $table->boolean('certificate')->default(false);  // 0 or 1 (using boolean = tinyint)
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_certificate');
    }
};
