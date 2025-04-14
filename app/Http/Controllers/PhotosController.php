<?php

namespace App\Http\Controllers;

use App\Models\photos;
use App\Http\Requests\StorephotosRequest;
use App\Http\Requests\UpdatephotosRequest;

class PhotosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorephotosRequest $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photo = new photos();
        $photo->appartement_id = $request->input('appartement_id');
        $photo->photo = $request->file('photo')->store('photos', 'public');
        $photo->save();

        return redirect()->route('photos.index')->with('success', 'Photo uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(photos $photos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(photos $photos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatephotosRequest $request, photos $photos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(photos $photos)
    {
        //
    }
}
