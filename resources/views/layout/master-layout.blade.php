@include('layout.header')
@include('layout.sibader-aside')
@yield('css')
<div class="main-content">
    @yield('content')
</div>
@include('layout.footer')
@yield('script')
