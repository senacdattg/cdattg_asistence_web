@if (auth()->check())
    <script>
        window.location = "{{ route('home') }}";
    </script>
@else
    <script>
        window.location = "{{ route('login') }}";
    </script>
@endif
