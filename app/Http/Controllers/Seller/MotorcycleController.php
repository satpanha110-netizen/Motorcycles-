<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMotorcycleRequest;
use App\Http\Requests\UpdateMotorcycleRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Motorcycle;
use App\Models\StorageHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MotorcycleController extends Controller
{
    public function index(): View
    {
        $motorcycles = Motorcycle::with(['brand', 'images'])
            ->where('seller_id', auth()->id())
            ->when(request('status'), fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('seller.motorcycles.index', compact('motorcycles'));
    }

    public function create(): View
    {
        return view('seller.motorcycles.create', $this->formOptions());
    }

    public function store(StoreMotorcycleRequest $request): RedirectResponse
    {
        $motorcycle = Motorcycle::create($this->payload($request) + [
            'seller_id' => auth()->id(),
            'status' => auth()->user()->isAdmin() && $request->filled('status')
                ? $request->input('status')
                : 'pending',
        ]);

        $this->handleUploads($request, $motorcycle);

        return redirect()
            ->route('seller.motorcycles.index')
            ->with('success', "Motorcycle \"{$motorcycle->title}\" has been listed and is pending approval.");
    }

    public function edit(Motorcycle $motorcycle): View
    {
        $this->authorizeOwnership($motorcycle);
        $motorcycle->load('images');

        return view('seller.motorcycles.edit', $this->formOptions() + compact('motorcycle'));
    }

    public function update(UpdateMotorcycleRequest $request, Motorcycle $motorcycle): RedirectResponse
    {
        $this->authorizeOwnership($motorcycle);

        $motorcycle->update($this->payload($request));

        $this->handleUploads($request, $motorcycle);

        return redirect()
            ->route('seller.motorcycles.index')
            ->with('success', "Motorcycle \"{$motorcycle->title}\" has been updated.");
    }

    public function destroy(Motorcycle $motorcycle): RedirectResponse
    {
        $this->authorizeOwnership($motorcycle);

        $this->deleteImages($motorcycle);
        $motorcycle->delete();

        return redirect()
            ->route('seller.motorcycles.index')
            ->with('success', 'Motorcycle listing deleted.');
    }

    protected function authorizeOwnership(Motorcycle $motorcycle): void
    {
        abort_unless(
            auth()->user()->isAdmin() || $motorcycle->seller_id === auth()->id(),
            403,
            'You can only manage your own motorcycle listings.'
        );
    }

    protected function formOptions(): array
    {
        return [
            'brands' => Brand::active()->orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ];
    }

    protected function payload(StoreMotorcycleRequest|UpdateMotorcycleRequest $request): array
    {
        $data = $request->safe()->except(['main_image', 'gallery_images', 'features']);

        $data['features'] = collect(preg_split('/\r\n|\r|\n/', (string) $request->input('features', '')))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();

        return $data;
    }

    protected function handleUploads(StoreMotorcycleRequest|UpdateMotorcycleRequest $request, Motorcycle $motorcycle): void
    {
        if ($request->hasFile('main_image')) {
            if ($motorcycle->main_image) {
                Storage::disk(StorageHelper::disk())->delete($motorcycle->main_image);
            }
            $motorcycle->update([
                'main_image' => $request->file('main_image')->store('motorcycles', StorageHelper::disk()),
            ]);
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $motorcycle->images()->create([
                    'image' => $image->store('motorcycles', StorageHelper::disk()),
                ]);
            }
        }
    }

    protected function deleteImages(Motorcycle $motorcycle): void
    {
        if ($motorcycle->main_image) {
            Storage::disk(StorageHelper::disk())->delete($motorcycle->main_image);
        }

        foreach ($motorcycle->images as $image) {
            Storage::disk(StorageHelper::disk())->delete($image->image);
        }
    }
}
