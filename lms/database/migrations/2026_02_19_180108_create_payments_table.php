<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Your access flag (simple gating)
            $table->boolean('is_paid')->default(false);

            // Stripe identifiers
            $table->string('stripe_customer_id')->nullable()->index();
            $table->string('stripe_checkout_session_id')->nullable()->index();
            $table->string('stripe_payment_intent_id')->nullable()->index();
            $table->string('stripe_subscription_id')->nullable()->index();
            $table->string('stripe_price_id')->nullable();
            $table->string('stripe_product_id')->nullable();

            // Payment metadata
            $table->string('type')->default('one_time'); // one_time | subscription
            $table->string('status')->nullable();         // e.g. paid, open, incomplete, active, canceled...
            $table->unsignedInteger('amount_total')->nullable(); // store in cents
            $table->string('currency', 10)->nullable();

            // Timestamps for access rules
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('access_expires_at')->nullable();

            // Useful for webhook dedupe / auditing
            $table->string('last_stripe_event_id')->nullable()->index();

            $table->timestamps();

            // If you want 1 payment/access row per user, uncomment this:
            // $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
