<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('notification_type_id')->constrained('notification_types')->onDelete('cascade'); // Link to notification_types table
            $table->boolean('is_enabled')->default(true); // User preference for enabling/disabling
            $table->unsignedBigInteger('context_id')->nullable(); // ID for specific context, e.g., a particular post or comment
            $table->string('context_type')->nullable(); // Type of context, e.g., 'post', 'comment'
            $table->timestamps();

            // Unique constraint to prevent duplicates
            $table->unique(['user_id', 'notification_type_id', 'context_id', 'context_type'], 'user_notification_pref_unique_idx'); // Shorter index name to prevent the 'too long' error

            // Indexes for faster querying
            $table->index(['user_id', 'notification_type_id']); // Index on user and type
            $table->index(['context_id', 'context_type']);       // Index on context fields
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notification_preferences');
    }
};
