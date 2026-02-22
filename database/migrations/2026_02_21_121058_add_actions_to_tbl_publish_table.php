<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_publish', function (Blueprint $table) {
            $table->integer('actions')->default(0)->after('views')->comment('Number of conversions/clicks');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_publish', function (Blueprint $table) {
            $table->dropColumn('actions');
        });
    }
};