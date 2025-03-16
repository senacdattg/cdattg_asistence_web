<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="{{ route('verificarLogin') }}" class="brand-link">
        <img src="{{ asset('dist/img/LogoSena.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">Registro Asistencia </span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @php
                    $user = auth()->user();
                    $canVerParametro = $user->can('VER PARAMETRO');
                    $canVerTema = $user->can('VER TEMA');
                @endphp
                @if ($canVerParametro || $canVerTema)
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Panel de Control
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('VER PARAMETRO')
                                <li class="nav-item">
                                    <a href="{{ route('parametro.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Parámetros</p>
                                    </a>
                                </li>
                            @endcan
                            @can('VER TEMA')
                                <li class="nav-item">
                                    <a href="{{ route('tema.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Temas</p>
                                    </a>
                                </li>
                            @endcan
                            @haspermission('ASIGNAR PERMISOS')
                                {{-- @can('ASIGNAR PERMISOS') --}}
                                <li class="nav-item">
                                    <a href="{{ route('permiso.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Asignar Permisos</p>
                                    </a>
                                </li>
                                {{-- @endcan --}}
                            @endhaspermission
                        </ul>
                    </li>
                @endif
                {{-- gestion de regionales --}}
                @can('VER REGIONAL')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Administrar Regionales
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('regional.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Regionales</p>
                                </a>
                            </li>
                            @can('CREAR REGIONAL')
                                <li class="nav-item">
                                    <a href="{{ route('regional.create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Crear Regional</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                {{-- gestion de sedes --}}
                @can('VER SEDE')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Administrar Sedes
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('sede.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Sedes</p>
                                </a>
                            </li>
                            @can('CREAR SEDE')
                                <li class="nav-item">
                                    <a href="{{ route('sede.create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Crear Sede</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                {{-- Administrad bloques --}}
                @can('VER BLOQUE')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Administrar Bloques
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('bloque.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Bloques</p>
                                </a>
                            </li>
                            @can('CREAR BLOQUE')
                                <li class="nav-item">
                                    <a href="{{ route('bloque.create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Crear Bloque</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                {{-- Administrad bloques --}}
                @can('VER PISO')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Administrar Pisos
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('piso.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pisos</p>
                                </a>
                            </li>
                            @can('CREAR PISO')
                                <li class="nav-item">
                                    <a href="{{ route('piso.create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Crear Piso</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                {{-- Administrad bloques --}}
                @can('VER AMBIENTE')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Administrar Ambientes
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('ambiente.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Ambientes</p>
                                </a>
                            </li>
                            @can('CREAR AMBIENTE')
                                <li class="nav-item">
                                    <a href="{{ route('ambiente.create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Crear Ambiente</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                {{-- administrar instructores --}}
                @can('VER PROGRAMA DE CARACTERIZACION')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Administrar Personal
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('instructor.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Instructores</p>
                                </a>
                            </li>
                            @can('VER PROGRAMA DE CARACTERIZACION')
                                <li class="nav-item">
                                    <a href="{{ route('instructor.create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Crear Instructor</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('instructor.createImportarCSV') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Importar CSV</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('instructor.create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Crear Vigilante</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                {{-- ADMINISTRAR JORNADAS DE FORMACION --}}
                @can('VER PROGRAMA DE CARACTERIZACION')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Administrar Jornadas
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('jornada.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Ver Jornadas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('jornada.create') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Crear Jornada</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                {{-- FIN DE ADMINISTRAR JORNADAS DE FORMACIÓN --}}
                {{-- administrar fichas de caracrerización --}}
                {{-- @can('VER FICHA DE CARACTERIZACION')

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-users"></i>
                        <p>
                            Administrar fichas
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('fichaCaracterizacion.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fichas de caracterización</p>
                            </a>
                        </li>
                        @can('CREAR FICHA DE CARACTERIZACION')

                        <li class="nav-item">
                            <a href="{{ route('fichaCaracterizacion.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Crear Ficha</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan --}}

                {{-- administrar caracterizacion de programas --}}
                @can('VER PROGRAMA DE CARACTERIZACION')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Administrar Programas
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/programa/index" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Ver Programas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/programa/create" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Crear Programa</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                {{-- fichas caracterizacion --}}
                @can('VER PROGRAMA DE CARACTERIZACION')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Administrar Fichas
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('ficha.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Ver Fichas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('fichaCaracterizacion.create') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Crear Fichas</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                {{-- caracterizacion de programas --}}
                @can('VER PROGRAMA DE CARACTERIZACION')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Admin Caracterización
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('caracter.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Ver Caracterización</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('caracterizacion.create') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Crear Caracterización</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                {{-- ADMMINISTRACION DE ASISTENCIAS PARA ADMINISTRADOR --}}
                @can('VER PROGRAMA DE CARACTERIZACION')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Administrar Asistencias
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('asistencia.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Consultas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Consulta Personalizada</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                {{-- FIN ADMMINISTRACION DE ASISTENCIAS PARA ADMINISTRADOR --}}

                {{-- INICIO ADMMINISTRACION DE ASISTENCIAS PARA INSTRUCTORES --}}
                @can('TOMAR ASISTENCIA')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Administrar Asistencias
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('asistencia.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Consultas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Consulta Personalizada</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                {{-- FIN ADMMINISTRACION DE ASISTENCIAS PARA INSTRUCTORES --}}

                {{-- ADMINISTRACIÓN DE CARNETS QR --}}
                @can('VER PROGRAMA DE CARACTERIZACION')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Administrar Carnet
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('carnet.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Crear Carnet</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('TOMAR ASISTENCIA')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Tomar Asistencia
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('asistence.web') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Asistencia</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                {{-- FIN ADMINISTRACIÓN CARNET QR --}}
            </ul>
        </nav>
    </div>
</aside>
