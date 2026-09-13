<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Motorcycle;
use App\Models\MotorcycleImage;
use App\Models\StorageHelper;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Imports the user's real photos from image_for_add_to_database/(2) Pinterest/
 * into the database. Files sharing the same hash suffix belong to the same
 * bike: the first becomes main_image, the rest become gallery images.
 *
 * Replaces all previously seeded placeholder listings.
 */
class PinterestImagesSeeder extends Seeder
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
        $sourceDir = base_path('image_for_add_to_database');

        // ---- Collect and group images by hash suffix ----
        $groups = [];
        foreach (glob($sourceDir . '/**/*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE) as $path) {
            $name = basename($path);
            if (!preg_match('/^imgi_(\d+)_(.+)\.(jpe?g|png|webp|gif)$/i', $name, $m)) {
                continue; // skips download (1).svg etc.
            }
            $key = strtolower($m[2]);
            $groups[$key][] = ['index' => (int) $m[1], 'path' => $path];
        }
        ksort($groups);
        foreach ($groups as &$files) {
            usort($files, fn ($a, $b) => $a['index'] <=> $b['index']);
        }
        unset($files);

        if (empty($groups)) {
            $this->command->warn('No importable images found.');
            return;
        }

        // ---- Fresh storage + wipe old listings ----
        Storage::disk(StorageHelper::disk())->deleteDirectory('motorcycles');
        Storage::disk(StorageHelper::disk())->makeDirectory('motorcycles');
        MotorcycleImage::query()->delete();
        Motorcycle::query()->delete();

        $seller = User::where('email', 'seller@gmail.com')->firstOrFail();
        $brands = Brand::all()->keyBy('name');
        $categories = Category::pluck('id')->all();

        $combos = [];
        foreach ($this->catalog as $brandName => $models) {
            foreach ($models as $m) {
                $combos[] = [$brandName, ...$m];
            }
        }

        $disk = Storage::disk(StorageHelper::disk());
        $i = 0;
        $imported = 0;
        $totalImages = 0;

        foreach ($groups as $key => $files) {
            [$brandName, $model, $cc, $transmission, $range] = $combos[$i % count($combos)];

            $condition = $i % 5 === 0 ? 'new' : 'used';
            $year = $condition === 'new'
                ? random_int(now()->year - 1, now()->year)
                : random_int(2016, now()->year - 1);
            $price = random_int((int) ($range[0] / 50), (int) ($range[1] / 50)) * 50;

            $status = match (true) {
                $i % 20 === 19 => 'pending',
                $i % 25 === 24 => 'sold',
                default => 'approved',
            };

            $title = "{$brandName} {$model}";
            $slug = \Illuminate\Support\Str::slug("{$title}-{$year}-{$i}");

            // Copy files into storage.
            $storedMain = null;
            $galleryPaths = [];
            foreach ($files as $gi => $file) {
                $ext = strtolower(pathinfo($file['path'], PATHINFO_EXTENSION));
                $storedName = $gi === 0
                    ? "{$slug}.{$ext}"
                    : "{$slug}-g{$gi}.{$ext}";
                $disk->putFileAs('motorcycles', new \Illuminate\Http\File($file['path']), $storedName);

                if ($gi === 0) {
                    $storedMain = "motorcycles/{$storedName}";
                } else {
                    $galleryPaths[] = "motorcycles/{$storedName}";
                }
                $totalImages++;
            }

            if (!$storedMain) {
                continue;
            }

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
                'main_image' => $storedMain,
                'status' => $status,
                'created_at' => now()->subDays(random_int(1, 300)),
            ]);

            foreach ($galleryPaths as $gp) {
                MotorcycleImage::create([
                    'motorcycle_id' => $moto->id,
                    'image' => $gp,
                ]);
            }

            $imported++;
            $i++;
        }

        // Feature 8 approved listings for the homepage.
        Motorcycle::where('status', 'approved')->inRandomOrder()->take(8)->update(['featured' => true]);

        $this->command->info("Imported {$imported} motorcycles from {$totalImages} real photos.");
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
