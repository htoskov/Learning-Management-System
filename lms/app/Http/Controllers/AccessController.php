<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccessController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

            if ($user && ($user->is_admin || $user->payments()->where('is_paid', true)->exists())) {
                return redirect()->route('dashboard');
            }
            
        return view('access.get-access');
    }

}
