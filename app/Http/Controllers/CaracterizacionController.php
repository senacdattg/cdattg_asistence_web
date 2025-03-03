<?php

namespace App\Http\Controllers;

use App\Models\AsistenciaAprendiz;
use App\Models\CaracterizacionPrograma;
use App\Models\FichaCaracterizacion;
use App\Models\Instructor;
use App\Models\JornadaFormacion;
use App\Models\Persona;
use App\Models\ProgramaFormacion;
use App\Models\Sede;
use Faker\Provider\ar_EG\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CaracterizacionController extends Controller
{

    /**
     * Muestra una lista de todos los caracteres con sus fichas asociadas.
     *
     * Este método recupera todos los registros de la tabla `CaracterizacionPrograma` 
     * junto con sus relaciones `ficha` y los pasa a la vista `caracterizacion.index`.
     *
     * @return \Illuminate\View\View La vista que muestra la lista de caracteres.
     */
    public function index()
    {
        $caracteres = CaracterizacionPrograma::with('ficha')->orderBy('id', 'desc')->paginate(7);
        return view('caracterizacion.index', compact('caracteres'));
    }



    /**
     * Muestra la vista para crear una nueva caracterización.
     *
     * @return \Illuminate\View\View La vista de creación de caracterización con todas las fichas de caracterización.
     */
    public function create()
    {
        return view('caracterizacion.create', [
            'fichas' => FichaCaracterizacion::all(),
        ]);
    }



    /**
     * Obtiene la caracterización por ficha.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene el ID de la ficha.
     * @return \Illuminate\View\View La vista de caracterización con los datos de la ficha, sede, instructores y jornadas.
     *
     * @throws \Illuminate\Validation\ValidationException Si la validación del ID de la ficha falla.
     */
    public function getCaracterByFicha(Request $request)
    {
        $request->validate([
            'ficha_id' => 'required|integer|exists:fichas_caracterizacion,id',
        ]);

        $fichaId = $request->input('ficha_id');


        $ficha = FichaCaracterizacion::with(['programaFormacion'])->find($fichaId);
        $sedePrograma = $ficha->programaFormacion->sede_id;


        $sede = Sede::find($sedePrograma);
        $instructors = Instructor::all();
        $jornadas = JornadaFormacion::all();



        return view('caracterizacion.caracterizacion', compact('ficha', 'sede', 'instructors', 'jornadas'));
    }


    /**
     * Almacena una nueva caracterización en la base de datos.
     *
     * Este método valida los datos de entrada y crea una nueva instancia de 
     * CaracterizacionPrograma con los datos proporcionados. Luego guarda la 
     * instancia en la base de datos y redirige al usuario al índice de 
     * caracterizaciones con un mensaje de éxito.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene los datos de entrada.
     * @return \Illuminate\Http\RedirectResponse Redirección a la ruta de índice de caracterizaciones con un mensaje de éxito.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ficha_id' => 'required|exists:fichas_caracterizacion,id',
            'programa_id' => 'required|exists:programas_formacion,id',
            'sede_id' => 'required|exists:sedes,id',
            'jornada_id' => 'required|exists:jornadas_formacion,id',
            'persona_id' => 'required|exists:instructors,persona_id',
        ]);

        $caracterizacion = new CaracterizacionPrograma();
        $caracterizacion->ficha_id = $request->input('ficha_id');
        $caracterizacion->programa_formacion_id = $request->input('programa_id');
        $caracterizacion->instructor_persona_id = $request->input('persona_id');
        $caracterizacion->jornada_id = $request->input('jornada_id');
        $caracterizacion->sede_id = $request->input('sede_id');

        $caracterizacion->save();

        return redirect()->route('caracterizacion.index')->with('success', 'Caracterización creada exitosamente.');
    }



    /**
     * Muestra el formulario de edición para una caracterización específica.
     *
     * @param string $id El ID de la caracterización a editar.
     * @return \Illuminate\View\View La vista del formulario de edición con los datos necesarios.
     * 
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentra la caracterización con el ID proporcionado.
     */
    public function edit(string $id)
    {
        $caracterizacion = CaracterizacionPrograma::findOrFail($id);
        return view('caracterizacion.edit', [
            'caracterizacion' => $caracterizacion,
            'fichas' => FichaCaracterizacion::all(),
            'programas' => ProgramaFormacion::all(),
            'instructores' => Instructor::all(),
            'jornadas' => JornadaFormacion::all(),
            'sedes' => Sede::all(),
        ]);
    }


    /**
     * Actualiza una caracterización existente en la base de datos.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene los datos de la caracterización a actualizar.
     * @param string $id El ID de la caracterización que se va a actualizar.
     * @return \Illuminate\Http\RedirectResponse Redirige a la ruta de índice de caracterización con un mensaje de éxito.
     *
     * @throws \Illuminate\Validation\ValidationException Si la validación de los datos de la solicitud falla.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentra la caracterización con el ID proporcionado.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'ficha_id' => 'required|exists:fichas_caracterizacion,id',
            'programa_formacion_id' => 'required|exists:programas_formacion,id',
            'instructor_persona_id' => 'required|exists:instructors,persona_id',
            'jornada_id' => 'required|exists:jornadas_formacion,id',
            'sede_id' => 'required|exists:sedes,id',
        ]);

        $caracterizacion = CaracterizacionPrograma::findOrFail($id);
        $caracterizacion->ficha_id = $request->input('ficha_id');
        $caracterizacion->programa_formacion_id = $request->input('programa_formacion_id');
        $caracterizacion->instructor_persona_id = $request->input('instructor_persona_id');
        $caracterizacion->jornada_id = $request->input('jornada_id');
        $caracterizacion->sede_id = $request->input('sede_id');

        $caracterizacion->save();

        return redirect()->route('caracterizacion.index')->with('success', 'Caracterización actualizada exitosamente.');
    }



    /**
     * Elimina una caracterización específica basada en el ID proporcionado.
     *
     * @param string $id El ID de la caracterización a eliminar.
     * @return \Illuminate\Http\RedirectResponse Redirige a la ruta de índice de caracterización con un mensaje de éxito.
     */
    public function destroy(string $id)
    {

        $caracterizacion = CaracterizacionPrograma::where('id', $id);

        $asistencias = AsistenciaAprendiz::where('caracterizacion_id', $id)->get();

        if (count($asistencias) > 0) {
            return redirect()->route('caracterizacion.index')->with('error', 'No se puede eliminar la caracterización porque tiene asistencias asociadas.');
        }

        $caracterizacion->delete();

        return redirect()->route('caracterizacion.index')->with('success', 'Caracterización eliminada exitosamente.');
    }


    /**
     * Obtiene las caracterizaciones asociadas a un instructor específico.
     *
     * @param string $id El ID del instructor.
     * @return \Illuminate\Http\JsonResponse Una respuesta JSON con las caracterizaciones encontradas o un mensaje de error si no se encuentran.
     *
     * Este método realiza una consulta a la base de datos para obtener las caracterizaciones que están asociadas al instructor cuyo ID se proporciona como parámetro.
     * Utiliza relaciones Eloquent para incluir datos de las tablas relacionadas: ficha, programa de formación, persona, jornada y sede.
     * Luego, mapea los resultados para devolver solo los campos necesarios en la respuesta JSON.
     * Si se encuentran caracterizaciones, se devuelve una respuesta JSON con un código de estado 200.
     * Si no se encuentran caracterizaciones, se devuelve una respuesta JSON con un mensaje de error y un código de estado 404.
     */
    public function CaracterizacionByInstructor(String $id)
    {
        $caracterizaciones = CaracterizacionPrograma::with('ficha', 'programaFormacion', 'persona', 'jornada', 'sede')
            ->where('instructor_persona_id', $id)
            ->get()
            ->map(function ($caracterizacion) {
                return [
                    'id' => $caracterizacion->id,
                    'ficha' => $caracterizacion->ficha->ficha ?? 'N/A',
                    'programa_formacion' => $caracterizacion->programaFormacion->nombre ?? 'N/A',
                    'persona' => $caracterizacion->persona->primer_nombre ?? '' . ' ' . $caracterizacion->persona->segundo_nombre  ?? '' . ' ' . $caracterizacion->persona->primer_apellido ?? '' . ' ' . $caracterizacion->persona->segundo_apellido ?? '',
                    'jornada' => $caracterizacion->jornada->jornada ?? 'N/A',
                    'sede' => $caracterizacion->sede->sede ?? 'N/A',
                ];
            });

        if ($caracterizaciones->isNotEmpty()) {
            return response()->json($caracterizaciones, 200);
        } else {
            return response()->json(['message' => 'No se encontraron caracterizaciones.'], 404);
        }
    }

    public function show(Request $request)
    {
        $search = $request->input('search');

        $caracteres = CaracterizacionPrograma::with(['ficha', 'programaFormacion', 'persona'])
            ->whereHas('ficha', function ($query) use ($search) {
                $query->where('ficha', 'like', '%' . $search . '%');
            })
            ->orWhereHas('programaFormacion', function ($query) use ($search) {
                $query->where('nombre', 'like', '%' . $search . '%');
            })
            ->orWhereHas('persona', function ($query) use ($search) {
                $query->where('primer_nombre', 'like', '%' . $search . '%')
                    ->orWhere('segundo_nombre', 'like', '%' . $search . '%')
                    ->orWhere('primer_apellido', 'like', '%' . $search . '%')
                    ->orWhere('segundo_apellido', 'like', '%' . $search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(7);

        if ($caracteres->isEmpty()) {
            return redirect()->route('caracterizacion.index')->with('error', 'No se encontraron resultados.');
        }

        return view('caracterizacion.index', compact('caracteres'));
    }
}
