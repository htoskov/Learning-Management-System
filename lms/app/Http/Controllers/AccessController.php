<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccessController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        // If logged in and already paid (or admin), go to dashboard
        if ($user && (
            ($user->is_admin ?? false) ||
            $user->payments()->where('is_paid', true)->exists()
        )) {
            return redirect()->route('dashboard');
        }

        return view('access.get-access');
    }
}
