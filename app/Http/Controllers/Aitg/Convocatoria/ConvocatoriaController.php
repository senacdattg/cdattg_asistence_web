<?php

namespace App\Http\Controllers\Aitg\Convocatoria;

use App\Http\Controllers\Controller;
use App\Http\Requests\Aitg\Convocatoria\StoreConvocatoriaRequest;
use App\Http\Requests\Aitg\Convocatoria\UpdateConvocatoriaRequest;
use App\Models\Aitg\Convocatoria\Convocatoria;
use App\Models\CentroFormacion;
use App\Models\Competencia;
use App\Models\Regional;
use App\Services\Aitg\Banco\AitgBancoConsultaService;
use App\Services\Aitg\Convocatoria\AitgConvocatoriaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ConvocatoriaController extends Controller
{
    public function __construct(
        private readonly AitgConvocatoriaService $convocatoriaService,
        private readonly AitgBancoConsultaService $consultaService
    ) {
        $this->middleware('auth');
        $this->middleware('can:VER CONVOCATORIA AITG')->only(['index', 'show', 'postulaciones']);
        $this->middleware('can:CREAR CONVOCATORIA AITG')->only(['create', 'store']);
        $this->middleware('can:EDITAR CONVOCATORIA AITG')->only(['edit', 'update']);
        $this->middleware('can:ELIMINAR CONVOCATORIA AITG')->only(['destroy']);
    }

    public function index(Request $request): View
    {
        return view('aitg.convocatorias.index', [
            'convocatorias' => $this->convocatoriaService->listarAdmin($request->only(['estado', 'busqueda'])),
        ]);
    }

    public function create(): View
    {
        return view('aitg.convocatorias.create', $this->formData());
    }

    public function store(StoreConvocatoriaRequest $request): RedirectResponse
    {
        try {
            $convocatoria = $this->convocatoriaService->crear($request->validated(), Auth::user());

            return redirect()->route('aitg.convocatorias.show', $convocatoria)
                ->with('success', 'Convocatoria creada correctamente.');
        } catch (\Throwable $e) {
            Log::error('AITG convocatoria crear', ['error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'No se pudo crear la convocatoria.');
        }
    }

    public function show(Convocatoria $convocatoria): View
    {
        $convocatoria->load(['competencia', 'plan.perfiles', 'regional', 'centroFormacion']);

        return view('aitg.convocatorias.show', compact('convocatoria'));
    }

    public function edit(Convocatoria $convocatoria): View
    {
        $convocatoria->load('competencia');

        return view('aitg.convocatorias.edit', array_merge(
            ['convocatoria' => $convocatoria],
            $this->formData($convocatoria)
        ));
    }

    public function update(UpdateConvocatoriaRequest $request, Convocatoria $convocatoria): RedirectResponse
    {
        try {
            $this->convocatoriaService->actualizar($convocatoria, $request->validated(), Auth::user());

            return redirect()->route('aitg.convocatorias.show', $convocatoria)
                ->with('success', 'Convocatoria actualizada correctamente.');
        } catch (\Throwable $e) {
            Log::error('AITG convocatoria actualizar', ['error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'No se pudo actualizar la convocatoria.');
        }
    }

    public function destroy(Convocatoria $convocatoria): RedirectResponse
    {
        if ($convocatoria->postulaciones()->exists()) {
            return back()->with('error', 'No se puede eliminar: tiene postulaciones registradas.');
        }

        $convocatoria->delete();

        return redirect()->route('aitg.convocatorias.index')
            ->with('success', 'Convocatoria eliminada.');
    }

    public function postulaciones(Convocatoria $convocatoria): View
    {
        $convocatoria->load('competencia');

        return view('aitg.convocatorias.postulaciones', [
            'convocatoria' => $convocatoria,
            'postulaciones' => $this->consultaService->listarPostulacionesDeConvocatoria($convocatoria),
        ]);
    }

    public function planesPorCompetencia(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate(['competencia_id' => ['required', 'integer', 'exists:competencias,id']]);

        $planes = $this->convocatoriaService->planesPorCompetencia((int) $request->input('competencia_id'));

        return response()->json($planes->map(fn ($p) => [
            'id' => $p->id,
            'label' => ($p->competencia->nombre ?? 'Plan') . ' · ' . $p->periodo . ' · ' . $p->modalidad_label,
        ]));
    }

    private function formData(?Convocatoria $convocatoria = null): array
    {
        $competenciaId = $convocatoria?->competencia_id ?? old('competencia_id');

        return [
            'competencias' => Competencia::orderBy('nombre')->get(),
            'regionales' => Regional::where('status', 1)->orderBy('nombre')->get(),
            'centros' => CentroFormacion::where('status', 1)->orderBy('nombre')->get(),
            'planes' => $competenciaId
                ? $this->convocatoriaService->planesPorCompetencia((int) $competenciaId)
                : collect(),
            'estados' => array_intersect_key(
                Convocatoria::ESTADOS,
                array_flip(Convocatoria::ESTADOS_MANUALES)
            ),
        ];
    }
}
