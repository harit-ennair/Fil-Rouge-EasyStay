<?php

namespace App\Http\Controllers;

use App\Models\appartements;
use App\Models\reservation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    
    public function login()
    {

        return view('login');

    }

    public function AuthLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended('/')->with('success', 'Login successful');
        }

        return redirect('/login')->with('error', 'Invalid credentials');
    }



    public function register()
    {

        $roles = role::all()->where('name', '!=', 'admin');

        return view('register', ['roles' => $roles]);

    }


    public function store(Request $request)
    {


        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4',
            'role_id' => 'required'
        ]);

        $user=User::create([

            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role_id' => $request->role_id

        ]);
        auth()->login($user);

        return redirect('/login');
        
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Successfully logged out');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect('/dashboard')->with('success', 'User deleted successfully');
    }


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
            'reservationsByApartment'
        ));
    }

    public function ownerDashboard()
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
        $recentBookings = $ownerReservations->sortByDesc('created_at')->take(5);
        
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

    public function showProfile($id)
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

    public function clientProfile($id)
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
