<?php

namespace App\Http\Controllers;

use App\Models\appartements;
use App\Models\reservation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Display list of all clients
     */
    public function index()
    {
        $clients = User::where('role_id', 3)->get();
        return view('all-clients', compact('clients'));
    }

    /**
     * Display client profile with statistics
     */
    public function show($id)
    {
        // Find the user
        $client = User::findOrFail($id);
        
        // Verify this is a client
        $clientRole = Role::where('name', 'client')->first();
        if ($client->role_id !== $clientRole->id) {
            return redirect()->back()->with('error', 'User is not a client');
        }
        
        // Get all the client's reservations with related data
        $reservations = reservation::where('user_id', $client->id)
            ->with(['appartement'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate statistics
        $totalSpent = $reservations->sum('total_price');
        $totalBookings = $reservations->count();
        $confirmedBookings = $reservations->where('status', 'confirmed')->count();
        $pendingBookings = $reservations->where('status', 'pending')->count();
        $cancelledBookings = $reservations->where('status', 'cancelled')->count();
        
        // Calculate monthly spending for the last 6 months
        $monthlySpending = [];
        for ($i = 0; $i < 6; $i++) {
            $month = now()->subMonths($i);
            $spending = $reservations
                ->where('created_at', '>=', $month->startOfMonth())
                ->where('created_at', '<=', $month->endOfMonth())
                ->sum('total_price');
                
            $monthlySpending[$month->format('M Y')] = $spending;
        }
        
        // Convert to JSON for chart
        $monthlySpendingJson = json_encode(array_reverse($monthlySpending));
        
        // Get unique destinations (locations) visited
        $visitedLocations = $reservations->map(function($reservation) {
            return $reservation->appartement->location ?? 'Unknown';
        })->unique()->values();
        
        // Get favorite destinations (most booked)
        $favoriteLocations = $reservations
            ->groupBy(function($reservation) {
                return $reservation->appartement->location ?? 'Unknown';
            })
            ->map(function($group) {
                return $group->count();
            })
            ->sortDesc()
            ->take(3);
        
        return view('client-profile', compact(
            'client',
            'reservations',
            'totalSpent',
            'totalBookings',
            'confirmedBookings',
            'pendingBookings',
            'cancelledBookings',
            'monthlySpendingJson',
            'visitedLocations',
            'favoriteLocations'
        ));
    }
}