@if ($banners->isNotEmpty())
    <section class="biogjgas-carousel mb-4">
        <div id="biogjgasCarousel" class="carousel slide shadow rounded overflow-hidden" data-ride="carousel">
            @if ($banners->count() > 1)
                <ol class="carousel-indicators">
                    @foreach ($banners as $banner)
                        <li data-target="#biogjgasCarousel" data-slide-to="{{ $loop->index }}"
                            class="{{ $loop->first ? 'active' : '' }}"></li>
                    @endforeach
                </ol>
            @endif
            <div class="carousel-inner">
                @foreach ($banners as $banner)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <div class="biogjgas-carousel__slide">
                            <div class="biogjgas-carousel__copy">
                                <span class="badge badge-light text-success mb-2">BIOGJGAS Guaviare</span>
                                <h2 class="h3 font-weight-bold mb-2">{{ $banner->titulo }}</h2>
                                @if ($banner->subtitulo)
                                    <p class="mb-0">{{ $banner->subtitulo }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($banners->count() > 1)
                <a class="carousel-control-prev" href="#biogjgasCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </a>
                <a class="carousel-control-next" href="#biogjgasCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </a>
            @endif
        </div>
    </section>
@endif
