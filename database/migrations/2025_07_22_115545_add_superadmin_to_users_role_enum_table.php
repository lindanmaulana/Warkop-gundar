<?php

use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $roles = array_map(fn($case) => "'{$case->value}'", UserRole::cases());
            $enumString = implode(', ', $roles);

            DB::statement("ALTER TABLE users CHANGE role role ENUM({$enumString}) DEFAULT '" . UserRole::Customer->value . "'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement("ALTER TABLE users CHANGE role role ENUM('customer', 'admin') DEFAULT '" . UserRole::Customer->value . "'");
        });
    }
};
