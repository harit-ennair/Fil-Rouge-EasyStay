<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Http\Requests\StorereservationRequest;
use App\Http\Requests\UpdatereservationRequest;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
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
        $reservation = new Reservation();
        $reservation->user_id = auth()->user()->id;
        $reservation->appartement_id = $request->appartement_id;
        $reservation->start_date = $request->start_date;
        $reservation->end_date = $request->end_date;
        $reservation->total_price = $request->total_price;
        $reservation->status = 'pending'; // default status
        $reservation->save();

        return redirect()->route('appartements_index')->with('success', 'Reservation created successfully.');
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
        
        // Check if the authenticated user owns the apartment
        $appartement = $reservation->appartement;
        if ($appartement->user_id != auth()->id()) {
            return redirect()->back()->with('error', 'You are not authorized to confirm this reservation.');
        }
        
        $reservation->status = 'confirmed';
        $reservation->save();
        
        return redirect()->back()->with('success', 'Reservation confirmed successfully.');
    }

    /**
     * Decline a reservation
     */
    public function decline($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        // Check if the authenticated user owns the apartment
        $appartement = $reservation->appartement;
        if ($appartement->user_id != auth()->id()) {
            return redirect()->back()->with('error', 'You are not authorized to decline this reservation.');
        }
        
        $reservation->status = 'cancelled';
        $reservation->save();
        
        return redirect()->back()->with('success', 'Reservation declined successfully.');
    }
}
