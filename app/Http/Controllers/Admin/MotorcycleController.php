<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Motorcycle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MotorcycleController extends Controller
{
    public function index(Request $request): View
    {
        $motorcycles = Motorcycle::with(['brand', 'seller'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->q . '%';
                $query->where(fn ($q) => $q->where('title', 'like', $term)->orWhere('model', 'like', $term));
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.motorcycles.index', compact('motorcycles'));
    }

    public function edit(Motorcycle $motorcycle): View
    {
        $motorcycle->load(['brand', 'category', 'images']);

        return view('admin.motorcycles.edit', [
            'motorcycle' => $motorcycle,
            'brands' => Brand::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Motorcycle $motorcycle): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'price' => ['required', 'numeric', 'min:1'],
            'status' => ['required', 'in:pending,approved,rejected,sold'],
            'featured' => ['nullable', 'boolean'],
        ]);

        $data['featured'] = $request->boolean('featured');
        $motorcycle->update($data);

        return redirect()->route('admin.motorcycles.index')
            ->with('success', "Motorcycle \"{$motorcycle->title}\" updated.");
    }

    public function destroy(Motorcycle $motorcycle): RedirectResponse
    {
        if ($motorcycle->main_image) {
            Storage::disk('public')->delete($motorcycle->main_image);
        }
        foreach ($motorcycle->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        $motorcycle->delete();

        return back()->with('success', 'Motorcycle deleted.');
    }

    public function setStatus(Request $request, Motorcycle $motorcycle): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected,sold'],
        ]);

        $motorcycle->update($validated);

        $label = match ($validated['status']) {
            'approved' => 'approved',
            'rejected' => 'rejected',
            'sold' => 'marked as sold',
            default => 'set to pending',
        };

        return back()->with('success', "Listing \"{$motorcycle->title}\" {$label}.");
    }
}
