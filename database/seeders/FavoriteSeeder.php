<?php

namespace Database\Seeders;

use App\Models\Favorite;
use App\Models\Motorcycle;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $motorcycles = Motorcycle::approved()->get();

        foreach ($customers as $customer) {
            $picks = $motorcycles->random(min(random_int(1, 5), $motorcycles->count()));

            foreach ($picks as $moto) {
                Favorite::firstOrCreate([
                    'user_id' => $customer->id,
                    'motorcycle_id' => $moto->id,
                ]);
            }
        }
    }
}
