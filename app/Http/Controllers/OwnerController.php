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
        
        // Get the owner's properties
        $ownerApartments = appartements::where('user_id', $user->id)->get();
        $totalProperties = $ownerApartments->count();
        
        // Calculate revenue and bookings
        $ownerReservations = reservation::whereIn('appartement_id', $ownerApartments->pluck('id'))->get();
        $activeBookings = $ownerReservations->where('status', 'confirmed')->count();
        $pendingBookings = $ownerReservations->where('status', 'pending')->count();
        $totalRevenue = $ownerReservations->where('status', 'confirmed')->sum('total_price');
        $monthlyRevenue = $ownerReservations->where('status', 'confirmed')
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('total_price');
        
        // Calculate occupancy rate - simplified calculation
        $occupancyRate = $totalProperties > 0 ? 
            round(($activeBookings / $totalProperties) * 100) : 0;
        
        // Get most recent bookings
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
     * Display list of all owners
     */
    public function index()
    {
        $owners = User::where('role_id', 2)->get();
        return view('all-owners', compact('owners'));
    }

    /**
     * Display owner profile with statistics
     */
    public function show($id)
    {
        // Find the user
        $owner = User::findOrFail($id);
        
        // Get the owner role
        $ownerRole = Role::where('name', 'owner')->first();
        
        // Check if current user is admin
        $currentUser = Auth::user();
        $adminRole = Role::where('name', 'admin')->first();
        
        // Only allow if the user being viewed is an owner OR the current user is an admin
        if ($owner->role_id !== $ownerRole->id && (!$currentUser || $currentUser->role_id !== $adminRole->id)) {
            return redirect()->back()->with('error', 'User is not a property owner');
        }
        
        // Get the owner's properties
        $properties = appartements::where('user_id', $owner->id)->get();
        
        // Get all reservations for the owner's properties
        $reservations = reservation::whereIn('appartement_id', $properties->pluck('id'))
            ->with(['appartement', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate statistics
        $totalRevenue = $reservations->sum('total_price');
        $activeBookings = $reservations->where('status', 'confirmed')->count();
        $totalBookings = $reservations->count();
        $completedBookings = $reservations->where('status', 'completed')->count();
        $pendingBookings = $reservations->where('status', 'pending')->count();
        
        // Calculate monthly revenue for the last 6 months
        $monthlyRevenue = [];
        for ($i = 0; $i < 6; $i++) {
            $month = now()->subMonths($i);
            $revenue = $reservations
                ->where('created_at', '>=', $month->startOfMonth())
                ->where('created_at', '<=', $month->endOfMonth())
                ->sum('total_price');
                
            $monthlyRevenue[$month->format('M Y')] = $revenue;
        }
        
        // Convert to JSON for chart
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