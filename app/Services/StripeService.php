<?php

namespace App\Services;

use App\Models\Reservation;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a payment intent for a reservation
     * This will authorize but not capture the payment
     *
     * @param Reservation $reservation
     * @param array $paymentMethodData
     * @return array
     * @throws ApiErrorException
     */
    public function createPaymentIntent(Reservation $reservation, array $paymentMethodData)
    {
        // Create or retrieve Stripe customer
        $user = $reservation->user;
        $stripeCustomerId = $user->stripe_customer_id ?? null;
        
        if (!$stripeCustomerId) {
            $customer = Customer::create([
                'email' => $user->email,
                'name' => $user->name,
                'metadata' => [
                    'user_id' => $user->id
                ]
            ]);
            $stripeCustomerId = $customer->id;
            
            // Save stripe customer ID to user for future payments
            $user->stripe_customer_id = $stripeCustomerId;
            $user->save();
        }
        
        // Convert to cents for Stripe
        $amountInCents = (int)($reservation->total_price * 100);
        
        // Create payment intent with capture_method=manual to only authorize the payment
        $paymentIntent = PaymentIntent::create([
            'amount' => $amountInCents,
            'currency' => 'eur', // Change according to your currency
            'customer' => $stripeCustomerId,
            'payment_method_types' => ['card'],
            'capture_method' => 'manual', // <-- This makes it auth only, not capture yet
            'description' => 'Reservation for ' . $reservation->appartement->title . ' from ' . $reservation->start_date . ' to ' . $reservation->end_date,
            'metadata' => [
                'reservation_id' => $reservation->id,
                'user_id' => $user->id,
                'appartement_id' => $reservation->appartement_id
            ]
        ]);
        
        // Update reservation with payment info
        $reservation->update([
            'payment_intent_id' => $paymentIntent->id,
            'stripe_customer_id' => $stripeCustomerId,
            'payment_status' => 'authorized' // Changed from 'awaiting_payment' to 'authorized'
        ]);
        
        return [
            'clientSecret' => $paymentIntent->client_secret,
            'paymentIntentId' => $paymentIntent->id
        ];
    }
    
    /**
     * Confirm a payment for a reservation
     *
     * @param Reservation $reservation
     * @return bool
     * @throws ApiErrorException
     */
    public function confirmPayment(Reservation $reservation)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($reservation->payment_intent_id);
            
            if ($paymentIntent->status === 'requires_capture') {
                $reservation->update([
                    'payment_status' => 'authorized',
                ]);
                return true;
            } else if ($paymentIntent->status === 'succeeded') {
                $reservation->update([
                    'payment_status' => 'paid',
                    'paid_at' => now()
                ]);
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            \Log::error('Payment confirmation error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Capture a payment for a reservation after owner confirms
     *
     * @param Reservation $reservation
     * @return bool
     */
    public function capturePayment(Reservation $reservation)
    {
        try {
            if ($reservation->payment_status === 'authorized') {
                $paymentIntent = PaymentIntent::retrieve($reservation->payment_intent_id);
                
                // Only proceed if the payment intent is in a state that allows capture
                if ($paymentIntent->status === 'requires_capture') {
                    $paymentIntent->capture();
                    
                    $reservation->update([
                        'payment_status' => 'paid',
                        'paid_at' => now()
                    ]);
                    
                    return true;
                }
            }
            
            return false;
        } catch (\Exception $e) {
            \Log::error('Payment capture error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cancel and refund a payment for a declined reservation
     *
     * @param Reservation $reservation
     * @return bool
     */
    public function cancelPayment(Reservation $reservation)
    {
        try {
            if ($reservation->payment_status === 'paid') {
                $paymentIntent = PaymentIntent::retrieve($reservation->payment_intent_id);
                $refund = \Stripe\Refund::create([
                    'payment_intent' => $paymentIntent->id,
                ]);
                
                $reservation->update([
                    'payment_status' => 'refunded'
                ]);
            } else if ($reservation->payment_intent_id) {
                $paymentIntent = PaymentIntent::retrieve($reservation->payment_intent_id);
                $paymentIntent->cancel();
                
                $reservation->update([
                    'payment_status' => 'cancelled'
                ]);
            }
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Payment cancellation error: ' . $e->getMessage());
            return false;
        }
    }
}