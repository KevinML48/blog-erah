<?php

namespace Database\Seeders;

use App\Models\NotificationType;
use Illuminate\Database\Seeder;

class NotificationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NotificationType::firstOrCreate([
            'name' => 'post_published',
        ], [
            'description' => 'Notifies when a post is published in a theme',
            'category' => 'theme',
        ]);

        NotificationType::firstOrCreate([
            'name' => 'comment_reply',
            'description' => 'Notifies when a user receives a reply to their comment.',
            'category' => 'interaction',
        ]);

        NotificationType::firstOrCreate([
            'name' => 'comment_like',
            'description' => 'Notifies when a user\'s comment gets a like.',
            'category' => 'interaction',
        ]);
    }
}
