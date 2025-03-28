    <?php

namespace App\Http\Controllers;

use App\Models\CentroFormacion;
use Illuminate\Http\Request;

class CentroFormacionController extends Controller
{
    public function __contruct()
    {
        $this->middleware('auth');

        $this->middleware('can:centroFormacion.index')->only('index');
        $this->middleware('can:centroFormacion.create')->only('create', 'store');
        $this->middleware('can:centroFormacion.edit')->only('edit', 'update');
        $this->middleware('can:centroFormacion.show')->only('show');
        $this->middleware('can:centroFormacion.destroy')->only('destroy');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $centros = CentroFormacion::paginate(10);

        return view('centros.index', compact('centros'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CentroFormacion $centroFormacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CentroFormacion $centroFormacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CentroFormacion $centroFormacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CentroFormacion $centroFormacion)
    {
        //
    }
}
