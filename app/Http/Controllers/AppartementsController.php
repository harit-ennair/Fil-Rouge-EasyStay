<?php

namespace App\Http\Controllers;

use App\Models\appartements;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateappartementsRequest;

class AppartementsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('appartements_index', [
            'appartements' => appartements::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('appartements_create');
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
        
        $appartement = new appartements($request->all());
        $appartement->user_id = auth()->id();
        $appartement->save();

        return redirect()->route('appartements_index')->with('success', 'Appartement created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(appartements $appartements)
    {
        
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
