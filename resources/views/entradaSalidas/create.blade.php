@extends('layout.master-layout')
@section('content')
    <div class="content-wrapper">

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
                <input type="hidden" value="{{ $ficha_caracterizacion_id }}" name="ficha_caracerizacion_id" id="ficha_caracterizacion_id">

                <input type="hidden" value="{{ $evento }}" name="evento" id="evento">
                    <div class="row justify-content-center mt-5">
                        <div class="col-sm-4 shadow p-3">
                            <h5 class="text-center">Escanear codigo QR</h5>
                            <div class="row text-center">
                                <a id="btn-scan-qr" href="#">
                                    <img src="https://dab1nmslvvntp.cloudfront.net/wp-content/uploads/2017/07/1499401426qr_icon.svg"
                                        class="img-fluid text-center" width="175">
                                </a>
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
