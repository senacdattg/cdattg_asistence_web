<div class="card-footer"></div>

<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
        <b>Versión</b> 3.2.0
    </div>
    <strong>Copyright &copy; {{ date('Y') }} 
        <a href="#">Industria y Tecnología SENA || Regional Guaviare</a>.
    </strong> Todos los derechos reservados.
</footer>

<aside class="control-sidebar control-sidebar-dark"></aside>
</div>

{{-- Scripts optimizados --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.min.js?v=3.2.0') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Incluir alertas --}}
@include('layout.alertas')

</body>
</html>
