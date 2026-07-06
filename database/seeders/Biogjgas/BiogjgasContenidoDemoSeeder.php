<?php

namespace Database\Seeders\Biogjgas;

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
use Illuminate\Database\Seeder;

class BiogjgasContenidoDemoSeeder extends Seeder
{
    public function run(): void
    {
        BiogjgasPresentacion::query()->firstOrCreate([], [
            'mision' => 'Fortalecer la cultura de investigación aplicada en el SENA Regional Guaviare.',
            'vision' => 'Ser referente regional en investigación, innovación y divulgación científica.',
            'objetivo_general' => 'Articular semilleros, proyectos y actividades de divulgación para el desarrollo regional.',
            'historia' => "BIOGJGAS Guaviare articula el trabajo de investigación del centro de formación.\nPromueve semilleros, publicaciones y actividades de divulgación científica.",
            'equipo' => [
                ['nombre' => 'Coordinación BIOGJGAS', 'cargo' => 'Coordinador de investigación', 'contacto' => 'investigacion@sena.edu.co'],
            ],
            'estado_publicacion' => 'publicado',
            'publicado_en' => now(),
        ]);

        BiogjgasRevistaEdicion::query()->firstOrCreate(['slug' => 'rupicola-vol1-2025'], [
            'titulo' => 'Revista Rupícola - Vol. 1',
            'volumen' => 1,
            'numero' => 1,
            'anio' => 2025,
            'editorial' => 'Primera edición de la revista científica del área de investigación BIOGJGAS Guaviare.',
            'issn' => '0000-0000',
            'articulos' => [
                ['titulo' => 'Innovación agroindustrial en la Amazonía', 'autores' => 'Equipo SIAMT', 'resumen' => 'Avances en procesos agroindustriales sostenibles.'],
                ['titulo' => 'Biotecnología aplicada', 'autores' => 'Equipo SCBA', 'resumen' => 'Resultados de investigación en ciencias básicas.'],
            ],
            'fecha_publicacion' => '2025-06-01',
            'orden' => 1,
            'estado_publicacion' => 'publicado',
            'publicado_en' => now(),
        ]);

        BiogjgasBoletin::query()->firstOrCreate(['titulo' => 'Boletín Divulgación #1'], [
            'numero' => '1',
            'fecha' => '2025-05-15',
            'resumen' => 'Resumen de actividades y logros del primer semestre de investigación.',
            'tematica' => 'Divulgación científica',
            'orden' => 1,
            'estado_publicacion' => 'publicado',
            'publicado_en' => now(),
        ]);

        BiogjgasPodcast::query()->firstOrCreate(['titulo' => 'Episodio 1: Semilleros en acción'], [
            'descripcion' => 'Conversatorio sobre el rol de los semilleros de investigación en la formación SENA.',
            'audio_url' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3',
            'duracion' => '25 min',
            'invitados' => 'Instructores líderes de semilleros',
            'fecha' => '2025-04-20',
            'orden' => 1,
            'estado_publicacion' => 'publicado',
            'publicado_en' => now(),
        ]);

        $scba = BiogjgasSemillero::query()->where('slug', 'scba')->first();

        BiogjgasConvocatoria::query()->firstOrCreate(['titulo' => 'Convocatoria integrantes semilleros 2025'], [
            'tipo' => 'Semillero',
            'descripcion' => 'Apertura para aprendices interesados en vincularse a los semilleros de investigación.',
            'requisitos' => "Ser aprendiz activo.\nCarta de motivación.",
            'fecha_apertura' => '2025-07-01',
            'fecha_cierre' => '2025-08-31',
            'estado_convocatoria' => 'abierta',
            'semillero_id' => $scba?->id,
            'orden' => 1,
            'estado_publicacion' => 'publicado',
            'publicado_en' => now(),
        ]);

        BiogjgasActividad::query()->firstOrCreate(['titulo' => 'Feria de proyectos investigativos'], [
            'tipo' => 'Feria',
            'fecha' => '2025-09-10',
            'lugar' => 'Centro de formación',
            'modalidad' => 'Presencial',
            'descripcion' => 'Socialización de proyectos en ejecución de los semilleros BIOGJGAS.',
            'estado_actividad' => 'programada',
            'semillero_id' => $scba?->id,
            'orden' => 1,
            'estado_publicacion' => 'publicado',
            'publicado_en' => now(),
        ]);

        if ($scba) {
            BiogjgasLineaInvestigacion::query()->firstOrCreate([
                'semillero_id' => $scba->id,
                'nombre' => 'Microbiología aplicada',
            ], [
                'descripcion' => 'Estudio de microorganismos con aplicación agroindustrial.',
                'orden' => 1,
                'estado_publicacion' => 'publicado',
            ]);

            BiogjgasIntegrante::query()->firstOrCreate([
                'semillero_id' => $scba->id,
                'nombre' => 'Aprendiz investigador demo',
            ], [
                'rol' => 'Aprendiz',
                'programa' => 'Tecnología en Agroindustria',
                'orden' => 1,
                'estado_publicacion' => 'publicado',
            ]);

            BiogjgasProyecto::query()->firstOrCreate([
                'semillero_id' => $scba->id,
                'titulo' => 'Caracterización de biomasa regional',
            ], [
                'descripcion' => 'Proyecto de investigación aplicada en biotecnología.',
                'estado_ejecucion' => 'en_ejecucion',
                'fecha_inicio' => '2025-01-15',
                'orden' => 1,
                'estado_publicacion' => 'publicado',
            ]);
        }
    }
}
