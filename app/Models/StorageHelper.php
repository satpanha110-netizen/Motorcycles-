<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    public static function exists(?string $path): bool
    {
        return $path !== null && Storage::disk('public')->exists($path);
    }

    public static function url(string $path): string
    {
        // Relative URL so links work on any host/port (dev server, production, etc.)
        return '/storage/' . ltrim($path, '/');
    }
}
