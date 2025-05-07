<?php

namespace App\Http\Controllers;

use App\Models\appartements;
use App\Models\categories;
use App\Models\photos;
use App\Models\reservation;
use App\Services\GeocodingService;
use Illuminate\Http\Request;


class AppartementsController extends Controller
{
    protected $geocodingService;
    
    public function __construct(GeocodingService $geocodingService)
    {
        $this->geocodingService = $geocodingService;
    }

// surch
    public function index()
    {
        $appartements = appartements::paginate(9);
        $categories = categories::all();
        if (request('categories')) {
            $appartements = appartements::whereHas('categories', function ($query) {
                $query->where('category_id', request('categories'));
            })->paginate(9);
        }
        if (request('location')) {
            $appartements = appartements::where('location', 'like', '%' . request('location') . '%')->paginate(9);
        }
        if (request('price')) {
            $appartements = appartements::where('price', '<=', request('price'))->paginate(9);
        }

        return view('appartements_index', [
            'appartements' => $appartements,
            'categories' => $categories,
        ]);

    }

    public function create()
    {

        $categories = categories::all();
        return view('appartements_create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'location' => 'required',
        ]);
        
        // address Google Maps 
        $coordinates = $this->geocodingService->geocode($request->location);
        
        $app = appartements::create([
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price, 
            'location' => $request->location,
            'latitude' => $coordinates['lat'] ?? null,
            'longitude' => $coordinates['lng'] ?? null,
        ]);
        
        if ($request->categories) {
            $app->categories()->attach($request->categories);
        }
  
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $app->photos()->create([
                    'photo_path' => $image->store('photos', 'public'),
                ]);
            }
        }

        return redirect()->route('appartements_index')->with('success', 'Appartement created successfully.');
    }

    public function show($id)
    {
        $appartement = appartements::findOrFail($id);
        $photos = photos::where('appartement_id', $id)->get();
        
        // latest reservations  
        $recentReservations = reservation::where('appartement_id', $appartement->id)
            ->with('user')
            ->take(4)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $latestReservation = $appartement->reservations()->orderBy('end_date', 'desc')->first();

        // apartments with similar location or categories
        $similarAppartements = appartements::where('id', '!=', $appartement->id)
        ->where(function($query) use ($appartement) {
            $locationQuery = clone $query;
            $locationQuery->where('location', $appartement->location);
            

            if (appartements::where('id', '!=', $appartement->id)
            ->where('location', $appartement->location)->exists()) {
            $query->where('location', $appartement->location);
            } 

            elseif ($appartement->categories->isNotEmpty()) {
            $query->whereHas('categories', function($subQuery) use ($appartement) {
                $subQuery->whereIn('categories.id', $appartement->categories->pluck('id'));
            });
            }
        })
        ->take(3)
        ->get();
        
        return view('appartements_show', compact('appartement', 'photos', 'similarAppartements', 'recentReservations', 'latestReservation'));
    }


    public function edit($id)
    {
        $appartements = appartements::findOrFail($id);
        $categories = categories::all();
        return view('appartements_edit', compact('appartements', 'categories'));
    }


    public function update(Request $request, $id)
    {
        $appartements = appartements::findOrFail($id);
        
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'location' => 'required',
        ]);

 
        if ($appartements->location !== $request->location) {
            $coordinates = $this->geocodingService->geocode($request->location);
            $request->merge([
                'latitude' => $coordinates['lat'] ?? null,
                'longitude' => $coordinates['lng'] ?? null,
            ]);
        }

        $appartements->update($request->all());

        return redirect()->route('appartements_index')->with('success', 'Appartement updated successfully.');
    }


    public function destroy(appartements $appartements)
    {
        $appartements->delete();

        return redirect()->route('appartements_index')->with('success', 'Appartement deleted successfully.');
    }


    public function allProperties()
    {
        // all properties and owner 
        $properties = appartements::with(['photos', 'categories', 'reservations', 'user'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);
        
        // statistics for each property
        foreach ($properties as $property) {
        
            $property->activeReservations = $property->reservations->where('status', 'confirmed')->count();
            
            $property->totalRevenue = $property->reservations->where('status', 'confirmed')->sum('total_price');
            
            $totalDays = now()->diffInDays($property->created_at);
            $bookedDays = $property->reservations->where('status', 'confirmed')
                ->sum(function($reservation) {
                    return \Carbon\Carbon::parse($reservation->start_date)
                        ->diffInDays(\Carbon\Carbon::parse($reservation->end_date));
                });
                
            $property->occupancyRate = $totalDays > 0 ? round(($bookedDays / $totalDays) * 100) : 0;
        }

        // user with properties
        $owners = \App\Models\User::whereHas('appartements')->get();
        
        // top booked properties
        $featuredCount = appartements::withCount(['reservations' => function($query) {
                           $query->where('status', 'confirmed');
                        }])
                        ->having('reservations_count', '>', 0)
                        ->count();
        
        // Get total active bookings 
        $activeBookingsCount = reservation::where('status', 'confirmed')->count();
        
        // revenue from all properties
        $totalRevenue = reservation::where('status', 'confirmed')->sum('total_price');
        
        // Get top performing properties
        $topProperties = appartements::withCount(['reservations as totalBookings' => function($query) {
                            $query->where('status', 'confirmed');
                        }])
                        ->withSum(['reservations as totalRevenue' => function($query) {
                            $query->where('status', 'confirmed');
                        }], 'total_price')
                        ->orderByDesc('totalRevenue')
                        ->take(5)
                        ->get();
        
        // Get category statistics
        $categoryStats = categories::withCount('appartements')
                            ->having('appartements_count', '>', 0)
                            ->orderByDesc('appartements_count')
                            ->get();
        
        // total properties 
        $propertiesCount = appartements::count();
        
        return view('all_properties', compact(
            'properties', 
            'owners', 
            'featuredCount', 
            'activeBookingsCount', 
            'totalRevenue', 
            'topProperties', 
            'categoryStats',
            'propertiesCount'
        ));
    }
}
