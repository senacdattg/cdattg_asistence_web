<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte general de selección</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #222; }
        h1 { font-size: 16px; margin: 0 0 4px; text-align: center; }
        h2 { font-size: 13px; margin: 16px 0 6px; border-bottom: 1px solid #999; padding-bottom: 3px; }
        .subtitle { text-align: center; color: #555; margin-bottom: 14px; font-size: 10px; }
        .meta p { margin: 2px 0; }
        .candidato { margin-bottom: 14px; page-break-inside: avoid; }
        .badge {
            display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 9px; font-weight: bold;
        }
        .badge-ok { background: #d4edda; color: #155724; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        .badge-warn { background: #fff3cd; color: #856404; }
        .badge-muted { background: #e2e3e5; color: #383d41; }
        .justificacion { text-align: justify; margin-top: 4px; line-height: 1.35; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th, td { border: 1px solid #ccc; padding: 4px 5px; text-align: left; vertical-align: top; }
        th { background: #f2f2f2; font-size: 10px; }
        .scores { margin: 4px 0; }
        .footer { margin-top: 18px; font-size: 9px; color: #666; text-align: center; }
    </style>
</head>
<body>
    <h1>Reporte general de justificación de selección</h1>
    <div class="subtitle">
        {{ $reporte['convocatoria']['codigo'] ?? '—' }} — {{ $reporte['convocatoria']['titulo'] }}<br>
        Generado el {{ $reporte['generado_en']->format('d/m/Y H:i') }}
    </div>

    <div class="meta">
        <p><strong>Competencia:</strong> {{ $reporte['convocatoria']['competencia'] }}</p>
        <p><strong>Regional:</strong> {{ $reporte['convocatoria']['regional'] }}</p>
        <p><strong>Estado convocatoria:</strong> {{ $reporte['convocatoria']['estado_label'] }}</p>
        <p>
            <strong>Resumen:</strong>
            {{ $reporte['resumen']['total_candidatos'] }} candidatos —
            {{ $reporte['resumen']['seleccionados'] }} seleccionado(s),
            {{ $reporte['resumen']['suplentes'] }} suplente(s),
            {{ $reporte['resumen']['no_seleccionados'] }} no seleccionado(s)
        </p>
    </div>

    <h2>Candidatos y justificación</h2>

    @foreach($reporte['candidatos'] as $candidato)
        @php
            $badgeClass = match($candidato['resultado_clave']) {
                'seleccionado' => 'badge-ok',
                'suplente' => 'badge-info',
                'pendiente_seleccion' => 'badge-warn',
                default => 'badge-muted',
            };
        @endphp
        <div class="candidato">
            <strong>#{{ $candidato['posicion'] }} {{ $candidato['nombre'] }}</strong>
            ({{ $candidato['documento'] }})
            <span class="badge {{ $badgeClass }}">{{ $candidato['resultado'] }}</span>
            <div class="scores">
                Checklist: {{ $candidato['puntaje_checklist'] }}% ·
                Bonus: {{ $candidato['puntaje_adicionales'] }} ·
                Total: <strong>{{ $candidato['puntaje_total'] }}</strong>
            </div>
            <div class="justificacion">{{ $candidato['justificacion'] }}</div>
        </div>
    @endforeach

    <div class="footer">
        Documento generado automáticamente por el módulo AITG — Selección.
        Justifica la decisión de selección o no selección de cada candidato en la convocatoria.
    </div>
</body>
</html>
