<?php

namespace App\Services\Biogjgas;

use App\Models\Biogjgas\BiogjgasActividad;
use App\Models\Biogjgas\BiogjgasBoletin;
use App\Models\Biogjgas\BiogjgasConvocatoria;
use App\Models\Biogjgas\BiogjgasIntegrante;
use App\Models\Biogjgas\BiogjgasLineaInvestigacion;
use App\Models\Biogjgas\BiogjgasPodcast;
use App\Models\Biogjgas\BiogjgasPresentacion;
use App\Models\Biogjgas\BiogjgasProyecto;
use App\Models\Biogjgas\BiogjgasRevistaEdicion;
use App\Models\Biogjgas\BiogjgasSemillero;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BiogjgasSubmoduleService
{
    public function presentacionPublica(): ?BiogjgasPresentacion
    {
        return BiogjgasPresentacion::query()->publicados()->first();
    }

    public function presentacionAdmin(): BiogjgasPresentacion
    {
        return BiogjgasPresentacion::query()->firstOrCreate([]);
    }

    public function revistaPublicada(): Collection
    {
        return BiogjgasRevistaEdicion::query()
            ->publicados()
            ->orderByDesc('anio')
            ->orderByDesc('numero')
            ->get();
    }

    public function revistaEdicionPorSlug(string $slug): BiogjgasRevistaEdicion
    {
        return BiogjgasRevistaEdicion::query()->publicados()->where('slug', $slug)->firstOrFail();
    }

    public function boletinesPublicados(): Collection
    {
        return BiogjgasBoletin::query()->publicados()->orderByDesc('fecha')->orderBy('orden')->get();
    }

    public function boletinPublicado(int $id): BiogjgasBoletin
    {
        return BiogjgasBoletin::query()->publicados()->findOrFail($id);
    }

    public function podcastsPublicados(): Collection
    {
        return BiogjgasPodcast::query()->publicados()->orderByDesc('fecha')->orderBy('orden')->get();
    }

    public function podcastPublicado(int $id): BiogjgasPodcast
    {
        return BiogjgasPodcast::query()->publicados()->findOrFail($id);
    }

    public function convocatoriasPublicadas(): Collection
    {
        return BiogjgasConvocatoria::query()
            ->publicados()
            ->with('semillero')
            ->orderByDesc('fecha_apertura')
            ->orderBy('orden')
            ->get();
    }

    public function convocatoriaPublicada(int $id): BiogjgasConvocatoria
    {
        return BiogjgasConvocatoria::query()->publicados()->with('semillero')->findOrFail($id);
    }

    public function actividadesPublicadas(): Collection
    {
        return BiogjgasActividad::query()
            ->publicados()
            ->with('semillero')
            ->orderByDesc('fecha')
            ->orderBy('orden')
            ->get();
    }

    public function actividadPublicada(int $id): BiogjgasActividad
    {
        return BiogjgasActividad::query()->publicados()->with('semillero')->findOrFail($id);
    }
(string $modelo, int $perPage = 15): LengthAwarePaginator
    {
        return $this->modelo($modelo)::query()
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function listarOrdenado(string $modelo): Collection
    {
        $query = $this->modelo($modelo)::query();

        if (in_array($modelo, ['revista', 'boletin', 'podcast', 'convocatoria', 'actividad'], true)) {
            $query->orderBy('orden')->orderByDesc('created_at');
        } else {
            $query->orderBy('orden')->orderBy('nombre');
        }

        return $query->get();
    }

    public function semillerosParaSelect(): Collection
    {
        return BiogjgasSemillero::query()->orderBy('nombre')->get(['id', 'sigla', 'nombre']);
    }

    public function guardar(string $modelo, array $data, ?int $userId = null, ?Model $existente = null): Model
    {
        $payload = $this->prepararPayload($modelo, $data, $userId, $existente);

        if ($existente) {
            $existente->update($payload);

            return $existente->fresh();
        }

        return $this->modelo($modelo)::create($payload);
    }

    public function eliminar(string $modelo, Model $registro): void
    {
        $registro->delete();
    }

    private function prepararPayload(string $modelo, array $data, ?int $userId, ?Model $existente): array
    {
        $payload = match ($modelo) {
            'presentacion' => [
                'mision' => $data['mision'] ?? null,
                'vision' => $data['vision'] ?? null,
                'objetivo_general' => $data['objetivo_general'] ?? null,
                'historia' => $data['historia'] ?? null,
                'video_url' => $data['video_url'] ?? null,
                'politicas_pdf' => $data['politicas_pdf'] ?? null,
                'equipo' => $this->equipoDesdeTexto($data['equipo_texto'] ?? null),
                'estado_publicacion' => $data['estado_publicacion'] ?? 'borrador',
            ],
            'revista' => [
                'slug' => Str::slug($data['slug'] ?? $data['titulo'].'-'.$data['anio']),
                'titulo' => $data['titulo'],
                'volumen' => $data['volumen'] ?? null,
                'numero' => $data['numero'] ?? null,
                'anio' => (int) $data['anio'],
                'portada_path' => $data['portada_path'] ?? null,
                'editorial' => $data['editorial'] ?? null,
                'issn' => $data['issn'] ?? null,
                'articulos' => $this->articulosDesdeTexto($data['articulos_texto'] ?? null),
                'fecha_publicacion' => $data['fecha_publicacion'] ?? null,
                'orden' => (int) ($data['orden'] ?? 0),
                'estado_publicacion' => $data['estado_publicacion'] ?? 'borrador',
            ],
            'boletin' => [
                'titulo' => $data['titulo'],
                'numero' => $data['numero'] ?? null,
                'fecha' => $data['fecha'] ?? null,
                'resumen' => $data['resumen'] ?? null,
                'pdf_path' => $data['pdf_path'] ?? null,
                'portada_path' => $data['portada_path'] ?? null,
                'tematica' => $data['tematica'] ?? null,
                'orden' => (int) ($data['orden'] ?? 0),
                'estado_publicacion' => $data['estado_publicacion'] ?? 'borrador',
            ],
            'podcast' => [
                'titulo' => $data['titulo'],
                'descripcion' => $data['descripcion'] ?? null,
                'audio_url' => $data['audio_url'] ?? null,
                'duracion' => $data['duracion'] ?? null,
                'invitados' => $data['invitados'] ?? null,
                'portada_path' => $data['portada_path'] ?? null,
                'fecha' => $data['fecha'] ?? null,
                'orden' => (int) ($data['orden'] ?? 0),
                'estado_publicacion' => $data['estado_publicacion'] ?? 'borrador',
            ],
            'convocatoria' => [
                'titulo' => $data['titulo'],
                'tipo' => $data['tipo'] ?? null,
                'descripcion' => $data['descripcion'] ?? null,
                'requisitos' => $data['requisitos'] ?? null,
                'fecha_apertura' => $data['fecha_apertura'] ?? null,
                'fecha_cierre' => $data['fecha_cierre'] ?? null,
                'documento_path' => $data['documento_path'] ?? null,
                'enlace_externo' => $data['enlace_externo'] ?? null,
                'estado_convocatoria' => $data['estado_convocatoria'] ?? 'proximamente',
                'semillero_id' => $data['semillero_id'] ?? null,
                'orden' => (int) ($data['orden'] ?? 0),
                'estado_publicacion' => $data['estado_publicacion'] ?? 'borrador',
            ],
            'actividad' => [
                'titulo' => $data['titulo'],
                'tipo' => $data['tipo'] ?? null,
                'fecha' => $data['fecha'] ?? null,
                'lugar' => $data['lugar'] ?? null,
                'modalidad' => $data['modalidad'] ?? null,
                'descripcion' => $data['descripcion'] ?? null,
                'semillero_id' => $data['semillero_id'] ?? null,
                'estado_actividad' => $data['estado_actividad'] ?? 'programada',
                'orden' => (int) ($data['orden'] ?? 0),
                'estado_publicacion' => $data['estado_publicacion'] ?? 'borrador',
            ],
            'linea' => [
                'semillero_id' => $data['semillero_id'],
                'nombre' => $data['nombre'],
                'descripcion' => $data['descripcion'] ?? null,
                'orden' => (int) ($data['orden'] ?? 0),
                'estado_publicacion' => $data['estado_publicacion'] ?? 'borrador',
            ],
            'integrante' => [
                'semillero_id' => $data['semillero_id'],
                'nombre' => $data['nombre'],
                'rol' => $data['rol'] ?? null,
                'programa' => $data['programa'] ?? null,
                'orden' => (int) ($data['orden'] ?? 0),
                'estado_publicacion' => $data['estado_publicacion'] ?? 'borrador',
            ],
            'proyecto' => [
                'semillero_id' => $data['semillero_id'],
                'titulo' => $data['titulo'],
                'descripcion' => $data['descripcion'] ?? null,
                'estado_ejecucion' => $data['estado_ejecucion'] ?? 'en_ejecucion',
                'fecha_inicio' => $data['fecha_inicio'] ?? null,
                'fecha_fin' => $data['fecha_fin'] ?? null,
                'orden' => (int) ($data['orden'] ?? 0),
                'estado_publicacion' => $data['estado_publicacion'] ?? 'borrador',
            ],
            default => $data,
        };

        return BiogjgasPublicacionHelper::preparar($payload, $userId, $existente);
    }

    private function equipoDesdeTexto(?string $texto): ?array
    {
        if ($texto === null || trim($texto) === '') {
            return null;
        }

        return array_values(array_filter(array_map(function (string $linea) {
            $partes = array_map('trim', explode('|', $linea, 3));

            if ($partes[0] === '') {
                return null;
            }

            return [
                'nombre' => $partes[0],
                'cargo' => $partes[1] ?? null,
                'contacto' => $partes[2] ?? null,
            ];
        }, preg_split('/\r\n|\r|\n/', $texto))));
    }

    private function articulosDesdeTexto(?string $texto): ?array
    {
        if ($texto === null || trim($texto) === '') {
            return null;
        }

        return array_values(array_filter(array_map(function (string $linea) {
            $partes = array_map('trim', explode('|', $linea, 3));

            if ($partes[0] === '') {
                return null;
            }

            return [
                'titulo' => $partes[0],
                'autores' => $partes[1] ?? null,
                'resumen' => $partes[2] ?? null,
            ];
        }, preg_split('/\r\n|\r|\n/', $texto))));
    }

    public function findOrFail(string $modelo, int $id): Model
    {
        return $this->modelo($modelo)::findOrFail($id);
    }

    private function modelo(string $modelo): string
    {
        return match ($modelo) {
            'presentacion' => BiogjgasPresentacion::class,
            'revista' => BiogjgasRevistaEdicion::class,
            'boletin' => BiogjgasBoletin::class,
            'podcast' => BiogjgasPodcast::class,
            'convocatoria' => BiogjgasConvocatoria::class,
            'actividad' => BiogjgasActividad::class,
            'linea' => BiogjgasLineaInvestigacion::class,
            'integrante' => BiogjgasIntegrante::class,
            'proyecto' => BiogjgasProyecto::class,
            default => throw new \InvalidArgumentException("Modelo BIOGJGAS no soportado: {$modelo}"),
        };
    }
}
