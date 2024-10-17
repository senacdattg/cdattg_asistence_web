<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Carnet Digital SENA</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: 'Arial', sans-serif;">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 20px;">
                <table cellpadding="0" cellspacing="0" border="0" width="340" style="background: linear-gradient(184deg, #4ef84e 0%, #4ef84e 15%, rgba(225, 239, 218, 0.9) 35%, #e1efda 40%, #e1efda 100%); border-radius: 10px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);">
                    <tr>
                        <td style="padding: 15px;">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td width="80" valign="top">
                                        <img src="{{ $message->embed(public_path('img/sena-logo.png')) }}" alt="Logo SENA" width="70" style="display: block; margin-bottom: 5px;">
                                        <p style="font-size: 12px; color: #333333; margin: 0; font-weight: 600; text-align: center;">Regional Guaviare</p>
                                    </td>
                                    <td align="right" valign="top">
                                        <img src="{{ $message->embed('data:image/jpeg;base64,' . $photo) }}" alt="Foto del aprendiz" style="width: 100%; height: 100%; object-fit: cover;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px;">
                            <h2 style="font-weight: 600; font-size: 18px; text-align: center; margin-bottom: 10px;">APRENDIZ</h2>
                            <hr style="border: none; height: 2px; background-color: #39ff14; margin-bottom: 10px;">
                            <p style="font-weight: 600; font-size: 18px; text-align: center; margin-bottom: 10px;">{{ $aprendiz }}</p>
                            <p style="font-size: 14px; margin-bottom: 5px;"><strong>CC:</strong> {{ $documento }}</p>
                            <p style="font-size: 14px; margin-bottom: 5px;"><strong>Ficha:</strong> {{ $ficha }}</p>
                            <p style="font-size: 12px; margin-bottom: 5px;"><strong>Programa:</strong> {{ $programa }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding-bottom: 20px;">
                            <div style="width: 150px; height: 150px; background-color: rgba(78, 248, 78, 0.2); display: flex; justify-content: center; align-items: center;">
                                {!! $qr_code !!}
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>