<?php

namespace App\Http\Controllers;

use App\Models\cartes;
use App\Http\Requests\StorecartesRequest;
use App\Http\Requests\UpdatecartesRequest;

class CartesController extends Controller
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
    public function store(StorecartesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(cartes $cartes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(cartes $cartes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatecartesRequest $request, cartes $cartes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cartes $cartes)
    {
        //
    }
}
