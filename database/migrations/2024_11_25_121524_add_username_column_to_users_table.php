<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('name');
        });

        User::all()->each(function ($user) {
            $user->username = Str::slug($user->name);
            $user->save();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unique('username');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unique('name');
        });

        // Step 2: Drop the 'username' column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};
