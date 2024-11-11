<?php

namespace Database\Seeders;

use App\Models\NotificationType;
use Illuminate\Database\Seeder;

class NotificationTypeSeeder extends Seeder
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
    }
}
