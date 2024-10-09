@extends('layout.master-layout')
@section('content')
<div class="content-wrapper mt-3">
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-body">
                    <a class="btn btn-warning btn-sm" href="{{route('caracterizacion.index')}}">
                        <i class="fas fa-arrow-left"></i>
                        Volver
                    </a>
                </div>
                <div class="card-body"></div>
                    <form action="{{route('caracterizacion.ficha')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ficha_id">Ficha de Caracterización</label>
                                    <input type="hidden" name="ficha_id" id="ficha_id" value="{{$ficha->id}}">
                                    <input type="text" class="form-control" value="{{$ficha->ficha}}" readonly disabled>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ficha_id">Programa de Formación</label>
                                    <input type="hidden" name="programa_id" id="programa_id" value="{{$ficha->programaFormacion->id}}">
                                    <input type="text" class="form-control" value="{{$ficha->programaFormacion->nombre}}" readonly disabled>
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ficha_id">Sede</label>
                                    <input type="hidden" name="sede_id" id="sede_id" value="{{$ficha->id}}">
                                    <input type="text" class="form-control" value="{{$sede->sede}}" readonly disabled>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ficha_id">Jornada de formación</label>
                                    <select name="jorna_id"  class="form-control select2" id="ficha_id" required>
                                        <option value="">Seleccione una ficha</option>
                                        @if(count($jornadas) > 0)
                                            @foreach($jornadas as $jornada)
                                                <option value="{{ $jornada->id }}">{{ $jornada->jornada }}</option>
                                            @endforeach
                                        @else
                                            <option value="">No hay Jornadas disponibles</option>
                                        @endif
                                    </select>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="persona_id">Instructor</label>
                                <select name="persona_id" class="form-control select2" id="persona_id" required>
                                    <option value="">Seleccionar instructor</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->persona_id }}">{{ $instructor->persona->primer_nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" style="margin-top: 32px;">Guardar</button>
                                </div>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function() {
        $('#persona_id').select2({
            tags: true,
            placeholder: 'Selecciona o escribe un nombre',
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

        $('#persona_id').on('select2:open', function() {
            $('#input-group').show();
        });

        $('#persona_id').on('input', function() {
            var searchTerm = $(this).val().toLowerCase();
            $('#persona_id').val(null).trigger('change'); // Clear the select2 value
            $('#persona_id option').each(function() {
                var optionText = $(this).text().toLowerCase();
                if (optionText.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        $('#persona_id').on('keypress', function(e) {
            if (e.which == 13) { 
                var newFicha = $(this).val();
                if (newFicha) {
                    var select = $('#persona_id');
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
