<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition()
    {
        return [];
    }

    /**
     * Create comments without parent IDs.
     */
    public function withoutParentId()
    {
        return $this->state(function (array $attributes) {
            $post = Post::inRandomOrder()->first();
            return [
                'post_id' => $post->id,
                'parent_id' => null,
            ];
        });
    }

    /**
     * Create comments with parent IDs (replying to another comment).
     */
    public function withParentId()
    {
        return $this->state(function (array $attributes) {
            $comment = Comment::inRandomOrder()->first();
            return [
                'post_id' => $comment->post_id,
                'parent_id' => $comment->id,
            ];
        });
    }
}
