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

class MotorcycleSeeder extends Seeder
{
    /** Gradient color pairs for generated placeholder images. */
    private array $palettes = [
        ['#0f172a', '#ea580c'],
        ['#1e293b', '#f59e0b'],
        ['#312e81', '#6366f1'],
        ['#134e4a', '#10b981'],
        ['#7f1d1d', '#ef4444'],
        ['#1e1b4b', '#a855f7'],
        ['#0c4a6e', '#0ea5e9'],
        ['#3f3f46', '#e4e4e7'],
    ];

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

        $brands = Brand::with('motorcycles')->get()->keyBy('name');
        $categories = Category::all();
        $sellers = User::where('role', 'seller')->get();

        $rows = [];
        $i = 0;

        foreach ($this->catalog as $brandName => $models) {
            $brand = $brands[$brandName];

            foreach ($models as [$model, $cc, $transmission, $priceRange]) {
                $condition = $i % 5 === 0 ? 'new' : 'used';
                $year = $condition === 'new'
                    ? random_int(now()->year - 1, now()->year)
                    : random_int(2016, now()->year - 1);
                $price = random_int((int) ($priceRange[0] / 50), (int) ($priceRange[1] / 50)) * 50;

                $status = match (true) {
                    $i % 11 === 0 => 'pending',
                    $i % 13 === 0 => 'sold',
                    default => 'approved',
                };

                $title = "{$brandName} {$model}";

                $rows[] = [
                    'brand_id' => $brand->id,
                    'category_id' => $categories->random()->id,
                    'seller_id' => $sellers[$i % $sellers->count()]->id,
                    'title' => $title,
                    'model' => $model,
                    'year' => $year,
                    'price' => $price,
                    'engine_cc' => $cc,
                    'mileage' => $condition === 'new' ? random_int(0, 40) : random_int(2000, 45000),
                    'condition' => $condition,
                    'transmission' => $transmission,
                    'fuel_type' => $cc >= 800 && $i % 7 === 0 ? 'petrol' : 'petrol',
                    'color' => collect(['Red', 'Black', 'Blue', 'White', 'Grey', 'Orange'])->random(),
                    'location' => $this->locations[$i % count($this->locations)],
                    'featured' => false,
                    'description' => $this->description($title, $year, $condition),
                    'features' => collect($this->featurePool)->random(random_int(4, 7))->values()->all(),
                    'main_image' => null,
                    'status' => $status,
                    'created_at' => now()->subDays(random_int(1, 300)),
                ];
                $i++;
            }
        }

        // Insert and attach images.
        foreach ($rows as $index => $row) {
            $moto = Motorcycle::create($row);

            $palette = $this->palettes[$index % count($this->palettes)];
            $moto->update([
                'main_image' => $this->makePlaceholder("{$moto->slug}-main", $moto->title, $palette),
            ]);

            for ($g = 1; $g <= 3; $g++) {
                MotorcycleImage::create([
                    'motorcycle_id' => $moto->id,
                    'image' => $this->makePlaceholder(
                        "{$moto->slug}-gallery-{$g}",
                        "{$moto->title} — {$g}/3",
                        $this->palettes[($index + $g) % count($this->palettes)]
                    ),
                ]);
            }

            $motos[] = $moto;
        }

        // Mark 8 approved bikes as featured.
        Motorcycle::where('status', 'approved')
            ->inRandomOrder()
            ->take(8)
            ->update(['featured' => true]);
    }

    private function description(string $title, int $year, string $condition): string
    {
        if ($condition === 'new') {
            return "Brand new {$title} ({$year}). Zero kilometers, full warranty from the dealer, " .
                "and ready for immediate pickup. Financing options available — contact the seller today.";
        }

        return "Well-maintained {$title} from {$year}. Runs perfectly with no issues — engine, brakes, " .
            "and electronics all in excellent condition. Regularly serviced at an authorized workshop. " .
            "Clean paperwork, ready to transfer. Serious buyers only, please.";
    }

    /**
     * Generate a stylized motorcycle placeholder image (SVG) in storage.
     */
    private function makePlaceholder(string $filename, string $label, array $colors): string
    {
        [$from, $to] = $colors;
        $label = htmlspecialchars(mb_substr($label, 0, 34), ENT_QUOTES);

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="800" height="600" viewBox="0 0 800 600">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="{$from}"/>
      <stop offset="100%" stop-color="{$to}"/>
    </linearGradient>
  </defs>
  <rect width="800" height="600" fill="url(#bg)"/>
  <circle cx="670" cy="110" r="170" fill="rgba(255,255,255,.06)"/>
  <circle cx="90" cy="520" r="220" fill="rgba(0,0,0,.10)"/>
  <rect y="470" width="800" height="130" fill="rgba(0,0,0,.28)"/>
  <line x1="30" y1="535" x2="770" y2="535" stroke="rgba(255,255,255,.35)" stroke-width="6" stroke-dasharray="42 30"/>
  <text x="400" y="96" font-family="Segoe UI,Arial,sans-serif" font-size="40" font-weight="800"
        fill="#ffffff" text-anchor="middle">{$label}</text>
  <g transform="translate(400,350)" fill="none" stroke="#f8fafc" stroke-width="14" stroke-linecap="round">
    <circle cx="-140" cy="85" r="62"/>
    <circle cx="-140" cy="85" r="22" fill="#f8fafc" stroke="none"/>
    <circle cx="150" cy="85" r="62"/>
    <circle cx="150" cy="85" r="22" fill="#f8fafc" stroke="none"/>
    <path d="M -140 85 L -58 -12 L 42 -12 L 92 38 L 150 85"/>
    <path d="M -58 -12 L -18 -72 L 72 -72"/>
    <path d="M -112 -20 L -20 -20"/>
    <path d="M 72 -72 L 98 -98 M 76 -102 L 118 -94"/>
    <path d="M -36 48 L 62 56" stroke-width="10"/>
  </g>
</svg>
SVG;

        $path = "motorcycles/{$filename}.svg";
        Storage::disk(StorageHelper::disk())->put($path, $svg);

        return $path;
    }
}
