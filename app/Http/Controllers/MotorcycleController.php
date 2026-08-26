<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Motorcycle;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MotorcycleController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only([
            'q', 'brand', 'category', 'model', 'min_price', 'max_price',
            'year', 'min_cc', 'max_cc', 'condition', 'transmission',
            'fuel_type', 'location', 'sort',
        ]);

        $motorcycles = Motorcycle::with(['brand', 'images'])
            ->approved()
            ->filter($filters)
            ->sort($filters['sort'] ?? null)
            ->paginate(12)
            ->withQueryString();

        $brands = Brand::active()->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $locations = Motorcycle::approved()
            ->select('location')
            ->distinct()
            ->orderBy('location')
            ->pluck('location');
        $years = Motorcycle::approved()->distinct()->orderByDesc('year')->pluck('year');

        return view('motorcycles.index', compact(
            'motorcycles', 'brands', 'categories', 'locations', 'years', 'filters'
        ));
    }

    public function show(Motorcycle $motorcycle): View
    {
        abort_unless($motorcycle->status === 'approved' || $this->canPreview($motorcycle), 404);

        $motorcycle->load(['brand', 'category', 'seller.motorcycles', 'images']);

        $related = Motorcycle::with(['brand', 'images'])
            ->approved()
            ->where('id', '!=', $motorcycle->id)
            ->where(function ($q) use ($motorcycle) {
                $q->where('brand_id', $motorcycle->brand_id)
                    ->orWhere('category_id', $motorcycle->category_id);
            })
            ->take(4)
            ->get();

        $isFavorited = auth()->check()
            && auth()->user()->favorites()->whereKey($motorcycle->id)->exists();

        return view('motorcycles.show', compact('motorcycle', 'related', 'isFavorited'));
    }

    protected function canPreview(Motorcycle $motorcycle): bool
    {
        return auth()->check()
            && (auth()->user()->isAdmin() || auth()->id() === $motorcycle->seller_id);
    }
}
