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
        Theme::create(['name' => 'ERAH']);
        Theme::create(['name' => 'Valorant']);
        Theme::create(['name' => 'Rocket League']);
        Theme::create(['name' => 'Black Ops 6']);
    }
}
