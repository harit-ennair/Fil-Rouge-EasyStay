<?php

namespace App\Http\Controllers;

use App\Models\appartements;
use App\Models\reservation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerController extends Controller
{
    /**
     * Display the owner dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // owner properties
        $ownerApartments = appartements::where('user_id', $user->id)->get();
        $totalProperties = $ownerApartments->count();
        
        // bookings
        $ownerReservations = reservation::whereIn('appartement_id', $ownerApartments->pluck('id'))->get();
        $activeBookings = $ownerReservations->where('status', 'confirmed')->count();
        $pendingBookings = $ownerReservations->where('status', 'pending')->count();
        $totalRevenue = $ownerReservations->where('status', 'confirmed')->sum('total_price');
        $monthlyRevenue = $ownerReservations->where('status', 'confirmed')
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('total_price');
        
        // Calculate 
        $occupancyRate = $totalProperties > 0 ? 
            round(($activeBookings / $totalProperties) * 100) : 0;
        
        // most bookings
        $recentBookings = $ownerReservations->sortByDesc('status')->take(5);
        
        return view('ownerdashboard', compact(
            'user', 
            'ownerApartments', 
            'totalProperties', 
            'activeBookings',
            'pendingBookings',
            'totalRevenue',
            'monthlyRevenue',
            'occupancyRate',
            'recentBookings'
        ));
    }

    /**
     * all owners
     */
    public function index()
    {
        $owners = User::where('role_id', 2)->get();
        return view('all-owners', compact('owners'));
    }

    /**
     * owner statistics
     */
    public function show($id)
    {

        $owner = User::findOrFail($id);
        

        $ownerRole = Role::where('name', 'owner')->first();
        

        $currentUser = Auth::user();
        $adminRole = Role::where('name', 'admin')->first();
        
    
        if ($owner->role_id !== $ownerRole->id && (!$currentUser || $currentUser->role_id !== $adminRole->id)) {
            return redirect()->back()->with('error', 'User is not a property owner');
        }
        
        // owner properties
        $properties = appartements::where('user_id', $owner->id)->get();
        
        // reservations for owner 
        $reservations = reservation::whereIn('appartement_id', $properties->pluck('id'))
            ->with(['appartement', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // statistics
        $totalRevenue = $reservations->sum('total_price');
        $activeBookings = $reservations->where('status', 'confirmed')->count();
        $totalBookings = $reservations->count();
        $completedBookings = $reservations->where('status', 'completed')->count();
        $pendingBookings = $reservations->where('status', 'pending')->count();
        
        // last 6 months
        $monthlyRevenue = [];
        for ($i = 0; $i < 6; $i++) {
            $month = now()->subMonths($i);
            $revenue = $reservations
                ->where('created_at', '>=', $month->startOfMonth())
                ->where('created_at', '<=', $month->endOfMonth())
                ->sum('total_price');
                
            $monthlyRevenue[$month->format('M Y')] = $revenue;
        }
        
        // JSON for chart
        $monthlyRevenueJson = json_encode(array_reverse($monthlyRevenue));
        
        return view('owner-profile', compact(
            'owner',
            'properties',
            'reservations',
            'totalRevenue',
            'activeBookings',
            'totalBookings',
            'completedBookings',
            'pendingBookings',
            'monthlyRevenueJson'
        ));
    }
}