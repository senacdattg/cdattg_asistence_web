@props(['semillero'])

<a href="{{ route('biogjgas.semilleros.show', $semillero) }}"
   class="biogjgas-semillero-card text-decoration-none"
   data-show-preloader
   title="Ver {{ $semillero->nombre }}">
    <div class="biogjgas-semillero-card__icon"
         style="--semillero-color: {{ $semillero->color_identidad }};">
        <i class="fas {{ $semillero->icono }}"></i>
    </div>
    <div class="biogjgas-semillero-card__sigla">{{ $semillero->sigla }}</div>
    <div class="biogjgas-semillero-card__nombre">{{ $semillero->nombre }}</div>
    @if ($semillero->resumen)
        <p class="biogjgas-semillero-card__resumen">{{ \Illuminate\Support\Str::limit($semillero->resumen, 90) }}</p>
    @endif
    <span class="biogjgas-semillero-card__cta">
        Ver semillero <i class="fas fa-arrow-right ml-1"></i>
    </span>
</a>
