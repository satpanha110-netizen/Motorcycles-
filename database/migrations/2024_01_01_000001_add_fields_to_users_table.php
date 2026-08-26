<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->after('email');
            $table->enum('role', ['admin', 'seller', 'customer'])->default('customer')->after('password');
            $table->string('profile_image')->nullable()->after('role');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('profile_image');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'role', 'profile_image', 'status']);
        });
    }
};
