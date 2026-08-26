<?php

namespace Database\Seeders;

use App\Models\Motorcycle;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $motorcycles = Motorcycle::whereIn('status', ['approved', 'sold'])->get();

        if ($motorcycles->isEmpty() || $customers->isEmpty()) {
            return;
        }

        // Spread orders across the last 10 months so charts look alive.
        foreach (range(1, 18) as $i) {
            $moto = $motorcycles->random();
            $customer = $customers->random();

            Order::create([
                'user_id' => $customer->id,
                'motorcycle_id' => $moto->id,
                'seller_id' => $moto->seller_id,
                'price' => $moto->price,
                'status' => collect(['pending', 'confirmed', 'processing', 'completed', 'completed', 'cancelled'])->random(),
                'customer_name' => $customer->name,
                'customer_phone' => $customer->phone,
                'customer_address' => fake()->streetAddress() . ', Phnom Penh',
                'notes' => fake()->optional(.6)->sentence(8),
                'created_at' => now()->subDays(random_int(1, 300)),
            ]);
        }
    }
}
