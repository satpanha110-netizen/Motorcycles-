<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Motorcycle;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = User::where('role', 'seller')->get();
        $motorcycles = Motorcycle::approved()->get();

        $samples = [
            'Hi, is this bike still available? I can come see it this weekend.',
            'Does the price include the transfer paperwork?',
            'Any chance you would accept $200 less if I pay cash today?',
            'What is the service history like? Any accidents before?',
            'I am very interested. Can we schedule a test ride?',
        ];

        foreach (range(1, 10) as $i) {
            $moto = $motorcycles->random();

            Contact::create([
                'user_id' => null,
                'motorcycle_id' => $moto->id,
                'seller_id' => $moto->seller_id,
                'name' => fake()->name(),
                'email' => fake()->safeEmail(),
                'phone' => '+855 ' . random_int(10, 99) . ' ' . random_int(100, 999) . ' ' . random_int(100, 999),
                'message' => $samples[$i % count($samples)],
                'status' => $i % 3 === 0 ? 'read' : 'new',
                'created_at' => now()->subDays(random_int(0, 30)),
            ]);
        }

        // A couple of general messages (no seller) for the admin inbox.
        foreach (range(1, 2) as $j) {
            Contact::create([
                'name' => fake()->name(),
                'email' => fake()->safeEmail(),
                'phone' => '+855 ' . random_int(10, 99) . ' ' . random_int(100, 999) . ' ' . random_int(100, 999),
                'message' => 'Hello, I would like to know more about how to become a verified seller on MotoMarket.',
                'status' => 'new',
            ]);
        }
    }
}
