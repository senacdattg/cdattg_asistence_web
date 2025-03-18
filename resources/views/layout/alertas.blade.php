<script>
    document.addEventListener('DOMContentLoaded', function() {
        var successMessage = @json(Session::get('success'));
        var errorMessage = @json(Session::get('error'));

        if (successMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: successMessage
            });
        }

        if (errorMessage) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage
            });
        }

        document.addEventListener('submit', function(e) {
            if (e.target.matches('.formulario-eliminar')) {
                e.preventDefault(); // Evita que el formulario se envíe automáticamente
                var form = e.target; // Referencia al formulario actual

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
                        form.submit();
                    }
                });
            }
        });

        // Mostrar errores de validación
        var validationErrors = @json($errors->all());
        if (validationErrors.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: validationErrors.join('<br>'),
            });
        }
    });
</script>
