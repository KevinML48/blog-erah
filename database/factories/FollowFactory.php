<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Follow>
 */
class FollowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $follower = User::inRandomOrder()->first();

        do {
            $followed = User::inRandomOrder()->first();
        } while (
            $follower->id === $followed->id ||
            $follower->follows->contains('followed_id', $followed->id)
        );


        return [
            'follower_id' => $follower->id,
            'followed_id' => $followed->id,
        ];
    }
}
