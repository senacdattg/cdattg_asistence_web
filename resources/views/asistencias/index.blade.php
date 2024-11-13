@extends('layout.master-layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header mt-3">
        <div class="container-fluid mt-3">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Consultar Asistencias</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">Asistencias de formación
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content"></section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Consultar Asistencias por Fichas</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('asistencia.getAttendanceByFicha')}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="ficha">Número de Ficha:</label>
                                    <select name="ficha" id="ficha_id" class="form-control ficha_id">
                                        <option value="">Seleccione una ficha</option>
                                        @foreach($fichas as $ficha)
                                        <option value="{{ $ficha->id }}">{{ $ficha->ficha }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Consultar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
    </section>
    <section class="content"></section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Consultar Asistencias por Ficha y Fecha</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('asistencia.getAttendanceByDateAndFicha')}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="ficha">Número de Ficha:</label>
                                    <select name="ficha" id="ficha" class="form-control ficha_id">
                                        <option value="">Seleccione una ficha</option>
                                        @foreach($fichas as $ficha)
                                            <option value="{{ $ficha->id }}">{{ $ficha->ficha }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="fecha_inicio">Fecha Inicio:</label>
                                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="fecha_fin">Fecha Fin:</label>
                                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Consultar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content"></section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Consultar Asistencias por Ficha y Número de Documento</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('asistencia.getDocumentsByFicha')}}" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="ficha">Número de Ficha:</label>
                                        <select name="ficha" id="ficha_id" class="form-control ficha_id">
                                            <option value="">Seleccione una ficha</option>
                                            @foreach($fichas as $ficha)
                                                <option value="{{ $ficha->id }}">{{ $ficha->ficha }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Consultar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function() {
        $('.ficha_id').select2({
            tags: true,
            placeholder: 'Selecciona o escribe un número',
            allowClear: true,
            createTag: function(params) {
                var term = $.trim(params.term);
                if (term === '') {
                    return null;
                }
                return {
                    id: term,
                    text: term,
                    newTag: true // add additional parameters
                };
            }
        });

        $('.ficha_id').on('select2:open', function() {
            $('#input-group').show();
        });

     
        $('#ficha_input').on('input', function() {
            var searchTerm = $(this).val().toLowerCase();
            $('.ficha_id').val(null).trigger('change'); // Clear the select2 value
            $('.ficha_id option').each(function() {
                var optionText = $(this).text().toLowerCase();
                if (optionText.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        $('#ficha_input').on('keypress', function(e) {
            if (e.which == 13) { 
                var newFicha = $(this).val();
                if (newFicha) {
                    var select = $('.ficha_id');
                    if (select.find("option[value='" + newFicha + "']").length) {
                        select.val(newFicha).trigger('change');
                    } else {
                        var newOption = new Option(newFicha, newFicha, true, true);
                        select.append(newOption).trigger('change');
                    }
                    $(this).val(''); 
                }
                e.preventDefault(); 
            }
        });
    });
</script>
@endsection