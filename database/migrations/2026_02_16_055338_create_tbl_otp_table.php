<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_otp', function (Blueprint $table) {
            $table->id();                           // auto-increment primary key (bigint unsigned)

            $table->foreignId('user_id')
                ->constrained('tbl_user')         // assumes your users table is tbl_user
                ->onDelete('cascade');            // delete OTPs if user is deleted

            $table->string('mobile', 15);           // mobile number (with country code or without)

            $table->string('otp', 6);               // usually 4 or 6 digits, stored as string

            $table->timestamp('expiry_time');       // when OTP becomes invalid

            $table->string('ip_address', 45)->nullable();  // supports IPv4 & IPv6

            $table->tinyInteger('rate_limit')
                ->default(0)
                ->comment('number of attempts (1-5 typically)');

            $table->timestamps();                   // created_at + updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_otp');
    }
};
