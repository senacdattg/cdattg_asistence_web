<?php

namespace App\Http\Controllers;

use App\Models\ProgramaFormacion;
use App\Models\Sede;
use App\Models\TipoPrograma;
use Database\Seeders\TiposProgramas;
use Illuminate\Http\Request;

class ProgramaFormacionController extends Controller
{
    
    /**
     * Muestra una lista paginada de programas de formación.
     *
     * Este método recupera una lista de programas de formación desde la base de datos,
     * incluyendo las relaciones con 'sede' y 'tipoPrograma', y los pagina en grupos de 7.
     * Luego, pasa esta lista a la vista 'programas.index'.
     *
     * @return \Illuminate\View\View La vista que muestra la lista de programas de formación.
     */
    public function index()
    {
        $programas = ProgramaFormacion::with(['sede', 'tipoPrograma'])->paginate(7);
        if($programas == null){
            $programas = null;
        }
        return view('programas.index', compact('programas'));
    }

  
    /**
     * Muestra el formulario para crear un nuevo programa de formación.
     *
     * Este método obtiene todas las sedes y tipos de programas disponibles.
     * Si no hay sedes o tipos de programas, se asigna null a las variables correspondientes.
     *
     * @return \Illuminate\View\View La vista del formulario de creación de programas de formación.
     */
    public function create()
    {
        $sedes = Sede::all();
        $tipos = TipoPrograma::all();


        if(count($sedes) == 0){
            $sedes = null; 
        }
        
        if(count($tipos) == 0){
            $tipos = null; 
        }

        return view('programas.create', compact('sedes', 'tipos'));
    }

 
    /**
     * Almacena un nuevo programa de formación en la base de datos.
     *
     * Valida los datos de entrada del formulario y crea un nuevo registro
     * en la tabla 'programas_formacion' con los datos proporcionados.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene los datos del formulario.
     * @return \Illuminate\Http\RedirectResponse Redirige a la página de índice de programas con un mensaje de éxito.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_programa' => 'required|string|max:255|unique:programas_formacion,nombre',
            'sede_id' => 'required|exists:sedes,id',
            'tipo_programa_id' => 'required|exists:tipos_programas,id',
        ]);

        $programaFormacion = new ProgramaFormacion();
        $programaFormacion->nombre = $request->input('nombre_programa');
        $programaFormacion->sede_id = $request->input('sede_id');
        $programaFormacion->tipo_programa_id = $request->input('tipo_programa_id');
        $programaFormacion->save();

        return redirect('programa/index')->with('success', 'Programa de formación creado exitosamente.');
    }

  
    /**
     * Muestra el formulario de edición para un programa de formación específico.
     *
     * @param string $id El ID del programa de formación a editar.
     * @return \Illuminate\View\View La vista del formulario de edición con los datos del programa, sedes y tipos de programa.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentra el programa de formación con el ID proporcionado.
     */
    public function edit(string $id)
    {
        $programa = ProgramaFormacion::findOrFail($id);
        $sedes = Sede::all();
        $tipos = TipoPrograma::all(); 

        if($programa == null){
            $programa = null; 
        }

        return view('programas.edit', compact('programa', 'sedes', 'tipos'));
    }

   
    /**
     * Actualiza un programa de formación existente.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene los datos del formulario.
     * @param string $id El ID del programa de formación que se va a actualizar.
     * @return \Illuminate\Http\RedirectResponse Redirige a la página de índice de programas con un mensaje de éxito.
     *
     * @throws \Illuminate\Validation\ValidationException Si la validación de los datos falla.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentra el programa de formación con el ID proporcionado.
     */
    public function update(Request $request, string $id)
    {
   
        $request->validate([
            'nombre_programa' => 'required|string|max:255|unique:programas_formacion,nombre,' . $id,
            'sede_id' => 'required|exists:sedes,id',
            'tipo_programa_id' => 'required|exists:tipos_programas,id',
        ]);

        $programaFormacion = ProgramaFormacion::findOrFail($id);
        $programaFormacion->nombre = $request->input('nombre_programa');
        $programaFormacion->sede_id = $request->input('sede_id');
        $programaFormacion->tipo_programa_id = $request->input('tipo_programa_id');
        $programaFormacion->save();

        return redirect('programa/index')->with('success', 'Programa de formación actualizado exitosamente.');
    }

 
    /**
     * Elimina un programa de formación especificado por su ID.
     *
     * @param string $id El ID del programa de formación a eliminar.
     * @return \Illuminate\Http\RedirectResponse Redirige a la página de índice de programas con un mensaje de éxito.
     */
    public function destroy(string $id)
    {
       
        $programaFormacion = ProgramaFormacion::findOrFail($id);
        $programaFormacion->delete();

        return redirect('programa/index')->with('success', 'Programa de formación eliminado exitosamente.');
    }

  
    /**
     * Busca programas de formación basados en el término de búsqueda proporcionado.
     *
     * Este método toma una solicitud HTTP que contiene un término de búsqueda y busca
     * programas de formación cuyo nombre coincida con el término de búsqueda. También
     * busca programas de formación que estén asociados con sedes o tipos de programas
     * cuyo nombre coincida con el término de búsqueda.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene el término de búsqueda.
     * @return \Illuminate\View\View La vista que muestra los programas de formación encontrados.
     */
    public function search(Request $request)
    {
        $query = $request->input('search');
        $programas = ProgramaFormacion::where('nombre', 'LIKE', "%{$query}%")
            ->orWhereHas('sede', function($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%");
            })
            ->orWhereHas('tipoPrograma', function($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%");
            })
            ->get();

        if(count($programas) == 0){
            $programas = null;
        }

        return view('programas.index', compact('programas'));
    }
}
