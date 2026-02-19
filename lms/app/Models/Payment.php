<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'is_paid',
        'paid_at',
        'type',
        'status',
        'amount_total',
        'currency',
        'stripe_customer_id',
        'stripe_checkout_session_id',
        'stripe_payment_intent_id',
        'stripe_subscription_id',
        'stripe_price_id',
        'stripe_product_id',
        'last_stripe_event_id',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'paid_at' => 'datetime',
    ];
}
