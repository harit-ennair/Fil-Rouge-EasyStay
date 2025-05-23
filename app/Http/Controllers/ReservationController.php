<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Http\Requests\UpdatereservationRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\appartements;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Notifications\NewReservationNotification;
use App\Notifications\ReservationConfirmedNotification;
use App\Notifications\ReservationDeclinedNotification;
use App\Services\StripeService;

class ReservationController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    
        $validator = Validator::make($request->all(), [
            'appartement_id' => 'required|exists:appartements,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get the apartment details to calculate price
        $appartement = appartements::findOrFail($request->appartement_id);
        
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $endDate->diffInDays($startDate);
        
        // Max ben 1 o days
        $days = max(1, $days);
        
        $totalPrice = $appartement->price * $days;
        
        $reservation = new Reservation();
        $reservation->user_id = auth()->user()->id;
        $reservation->appartement_id = $request->appartement_id;
        $reservation->start_date = $request->start_date;
        $reservation->end_date = $request->end_date;
        $reservation->total_price = $totalPrice;
        $reservation->status = 'pending';
        $reservation->payment_status = 'pending';
        $reservation->save();

        // Get the owner of the apartment and send notification
        $owner = User::find($reservation->appartement->user_id);
        $owner->notify(new NewReservationNotification($reservation));

        // Redirect to payment page instead of back to index
        return redirect()->route('payments.form', $reservation->id)
            ->with('success', 'Reservation created. Please complete your payment.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatereservationRequest $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }

    /**
     * Confirm a reservation
     */
    public function confirm($id)
    {
        $reservation = Reservation::findOrFail($id);
        

        $appartement = $reservation->appartement;
        if ($appartement->user_id != auth()->id()) {
            return redirect()->back()->with('error', 'You are not authorized to confirm this reservation.');
        }
        
        $reservation->status = 'confirmed';
        $reservation->save();
        

        if ($reservation->payment_status === 'authorized' && $reservation->payment_intent_id) {
            $captureResult = $this->stripeService->capturePayment($reservation);
            
            if ($captureResult) {
            
                $client = User::find($reservation->user_id);
                $client->notify(new ReservationConfirmedNotification($reservation));
                
                return redirect()->back()->with('success', 'Reservation confirmed and payment processed successfully. The client has been notified.');
            } else {
 
                $client = User::find($reservation->user_id);
                $client->notify(new ReservationConfirmedNotification($reservation));
                
                return redirect()->back()->with('warning', 'Reservation confirmed but there was an issue processing the payment. Please check the payment status.');
            }
        }
        

        $client = User::find($reservation->user_id);
        $client->notify(new ReservationConfirmedNotification($reservation));
        
        return redirect()->back()->with('success', 'Reservation confirmed successfully. The client has been notified.');
    }

    /**
     * Decline a reservation
     */
    public function decline($id)
    {
        $reservation = Reservation::findOrFail($id);
        

        $appartement = $reservation->appartement;
        if ($appartement->user_id != auth()->id()) {
            return redirect()->back()->with('error', 'You are not authorized to decline this reservation.');
        }
        
        $reservation->status = 'cancelled';
        $reservation->save();

        // Cancel paymen
        if ($reservation->payment_status === 'paid' || $reservation->payment_intent_id) {
            $this->stripeService->cancelPayment($reservation);
        }
        
        // Notify
        $client = User::find($reservation->user_id);
        $client->notify(new ReservationDeclinedNotification($reservation));
        
        return redirect()->back()->with('success', 'Reservation declined successfully. The client has been notified.');
    }
}
