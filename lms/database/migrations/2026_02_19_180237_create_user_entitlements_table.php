<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_entitlements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unique('user_id'); // 1 entitlement row per user

            // Lifetime access flag
            $table->boolean('is_paid')->default(false);
            $table->timestamp('paid_at')->nullable();

            // Stripe (lifetime mode=payment)
            $table->string('stripe_customer_id')->nullable()->index();
            $table->string('stripe_checkout_session_id')->nullable()->index();
            $table->string('stripe_payment_intent_id')->nullable()->index();

            // Optional but useful (what they bought)
            $table->string('stripe_price_id')->nullable();
            $table->string('stripe_product_id')->nullable();

            // For webhook dedupe / safety
            $table->string('last_stripe_event_id')->nullable()->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_entitlements');
    }
};
