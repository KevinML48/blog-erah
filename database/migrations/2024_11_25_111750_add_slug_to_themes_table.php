<?php

use App\Models\Theme;
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
        // Step 1: Add the 'slug' column to the 'themes' table
        Schema::table('themes', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name'); // Add 'slug' column
        });

        // Step 2: Populate the 'slug' column for all existing records
        // Generate slugs from the 'name' field and save them
        Theme::all()->each(function ($theme) {
            $theme->slug = Str::slug($theme->name);  // Generate slug from name
            $theme->save();
        });

        // Step 3: Add the unique constraint to the 'slug' column
        Schema::table('themes', function (Blueprint $table) {
            $table->unique('slug');  // Add unique constraint on 'slug'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('themes', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
