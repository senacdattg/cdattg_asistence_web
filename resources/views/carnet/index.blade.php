
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Carnets Digitales</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
         body {
            margin: 0;
            padding: 0;
            background-color: #111;
            background-image:
                linear-gradient(135deg, rgba(30, 30, 30, 0.4) 0%, rgba(10, 10, 10, 0.8) 100%),
                url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M20 20.5V18H0v-2h20v-2H0v-2h20v-2H0V8h20V6H0V4h20V2H0V0h22v20h2V0h2v20h2V0h2v20h2V0h2v20h2V0h2v20h2v2H20v-1.5zM0 20h2v20H0V20zm4 0h2v20H4V20zm4 0h2v20H8V20zm4 0h2v20h-2V20zm4 0h2v20h-2V20zm4 4h20v2H20v-2zm0 4h20v2H20v-2zm0 4h20v2H20v-2zm0 4h20v2H20v-2z' fill='%23333' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Generador de Carnets Digitales</h1>
        
        <form action="{{ route('carnet.process') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="csv_file">
                    Archivo CSV
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="csv_file" type="file" name="csv_file" accept=".csv" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="photos">
                    Fotos de los Aprendices
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="photos" type="file" name="photos[]" accept="image/*" multiple required>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Generar Carnets
                </button>
            </div>
        </form>
        
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-xl font-bold mb-4">Instrucciones</h2>
            <ol class="list-decimal list-inside">
                <li class="mb-2">Prepare un archivo CSV con las columnas: Aprendiz, Documento, Correo (separados por punto y coma ';').</li>
                <li class="mb-2">Asegúrese de que la primera fila del CSV contenga los encabezados.</li>
                <li class="mb-2">Prepare las fotos de los aprendices (una por cada fila del CSV, excluyendo la primera fila de encabezados).</li>
                <li class="mb-2">Suba el archivo CSV y las fotos usando el formulario anterior.</li>
                <li>Haga clic en "Generar Carnets" para crear los carnets digitales.</li>
            </ol>
        </div>
    </div>
</body>
</html>
