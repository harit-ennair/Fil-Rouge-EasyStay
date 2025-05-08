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
    
    //  admin statistics
     
    public function dashboard()
    {
        // statistics
        $user = Auth::user();
        $Total_Users = User::count();
        $Total_Properties = appartements::count();
        $Active_Bookings = Reservation::where('status', 'confirmed')->count();
        
        // reservations and appartement 
        $reservations = reservation::with('appartement')->get();
        $totalRevenue = $reservations->sum('total_price');
        
        // owners abd properties
        $ownerRole = Role::where('name', 'owner')->first();
        $allOwners = $ownerRole ? User::where('role_id', $ownerRole->id)->get() : collect();
        $allApartments = appartements::with('user')->get();
        $apartmentsByOwner = $allApartments->groupBy('user_id');
        
        // all reservations
        $allReservations = reservation::with('appartement', 'user')->get();
        $reservationsByApartment = $allReservations->groupBy('appartement_id');
        
        // all clients
        $clientRole = Role::where('name', 'client')->first();
        $allClients = $clientRole ? User::where('role_id', $clientRole->id)->get() : collect();
        
        // top 5 clients 
        $clientsWithSpending = collect();
        foreach ($allClients as $client) {
            $clientReservations = $allReservations->where('user_id', $client->id);
            $totalSpending = $clientReservations->sum('total_price');
            
            $client->totalSpending = $totalSpending;
            $client->bookingsCount = $clientReservations->count();
            $clientsWithSpending->push($client);
        }
        $topClients = $clientsWithSpending->sortByDesc('totalSpending')->take(5);
        
        // top 5 owners 
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
        
        //  platform data
        $previousMonthOwners = User::where('role_id', 2)
            ->where('created_at', '<', \Carbon\Carbon::now()->startOfMonth())
            ->count();
        $currentOwners = $allOwners->count();
        $ownerGrowthRate = $previousMonthOwners > 0 
            ? round(($currentOwners - $previousMonthOwners) / $previousMonthOwners * 100) 
            : 0;
            

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

    
    //   Delete a user
     
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if( $user->id!=1){
            $user->delete();
            return redirect('/dashboard')->with('success', 'User deleted successfully');
        }
        return redirect('/dashboard')->with('erorre', 'admine can not de deleted');
    }
}