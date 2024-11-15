<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\CommentContent;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Comment::factory()
            ->count(100)
            ->withoutParentId()
            ->create()
            ->each(function ($comment) {
                CommentContent::factory([
                    'comment_id' => $comment->id,
                ])->create();
            })
        ;

        Comment::factory()
            ->count(500)
            ->withParentId()
            ->create()
            ->each(function ($comment) {
                CommentContent::factory([
                    'comment_id' => $comment->id,
                ])->create();
            })
        ;
    }
}
