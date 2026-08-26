<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class MotoImageGenerator
{
    /** Gradient color pairs for generated placeholder images. */
    public static array $palettes = [
        ['#0f172a', '#ea580c'],
        ['#1e293b', '#f59e0b'],
        ['#312e81', '#6366f1'],
        ['#134e4a', '#10b981'],
        ['#7f1d1d', '#ef4444'],
        ['#1e1b4b', '#a855f7'],
        ['#0c4a6e', '#0ea5e9'],
        ['#3f3f46', '#e4e4e7'],
    ];

    /**
     * Generate a stylized motorcycle placeholder image (SVG) in storage
     * and return its relative path.
     */
    public static function make(string $filename, string $label, int $paletteIndex = 0): string
    {
        [$from, $to] = self::$palettes[abs($paletteIndex) % count(self::$palettes)];
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
        Storage::disk('public')->put($path, $svg);

        return $path;
    }
}
