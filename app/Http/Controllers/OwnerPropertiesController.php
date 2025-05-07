<?php

namespace App\Http\Controllers;

use App\Models\appartements;
use App\Models\photos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerPropertiesController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        // $appartements = appartements::where('user_id', $user->id)->get();
        
        $properties = appartements::where('user_id', $user->id)
                        ->with('photos', 'categories', 'reservations')
                        ->get();
        

        foreach ($properties as $property) {

            $property->activeReservations = $property->reservations->where('status', 'confirmed')->count();
            
            $property->totalRevenue = $property->reservations->where('status', 'confirmed')->sum('total_price');
            
        }
        
        return view('owner_properties', compact('properties', 'user'));
    }
    

    public function delete($id)
    {
        $property = appartements::findOrFail($id);
        
        if ($property->user_id != auth()->id()) {
            return redirect()->back()->with('error', 'You are not authorized to delete this property.');
        }
        
        $property->delete();
        
        return redirect()->route('owner.properties')->with('success', 'Property deleted successfully.');
    }
}
