<?php

namespace App\Http\Controllers\Aitg\Seleccion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Aitg\Seleccion\ConfirmarSeleccionRequest;
use App\Models\Aitg\Banco\PostulacionPlan;
use App\Models\Aitg\Convocatoria\Convocatoria;
use App\Services\Aitg\Seleccion\AitgSeleccionReporteService;
use App\Services\Aitg\Seleccion\AitgSeleccionService;
use App\Services\ExportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SeleccionController extends Controller
{
    public function __construct(
        private readonly AitgSeleccionService $seleccionService,
        private readonly AitgSeleccionReporteService $reporteService,
        private readonly ExportService $exportService,
    ) {
        $this->middleware('auth');
        $this->middleware('can:VER SELECCION AITG');
        $this->middleware('can:SELECCIONAR INSTRUCTOR AITG')->only(['confirmar']);
    }

    public function index(): View
    {
        return view('aitg.seleccion.index', [
            'convocatorias' => $this->seleccionService->convocatoriasParaSeleccion(),
        ]);
    }

    public function candidatos(Request $request, Convocatoria $convocatoria): View
    {
        $orden = $request->input('orden', 'desc') === 'asc' ? 'asc' : 'desc';

        return view('aitg.seleccion.candidatos', [
            'convocatoria' => $convocatoria->load(['competencia', 'regional', 'plan', 'postulacionSeleccionada.user.persona']),
            'candidatos' => $this->seleccionService->candidatos($convocatoria, $orden),
            'orden' => $orden,
        ]);
    }

    public function confirmar(ConfirmarSeleccionRequest $request, Convocatoria $convocatoria): RedirectResponse
    {
        $ganador = PostulacionPlan::findOrFail($request->validated('postulacion_ganador_id'));
        $suplente = $request->validated('postulacion_suplente_id')
            ? PostulacionPlan::findOrFail($request->validated('postulacion_suplente_id'))
            : null;

        try {
            $this->seleccionService->seleccionarInstructor(
                $convocatoria,
                $ganador,
                Auth::user(),
                $suplente,
                $request->validated('observaciones')
            );

            return redirect()
                ->route('aitg.seleccion.candidatos', $convocatoria)
                ->with('success', 'Instructor seleccionado. La convocatoria fue finalizada y el proceso continúa en Formalización.');
        } catch (\Throwable $e) {
            Log::error('Error en selección AITG', ['exception' => $e]);

            return back()->with('error', $e->getMessage() ?: 'No fue posible confirmar la selección.');
        }
    }

    public function reporteGeneral(Convocatoria $convocatoria): View
    {
        return view('aitg.seleccion.reporte-general', [
            'convocatoria' => $convocatoria,
            'reporte' => $this->reporteService->reporteGeneral($convocatoria),
        ]);
    }

    public function reporteGeneralPdf(Convocatoria $convocatoria): Response
    {
        $reporte = $this->reporteService->reporteGeneral($convocatoria);
        $codigo = $reporte['convocatoria']['codigo'] ?? $convocatoria->id;

        $pdf = Pdf::loadView('aitg.seleccion.pdf.reporte-general', compact('reporte'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('reporte_seleccion_'.$codigo.'.pdf');
    }

    public function reporteGeneralExcel(Convocatoria $convocatoria): BinaryFileResponse
    {
        $filas = $this->reporteService->filasExcelGeneral($convocatoria);
        $codigo = $convocatoria->codigo ?? ('conv_'.$convocatoria->id);

        $columnas = [
            ['field' => 'posicion', 'label' => 'Posición'],
            ['field' => 'documento', 'label' => 'Documento'],
            ['field' => 'nombre', 'label' => 'Nombre'],
            ['field' => 'perfil', 'label' => 'Perfil'],
            ['field' => 'puntaje_checklist', 'label' => '% Checklist'],
            ['field' => 'puntaje_adicionales', 'label' => 'Bonus'],
            ['field' => 'puntaje_total', 'label' => 'Total ranking'],
            ['field' => 'resultado', 'label' => 'Resultado'],
            ['field' => 'justificacion', 'label' => 'Justificación'],
            ['field' => 'criterios_detalle', 'label' => 'Detalle criterios'],
        ];

        $relativePath = $this->exportService->exportarExcel(
            $filas,
            $columnas,
            'seleccion_'.$codigo
        );
        $absolutePath = storage_path('app/public/'.$relativePath);

        return response()
            ->download($absolutePath, 'reporte_seleccion_'.$codigo.'.xlsx')
            ->deleteFileAfterSend(true);
    }

    public function reporteIndividual(Convocatoria $convocatoria, PostulacionPlan $postulacion): View
    {
        return view('aitg.seleccion.reporte-individual', [
            'convocatoria' => $convocatoria,
            'reporte' => $this->reporteService->reporteIndividual($convocatoria, $postulacion),
        ]);
    }

    public function reporteIndividualPdf(Convocatoria $convocatoria, PostulacionPlan $postulacion): Response
    {
        $reporte = $this->reporteService->reporteIndividual($convocatoria, $postulacion);
        $codigo = $reporte['convocatoria']['codigo'] ?? $convocatoria->id;
        $doc = $reporte['candidato']['documento'] ?? $postulacion->id;

        $pdf = Pdf::loadView('aitg.seleccion.pdf.reporte-individual', compact('reporte'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('reporte_seleccion_'.$codigo.'_'.$doc.'.pdf');
    }
}
