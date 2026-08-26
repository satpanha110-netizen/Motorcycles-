<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'seller_id' => User::factory(),
            'price' => fake()->randomFloat(2, 1000, 15000),
            'status' => fake()->randomElement(['pending', 'confirmed', 'processing', 'completed', 'completed', 'cancelled']),
            'customer_name' => fake()->name(),
            'customer_phone' => '+855 ' . fake()->numberBetween(10, 99) . ' ' . fake()->numberBetween(100, 999) . ' ' . fake()->numberBetween(100, 999),
            'customer_address' => fake()->streetAddress() . ', Phnom Penh',
            'notes' => fake()->optional(.5)->sentence(),
            'created_at' => fake()->dateTimeBetween('-10 months', 'now'),
        ];
    }
}
