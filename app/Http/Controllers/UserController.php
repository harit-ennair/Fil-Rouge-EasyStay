<?php

namespace App\Http\Controllers;

use App\Models\appartements;
use App\Models\reservation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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


}
