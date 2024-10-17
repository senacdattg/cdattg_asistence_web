<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carnets Digitales Generados</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #111;
            background-image:
                linear-gradient(135deg, rgba(30, 30, 30, 0.4) 0%, rgba(10, 10, 10, 0.8) 100%),
                url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M20 20.5V18H0v-2h20v-2H0v-2h20v-2H0V8h20V6H0V4h20V2H0V0h22v20h2V0h2v20h2V0h2v20h2V0h2v20h2v2H20v-1.5zM0 20h2v20H0V20zm4 0h2v20H4V20zm4 0h2v20H8V20zm4 0h2v20h-2V20zm4 0h2v20h-2V20zm4 4h20v2H20v-2zm0 4h20v2H20v-2zm0 4h20v2H20v-2zm0 4h20v2H20v-2z' fill='%23333' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            background-size: cover;
            background-position: center;
            font-family: 'Poppins', Arial, sans-serif;
        }

        .card {
            width: 340px;
            height: 460px;
            background: linear-gradient(184deg,
                    #4ef84e 0%,
                    #4ef84e 15%,
                    rgba(225, 239, 218, 0.9) 35%,
                    #e1efda 40%,
                    #e1efda 100%);
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            position: relative;
            display: flex;
            flex-direction: column;
            font-family: 'Poppins', sans-serif;
        }

        .logo-container {
            position: absolute;
            top: 15px;
            left: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 80px;
        }

        .logo {
            width: 70px;
            height: auto;
        }

        .logo-text {
            font-size: 12px;
            color: #333333;
            margin-top: 5px;
            font-weight: 600;
            text-align: center;
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
            mask-image: linear-gradient(to bottom, black 90%, transparent 100%);
            -webkit-mask-image: linear-gradient(to bottom, black 90%, transparent 100%);
        }

        .info-container {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 20px;
            /* Ajusta este valor según sea necesario */
        }

        .aprendiz-title {
            font-weight: 600;
            font-size: 18px;
            font-weight: bold;
        }

        .separator {
            border: none;
            height: 2px;
            background-color: #39ff14;
        }


        
        .cedula,
        .ficha,
        .programa {
            font-size: 14px;
            font-weight: 400;
        }

        .nombre {
            font-weight: 600;
            font-size: 18px;
            text-align: center;
        }

        .programa {
            font-size: 12px;
        }

        /* Ajuste para el contenedor del QR */
        .qr-container {
            position: absolute;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%);
            width: 150px;
            background-color: rgba(78, 248, 78, 0.2);
            /* Verde transparente */
            height: 150px;
            display: flex;
            justify-content: center;
            align-items: center;
        }



        .qr {
            top: 20px;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            max-width: 90%;
            max-height: 90%;
            overflow-y: auto;
        }

    </style>
</head>

<body class="bg-gray-100" x-data="carnetApp()">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 text-center text-white">Carnets Digitales Generados</h1>

        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-center text-white">Lista de Aprendices</h2>
            <ul class="bg-white shadow-md rounded-lg divide-y divide-gray-200">
                <template x-for="(carnet, index) in carnets" :key="index">
                    <li class="px-6 py-4 flex items-center justify-between">
                        <span class="text-lg" x-text="carnet.aprendiz"></span>
                        <button @click="selectedCarnet = index"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Mostrar Carnet
                        </button>
                    </li>
                </template>
            </ul>
        </div>

        <div x-show="selectedCarnet !== null" class="modal-overlay" x-cloak @click.self="selectedCarnet = null">
            <div class="modal-content">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 text-center w-full">Carnet Digital</h3>
                    <button @click="selectedCarnet = null" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <template x-if="selectedCarnet !== null">
                    <div class="card mx-auto">
                        <div class="logo-container">
                            <img src="{{ asset('img/sena-logo.png') }}" alt="Logo" class="logo">
                            <span class="logo-text">Regional Guaviare</span>
                        </div>
                        <div class="aprendiz-container">
                            <template x-if="carnets[selectedCarnet].photo">
                                <img :src="'data:image/jpeg;base64,' + carnets[selectedCarnet].photo"
                                    :alt="'Foto de ' + carnets[selectedCarnet].aprendiz" class="aprendiz-img">
                            </template>
                        </div>
                        <div class="info-container">
                            <h2 class="aprendiz-title">APRENDIZ</h2>
                            <hr class="separator">
                            <p class="nombre" x-text="carnets[selectedCarnet].aprendiz"></p>
                            <p class="cedula"><strong>CC:</strong> <span
                                    x-text="carnets[selectedCarnet].documento"></span></p>
                            <p class="ficha"><strong>Ficha:</strong> <span
                                    x-text="carnets[selectedCarnet].ficha"></span></p>
                            <p class="programa"><strong>Programa:</strong> <span
                                    x-text="carnets[selectedCarnet].programa"></span></p>
                        </div>
                        <div class="qr-container">
                            <div class="qr" x-html="carnets[selectedCarnet].qr_code"></div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('carnet.index') }}"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline inline-block">
                Volver al inicio
            </a>
        </div>
        <div class="text-center mt-8">
            <form action="{{ route('carnet.sendAll') }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Enviar todos los carnets por correo
                </button>
            </form>
        </div>
    </div>

    <script>
        function carnetApp() {
            return {
                carnets: @json($carnets),
                selectedCarnet: null
            }
        }
    </script>
</body>

</html>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif