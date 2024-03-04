@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ request()->path() }}


                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                {{-- <a href="{{ route('home.index') }}">Inicio</a> --}}
                            </li>
                            <li class="breadcrumb-item active">{{ request()->path() }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="card">
                <div class="card-header">
                    {{-- <h3 class="card-title">{{ request()->path() }}</h3> --}}
                    {{-- formulario de registro --}}
                    <h1>Crear piso</h1>
                    <form action="{{ route('ambiente.store') }}" method="post">
                        @csrf

                        {{-- Tipo de Documento y Número de Documento --}}
                        <div class="row">
                            <div class="col-md-6">
                                <label for="descripcion">Descripcion</label>
                                <input type="text" class="form-control" value="{{ old('descripcion') }}"
                                    name="descripcion" placeholder="Descripion del piso" required autofocus>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="piso_id">Seleccione el piso</label>
                                <select name="piso_id" id="piso_id" class="form-control">
                                    @forelse ($pisos as $bloque)
                                        <option value="{{ $bloque->id }}">{{ $bloque->descripcion }}</option>
                                    @empty
                                        <p>No hay pisos disponibles</p>
                                    @endforelse
                                </select>

                            </div>
                        </div>


                        {{-- Botón de Registro --}}
                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="submit" class="btn btn-primary btn-lg">Crear Ambiente</button>
                        </div>
                    </form>

                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')

<script>
    <script>
    $(document).ready(function () {
        $('#piso_id').on('change', function () {
            var pisoId = $(this).val();

            $.ajax({
                url: '/cargar-ambientes-piso/' + pisoId,
                type: 'GET',
                success: function (data) {
                    // Limpiar y llenar el select de ambientes
                    $('#ambiente_id').empty();
                    $.each(data, function (key, value) {
                        $('#ambiente_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>
</script>
@endsection
