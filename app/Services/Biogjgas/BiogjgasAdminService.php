<?php

namespace App\Services\Biogjgas;

use App\Models\Biogjgas\BiogjgasBanner;
use App\Models\Biogjgas\BiogjgasSemillero;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class BiogjgasAdminService
{
    public function semillerosPaginados(int $perPage = 15): LengthAwarePaginator
    {
        return BiogjgasSemillero::query()
            ->orderBy('orden')
            ->orderBy('nombre')
            ->paginate($perPage);
    }

    public function bannersOrdenados(): Collection
    {
        return BiogjgasBanner::query()
            ->orderBy('orden')
            ->orderByDesc('created_at')
            ->get();
    }

    public function crearSemillero(array $data, ?int $userId = null): BiogjgasSemillero
    {
        return BiogjgasSemillero::create($this->prepararSemillero($data, $userId));
    }

    public function actualizarSemillero(BiogjgasSemillero $semillero, array $data, ?int $userId = null): BiogjgasSemillero
    {
        $semillero->update($this->prepararSemillero($data, $userId, $semillero));

        return $semillero->fresh();
    }

    public function crearBanner(array $data, ?int $userId = null): BiogjgasBanner
    {
        return BiogjgasBanner::create($this->prepararBanner($data, $userId));
    }

    public function actualizarBanner(BiogjgasBanner $banner, array $data, ?int $userId = null): BiogjgasBanner
    {
        $banner->update($this->prepararBanner($data, $userId, $banner));

        return $banner->fresh();
    }

    private function prepararSemillero(array $data, ?int $userId, ?BiogjgasSemillero $existente = null): array
    {
        $payload = [
            'slug' => Str::slug($data['slug'] ?? $data['sigla'] ?? ''),
            'nombre' => $data['nombre'],
            'sigla' => strtoupper($data['sigla']),
            'icono' => $this->normalizarIcono($data['icono'] ?? 'fa-flask'),
            'color_identidad' => $data['color_identidad'] ?? '#0f9d58',
            'resumen' => $data['resumen'] ?? null,
            'descripcion' => $data['descripcion'] ?? null,
            'mision' => $data['mision'] ?? null,
            'vision' => $data['vision'] ?? null,
            'objetivos' => $this->objetivosDesdeTexto($data['objetivos_texto'] ?? null),
            'instructor_lider' => $data['instructor_lider'] ?? null,
            'correo_contacto' => $data['correo_contacto'] ?? null,
            'orden' => (int) ($data['orden'] ?? 0),
            'estado_publicacion' => $data['estado_publicacion'] ?? 'borrador',
        ];

        if ($payload['estado_publicacion'] === 'publicado' && ($existente?->estado_publicacion !== 'publicado')) {
            $payload['publicado_en'] = now();
        }

        if ($userId) {
            $payload[$existente ? 'user_update_id' : 'user_create_id'] = $userId;
        }

        return $payload;
    }

    private function prepararBanner(array $data, ?int $userId, ?BiogjgasBanner $existente = null): array
    {
        $payload = [
            'titulo' => $data['titulo'],
            'subtitulo' => $data['subtitulo'] ?? null,
            'enlace' => $data['enlace'] ?? null,
            'orden' => (int) ($data['orden'] ?? 0),
            'vigente_desde' => $data['vigente_desde'] ?? null,
            'vigente_hasta' => $data['vigente_hasta'] ?? null,
            'estado_publicacion' => $data['estado_publicacion'] ?? 'borrador',
        ];

        if ($payload['estado_publicacion'] === 'publicado' && ($existente?->estado_publicacion !== 'publicado')) {
            $payload['publicado_en'] = now();
        }

        if ($userId) {
            $payload[$existente ? 'user_update_id' : 'user_create_id'] = $userId;
        }

        return $payload;
    }

    private function normalizarIcono(string $icono): string
    {
        $icono = trim($icono);

        return str_starts_with($icono, 'fa-') ? $icono : 'fa-'.$icono;
    }

    private function objetivosDesdeTexto(?string $texto): ?array
    {
        if ($texto === null || trim($texto) === '') {
            return null;
        }

        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $texto))));
    }
}
