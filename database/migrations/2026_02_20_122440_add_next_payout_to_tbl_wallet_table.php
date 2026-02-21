<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_wallet', function (Blueprint $table) {
            // Add next_payout column as timestamp
            $table->timestamp('next_payout')->nullable()->after('currency');
        });

        // Update existing records: next_payout = created_at + 30 days
        DB::statement('UPDATE tbl_wallet SET next_payout = DATE_ADD(created_at, INTERVAL 30 DAY) WHERE next_payout IS NULL');
    }

    public function down(): void
    {
        Schema::table('tbl_wallet', function (Blueprint $table) {
            $table->dropColumn('next_payout');
        });
    }
};