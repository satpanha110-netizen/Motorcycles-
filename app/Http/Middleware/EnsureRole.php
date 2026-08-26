<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Usage: ->middleware('role:admin') or 'role:seller,admin'
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        // Admins can access every protected area.
        if ($user->isAdmin() || in_array($user->role, $roles, true)) {
            return $next($request);
        }

        abort(403, 'You do not have permission to access this area.');
    }
}
