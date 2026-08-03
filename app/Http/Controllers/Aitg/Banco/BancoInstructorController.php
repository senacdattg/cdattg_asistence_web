<?php

namespace App\Http\Controllers\Aitg\Banco;

use App\Http\Controllers\Controller;
use App\Http\Requests\Aitg\Banco\StoreBulkDocumentosBancoRequest;
use App\Http\Requests\Aitg\Banco\StoreDocumentoBancoRequest;
use App\Models\Aitg\Banco\ArchivoTalento;
use App\Models\Aitg\Banco\PostulacionArchivo;
use App\Models\Aitg\Banco\PostulacionPlan;
use App\Models\Aitg\PlanContratacion;
use App\Models\Competencia;
use App\Models\Regional;
use App\Models\User;
use App\Services\Aitg\Banco\AitgBancoConsultaService;
use App\Services\Aitg\Banco\AitgBancoEnvioService;
use App\Services\Aitg\Banco\AitgBancoTalentoService;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BancoInstructorController extends Controller
{
    private const PERMISO_VALIDAR_DOCUMENTO_BANCO = 'VALIDAR DOCUMENTO BANCO AITG';

    public function __construct(
        private readonly AitgBancoTalentoService $talentoService,
        private readonly AitgBancoEnvioService $envioService,
        private readonly AitgBancoConsultaService $consultaService
    ) {
        $this->middleware('auth');
        $this->middleware('can:VER BANCO INSTRUCTOR AITG');
        $this->middleware('can:SUBIR DOCUMENTO BANCO AITG')->only([
            'store', 'storeLote', 'reutilizar', 'enviarRevision', 'destroyDocumento',
        ]);
    }

    public function index(Request $request): View
    {
        $user = $this->currentUser();
        $competencias = $this->consultaService->buscarCompetencias($request->only(['competencia', 'regional_id', 'modalidad']));

        return view('aitg.banco-instructores.buscar', [
            'competencias' => $competencias,
            'regionales' => Regional::where('status', 1)->orderBy('nombre')->get(),
            'modalidades' => PlanContratacion::MODALIDADES,
            'persona' => $user->persona,
            'misPostulaciones' => $this->consultaService->listarPostulacionesBanco($user),
        ]);
    }

    public function postulacion(Competencia $competencia): View|RedirectResponse
    {
        $user = $this->currentUser();
        $competencia->load(['aitgPlanes' => fn ($q) => $q->whereIn('estado', ['activo', 'borrador'])->with('regional')]);

        if ($competencia->aitgPlanes->isEmpty()) {
            return redirect()->route('aitg.banco-instructores.index')
                ->with('error', 'Esta competencia no tiene un plan de contratación activo para acreditación.');
        }

        $postulacion = $this->talentoService->obtenerPostulacion($user, $competencia);
        $postulacion->setRelation('competencia', $competencia);

        return view('aitg.banco-instructores.postulacion', [
            'competencia' => $competencia,
            'postulacion' => $postulacion,
            'persona' => $user->persona,
            'secciones' => $this->envioService->seccionesDocumentales($postulacion, $user),
            'puedeEnviar' => $this->envioService->puedeEnviar($postulacion, $user),
        ]);
    }

    public function store(StoreDocumentoBancoRequest $request, Competencia $competencia): RedirectResponse
    {
        return $this->guardarArchivo($request, $competencia);
    }

    public function storeLote(StoreBulkDocumentosBancoRequest $request, Competencia $competencia): RedirectResponse
    {
        $user = $this->currentUser();
        $postulacion = $this->talentoService->obtenerPostulacion($user, $competencia);

        if (! $postulacion->puedeEditar()) {
            return back()->with('error', 'No puede modificar documentos en el estado actual.');
        }

        try {
            $subidos = $this->talentoService->subirArchivosLote(
                $postulacion,
                $request->file('archivos', []),
                $user
            );

            $mensaje = $subidos === 0
                ? ['error', 'No se recibieron archivos válidos para cargar.']
                : ['success', "Se cargaron {$subidos} documento(s) correctamente."];

            return back()->with($mensaje[0], $mensaje[1]);
        } catch (\Throwable $e) {
            Log::error('AITG Banco talento: error lote', ['error' => $e->getMessage()]);

            return back()->with('error', 'No se pudieron cargar los documentos.');
        }
    }

    public function reutilizar(Request $request, Competencia $competencia): RedirectResponse
    {
        $request->validate(['archivo_talento_id' => ['required', 'integer', 'exists:aitg_archivos_talento,id']]);

        $user = $this->currentUser();
        $postulacion = $this->talentoService->obtenerPostulacion($user, $competencia);
        $archivo = ArchivoTalento::findOrFail($request->input('archivo_talento_id'));

        try {
            $this->talentoService->reutilizarArchivo($postulacion, $archivo, $user);

            return back()->with('success', 'Se reutilizó un documento existente de su banco de talento.');
        } catch (\Throwable $e) {
            Log::error('AITG Banco talento: error al reutilizar', ['error' => $e->getMessage()]);

            return back()->with('error', 'No se pudo reutilizar el documento.');
        }
    }

    public function verArchivo(ArchivoTalento $archivo): View|RedirectResponse
    {
        $this->autorizarArchivo($archivo);

        if (! $this->diskArchivo($archivo)->exists($archivo->storage_path)) {
            return back()->with('error', 'El archivo no está disponible.');
        }

        return view('aitg.banco-instructores.ver-archivo', compact('archivo'));
    }

    public function downloadArchivo(ArchivoTalento $archivo): StreamedResponse|RedirectResponse
    {
        $this->autorizarArchivo($archivo);
        $disk = $this->diskArchivo($archivo);

        if (! $disk->exists($archivo->storage_path)) {
            return back()->with('error', 'El archivo no está disponible.');
        }

        return $disk->download($archivo->storage_path, $archivo->nombre_original);
    }

    public function streamArchivo(ArchivoTalento $archivo): StreamedResponse
    {
        $this->autorizarArchivo($archivo);
        $disk = $this->diskArchivo($archivo);

        abort_unless($disk->exists($archivo->storage_path), 404);

        return $disk->response($archivo->storage_path, $archivo->nombre_original, [
            'Content-Type' => $archivo->mime_type ?? 'application/pdf',
        ]);
    }

    public function destroyDocumento(Competencia $competencia, PostulacionArchivo $postulacionArchivo): RedirectResponse
    {
        $user = $this->currentUser();
        $postulacion = $this->talentoService->obtenerPostulacion($user, $competencia);

        try {
            $this->talentoService->eliminarDocumentoPostulacion($postulacion, $postulacionArchivo, $user);

            return back()->with('success', 'Documento eliminado. Puede cargar uno nuevo.');
        } catch (\Throwable $e) {
            Log::error('AITG Banco talento: error al eliminar', ['error' => $e->getMessage()]);

            return back()->with('error', 'No se pudo eliminar el documento.');
        }
    }

    public function enviarRevision(Competencia $competencia): RedirectResponse
    {
        $user = $this->currentUser();
        $postulacion = $this->talentoService->obtenerPostulacion($user, $competencia);

        if (! $postulacion->puedeEditar()) {
            return back()->with('error', 'Su postulación ya fue enviada o no puede modificarse.');
        }

        if (! $this->envioService->puedeEnviar($postulacion, $user)) {
            return back()->with('error', 'Complete todos los documentos obligatorios de postulación (validación inicial).');
        }

        try {
            $this->talentoService->enviarRevision($postulacion, $user);
            $flash = ['success', 'Documentos enviados a revisión del Banco de Talento.'];
        } catch (\InvalidArgumentException $e) {
            $flash = ['error', $e->getMessage()];
        }

        return back()->with($flash[0], $flash[1]);
    }

    public function destroyPostulacion(Competencia $competencia): RedirectResponse
    {
        $postulacion = PostulacionPlan::where('user_id', Auth::id())
            ->where('competencia_id', $competencia->id)
            ->whereNull('convocatoria_id')
            ->firstOrFail();

        try {
            $this->talentoService->eliminarPostulacion($postulacion, $this->currentUser());

            return redirect()->route('aitg.banco-instructores.index')
                ->with('success', 'Postulación eliminada. Puede volver a inscribirse en esta competencia cuando lo desee.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage() ?: 'No se pudo eliminar la postulación.');
        }
    }

    private function guardarArchivo(StoreDocumentoBancoRequest $request, Competencia $competencia): RedirectResponse
    {
        $user = $this->currentUser();
        $postulacion = $this->talentoService->obtenerPostulacion($user, $competencia);

        if (! $postulacion->puedeEditar()) {
            return back()->with('error', 'No puede modificar documentos en el estado actual.');
        }

        try {
            $this->talentoService->subirArchivo(
                $postulacion,
                $request->file('archivo'),
                $user,
                [
                    'tipo_archivo_id' => $request->input('tipo_archivo_id') ? (int) $request->input('tipo_archivo_id') : null,
                    'punto_adicional_id' => $request->input('punto_adicional_id') ? (int) $request->input('punto_adicional_id') : null,
                    'checklist_item_id' => $request->input('checklist_item_id') ? (int) $request->input('checklist_item_id') : null,
                    'punto_item_id' => $request->input('punto_item_id') ? (int) $request->input('punto_item_id') : null,
                    'perfil_plan_id' => $request->input('perfil_plan_id') ? (int) $request->input('perfil_plan_id') : null,
                ]
            );

            return back()->with('success', 'Documento cargado correctamente.');
        } catch (\Throwable $e) {
            Log::error('AITG Banco talento: error al subir', ['error' => $e->getMessage()]);

            return back()->with('error', 'No se pudo cargar el documento.');
        }
    }

    private function currentUser(): User
    {
        return Auth::user() ?? abort(403);
    }

    private function autorizarArchivo(ArchivoTalento $archivo): void
    {
        $esDueno = $archivo->user_id === Auth::id();
        $puedeValidar = Gate::allows(self::PERMISO_VALIDAR_DOCUMENTO_BANCO);

        abort_unless($esDueno || $puedeValidar, 403);
    }

    private function diskArchivo(ArchivoTalento $archivo): FilesystemAdapter
    {
        /** @var FilesystemAdapter */
        return Storage::disk($archivo->storage_disk);
    }
}
