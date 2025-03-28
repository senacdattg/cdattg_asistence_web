@extends('adminlte::page')
@section('content')
    

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Leer QR</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Blank Page</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">

            <div class="card">

                <div class="card-body">
                    <a class="btn btn-warning btn-sm" href="javascript:history.back()">
                        <i class="fas fa-arrow-left"></i>
                        </i>
                        Volver
                    </a>
                </div>
                <input type="hidden" value="{{ $ficha_id }}" name="ficha_id"
                    id="ficha_id">
                <input type="hidden" value="{{ $ambiente_id }}" name="ambiente_id"
                    id="ambiente_id">
                <input type="hidden" value="{{ $descripcion }}" name="descripcion"
                    id="descripcion">
                <input type="hidden" value="{{ $evento }}" name="evento" id="evento">
                
                <div class="row justify-content-center mt-5">
                    <div class="col-sm-4 shadow p-3">
                        <h5 class="text-center">Escanear codigo QR</h5>
                        <div class="row text-center">
                            <a id="btn-scan-qr" href="#">
                                {{-- <img src="https://dab1nmslvvntp.cloudfront.net/wp-content/uploads/2017/07/1499401426qr_icon.svg"
                                        class="img-fluid text-center" width="175"> --}}
                                {{-- <img src="{{ asset('dist/img/LogoSena.jpeg') }}"
                                        class="img-fluid text-center" width="175"> --}}
                            </a>
                            {{-- <input type="file" capture="camera"> --}}
                            <canvas hidden="" id="qr-canvas" class="img-fluid"></canvas>

                        </div>

                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/qrCode.min.js') }}"></script>
    <script src="{{ asset('js/leerQR.js') }}"></script>

@endsection
