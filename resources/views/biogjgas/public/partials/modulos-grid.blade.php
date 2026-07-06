    <section class="mb-4">
        <h2 class="h4 font-weight-bold mb-3">Explora el área de investigación</h2>
        <div class="row">
            @foreach ([
                ['icon' => 'fa-building', 'titulo' => 'Presentación', 'texto' => 'Misión, visión y equipo institucional', 'url' => route('biogjgas.presentacion.show')],
            ] as $modulo)
                <div class="col-md-6 col-lg-4 mb-3">
                    <a href="{{ $modulo['url'] }}" class="text-decoration-none text-dark">
                        <div class="card border-0 shadow-sm h-100 biogjgas-modulo-card">
                            <div class="card-body text-center py-4">
                                <div class="biogjgas-modulo-card__icon mb-3">
                                    <i class="fas {{ $modulo['icon'] }}"></i>
                                </div>
                                <h3 class="h6 font-weight-bold">{{ $modulo['titulo'] }}</h3>
                                <p class="text-muted small mb-0">{{ $modulo['texto'] }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>