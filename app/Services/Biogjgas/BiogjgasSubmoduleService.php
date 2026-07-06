<?php

namespace App\Services\Biogjgas;

use App\Models\Biogjgas\BiogjgasBoletin;
use App\Models\Biogjgas\BiogjgasPresentacion;
use App\Models\Biogjgas\BiogjgasRevistaEdicion;
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

    public function paginar(string $modelo, int $perPage = 15): LengthAwarePaginator
    {
        return $this->modelo($modelo)::query()
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function listarOrdenado(string $modelo): Collection
    {
        $query = $this->modelo($modelo)::query();

        if (in_array($modelo, ['revista', 'boletin'], true)) {
            $query->orderBy('orden')->orderByDesc('created_at');
        } else {
            $query->orderBy('orden')->orderByDesc('created_at');
        }

        return $query->get();
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

    public function findOrFail(string $modelo, int $id): Model
    {
        return $this->modelo($modelo)::findOrFail($id);
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

    private function modelo(string $modelo): string
    {
        return match ($modelo) {
            'presentacion' => BiogjgasPresentacion::class,
            'revista' => BiogjgasRevistaEdicion::class,
            'boletin' => BiogjgasBoletin::class,
            default => throw new \InvalidArgumentException("Modelo BIOGJGAS no soportado: {$modelo}"),
        };
    }
}
