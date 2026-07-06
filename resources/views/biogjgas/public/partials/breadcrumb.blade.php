@props(['items' => []])

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white shadow-sm rounded px-3 py-2 mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}"><i class="fas fa-home"></i> Inicio</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('biogjgas.home') }}">Investigación</a>
        </li>
        @foreach ($items as $item)
            @if ($loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $item['label'] }}</li>
            @else
                <li class="breadcrumb-item">
                    @if (!empty($item['url']))
                        <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                    @else
                        {{ $item['label'] }}
                    @endif
                </li>
            @endif
        @endforeach
    </ol>
</nav>
