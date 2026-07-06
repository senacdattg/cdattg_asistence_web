<?php

namespace Database\Seeders\Biogjgas;

use App\Models\Biogjgas\BiogjgasBanner;
use App\Models\Biogjgas\BiogjgasSemillero;
use Illuminate\Database\Seeder;

class BiogjgasSemillerosSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->info('🌱 BIOGJGAS — semilleros y banners demo...');

        $semilleros = [
            [
                'slug' => 'scba',
                'nombre' => 'Semillero de Ciencias Básicas Aplicadas',
                'sigla' => 'SCBA',
                'icono' => 'fa-flask',
                'color_identidad' => '#1565c0',
                'resumen' => 'Investigación aplicada en biotecnología, química y microbiología para el sector productivo regional.',
                'descripcion' => 'El semillero SCBA promueve proyectos de investigación formativa en ciencias básicas con aplicación agroindustrial.',
                'mision' => 'Fortalecer competencias investigativas en ciencias básicas aplicadas al contexto del Guaviare.',
                'vision' => 'Ser referente regional en investigación formativa en ciencias básicas.',
                'objetivos' => [
                    'Desarrollar proyectos de investigación con aprendices e instructores.',
                    'Socializar resultados mediante eventos de divulgación científica.',
                ],
                'instructor_lider' => 'Por asignar',
                'correo_contacto' => 'biogjgas@sena.edu.co',
                'orden' => 1,
            ],
            [
                'slug' => 'sigemc',
                'nombre' => 'Semillero de Investigación en Gestión y Modelación de Datos',
                'sigla' => 'SIGEMC',
                'icono' => 'fa-database',
                'color_identidad' => '#6a1b9a',
                'resumen' => 'Analítica de datos, software aplicado y modelación para la gestión del conocimiento.',
                'descripcion' => 'SIGEMC articula proyectos de software, bases de datos y analítica orientados a problemas reales.',
                'mision' => 'Impulsar soluciones basadas en datos para la investigación y la gestión institucional.',
                'vision' => 'Consolidar un ecosistema de innovación digital en el área de investigación.',
                'objetivos' => [
                    'Implementar prototipos de software para procesos investigativos.',
                    'Capacitar a aprendices en gestión y análisis de información.',
                ],
                'instructor_lider' => 'Por asignar',
                'correo_contacto' => 'biogjgas@sena.edu.co',
                'orden' => 2,
            ],
            [
                'slug' => 'svse',
                'nombre' => 'Semillero de Valor Social y Sostenibilidad Empresarial',
                'sigla' => 'SVSE',
                'icono' => 'fa-leaf',
                'color_identidad' => '#2e7d32',
                'resumen' => 'Emprendimiento social, economía circular y sostenibilidad en comunidades y empresas.',
                'descripcion' => 'SVSE investiga prácticas sostenibles y modelos de valor social en el territorio.',
                'mision' => 'Generar conocimiento aplicado en sostenibilidad y responsabilidad social.',
                'vision' => 'Impulsar iniciativas que conecten investigación, comunidad y sostenibilidad.',
                'objetivos' => [
                    'Documentar experiencias de sostenibilidad en el territorio.',
                    'Acompañar emprendimientos con enfoque social.',
                ],
                'instructor_lider' => 'Por asignar',
                'correo_contacto' => 'biogjgas@sena.edu.co',
                'orden' => 3,
            ],
            [
                'slug' => 'siamt',
                'nombre' => 'Semillero de Innovación Agroindustrial y Materiales',
                'sigla' => 'SIAMT',
                'icono' => 'fa-industry',
                'color_identidad' => '#ef6c00',
                'resumen' => 'Innovación en procesos agroindustriales, alimentos y materiales para la competitividad regional.',
                'descripcion' => 'SIAMT desarrolla proyectos de investigación aplicada en transformación agroindustrial y materiales.',
                'mision' => 'Aportar soluciones innovadoras al sector agroindustrial amazónico.',
                'vision' => 'Ser semillero líder en innovación agroindustrial en la Regional Guaviare.',
                'objetivos' => [
                    'Desarrollar prototipos y validaciones de procesos agroindustriales.',
                    'Articular investigación con el sector productivo.',
                ],
                'instructor_lider' => 'Por asignar',
                'correo_contacto' => 'biogjgas@sena.edu.co',
                'orden' => 4,
            ],
        ];

        foreach ($semilleros as $data) {
            BiogjgasSemillero::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'estado_publicacion' => 'publicado',
                    'publicado_en' => now(),
                ])
            );
        }

        BiogjgasBanner::updateOrCreate(
            ['titulo' => 'BIOGJGAS Guaviare — Investigación SENA'],
            [
                'subtitulo' => 'Conoce nuestros semilleros, proyectos y actividades de divulgación científica.',
                'orden' => 1,
                'estado_publicacion' => 'publicado',
                'publicado_en' => now(),
            ]
        );

        $this->command?->info('✅ BIOGJGAS — datos demo listos.');
        $this->command?->info('   Portal: /investigacion');
    }
}
