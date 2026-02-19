<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sig = $request->header('Stripe-Signature');

        $event = Webhook::constructEvent(
            $payload,
            $sig,
            config('services.stripe.webhook_secret')
        );

        if (!empty($event->id) && Payment::where('last_stripe_event_id', $event->id)->exists()) {
            return response()->json(['received' => true, 'duplicate' => true]);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $userId = (int) ($session->client_reference_id ?? ($session->metadata->user_id ?? 0));

            if ($userId > 0) {
                Payment::updateOrCreate(
                    ['user_id' => $userId], // lifetime: 1 row per user (unique)
                    [
                        'is_paid' => true,
                        'paid_at' => now(),
                        'status' => 'paid',
                        'type' => 'one_time',

                        'stripe_checkout_session_id' => $session->id ?? null,
                        'stripe_customer_id' => $session->customer ?? null,
                        'stripe_payment_intent_id' => $session->payment_intent ?? null,

                        'stripe_price_id' => $session->metadata->price_id ?? null,
                        'stripe_product_id' => $session->metadata->product_id ?? null,

                        'currency' => $session->currency ?? null,
                        'amount_total' => $session->amount_total ?? null,

                        // ✅ store processed event id
                        'last_stripe_event_id' => $event->id ?? null,
                    ]
                );
            }
        }

        return response()->json(['received' => true]);
    }
}
