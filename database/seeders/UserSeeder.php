<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'phone' => '+855 11 111 111',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // 3 sellers (first one is the documented demo seller)
        $sellerNames = [
            'Sok Vuthy', 'Chan Sophea', 'Dara Motors Shop',
        ];

        foreach ($sellerNames as $index => $name) {
            User::create([
                'name' => $name,
                'email' => $index === 0 ? 'seller@example.com' : "seller{$index}@example.com",
                'phone' => '+855 12 200 00' . $index,
                'password' => Hash::make('password'),
                'role' => 'seller',
                'status' => 'active',
                'telegram_username' => 'LEN_G168',
                'created_at' => now()->subMonths($index + 2),
            ]);
        }

        // 10 customers
        foreach (range(1, 10) as $i) {
            User::factory()->create([
                'name' => fake()->name(),
                'created_at' => now()->subDays(random_int(1, 330)),
            ]);
        }
    }
}
