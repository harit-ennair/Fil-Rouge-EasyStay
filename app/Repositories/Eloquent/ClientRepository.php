<?php

namespace App\Repositories\Eloquent;

use App\Models\Role;
use App\Models\User;
use App\Models\reservation;
use App\Repositories\Interfaces\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    /**
     * Get all clients
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllClients()
    {
        return User::where('role_id', 3)->get();
    }

    /**
     * Get client by ID with statistics
     * 
     * @param int $id
     * @return array
     */
    public function getClientWithStats($id)
    {
        // Find the user
        $client = User::findOrFail($id);
        
        // Verify this is a client
        $clientRole = Role::where('name', 'client')->first();
        if ($client->role_id !== $clientRole->id) {
            return ['error' => 'User is not a client'];
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
        
        return [
            'client' => $client,
            'reservations' => $reservations,
            'totalSpent' => $totalSpent,
            'totalBookings' => $totalBookings,
            'confirmedBookings' => $confirmedBookings,
            'pendingBookings' => $pendingBookings,
            'cancelledBookings' => $cancelledBookings,
            'monthlySpendingJson' => $monthlySpendingJson,
            'visitedLocations' => $visitedLocations,
            'favoriteLocations' => $favoriteLocations
        ];
    }
}