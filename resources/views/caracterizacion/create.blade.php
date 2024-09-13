
@extends('layout.master-layout')
@section('content')
<div class="content-wrapper">

    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-body">
                    <a class="btn btn-warning btn-sm" href="">
                        <i class="fas fa-arrow-left"></i>
                        </i>
                        Volver
                    </a>
                </div>
                <div class="card-body"></div>
                    <form action="" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nombre_programa">Nombre del Programa</label>
                            <input type="text" name="nombre_programa" class="form-control" id="nombre_programa" required>
                        </div>
                        <div class="form-group">
                            <label for="tipo_programa_id">Tipo programa</label>
                            <select name="tipo_programa_id" class="form-control" id="tipo_programa_id" required>
                                <option value="">Tipo de programa</option>
                                <!-- Add options dynamically from the database -->
                                
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sede_id">Sede</label>
                            <select name="sede_id" class="form-control" id="sede_id" required>
                                <option value="">Seleccione una sede</option>
                                <!-- Add options dynamically from the database -->
                                
                                
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>

            </div>
        </div>
    </section>
</div>
@endsection

