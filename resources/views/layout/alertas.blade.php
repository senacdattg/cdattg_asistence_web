<script>
    document.addEventListener('DOMContentLoaded', function() {
        var successMessage = "{{ Session::get('success') }}";
        var errorMessage = "{{ Session::get('error') }}";

        function mostrarSuccess() {
            if (successMessage) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: successMessage
                });
            }
        }

        function mostrarError() {
            if (errorMessage) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
            }
        }

        function confirmarEliminar() {
            $('.formulario-eliminar').submit(function(e) {
                e.preventDefault(); // Evita que el formulario se envíe automáticamente

                var form = this; // Referencia al formulario actual

                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¡No podrás revertir esto!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, eliminarlo",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si se confirma la eliminación, envía el formulario actual
                        form.submit();
                    }
                });
            });
        }

        mostrarSuccess();
        mostrarError();
        confirmarEliminar();
    });
</script>
@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: '{!! implode('<br>', $errors->all()) !!}',
            });
        });
    </script>
@endif
{{-- @if (Session::has('success'))
<script>
    mostrarSuccess();
</script>
@endif

@if (Session::has('error'))
<script>
    mostrarError();
</script>
@endif --}}
