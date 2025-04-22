<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('payment_status')->default('pending')->after('status');
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->string('payment_intent_id')->nullable()->after('payment_method');
            $table->string('stripe_customer_id')->nullable()->after('payment_intent_id');
            $table->timestamp('paid_at')->nullable()->after('stripe_customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'payment_method',
                'payment_intent_id',
                'stripe_customer_id',
                'paid_at'
            ]);
        });
    }
};
