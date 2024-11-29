<?php

namespace App\Http\Controllers;

use App\Models\FichaCaracterizacion;
use App\Models\ProgramaFormacion;
use Illuminate\Http\Request;

class FichaCaracterizacionController extends Controller
{
    
    /**
     * Muestra una lista de todas las fichas de caracterización.
     *
     * Este método recupera todas las fichas de caracterización junto con su
     * relación 'programaFormacion' y las pasa a la vista 'fichas.index'.
     *
     * @return \Illuminate\View\View La vista que muestra la lista de fichas de caracterización.
     */
    public function index()
    {
        
        $fichas = FichaCaracterizacion::with('programaFormacion')->orderBy('id', 'desc')->get();
        return view('fichas.index', compact('fichas'));
    }

  
    /**
     * Muestra el formulario para crear una nueva ficha de caracterización.
     *
     * Obtiene una lista de programas de formación ordenados alfabéticamente por nombre
     * y los pasa a la vista 'fichas.create'.
     *
     * @return \Illuminate\View\View La vista para crear una nueva ficha de caracterización con los programas de formación.
     */
    public function create()
    {
      
        $programas = ProgramaFormacion::orderBy('nombre', 'asc')->get();
       
        return view('fichas.create', compact('programas')); 
    }

  
    /**
     * Almacena una nueva ficha de caracterización en la base de datos.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene los datos de la ficha.
     * 
     * @return \Illuminate\Http\RedirectResponse Redirige a la ruta 'fichaCaracterizacion.index' con un mensaje de éxito.
     * 
     * @throws \Illuminate\Validation\ValidationException Si la validación de los datos falla.
     */
    public function store(Request $request)
    {
        $request->validate([
            'programa_id' => 'required|exists:programas_formacion,id',
            'numero_ficha' => 'required|numeric|unique:fichas_caracterizacion,ficha',
        ]);

        $ficha = new FichaCaracterizacion();
        $ficha->programa_formacion_id = $request->input('programa_id');
        $ficha->ficha = $request->input('numero_ficha');

    
        $ficha->save();

        return redirect()->route('fichaCaracterizacion.index')->with('success', 'Caracterización creada exitosamente.');
    }

  
    /**
     * Muestra el formulario de edición para una ficha de caracterización específica.
     *
     * @param string $id El ID de la ficha de caracterización a editar.
     * @return \Illuminate\View\View La vista del formulario de edición con la ficha de caracterización y los programas de formación.
     */
    public function edit(string $id)
    {
        $ficha = FichaCaracterizacion::findOrFail($id);
        $programas = ProgramaFormacion::orderBy('nombre', 'asc')->get();

        return view('fichas.edit', compact('ficha', 'programas'));
    }

   
    /**
     * Actualiza una ficha de caracterización existente.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene los datos de la ficha a actualizar.
     * @param string $id El ID de la ficha de caracterización que se va a actualizar.
     * @return \Illuminate\Http\RedirectResponse Redirige a la lista de fichas de caracterización con un mensaje de éxito.
     *
     * @throws \Illuminate\Validation\ValidationException Si la validación de los datos falla.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentra la ficha de caracterización con el ID proporcionado.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'programa_id' => 'required|exists:programas_formacion,id',
            'numero_ficha' => 'required|numeric|unique:fichas_caracterizacion,ficha,' . $id,
        ]);

        $ficha = FichaCaracterizacion::findOrFail($id);
        $ficha->programa_formacion_id = $request->input('programa_id');
        $ficha->ficha = $request->input('numero_ficha');

        $ficha->save();

        return redirect()->route('fichaCaracterizacion.index')->with('success', 'Caracterización actualizada exitosamente.');
    }


    /**
     * Elimina una ficha de caracterización específica.
     *
     * @param string $id El ID de la ficha de caracterización a eliminar.
     * @return \Illuminate\Http\RedirectResponse Redirige a la lista de fichas de caracterización con un mensaje de éxito.
     */
    public function destroy(string $id)
    {
        $ficha = FichaCaracterizacion::findOrFail($id);
        $ficha->delete();

        return redirect()->route('fichaCaracterizacion.index')->with('success', 'Caracterización eliminada exitosamente.');
    }
}
