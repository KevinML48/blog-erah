<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $adminUser = User::where('role', 'admin')->inRandomOrder()->first();

        $theme = Theme::inRandomOrder()->first();

        $publicationTime = $this->faker->dateTimeBetween('-48 day', '2 day');

        return [
            'title' => $this->faker->sentence,
            'body' => implode("\n\n", $this->faker->paragraphs(rand(1, 10))),

            'publication_time' => $publicationTime,
            'user_id' => $adminUser->id,
            'theme_id' => $theme->id,
            'created_at' => $publicationTime,
            'updated_at' => $publicationTime,
        ];
    }
}
