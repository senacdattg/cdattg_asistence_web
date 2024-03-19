@if (auth()->check())
    {{ return redirect('home') }}"
@else
    {{ return redirect('login') }}
@endif
