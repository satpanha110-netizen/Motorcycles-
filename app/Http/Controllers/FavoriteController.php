<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Motorcycle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(Request $request): View
    {
        $favorites = $request->user()
            ->favorites()
            ->with(['brand', 'images'])
            ->latest('favorites.created_at')
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }

    public function toggle(Request $request, Motorcycle $motorcycle): JsonResponse|RedirectResponse
    {
        $exists = $request->user()
            ->favorites()
            ->whereKey($motorcycle->id)
            ->exists();

        if ($exists) {
            $request->user()->favorites()->detach($motorcycle->id);
            $message = 'Removed from favorites.';
            $favorited = false;
        } else {
            // Prevent duplicates at the DB level as well.
            Favorite::firstOrCreate([
                'user_id' => $request->user()->id,
                'motorcycle_id' => $motorcycle->id,
            ]);
            $message = 'Added to favorites.';
            $favorited = true;
        }

        if ($request->wantsJson()) {
            return response()->json([
                'favorited' => $favorited,
                'message' => $message,
                'count' => $request->user()->favorites()->count(),
            ]);
        }

        return back()->with('success', $message);
    }
}
