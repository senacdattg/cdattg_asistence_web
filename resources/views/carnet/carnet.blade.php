<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Carnet Digital SENA</title>
    <style>
        @font-face {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: normal;
            src: url({{ storage_path('fonts/Poppins-Regular.ttf') }}) format('truetype');
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        .card {
            width: 440px;
            height: 560px;
            background-color: #e1efda;
            border-radius: 10px;
            position: relative;
            margin: 0 auto;
        }

        .header-green {
            height: 80px;
            background-color: #4ef84e;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .logo-container {
            position: absolute;
            top: 15px;
            left: 15px;
            width: 80px;
            text-align: center;
        }

        .logo {
            width: 70px;
            height: auto;
        }

        .logo-text {
            font-size: 12px;
            color: #333333;
            margin-top: 5px;
            font-weight: bold;
        }

        .aprendiz-container {
            position: absolute;
            top: 20px;
            right: 30px;
            width: 140px;
            height: 130px;
            overflow: hidden;
            border-radius: 10px;
        }

        .aprendiz-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .info-container {
            padding: 20px;
            margin-top: 100px;
            text-align: center;
        }

        .aprendiz-title {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .separator {
            border: none;
            height: 2px;
            background-color: #39ff14;
            margin: 10px 0;
        }

        .cedula,
        .ficha,
        .programa {
            font-size: 14px;
            margin: 5px 0;
        }

        .nombre {
            font-weight: bold;
            font-size: 18px;
            margin: 10px 0;
        }

        .programa {
            font-size: 12px;
        }

        .qr-container {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 150px;
            height: 150px;
            background-color: rgba(78, 248, 78, 0.2);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .qr {
            width: 120px;
            height: 120px;
        }

        .qr-unavailable {
            font-size: 14px;
            color: #333;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="header-green"></div>
        <div class="logo-container">
            <img src="{{ public_path('img/sena-logo.png') }}" alt="Logo SENA" class="logo">
            <div class="logo-text">Regional Guaviare</div>
        </div>
        <div class="aprendiz-container">
            @if (isset($photo))
                <img src="data:image/jpeg;base64,{{ $photo }}" alt="Foto del Aprendiz" class="aprendiz-img">
            @else
                <img src="{{ public_path('img/default-photo.jpg') }}" alt="Foto por defecto" class="aprendiz-img">
            @endif
        </div>
        <div class="info-container">
            <h2 class="aprendiz-title">Aprendiz</h2>
            <hr class="separator">
            @isset($aprendiz)
                <p class="nombre">{{ $aprendiz }}</p>
            @endisset
            @isset($documento)
                <p class="cedula">CC: {{ $documento }}</p>
            @endisset
            @isset($ficha)
                <p class="ficha">Ficha: {{ $ficha }}</p>
            @endisset
            @isset($programa)
                <p class="programa">{{ $programa }}</p>
            @endisset
        </div>
        <div class="qr-container">
            @if (!empty($qr_code) && filter_var($qr_code, FILTER_VALIDATE_URL) || strpos($qr_code, 'data:image') === 0)
                <img src="{{ $qr_code }}" alt="QR Code" class="qr">
            @else
                <img src="{{ public_path('img/qr-placeholder.png') }}" alt="QR Code por defecto" class="qr">
            @endif
        </div>
    </div>
</body>

</html>