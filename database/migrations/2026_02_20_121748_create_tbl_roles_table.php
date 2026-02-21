<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_roles', function (Blueprint $table) {
            $table->id();
            $table->integer('role_id')->unique(); // 1,2,3
            $table->string('role_name'); // super admin, admin, employee
            $table->string('description')->nullable();
            $table->json('permissions')->nullable();
            $table->timestamps();
        });

        // Insert default roles
        DB::table('tbl_roles')->insert([
            ['role_id' => 1, 'role_name' => 'Super Admin', 'description' => 'Full system access', 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'role_name' => 'Admin', 'description' => 'Manage users and content', 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 3, 'role_name' => 'Employee', 'description' => 'Limited access', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_roles');
    }
};