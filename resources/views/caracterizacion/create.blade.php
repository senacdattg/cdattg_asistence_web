@extends('layout.master-layout')
@section('content')
<div class="content-wrapper">
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
                                    <select name="ficha_id" class="form-control select2" id="ficha_id" required>
                                        <option value="">Seleccione una ficha</option>
                                        @if(count($fichas) > 0)
                                            @foreach($fichas as $ficha)
                                                <option value="{{ $ficha->id }}">{{ $ficha->ficha }}</option>
                                            @endforeach
                                        @else
                                            <option value="">No hay fichas disponibles</option>
                                        @endif
                                    </select>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" style="margin-top: 32px;">Seleccionar</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#ficha_id').select2({
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

        $('#ficha_id').on('select2:open', function() {
            $('#input-group').show();
        });

     
        $('#ficha_input').on('input', function() {
            var searchTerm = $(this).val().toLowerCase();
            $('#ficha_id').val(null).trigger('change'); // Clear the select2 value
            $('#ficha_id option').each(function() {
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
                    var select = $('#ficha_id');
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