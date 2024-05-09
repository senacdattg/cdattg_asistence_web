<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Http\Requests\StoreDepartamentoRequest;
use App\Http\Requests\UpdateDepartamentoRequest;

class DepartamentoController extends Controller
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
    public function cargarDepartamentos()
    {
        // DB::enableQueryLog();
        $departamentos = Departamento::where('status', 1)->get() ;
        return response()->json(['success' => true, 'departamentos' => $departamentos], 200);
        // dd(DB::getQueryLog());
    }
    public function apiCargarDepartamentos()
    {
        // DB::enableQueryLog();
        $departamentos = Departamento::where('status', 1)->get();
        return response()->json(['departamentos' => $departamentos], 200);
        // dd(DB::getQueryLog());
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartamentoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Departamento $departamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departamento $departamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartamentoRequest $request, Departamento $departamento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departamento $departamento)
    {
        //
    }
}
