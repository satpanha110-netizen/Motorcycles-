<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['Scooter', 'Automatic step-through bikes — perfect for city commuting.'],
            ['Sport Bike', 'High-performance machines inspired by racing.'],
            ['Cruiser', 'Relaxed riding position with classic, laid-back styling.'],
            ['Off-Road / Dirt', 'Built for trails, jumps, and adventure rides.'],
            ['Commuter', 'Fuel-efficient, dependable daily riders.'],
        ];

        foreach ($categories as [$name, $description]) {
            Category::create(['name' => $name, 'description' => $description]);
        }
    }
}
