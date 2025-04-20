<?php

namespace App\Http\Controllers;

use App\Models\appartements;
use App\Models\photos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerPropertiesController extends Controller
{
    /**
     * Display a listing of the owner properties.
     */
    public function index()
    {
        $user = Auth::user();

        // $appartements = appartements::where('user_id', $user->id)->get();
        
        // Get the owner's properties with their photos
        $properties = appartements::where('user_id', $user->id)
                        ->with('photos', 'categories', 'reservations')
                        ->get();
        
        // Get some stats for each property
        foreach ($properties as $property) {
            // Calculate the number of active reservations
            $property->activeReservations = $property->reservations->where('status', 'confirmed')->count();
            
            // Calculate total revenue for this property
            $property->totalRevenue = $property->reservations->where('status', 'confirmed')->sum('total_price');
            
            // Calculate average rating if you had a rating system
            // $property->averageRating = ...
        }
        
        return view('owner_properties', compact('properties', 'user'));
    }
    
    /**
     * Delete a property
     */
    public function delete($id)
    {
        $property = appartements::findOrFail($id);
        
        // Check if the authenticated user owns the property
        if ($property->user_id != auth()->id()) {
            return redirect()->back()->with('error', 'You are not authorized to delete this property.');
        }
        
        $property->delete();
        
        return redirect()->route('owner.properties')->with('success', 'Property deleted successfully.');
    }
}
