<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Motorcycle;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featured = Motorcycle::with(['brand', 'images'])
            ->approved()
            ->featured()
            ->latest()
            ->take(8)
            ->get();

        $latest = Motorcycle::with(['brand', 'images'])
            ->approved()
            ->latest()
            ->take(8)
            ->get();

        $brands = Brand::active()->withCounts()->orderBy('name')->take(8)->get();

        $stats = [
            'motorcycles' => Motorcycle::approved()->count(),
            'brands' => Brand::count(),
            'sellers' => \App\Models\User::where('role', 'seller')->count(),
        ];

        return view('home', compact('featured', 'latest', 'brands', 'stats'));
    }

    public function about(): View
    {
        return view('pages.about');
    }
}
