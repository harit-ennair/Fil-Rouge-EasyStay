<?php

namespace App\Http\Controllers;

use App\Models\reservation;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
        $this->middleware('auth');
    }

    /**
     * Show payment form
     */
    public function showPaymentForm($reservationId)
    {
        $reservation = reservation::findOrFail($reservationId);
        
        if ($reservation->user_id !== Auth::id()) {
            return redirect()->route('reservations.index')
                ->with('error', 'Not authorized to pay for this reservation.');
        }
        
        if ($reservation->status !== 'pending') {
            return redirect()->route('reservations.index')
                ->with('error', 'This reservation cannot be paid now.');
        }
        
        return view('payments.form', [
            'reservation' => $reservation,
            'stripeKey' => config('services.stripe.key')
        ]);
    }

    /**
     * Process payment
     */
    public function processPayment(Request $request, $reservationId)
    {
        $reservation = reservation::findOrFail($reservationId);
        
        if ($reservation->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'payment_method' => 'required|string',
        ]);
        
        try {
            $paymentData = $this->stripeService->createPaymentIntent(
                $reservation, 
                ['payment_method' => $request->payment_method]
            );
            
            return response()->json($paymentData);
        } catch (\Exception $e) {
            Log::error('Payment error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment failed'], 500);
        }
    }

    /**
     * Confirm payment
     */
    public function confirmPayment(Request $request, $reservationId)
    {
        $reservation = reservation::findOrFail($reservationId);
        
        if ($reservation->user_id !== Auth::id()) {
            return redirect()->route('reservations.index')
                ->with('error', 'Not authorized for this payment.');
        }
        
        try {
            $paymentIntentId = $request->query('payment_intent');
            
            if ($paymentIntentId && !$reservation->payment_intent_id) {
                $reservation->update(['payment_intent_id' => $paymentIntentId]);
            }
            
            if ($paymentIntentId && $this->stripeService->confirmPayment($reservation)) {
                return view('payments.confirmation', [
                    'reservation' => $reservation->fresh(),
                    'success' => true
                ])->with('success', 'Payment authorized successfully!');
            }
            
            return view('payments.confirmation', [
                'reservation' => $reservation,
                'success' => false
            ])->with('error', 'Payment not successful. Please contact support.');
            
        } catch (\Exception $e) {
            Log::error('Payment error: ' . $e->getMessage());
            return view('payments.confirmation', [
                'reservation' => $reservation,
                'success' => false
            ])->with('error', 'Payment error. Please contact support.');
        }
    }

    /**
     * Capture payment after approval
     */
    public function capturePayment($reservationId)
    {
        $reservation = reservation::findOrFail($reservationId);
        
        try {
            if ($this->stripeService->capturePayment($reservation)) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Payment captured successfully'
                ]);
            }
            
            return response()->json([
                'success' => false, 
                'message' => 'Payment capture failed'
            ], 400);
        } catch (\Exception $e) {
            Log::error('Payment error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment failed'], 500);
        }
    }

    /**
     * Cancel payment
     */
    public function cancelPayment($reservationId)
    {
        $reservation = reservation::findOrFail($reservationId);
        
        try {
            if ($this->stripeService->cancelPayment($reservation)) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Payment cancelled'
                ]);
            }
            
            return response()->json([
                'success' => false, 
                'message' => 'Payment cancellation failed'
            ], 400);
        } catch (\Exception $e) {
            Log::error('Payment error: ' . $e->getMessage());
            return response()->json(['error' => 'Cancellation failed'], 500);
        }
    }
}
