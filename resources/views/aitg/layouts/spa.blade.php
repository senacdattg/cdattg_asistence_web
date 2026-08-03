{{--
  Shell SPA AITG
  - Página completa: AdminLTE + JS SPA
  - Header X-AITG-SPA: 1 → fragmento HTML liviano (navegación rápida)
--}}
@php
    $aitgSpaFragment = \App\Support\Aitg\AitgSpaRequest::isFragment();
    $aitgPageTitle = trim($__env->yieldContent('title'));
    if ($aitgPageTitle === '') {
        $aitgPageTitle = 'AITG';
    }
@endphp

@extends($aitgSpaFragment ? 'aitg.layouts.fragment' : 'adminlte::page')

@section('css')
    @unless($aitgSpaFragment)
        <x-vite-stylesheet paths="resources/css/aitg/planes-contratacion/app.css" />
        @stack('aitg_css')
    @endunless
@endsection

@section('content')
    <div id="aitg-spa-root"
         data-aitg-spa-root
         @if($aitgSpaFragment) data-aitg-spa-fragment="1" @endif
         data-aitg-title="{{ e($aitgPageTitle) }}">
        <div class="aitg-spa-progress" data-aitg-spa-progress hidden></div>
        @include('aitg.partials.spa-subnav')
        @yield('aitg_header')
        <div class="aitg-spa-shell" data-aitg-spa-shell>
            @yield('aitg_content')
        </div>
        @if($aitgSpaFragment)
            <div data-aitg-spa-scripts hidden>
                @stack('aitg_js')
                @stack('js')
            </div>
        @endif
    </div>
@endsection

@section('js')
    @unless($aitgSpaFragment)
        @vite(['resources/js/aitg/spa.js'])
        @stack('aitg_js')
    @endunless
@endsection
