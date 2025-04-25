<?php

namespace App\Http\Controllers;

use App\Models\appartements;
use App\Models\reservation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard with statistics
     */
    public function dashboard()
    {
        // Get main statistics
        $user = Auth::user();
        $Total_Users = User::count();
        $Total_Properties = appartements::count();
        $Active_Bookings = Reservation::where('status', 'confirmed')->count();
        
        // Get all reservations with appartement relation for revenue calculation
        $reservations = reservation::with('appartement')->get();
        $totalRevenue = $reservations->sum('total_price');
        
        // Get all owners with their properties
        $ownerRole = Role::where('name', 'owner')->first();
        $allOwners = $ownerRole ? User::where('role_id', $ownerRole->id)->get() : collect();
        
        // Get all apartments with their owners
        $allApartments = appartements::with('user')->get();
        
        // Group apartments by owner for easy access
        $apartmentsByOwner = $allApartments->groupBy('user_id');
        
        // Get all reservations for revenue calculations
        $allReservations = reservation::with('appartement', 'user')->get();
        
        // Group reservations by apartment for easy access
        $reservationsByApartment = $allReservations->groupBy('appartement_id');
        
        // Get client role and all clients
        $clientRole = Role::where('name', 'client')->first();
        $allClients = $clientRole ? User::where('role_id', $clientRole->id)->get() : collect();
        
        // Calculate top 5 clients by spending
        $clientsWithSpending = collect();
        foreach ($allClients as $client) {
            $clientReservations = $allReservations->where('user_id', $client->id);
            $totalSpending = $clientReservations->sum('total_price');
            
            $client->totalSpending = $totalSpending;
            $client->bookingsCount = $clientReservations->count();
            $clientsWithSpending->push($client);
        }
        $topClients = $clientsWithSpending->sortByDesc('totalSpending')->take(5);
        
        // Calculate top 5 owners by revenue
        $ownersWithRevenue = collect();
        foreach ($allOwners as $owner) {
            $ownerApartmentsIds = $apartmentsByOwner->get($owner->id, collect())->pluck('id');
            $ownerReservations = $allReservations->whereIn('appartement_id', $ownerApartmentsIds);
            $totalRevenue = $ownerReservations->sum('total_price');
            
            $owner->totalRevenue = $totalRevenue;
            $owner->propertiesCount = $ownerApartmentsIds->count();
            $ownersWithRevenue->push($owner);
        }
        $topOwners = $ownersWithRevenue->sortByDesc('totalRevenue')->take(5);
        
        // Calculate platform summary data
        $previousMonthOwners = User::where('role_id', 2)
            ->where('created_at', '<', \Carbon\Carbon::now()->startOfMonth())
            ->count();
        $currentOwners = $allOwners->count();
        $ownerGrowthRate = $previousMonthOwners > 0 
            ? round(($currentOwners - $previousMonthOwners) / $previousMonthOwners * 100) 
            : 0;
            
        // System status data - in a real application, you might get this from a monitoring service
        $systemStatus = [
            'status' => 'operational',
            'uptime' => '99.9%',
            'services' => [
                'website' => 'operational',
                'booking' => 'operational',
                'payment' => 'operational',
            ]
        ];
        
        return view('dashboard', compact(
            'user', 
            'Total_Users', 
            'Total_Properties', 
            'reservations', 
            'Active_Bookings', 
            'allOwners', 
            'totalRevenue',
            'topClients',
            'topOwners',
            'apartmentsByOwner',
            'reservationsByApartment',
            'allApartments',
            'allReservations',
            'ownerGrowthRate',
            'systemStatus'
        ));
    }

    /**
     * Delete a user
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect('/dashboard')->with('success', 'User deleted successfully');
    }
}