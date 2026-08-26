<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['Honda', 'The world\'s largest motorcycle manufacturer — famous for reliability and resale value.', null],
            ['Yamaha', 'Innovative Japanese engineering with a sporty spirit.', null],
            ['Suzuki', 'Legendary performance, from commuters to superbikes.', 'images/brands/suzuki.svg'],
            ['Kawasaki', 'Home of the Ninja — aggressive styling and thrilling power.', null],
            ['Vespa', 'Iconic Italian scooters with timeless design.', null],
            ['Ducati', 'Premium Italian superbikes — passion on two wheels.', null],
            ['BMW', 'German precision engineering for touring and adventure.', null],
            ['KTM', 'Ready to race — Austrian off-road and street performance.', null],
        ];

        foreach ($brands as [$name, $description, $logo]) {
            Brand::create(['name' => $name, 'description' => $description, 'logo' => $logo]);
        }
    }
}
