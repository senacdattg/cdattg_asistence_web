<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use App\Http\Requests\StoreMunicipioRequest;
use App\Http\Requests\UpdateMunicipioRequest;

class MunicipioController extends Controller
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
    public function cargarMunicipios($departamento_id)
    {
        // DB::enableQueryLog();
        $municipios = Municipio::where('departamento_id', $departamento_id)
        ->where('status', 1)->get();
        return response()->json(['success' => true, 'municipios' => $municipios]);
        // dd(DB::getQueryLog());
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMunicipioRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Municipio $municipio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Municipio $municipio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMunicipioRequest $request, Municipio $municipio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Municipio $municipio)
    {
        //
    }
}
