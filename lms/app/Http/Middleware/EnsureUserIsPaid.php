<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsPaid
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // not logged in -> go login
        if (!$user) {
            return redirect()->route('login');
        }

        // Admins optionally bypass paywall
        if (($user->is_admin ?? false) === true) {
            return $next($request);
        }

        $hasAccess = $user->payments()
            ->where('is_paid', true)
            ->exists();

        if (!$hasAccess) {
            return redirect()->route('access.get');
        }

        return $next($request);
    }
}
