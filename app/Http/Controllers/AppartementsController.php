<?php

namespace App\Http\Controllers;

use App\Models\appartements;
use App\Models\categories;
use App\Models\photos;
use App\Models\reservation;
use App\Services\GeocodingService;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateappartementsRequest;

class AppartementsController extends Controller
{
    protected $geocodingService;
    
    public function __construct(GeocodingService $geocodingService)
    {
        $this->geocodingService = $geocodingService;
    }

    /**
     * Display a listing of the resource.
     */
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

    /**
     * Show the form for creating a new resource.
     */
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
        
        // Get coordinates from the address using Google Maps Geocoding API
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

    /**
     * Display the specified resource.
     */
    public function show(Request $appartements)
    {
        $appartement = appartements::find($appartements->id);
        $photos = photos::where('appartement_id', $appartements->id)->get();
        $reservations = reservation::where('appartement_id', $appartements->id)->get();

        $similarAppartements = appartements::where('id', '!=', $appartement->id)
        ->where('location', $appartement->location)
        ->whereHas('categories', function($query) use ($appartement) {
            $query->whereIn('categories.id', $appartement->categories->pluck('id'));
        })
        ->take(3)
        ->get();
        

        return view('appartements_show', compact('appartement', 'photos', 'similarAppartements', 'reservations'));

    
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(appartements $appartements)
    {
        return view('appartements_edit', compact('appartements'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateappartementsRequest $request, appartements $appartements)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'location' => 'required',
        ]);

        // Only geocode if the location has changed
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(appartements $appartements)
    {
        $appartements->delete();

        return redirect()->route('appartements_index')->with('success', 'Appartement deleted successfully.');
    }
}
