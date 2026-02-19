<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;

class CheckoutController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();

        // If already paid, don't sell again
        if (($user->is_admin ?? false) || $user->payments()->where('is_paid', true)->exists()) {
            return redirect()->route('dashboard');
        }

        $stripe = new StripeClient(config('services.stripe.secret'));

        $session = $stripe->checkout->sessions->create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => config('services.stripe.price_id'),
                'quantity' => 1,
            ]],

            // mapping user <-> stripe
            'customer_email' => $user->email,
            'client_reference_id' => (string) $user->id,
            'metadata' => [
                'user_id' => (string) $user->id,
                'purpose' => 'lifetime_course_access',
            ],

            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('checkout.cancel'),
        ]);

        return redirect()->away($session->url);
    }

        public function status(Request $request)
    {
        $user = $request->user();

        $paid = ($user->is_admin ?? false) || $user->payments()->where('is_paid', true)->exists();

        return response()->json([
            'paid' => $paid,
        ]);
    }


    public function success()
    {
        // DO NOT unlock access here; webhook does that.
        return view('checkout.success');
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }
}
