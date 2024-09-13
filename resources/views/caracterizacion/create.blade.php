
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
                    <form action="" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="sede_id">Ficha de Caracterización</label>
                            <select name="programa_id" class="form-control" id="sede_id" required>
                                <option value="">Seleccionar ficha de caracterizacion</option>
                                
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="sede_id">Program de formación</label>
                            <select name="programa_id" class="form-control" id="sede_id" required>
                                <option value="">Seleccionar Program de formación</option>
                                
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sede_id">Instructor</label>
                            <select name="programa_id" class="form-control" id="sede_id" required>
                                <option value="">Seleccionar instructor</option>
                                
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sede_id">Jornada de formación</label>
                            <select name="programa_id" class="form-control" id="sede_id" required>
                                <option value="">Seleccionar Jornada</option>
                                
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sede_id">Sede</label>
                            <select name="programa_id" class="form-control" id="sede_id" required>
                                <option value="">Seleccionar sede</option>
                                
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


