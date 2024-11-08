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
        Schema::table('comments_content', function (Blueprint $table) {
            $table->unsignedBigInteger('comment_id')->after('id')->nullable();

            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['content_id']);

            $table->dropIndex(['content_id']);

            $table->dropColumn('content_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments_content', function (Blueprint $table) {
            $table->dropForeign(['comment_id']);
            $table->dropColumn('comment_id');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedBigInteger('content_id')->nullable();

            $table->foreign('content_id')->references('id')->on('comments_content')->onDelete('set_null');
        });
    }
};
