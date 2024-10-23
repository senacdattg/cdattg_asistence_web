@extends('layout.master-layout')
@section('content')
<div class="content-wrapper">
    <section class="content mt-3">
        <div class="card">
            <div class="card-header">
                <div class="card-body">
                    <a class="btn btn-warning btn-sm" href="{{ route('verificarLogin') }}">
                        <i class="fas fa-arrow-left"></i>
                        Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="qr-result" id="qr-result"></div>
                <h3>Tomar asistencia</h3>
                <div style="display: flex; justify-content: center;">
                    <div id="qr-lector" style="width: 40%; margin-bottom: 3%;"></div>
                </div>
                <form id="asistencia-form" action="{{route('asistence.store')}}" method="POST">
                    @csrf
                    <div class="card">
                        <ul id="asistencia-list"></ul>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Guardar Asistencia</button>
                </form>
            </div>
        </div>
    </section>
</div>
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function domIsReady(fn) {
        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            setTimeout(fn, 1);
        } else {
            document.addEventListener("DOMContentLoaded", fn);
        }
    }

    domIsReady(() => {
        let thisQr = document.getElementById('qr-result');
        let asistenciaList = document.getElementById('asistencia-list');
        let lastResult, countResults = 0;

        function onScanSuccess(decodedText, decodedResult) {
            if (decodedText !== lastResult) {
                ++countResults;
                lastResult = decodedText;

                // Dividir el texto escaneado por el delimitador "|"
                let parts = decodedText.split('|');
                let nombre = parts[0] ? parts[0].trim() : 'N/A';
                let apellidos = parts[1] ? parts[1].trim() : 'N/A';
                let identificacion = parts[2] ? parts[2].trim() : 'N/A';

                // Mostrar los valores en la lista
                let listItem = document.createElement('li');
                listItem.innerHTML = `
                    <strong>Nombre:</strong> ${nombre} <br>
                    <strong>Apellidos:</strong> ${apellidos} <br>
                    <strong>Identificación:</strong> ${identificacion}
                `;
                asistenciaList.appendChild(listItem);

                // Añadir un campo oculto al formulario con el valor escaneado
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'asistencia[]';
                input.value = decodedText;
                document.getElementById('asistencia-form').appendChild(input);
            }
        }

        let htmlScan = new Html5QrcodeScanner("qr-lector", { fps: 10, qrbox: 250 });
        htmlScan.render(onScanSuccess);
    });
</script>
@endsection