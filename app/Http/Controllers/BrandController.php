<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Motorcycle;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(): View
    {
        $brands = Brand::active()->withCounts()->orderBy('name')->paginate(12);

        return view('brands.index', compact('brands'));
    }

    public function show(Brand $brand): View
    {
        $motorcycles = Motorcycle::with(['brand', 'images'])
            ->approved()
            ->where('brand_id', $brand->id)
            ->latest()
            ->paginate(12);

        return view('brands.show', compact('brand', 'motorcycles'));
    }
}
