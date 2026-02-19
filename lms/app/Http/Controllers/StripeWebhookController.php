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

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $userId = (int) ($session->client_reference_id ?? ($session->metadata->user_id ?? 0));
            if ($userId > 0) {
                Payment::updateOrCreate(
                    ['user_id' => $userId],
                    [
                        'is_paid' => true,
                        'paid_at' => now(),
                        'status' => 'paid',
                        'type' => 'one_time',

                        'stripe_checkout_session_id' => $session->id ?? null,
                        'stripe_customer_id' => $session->customer ?? null,
                        'stripe_payment_intent_id' => $session->payment_intent ?? null,

                        'currency' => $session->currency ?? null,
                        'amount_total' => $session->amount_total ?? null,

                        'last_stripe_event_id' => $event->id ?? null,
                    ]
                );
            }
        }

        return response()->json(['received' => true]);
    }
}
