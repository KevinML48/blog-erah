<?php

namespace Database\Factories;

use App\Models\CommentContent;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Like>
 */
class LikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();

        $likeable = $this->faker->randomElement([
            Post::whereDoesntHave('likes', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->inRandomOrder()->first(),

            CommentContent::whereDoesntHave('likes', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->inRandomOrder()->first(),
        ]);

        return [
            'user_id' => $user->id,
            'likeable_id' => $likeable->id,
            'likeable_type' => get_class($likeable),
        ];
    }
}
