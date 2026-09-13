<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Motorcycle;
use App\Models\MotorcycleImage;
use App\Models\StorageHelper;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Support\MotoImageGenerator;

/**
 * Seeds the real starting dataset:
 *  - admin:  nha@gmail.com / 12345678
 *  - seller: seller@gmail.com / 12345678
 *  - brands, categories and 100 motorcycle listings.
 */
class InitialDataSeeder extends Seeder
{
    private array $catalog = [
        'Honda' => [
            ['Click 160', 160, 'automatic', [2900, 3600]],
            ['Click 125', 125, 'automatic', [2300, 2800]],
            ['Wave Alpha 110', 110, 'manual', [1200, 1600]],
            ['CBR500R', 500, 'manual', [7200, 9500]],
            ['Rebel 300', 300, 'manual', [5800, 7000]],
            ['PCX 160', 160, 'automatic', [3400, 4200]],
        ],
        'Yamaha' => [
            ['NMAX 155', 155, 'automatic', [3100, 3900]],
            ['Mio i 125', 125, 'automatic', [1900, 2400]],
            ['MT-07', 700, 'manual', [8900, 11500]],
            ['YZF-R15', 150, 'manual', [3800, 4600]],
            ['Exciter 155', 155, 'manual', [3200, 4000]],
        ],
        'Suzuki' => [
            ['GSX-R600', 600, 'manual', [9500, 12500]],
            ['Smash 115', 115, 'manual', [1300, 1700]],
            ['Burgman Street', 125, 'automatic', [2600, 3200]],
        ],
        'Kawasaki' => [
            ['Ninja 400', 400, 'manual', [6500, 8200]],
            ['Z900', 900, 'manual', [10500, 13500]],
            ['W175', 175, 'manual', [2700, 3300]],
        ],
        'Vespa' => [
            ['Primavera 150', 150, 'automatic', [4800, 5900]],
            ['Sprint 150', 150, 'automatic', [5200, 6300]],
        ],
        'Ducati' => [
            ['Monster 797', 800, 'manual', [11800, 14500]],
            ['Panigale V2', 1000, 'manual', [19500, 24000]],
        ],
        'BMW' => [
            ['G 310 R', 310, 'manual', [5900, 7200]],
            ['R 1250 GS', 1250, 'manual', [21000, 26000]],
        ],
        'KTM' => [
            ['Duke 390', 390, 'manual', [6200, 7800]],
            ['RC 390', 390, 'manual', [6800, 8400]],
        ],
    ];

    private array $locations = [
        'Phnom Penh', 'Siem Reap', 'Battambang', 'Sihanoukville',
        'Kampong Cham', 'Kampot', 'Poipet', 'Takeo',
    ];

    private array $featurePool = [
        'ABS brakes', 'LED lighting', 'Digital dashboard', 'USB charging port',
        'Keyless ignition', 'Disc brakes front & rear', 'Tubeless tires',
        'Alarm system', 'Alloy wheels', 'Full service history',
        'New tires installed', 'Recently serviced', 'Original paint',
        'Aftermarket exhaust', 'Crash guards included',
    ];

    public function run(): void
    {
        Storage::disk(StorageHelper::disk())->deleteDirectory('motorcycles');
        Storage::disk(StorageHelper::disk())->makeDirectory('motorcycles');

        // ---- Accounts ----
        $admin = User::updateOrCreate(
            ['email' => 'nha@gmail.com'],
            [
                'name' => 'Admin',
                'phone' => '+855 11 222 333',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        $seller = User::updateOrCreate(
            ['email' => 'seller@gmail.com'],
            [
                'name' => 'Seller',
                'phone' => '+855 12 333 444',
                'password' => Hash::make('12345678'),
                'role' => 'seller',
                'status' => 'active',
            ]
        );

        // ---- Brands & categories ----
        foreach ([
            ['Honda', 'The world\'s largest motorcycle manufacturer — famous for reliability.'],
            ['Yamaha', 'Innovative Japanese engineering with a sporty spirit.'],
            ['Suzuki', 'Legendary performance, from commuters to superbikes.'],
            ['Kawasaki', 'Home of the Ninja — aggressive styling and thrilling power.'],
            ['Vespa', 'Iconic Italian scooters with timeless design.'],
            ['Ducati', 'Premium Italian superbikes — passion on two wheels.'],
            ['BMW', 'German precision engineering for touring and adventure.'],
            ['KTM', 'Ready to race — Austrian off-road and street performance.'],
        ] as [$name, $desc]) {
            Brand::updateOrCreate(['name' => $name], ['description' => $desc]);
        }

        foreach ([
            ['Scooter', 'Automatic step-through bikes — perfect for city commuting.'],
            ['Sport Bike', 'High-performance machines inspired by racing.'],
            ['Cruiser', 'Relaxed riding position with classic styling.'],
            ['Off-Road / Dirt', 'Built for trails, jumps, and adventure rides.'],
            ['Commuter', 'Fuel-efficient, dependable daily riders.'],
        ] as [$name, $desc]) {
            Category::updateOrCreate(['name' => $name], ['description' => $desc]);
        }

        $brands = Brand::all()->keyBy('name');
        $categories = Category::pluck('id')->all();

        // ---- 100 motorcycles ----
        Storage::disk(StorageHelper::disk())->deleteDirectory('motorcycles');
        Storage::disk(StorageHelper::disk())->makeDirectory('motorcycles');

        $combos = [];
        foreach ($this->catalog as $brandName => $models) {
            foreach ($models as $m) {
                $combos[] = [$brandName, ...$m];
            }
        }

        $total = 100;
        for ($i = 0; $i < $total; $i++) {
            [$brandName, $model, $cc, $transmission, $range] = $combos[$i % count($combos)];

            $condition = $i % 5 === 0 ? 'new' : 'used';
            $year = $condition === 'new'
                ? random_int(now()->year - 1, now()->year)
                : random_int(2016, now()->year - 1);
            $price = random_int((int) ($range[0] / 50), (int) ($range[1] / 50)) * 50;

            $status = match (true) {
                $i % 14 === 0 => 'pending',
                $i % 17 === 0 => 'sold',
                default => 'approved',
            };

            $title = "{$brandName} {$model}";

            $moto = Motorcycle::create([
                'brand_id' => $brands[$brandName]->id,
                'category_id' => $categories[array_rand($categories)],
                'seller_id' => $seller->id,
                'title' => $title,
                'model' => $model,
                'year' => $year,
                'price' => $price,
                'engine_cc' => $cc,
                'mileage' => $condition === 'new' ? random_int(0, 40) : random_int(2000, 45000),
                'condition' => $condition,
                'transmission' => $transmission,
                'fuel_type' => 'petrol',
                'color' => collect(['Red', 'Black', 'Blue', 'White', 'Grey', 'Orange'])->random(),
                'location' => $this->locations[$i % count($this->locations)],
                'featured' => false,
                'description' => $this->description($title, $year, $condition),
                'features' => collect($this->featurePool)->random(random_int(4, 7))->values()->all(),
                'main_image' => MotoImageGenerator::make("moto-{$i}-main", $title, $i),
                'status' => $status,
                'created_at' => now()->subDays(random_int(1, 300)),
            ]);

            for ($g = 1; $g <= 3; $g++) {
                MotorcycleImage::create([
                    'motorcycle_id' => $moto->id,
                    'image' => MotoImageGenerator::make("moto-{$i}-gallery-{$g}", "{$title} — {$g}/3", $i + $g),
                ]);
            }
        }

        // Feature 8 approved listings for the homepage.
        Motorcycle::where('status', 'approved')->inRandomOrder()->take(8)->update(['featured' => true]);
    }

    private function description(string $title, int $year, string $condition): string
    {
        if ($condition === 'new') {
            return "Brand new {$title} ({$year}). Zero kilometers, full warranty from the dealer, " .
                "and ready for immediate pickup. Financing options available — contact the seller today.";
        }

        return "Well-maintained {$title} from {$year}. Runs perfectly with no issues — engine, brakes, " .
            "and electronics all in excellent condition. Regularly serviced at an authorized workshop. " .
            "Clean paperwork, ready to transfer.";
    }
}
