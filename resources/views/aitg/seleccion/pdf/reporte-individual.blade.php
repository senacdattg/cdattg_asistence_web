<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte individual de selección</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #222; }
        h1 { font-size: 16px; margin: 0 0 4px; text-align: center; }
        h2 { font-size: 13px; margin: 14px 0 6px; border-bottom: 1px solid #999; padding-bottom: 3px; }
        .subtitle { text-align: center; color: #555; margin-bottom: 14px; font-size: 10px; }
        .meta p { margin: 2px 0; }
        .justificacion { text-align: justify; line-height: 1.4; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th, td { border: 1px solid #ccc; padding: 4px 5px; text-align: left; vertical-align: top; }
        th { background: #f2f2f2; font-size: 10px; }
        .badge {
            display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 9px; font-weight: bold;
        }
        .badge-ok { background: #d4edda; color: #155724; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        .badge-warn { background: #fff3cd; color: #856404; }
        .badge-muted { background: #e2e3e5; color: #383d41; }
        .highlight { background: #e8f0fe; }
        .footer { margin-top: 18px; font-size: 9px; color: #666; text-align: center; }
    </style>
</head>
<body>
    @php $c = $reporte['candidato']; @endphp
    <h1>Reporte individual de justificación de selección</h1>
    <div class="subtitle">
        {{ $reporte['convocatoria']['codigo'] ?? '—' }} — {{ $reporte['convocatoria']['titulo'] }}<br>
        Generado el {{ $reporte['generado_en']->format('d/m/Y H:i') }}
    </div>

    @php
        $badgeClass = match($c['resultado_clave']) {
            'seleccionado' => 'badge-ok',
            'suplente' => 'badge-info',
            'pendiente_seleccion' => 'badge-warn',
            default => 'badge-muted',
        };
    @endphp

    <div class="meta">
        <p><strong>Candidato:</strong> {{ $c['nombre'] }}</p>
        <p><strong>Documento:</strong> {{ $c['documento'] }}</p>
        <p><strong>Perfil:</strong> {{ $c['perfil'] }}</p>
        <p><strong>Resultado:</strong> <span class="badge {{ $badgeClass }}">{{ $c['resultado'] }}</span></p>
        <p><strong>Posición ranking:</strong> #{{ $c['posicion'] }}</p>
        <p>
            <strong>Puntajes:</strong>
            Checklist {{ $c['puntaje_checklist'] }}% ·
            Bonus {{ $c['puntaje_adicionales'] }} ·
            Total {{ $c['puntaje_total'] }}
        </p>
    </div>

    <h2>Justificación detallada</h2>
    <p class="justificacion">{{ $c['justificacion'] }}</p>

    <h2>Criterios del checklist</h2>
    <table>
        <thead>
            <tr>
                <th>Criterio</th>
                <th>Obligatorio</th>
                <th>Resultado</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($c['criterios'] as $crit)
                <tr>
                    <td>{{ $crit['nombre'] }}</td>
                    <td>{{ $crit['obligatorio'] ? 'Sí' : 'No' }}</td>
                    <td>
                        @if($crit['cumple'] === true) Cumple
                        @elseif($crit['cumple'] === false) No cumple
                        @else —
                        @endif
                    </td>
                    <td>{{ $crit['observaciones'] ?: '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if(count($c['puntos_adicionales']) > 0)
        <h2>Puntos adicionales</h2>
        <table>
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Puntaje</th>
                    <th>Resultado</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($c['puntos_adicionales'] as $punto)
                    <tr>
                        <td>{{ $punto['descripcion'] }}</td>
                        <td>+{{ number_format($punto['puntaje'], 2) }}</td>
                        <td>
                            @if(! $punto['tiene_evidencia']) Sin evidencia
                            @elseif($punto['cumple'] === true) Cumple
                            @elseif($punto['cumple'] === false) No cumple
                            @else —
                            @endif
                        </td>
                        <td>{{ $punto['observaciones'] ?: '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2>Contexto del ranking</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Documento</th>
                <th>Nombre</th>
                <th>Puntaje</th>
                <th>Resultado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reporte['contexto_ranking'] as $row)
                <tr class="{{ $row['documento'] === $c['documento'] ? 'highlight' : '' }}">
                    <td>{{ $row['posicion'] }}</td>
                    <td>{{ $row['documento'] }}</td>
                    <td>{{ $row['nombre'] }}</td>
                    <td>{{ $row['puntaje_total'] }}</td>
                    <td>{{ $row['resultado'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Documento generado automáticamente por el módulo AITG — Selección.
    </div>
</body>
</html>
