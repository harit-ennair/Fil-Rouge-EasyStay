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
     * Show payment form for a reservation
     *
     * @param int $reservationId
     * @return \Illuminate\View\View
     */
    public function showPaymentForm($reservationId)
    {
        $reservation = reservation::findOrFail($reservationId);
        
        // Check if the reservation belongs to the authenticated user
        if ($reservation->user_id !== Auth::id()) {
            return redirect()->route('reservations.index')
                ->with('error', 'You are not authorized to pay for this reservation.');
        }
        
        // If the reservation is not pending, redirect with message
        if ($reservation->status !== 'pending') {
            return redirect()->route('reservations.index')
                ->with('error', 'This reservation cannot be paid for at this time.');
        }
        
        return view('payments.form', [
            'reservation' => $reservation,
            'stripeKey' => config('services.stripe.key')
        ]);
    }

    /**
     * Process payment for a reservation
     *
     * @param Request $request
     * @param int $reservationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment(Request $request, $reservationId)
    {
        $reservation = reservation::findOrFail($reservationId);
        
        // Check if the reservation belongs to the authenticated user
        if ($reservation->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Validate payment information
        $request->validate([
            'payment_method' => 'required|string',
        ]);
        
        try {
            // Create payment intent
            $paymentData = $this->stripeService->createPaymentIntent(
                $reservation, 
                ['payment_method' => $request->payment_method]
            );
            
            return response()->json($paymentData);
        } catch (\Exception $e) {
            Log::error('Payment processing error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment processing failed'], 500);
        }
    }

    /**
     * Confirm payment completion
     *
     * @param Request $request
     * @param int $reservationId
     * @return \Illuminate\View\View
     */
    public function confirmPayment(Request $request, $reservationId)
    {
        $reservation = reservation::findOrFail($reservationId);
        
        // Check if the reservation belongs to the authenticated user
        if ($reservation->user_id !== Auth::id()) {
            return redirect()->route('reservations.index')
                ->with('error', 'You are not authorized to view this payment.');
        }
        
        try {
            // Get payment intent from query string
            $paymentIntentId = $request->query('payment_intent');
            
            if ($paymentIntentId) {
                // Update payment intent ID if not already set
                if (!$reservation->payment_intent_id) {
                    $reservation->update(['payment_intent_id' => $paymentIntentId]);
                }
                
                $success = $this->stripeService->confirmPayment($reservation);
                
                if ($success) {
                    return view('payments.confirmation', [
                        'reservation' => $reservation->fresh(),
                        'success' => true
                    ])->with('success', 'Payment authorized successfully! Your card will only be charged after the owner confirms your reservation.');
                }
            }
            
            // If we reach here, payment was not successful or still processing
            return view('payments.confirmation', [
                'reservation' => $reservation,
                'success' => false
            ])->with('error', 'Payment authorization is still processing or was not successful. Please contact support if this persists.');
            
        } catch (\Exception $e) {
            Log::error('Payment confirmation error: ' . $e->getMessage());
            return view('payments.confirmation', [
                'reservation' => $reservation,
                'success' => false
            ])->with('error', 'There was an error confirming your payment authorization. Please contact support.');
        }
    }

    /**
     * Handle payment after owner confirmation
     *
     * @param int $reservationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function capturePayment($reservationId)
    {
        // This should be called by the owner through a webhook or after accepting the reservation
        $reservation = reservation::findOrFail($reservationId);
        
        try {
            $success = $this->stripeService->capturePayment($reservation);
            
            if ($success) {
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
            Log::error('Payment capture error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment capture failed'], 500);
        }
    }

    /**
     * Cancel payment for a declined reservation
     *
     * @param int $reservationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelPayment($reservationId)
    {
        // This should be called when a reservation is declined
        $reservation = reservation::findOrFail($reservationId);
        
        try {
            $success = $this->stripeService->cancelPayment($reservation);
            
            if ($success) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Payment cancelled successfully'
                ]);
            }
            
            return response()->json([
                'success' => false, 
                'message' => 'Payment cancellation failed'
            ], 400);
        } catch (\Exception $e) {
            Log::error('Payment cancellation error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment cancellation failed'], 500);
        }
    }
}
