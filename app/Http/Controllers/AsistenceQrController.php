<?php

namespace App\Http\Controllers;

use App\Models\CaracterizacionPrograma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsistenceQrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user(); 
        $id_person = $user->persona_id; 

        $caracterización = CaracterizacionPrograma::where('instructor_persona_id', $id_person)->get(); 

        dd($caracterización); 

        return view('qr_asistence.index'); 
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
    public function store(Request $request)
    {
        dd($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
