<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Theme::create(['name' => 'ERAH', 'slug' => 'erah']);
        Theme::create(['name' => 'Valorant', 'slug' => 'valorant']);
        Theme::create(['name' => 'Rocket League', 'slug' => 'rocket-league']);
        Theme::create(['name' => 'Black Ops 6', 'slug' => 'black-ops-6']);
    }
}
