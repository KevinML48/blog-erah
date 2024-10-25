<?php

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
        $defaultThemeId = DB::table('themes')->insertGetId([
            'name' => 'ERAH',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Schema::table('posts', function (Blueprint $table) use ($defaultThemeId) {
            $table->foreignId('theme_id')->default($defaultThemeId)->constrained('themes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['theme_id']);
            $table->dropColumn('theme_id');
        });

        DB::table('themes')->where('name', 'Default Theme')->delete();
    }
};
