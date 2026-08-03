@php
    $aitgNavItems = \App\Support\Aitg\AitgSpaNavigation::visibleItems();
@endphp

@if(count($aitgNavItems) > 0)
<nav class="aitg-spa-nav" aria-label="Navegación AITG" data-aitg-spa-nav>
    <div class="aitg-spa-nav__track">
        @foreach($aitgNavItems as $item)
            <a href="{{ $item['href'] }}"
               class="aitg-spa-nav__item {{ $item['active'] ? 'is-active' : '' }}"
               data-aitg-spa-link
               data-aitg-key="{{ $item['key'] }}"
               data-aitg-spa-prefetch>
                <i class="fas {{ $item['icon'] }}" aria-hidden="true"></i>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</nav>
@endif
