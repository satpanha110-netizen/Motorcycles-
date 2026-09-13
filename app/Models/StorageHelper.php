<?php

namespace App\Models;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    /**
     * The disk configured for image storage (see config/filesystems.php).
     */
    public static function disk(): string
    {
        return (string) config('filesystems.image_disk', 'public');
    }

    public static function exists(?string $path): bool
    {
        if ($path === null || trim($path) === '') {
            return false;
        }

        $disk = self::disk();

        // Remote disks (S3, Supabase Storage, ...) resolve paths against the
        // database, so a non-empty path is treated as existing. Checking the
        // actual object during every render would add one network request per
        // image.
        if (Config::get("filesystems.disks.{$disk}.driver") !== 'local') {
            return true;
        }

        return Storage::disk($disk)->exists($path);
    }

    public static function url(string $path): string
    {
        $disk = self::disk();

        if (Config::get("filesystems.disks.{$disk}.driver") !== 'local') {
            return Storage::disk($disk)->url($path);
        }

        // Relative URL so links work on any host/port (dev server, production, etc.)
        return '/storage/'.ltrim($path, '/');
    }
}
