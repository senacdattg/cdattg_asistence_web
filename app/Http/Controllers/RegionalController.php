<?php

namespace App\Http\Controllers;

use App\Models\Regional;
use App\Http\Requests\StoreRegionalRequest;
use App\Http\Requests\UpdateRegionalRequest;

class RegionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $regionales = Regional::where('status', 1)->paginate();
        return view('regional.index', compact('regionales'));
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
    public function store(StoreRegionalRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Regional $regional)
    {
        return view('regional.show', ['regional' => $regional]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Regional $regional)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegionalRequest $request, Regional $regional)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Regional $regional)
    {
        //
    }
}
