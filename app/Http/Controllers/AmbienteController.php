<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use App\Http\Requests\StoreAmbienteRequest;
use App\Http\Requests\UpdateAmbienteRequest;
use App\Models\Piso;

class AmbienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ambientes = Ambiente::paginate(10);
        return view('ambiente.index', compact('ambientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pisos = Piso::all();
        return view('ambiente.create', compact('pisos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAmbienteRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Ambiente $ambiente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ambiente $ambiente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAmbienteRequest $request, Ambiente $ambiente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ambiente $ambiente)
    {
        //
    }
}
