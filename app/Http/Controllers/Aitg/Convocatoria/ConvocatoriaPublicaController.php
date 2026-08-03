<?php

namespace App\Http\Controllers\Aitg\Convocatoria;

use App\Http\Controllers\Controller;
use App\Http\Requests\Aitg\Banco\StoreBulkDocumentosBancoRequest;
use App\Http\Requests\Aitg\Banco\StoreDocumentoBancoRequest;
use App\Models\Aitg\Banco\ArchivoTalento;
use App\Models\Aitg\Banco\PostulacionArchivo;
use App\Models\Aitg\Convocatoria\Convocatoria;
use App\Models\Regional;
use App\Models\User;
use App\Services\Aitg\AitgCatalogoService;
use App\Services\Aitg\Banco\AitgBancoConsultaService;
use App\Services\Aitg\Banco\AitgBancoEnvioService;
use App\Services\Aitg\Banco\AitgBancoTalentoService;
use App\Services\Aitg\Convocatoria\AitgConvocatoriaReglasService;
use App\Services\Aitg\Convocatoria\AitgConvocatoriaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/** Convocatorias para aspirantes dentro del módulo AITG. */
class ConvocatoriaPublicaController extends Controller
{
    private const PERMISO_SUBIR_DOCUMENTO_BANCO = 'SUBIR DOCUMENTO BANCO AITG';

    private const PERMISO_VER_CONVOCATORIA = 'VER CONVOCATORIA AITG';

    public function __construct(
        private readonly AitgConvocatoriaService $convocatoriaService,
        private readonly AitgBancoTalentoService $talentoService,
        private readonly AitgBancoEnvioService $envioService,
        private readonly AitgBancoConsultaService $consultaService,
        private readonly AitgConvocatoriaReglasService $reglasService,
        private readonly AitgCatalogoService $catalogoService
    ) {
        $this->middleware('auth');
        $this->middleware('can:'.self::PERMISO_SUBIR_DOCUMENTO_BANCO)->only([
            'postular', 'seleccionarPerfil', 'storeDocumentos', 'storeDocumentosLote', 'reutilizar',
            'enviarPostulacion', 'destroyDocumento', 'destroyPostulacion',
            'formalizacion', 'enviarFormalizacion',
        ]);
    }

    public function index(Request $request): View
    {
        $user = $this->currentUser();
        $filtros = $request->only(['competencia', 'regional_id', 'estado']);

        $convocatorias = $this->convocatoriaService->listarParaUsuario($user, $filtros);
        $misPostulaciones = $this->consultaService->listarPostulacionesConvocatoria($user);

        $tarjetas = $convocatorias->map(function ($conv) use ($user, $misPostulaciones) {
            return [
                'conv' => $conv,
                'postulacionUsuario' => $misPostulaciones->firstWhere('convocatoria_id', $conv->id),
                'puedePostular' => Gate::forUser($user)->allows(self::PERMISO_SUBIR_DOCUMENTO_BANCO)
                    && $this->reglasService->puedePostularUsuario($user, $conv),
                'mensajeBloqueo' => $this->reglasService->mensajeBloqueoPostulacion($user, $conv),
            ];
        });

        return view('aitg.convocatorias.publicas.index', [
            'tarjetas' => $tarjetas,
            'regionales' => Regional::where('status', 1)->orderBy('nombre')->get(),
            'misPostulaciones' => $misPostulaciones,
            'filtros' => $filtros,
            'puedeVerBorrador' => Gate::forUser($user)->allows(self::PERMISO_VER_CONVOCATORIA),
        ]);
    }

    public function show(Convocatoria $convocatoria): View|RedirectResponse
    {
        $user = $this->currentUser();
        abort_unless($convocatoria->esVisiblePara($user), 404);

        $convocatoria->load([
            'competencia', 'plan.perfiles', 'plan.checklist', 'plan.puntosAdicionales',
            'regional', 'centroFormacion',
            'postulacionSeleccionada.user.persona', 'postulacionSeleccionada.perfilPlan',
        ]);

        $postulacion = $convocatoria->postulaciones()
            ->where('user_id', $user->id)
            ->first();

        $puedePostular = Gate::forUser($user)->allows(self::PERMISO_SUBIR_DOCUMENTO_BANCO)
            && $this->reglasService->puedePostularUsuario($user, $convocatoria);

        return view('aitg.convocatorias.publicas.show', [
            'convocatoria' => $convocatoria,
            'postulacion' => $postulacion,
            'puedePostular' => $puedePostular,
            'mensajeBloqueo' => $this->reglasService->mensajeBloqueoPostulacion($user, $convocatoria),
            'bancoHabilitado' => $this->consultaService->bancoHabilitadoParaPlan($user, $convocatoria->plan_contratacion_id),
            'mensajeBancoRecomendado' => $this->reglasService->mensajeBancoRecomendado($user, $convocatoria),
        ]);
    }

    public function postular(Convocatoria $convocatoria): View|RedirectResponse
    {
        $user = $this->currentUser();
        abort_unless($convocatoria->esVisiblePara($user), 404);

        $redirectBloqueo = $this->redirectSiNoPuedePostular($convocatoria, $user);
        if ($redirectBloqueo !== null) {
            return $redirectBloqueo;
        }

        try {
            return $this->vistaPostulacion($convocatoria, $user);
        } catch (\Throwable $e) {
            return $this->manejarErrorPostulacion($convocatoria, $user, $e);
        }
    }

    public function destroyPostulacion(Convocatoria $convocatoria): RedirectResponse
    {
        $user = $this->currentUser();
        $postulacion = $convocatoria->postulaciones()
            ->where('user_id', $user->id)
            ->firstOrFail();

        try {
            $this->talentoService->eliminarPostulacion($postulacion, $user);

            return redirect()->route('aitg.convocatorias.publicas.index')
                ->with('success', 'Su postulación fue eliminada. Puede volver a postular si la convocatoria sigue abierta.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage() ?: 'No se pudo eliminar la postulación.');
        }
    }

    public function seleccionarPerfil(Request $request, Convocatoria $convocatoria): RedirectResponse
    {
        $request->validate(['perfil_plan_id' => ['required', 'integer']]);
        $user = $this->currentUser();
        $convocatoria->load('plan');
        $postulacion = $this->talentoService->obtenerPostulacionConvocatoria($user, $convocatoria);
        $postulacion->setRelation('plan', $convocatoria->plan);

        if (! $postulacion->puedeEditar() || $postulacion->estado !== 'borrador') {
            return back()->with('error', 'No puede modificar el perfil en el estado actual.');
        }

        $this->talentoService->seleccionarPerfil($postulacion, (int) $request->input('perfil_plan_id'), $user);

        return back()->with('success', 'Perfil seleccionado. Solo puede postular a un perfil en el centro de formación de esta convocatoria.');
    }

    public function storeDocumentos(StoreDocumentoBancoRequest $request, Convocatoria $convocatoria): RedirectResponse
    {
        return $this->guardarDocumento($request, $convocatoria);
    }

    public function storeDocumentosLote(StoreBulkDocumentosBancoRequest $request, Convocatoria $convocatoria): RedirectResponse
    {
        $user = $this->currentUser();
        $postulacion = $this->talentoService->obtenerPostulacionConvocatoria($user, $convocatoria);

        if (! $postulacion->puedeEditar() || $postulacion->requierePerfil()) {
            return back()->with('error', 'Seleccione el perfil y verifique que puede editar la postulación.');
        }

        try {
            $subidos = $this->talentoService->subirArchivosLote($postulacion, $request->file('archivos', []), $user);

            return back()->with('success', $subidos > 0
                ? "Se cargaron {$subidos} documento(s) en su Banco de Talento."
                : 'No se recibieron archivos válidos.');
        } catch (\Throwable $e) {
            Log::error('AITG convocatoria lote', ['error' => $e->getMessage()]);

            return back()->with('error', 'No se pudieron cargar los documentos.');
        }
    }

    public function destroyDocumento(Convocatoria $convocatoria, PostulacionArchivo $postulacionArchivo): RedirectResponse
    {
        $user = $this->currentUser();
        $postulacion = $this->talentoService->obtenerPostulacionConvocatoria($user, $convocatoria);

        try {
            $this->talentoService->eliminarDocumentoPostulacion($postulacion, $postulacionArchivo, $user);

            return back()->with('success', 'Documento eliminado. Puede cargar uno nuevo.');
        } catch (\Throwable $e) {
            Log::error('AITG convocatoria eliminar doc', ['error' => $e->getMessage()]);

            return back()->with('error', 'No se pudo eliminar el documento.');
        }
    }

    public function reutilizar(Request $request, Convocatoria $convocatoria): RedirectResponse
    {
        $request->validate(['archivo_talento_id' => ['required', 'integer', 'exists:aitg_archivos_talento,id']]);
        $user = $this->currentUser();
        $postulacion = $this->talentoService->obtenerPostulacionConvocatoria($user, $convocatoria);
        $archivo = ArchivoTalento::findOrFail($request->input('archivo_talento_id'));

        try {
            $this->talentoService->reutilizarArchivo($postulacion, $archivo, $user);

            return back()->with('success', 'Documento reutilizado desde su Banco de Talento.');
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo reutilizar el documento.');
        }
    }

    public function enviarPostulacion(Convocatoria $convocatoria): RedirectResponse
    {
        $user = $this->currentUser();
        $postulacion = $this->talentoService->obtenerPostulacionConvocatoria($user, $convocatoria);

        if (! $postulacion->puedeEditar()) {
            return back()->with('error', 'La postulación no puede enviarse en este estado.');
        }

        if (! $this->envioService->puedeEnviar($postulacion, $user)) {
            return back()->with('error', 'Complete el perfil y todos los documentos obligatorios pendientes de corrección.');
        }

        $this->talentoService->enviarRevision($postulacion, $user);

        return redirect()->route('aitg.convocatorias.publicas.show', $convocatoria)
            ->with('success', 'Postulación enviada correctamente a revisión documental.');
    }

    public function formalizacion(Convocatoria $convocatoria): View|RedirectResponse
    {
        $user = $this->currentUser();
        abort_unless($convocatoria->esVisiblePara($user), 404);

        $postulacion = $convocatoria->postulaciones()
            ->where('user_id', $user->id)
            ->first();

        if (! $postulacion || ! $postulacion->esEnFormalizacion()) {
            return redirect()->route('aitg.convocatorias.publicas.show', $convocatoria)
                ->with('error', 'Esta convocatoria no requiere formalización documental en su estado actual.');
        }

        $convocatoria->load(['competencia', 'plan.perfiles', 'centroFormacion']);
        $postulacion->load(['perfilPlan', 'plan.competencia']);
        $postulacion->setRelation('convocatoria', $convocatoria);

        return view('aitg.convocatorias.publicas.formalizacion', [
            'convocatoria' => $convocatoria,
            'plan' => $convocatoria->plan,
            'postulacion' => $postulacion,
            'persona' => $user->persona,
            'secciones' => $this->envioService->seccionesDocumentales($postulacion, $user),
            'puedeEnviar' => $this->envioService->puedeEnviar($postulacion, $user),
        ]);
    }

    public function enviarFormalizacion(Convocatoria $convocatoria): RedirectResponse
    {
        $user = $this->currentUser();
        $postulacion = $convocatoria->postulaciones()
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (! $postulacion->puedeEditar() || ! $postulacion->esEnFormalizacion()) {
            return back()->with('error', 'No puede enviar la formalización en el estado actual.');
        }

        if (! $this->envioService->puedeEnviar($postulacion, $user)) {
            return back()->with('error', 'Complete todos los documentos obligatorios de formalización.');
        }

        try {
            $this->talentoService->enviarFormalizacion($postulacion, $user);
            $flash = ['success', 'Documentos de formalización enviados a revisión.'];
            $redirect = redirect()->route('aitg.convocatorias.publicas.show', $convocatoria);
        } catch (\InvalidArgumentException $e) {
            $flash = ['error', $e->getMessage()];
            $redirect = back();
        }

        return $redirect->with($flash[0], $flash[1]);
    }

    private function guardarDocumento(StoreDocumentoBancoRequest $request, Convocatoria $convocatoria): RedirectResponse
    {
        $user = $this->currentUser();
        $postulacion = $this->talentoService->obtenerPostulacionConvocatoria($user, $convocatoria);

        $error = null;
        if (! $postulacion->puedeEditar()) {
            $error = 'No puede modificar documentos en el estado actual.';
        } elseif ($postulacion->requierePerfil() && $postulacion->faseDocumental() === 'inicial') {
            $error = 'Seleccione el perfil antes de cargar documentos.';
        }

        if ($error !== null) {
            return back()->with('error', $error);
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
            return back()->with('error', 'No se pudo cargar el documento.');
        }
    }

    private function currentUser(): User
    {
        return Auth::user() ?? abort(403);
    }

    private function redirectSiNoPuedePostular(Convocatoria $convocatoria, User $user): ?RedirectResponse
    {
        $postulacionExistente = $convocatoria->postulaciones()
            ->where('user_id', $user->id)
            ->first();

        if (! $postulacionExistente && ! $this->reglasService->puedePostularUsuario($user, $convocatoria)) {
            $mensaje = $this->reglasService->mensajeBloqueoPostulacion($user, $convocatoria)
                ?? 'Esta convocatoria no está abierta para postulaciones.';

            return $this->redirectShowError($convocatoria, $mensaje);
        }

        if (! $convocatoria->puedePostular() && ! $postulacionExistente?->puedeEditar()) {
            return $this->redirectShowError($convocatoria, 'Esta convocatoria no está abierta para postulaciones.');
        }

        return null;
    }

    private function vistaPostulacion(Convocatoria $convocatoria, User $user): View
    {
        $convocatoria->load(['competencia', 'plan.perfiles', 'plan.checklist', 'plan.puntosAdicionales', 'centroFormacion']);
        $plan = $convocatoria->plan;

        $postulacion = $this->talentoService->obtenerPostulacionConvocatoria($user, $convocatoria);
        $postulacion->setRelation('plan', $plan);
        $postulacion->setRelation('convocatoria', $convocatoria);

        return view('aitg.convocatorias.publicas.postulacion', [
            'convocatoria' => $convocatoria,
            'plan' => $plan,
            'postulacion' => $postulacion,
            'persona' => $user->persona,
            'secciones' => $this->envioService->seccionesDocumentales($postulacion, $user),
            'puedeEnviar' => $this->envioService->puedeEnviar($postulacion, $user),
            'requiereDocumentosBase' => $this->envioService->requiereDocumentosBaseEnConvocatoria($postulacion, $user),
            'bancoHabilitado' => (bool) $this->consultaService->bancoHabilitadoParaPlan($user, $convocatoria->plan_contratacion_id),
            'etiquetaBloque' => fn (int $n, int $total) => $this->catalogoService->etiquetaBloque($plan, $n, $total),
        ]);
    }

    private function redirectShowError(Convocatoria $convocatoria, ?string $mensaje): RedirectResponse
    {
        return redirect()->route('aitg.convocatorias.publicas.show', $convocatoria)
            ->with('error', $mensaje ?? 'No fue posible continuar.');
    }

    private function manejarErrorPostulacion(Convocatoria $convocatoria, User $user, \Throwable $e): RedirectResponse
    {
        if ($e instanceof ValidationException) {
            $mensaje = collect($e->errors())->flatten()->first();
        } elseif ($e instanceof \InvalidArgumentException) {
            $mensaje = $e->getMessage();
        } else {
            Log::error('Error al postular en convocatoria AITG', [
                'convocatoria_id' => $convocatoria->id,
                'user_id' => $user->id,
                'exception' => $e,
            ]);
            $mensaje = 'No fue posible iniciar la postulación. Si el problema continúa, contacte al administrador.';
        }

        return $this->redirectShowError($convocatoria, is_string($mensaje) ? $mensaje : null);
    }
}
