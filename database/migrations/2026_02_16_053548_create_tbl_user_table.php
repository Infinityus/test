<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Change this to 'vidya' when you want to run on second DB
    // or remove/comment it to use default (blog_db)
    // protected $connection = 'vidya';

    public function up(): void
    {
        Schema::create('tbl_user', function (Blueprint $table) {
            $table->id();                           // bigint unsigned auto-increment primary
            $table->string('name', 100);
            $table->string('mobile', 15)->nullable();   // or ->unique() if needed
            $table->string('device_name', 100)->nullable();
            $table->string('token', 255)->nullable();   // for device token / auth
            $table->tinyInteger('status')->default(1);  // 0=inactive, 1=active
            $table->timestamps();                       // created_at + updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_user');
    }
};
