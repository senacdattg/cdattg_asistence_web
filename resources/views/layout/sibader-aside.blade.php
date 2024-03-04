<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="../../index3.html" class="brand-link">
        <img src="../../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <div class="sidebar">

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                {{-- <img src="../../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> --}}
            </div>
            <div class="info">
                <a href="{{ route('persona.show', ['persona' => Auth::user()->persona->id]) }}" class="d-block">
                    {{ Auth::user()->persona->primer_nombre }} {{ Auth::user()->persona->primer_apellido }}

                </a>
            </div>
        </div>




        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Panel de control
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../index.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard v1</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../../index2.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard v2</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../../index3.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard v3</p>
                            </a>
                        </li>


                    </ul>
                </li>
                {{-- gestion de sedes --}}
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
                        <li class="nav-item">
                            <a href="{{ route('sede.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Crear sede</p>
                            </a>
                        </li>

                    </ul>
                </li>

                {{-- Administrad bloques --}}
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
                        <li class="nav-item">
                            <a href="{{ route('bloque.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Crear bloque</p>
                            </a>
                        </li>

                    </ul>
                </li>

                {{-- Administrad bloques --}}
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Administrar pisos
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('piso.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>pisos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('piso.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Crear piso</p>
                            </a>
                        </li>

                    </ul>
                </li>

                {{-- administrar instructores --}}
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-users"></i>
                        <p>
                            Administrar instructores
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('persona.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Instructores</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('persona.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Crear Instructor</p>
                            </a>
                        </li>


                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-users"></i>
                        <p>
                            Asistencia
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('fichaCaracterizacion.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Asistencia</p>
                            </a>
                        </li>

                    </ul>
                </li>

        </nav>

    </div>

</aside>
