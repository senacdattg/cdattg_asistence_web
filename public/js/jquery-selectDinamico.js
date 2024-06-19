$(document).ready(function() {
    // Llamar a la función para cargar las sedes al iniciar el documento
    cargarDepartamentos();
    // funcion para cargar los departamentos
    function cargarDepartamentos() {
        $.ajax({
            url: "/cargardepartamentos",
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    // Limpiar el select de sedes actual
                    $("#departamento_id").empty();

                    // Agregar la opción predeterminada
                    $("#departamento_id").append(
                        $("<option>", {
                            value: "",
                            text: "Selecciona un Departamento",
                            disabled: true,
                            selected: true,
                        })
                    );

                    // Recorrer las sedes y agregarlas al select
                    $.each(
                        response.departamentos,
                        function (index, departamento) {
                            $("#departamento_id").append(
                                $("<option>", {
                                    value: departamento.id,
                                    text: departamento.departamento,
                                })
                            );
                        }
                    );
                } else {
                    console.error(response.message);
                }
            },
            error: function (error) {
                console.error("Error en la llamada AJAX:", error);
            },
        });
    }
    // Función para cargar los municipios de acuerdo al departamento
    function cargarMunicipios(departamento_id) {
        $.ajax({
            url: "/cargarMunicipios/" + departamento_id,
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    // Limpiar el select de bloques actual
                    $("#municipio_id").empty();

                    // Agregar la opción predeterminada
                    $("#municipio_id").append(
                        $("<option>", {
                            value: "",
                            text: "Selecciona un Municipio",
                            disabled: true,
                            selected: true,
                        })
                    );

                    // Recorrer los bloques y agregarlos al select
                    $.each(response.municipios, function (index, municipio) {
                        $("#municipio_id").append(
                            $("<option>", {
                                value: municipio.id,
                                text: municipio.municipio,
                            })
                        );
                    });
                } else {
                    console.error(response.message);
                }
            },
            error: function (error) {
                console.error("Error en la llamada AJAX:", error);
            },
        });
    }
    // Función para cargar las sedes por municipio
    function cargarSedesByMunicipio(municipio_id) {
        $.ajax({
            url: "/cargarSedesByMunicipio/" + municipio_id,
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    // Limpiar el select de sedes actual
                    $("#sede_id").empty();

                    // Agregar la opción predeterminada
                    $("#sede_id").append(
                        $("<option>", {
                            value: "",
                            text: "Selecciona una sede",
                            disabled: true,
                            selected: true,
                        })
                    );

                    // Recorrer las sedes y agregarlas al select
                    $.each(response.sedes, function (index, sede) {
                        $("#sede_id").append(
                            $("<option>", {
                                value: sede.id,
                                text: sede.sede,
                            })
                        );
                    });
                } else {
                    console.error(response.message);
                }
            },
            error: function (error) {
                console.error("Error en la llamada AJAX:", error);
            },
        });
    }

    // Función para cargar las sedes por regional
    function cargarSedesByRegional(regional_id) {
        $.ajax({
            url: "/cargarSedesByRegional/" + regional_id,
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    // Limpiar el select de sedes actual
                    $("#sede_id").empty();

                    // Agregar la opción predeterminada
                    $("#sede_id").append(
                        $("<option>", {
                            value: "",
                            text: "Selecciona una sede",
                            disabled: true,
                            selected: true,
                        })
                    );

                    // Recorrer las sedes y agregarlas al select
                    $.each(response.sedes, function (index, sede) {
                        $("#sede_id").append(
                            $("<option>", {
                                value: sede.id,
                                text: sede.sede,
                            })
                        );
                    });
                } else {
                    console.error(response.message);
                }
            },
            error: function (error) {
                console.error("Error en la llamada AJAX:", error);
            },
        });
    }

    // Función para cargar los bloques en función de la sede seleccionada
    function cargarBloques(sede_id) {
        $.ajax({
            url: "/cargarBloques/" + sede_id,
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    // Limpiar el select de bloques actual
                    $("#bloque_id").empty();

                    // Agregar la opción predeterminada
                    $("#bloque_id").append(
                        $("<option>", {
                            value: "",
                            text: "Selecciona un bloque",
                            disabled: true,
                            selected: true,
                        })
                    );

                    // Recorrer los bloques y agregarlos al select
                    $.each(response.bloques, function (index, bloque) {
                        $("#bloque_id").append(
                            $("<option>", {
                                value: bloque.id,
                                text: bloque.bloque,
                            })
                        );
                    });
                } else {
                    console.error(response.message);
                }
            },
            error: function (error) {
                console.error("Error en la llamada AJAX:", error);
            },
        });
    }

    // Función para cargar los pisos en función de la sede seleccionada
    function cargarPisos(bloque_id) {
        $.ajax({
            url: "/cargarPisos/" + bloque_id,
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    // Limpiar el select de bloques actual
                    $("#piso_id").empty();

                    // Agregar la opción predeterminada
                    $("#piso_id").append(
                        $("<option>", {
                            value: "",
                            text: "Selecciona un piso",
                            disabled: true,
                            selected: true,
                        })
                    );

                    // Recorrer los bloques y agregarlos al select
                    $.each(response.pisos, function (index, piso) {
                        $("#piso_id").append(
                            $("<option>", {
                                value: piso.id,
                                text: piso.piso,
                            })
                        );
                    });
                } else {
                    console.error(response.message);
                }
            },
            error: function (error) {
                console.error("Error en la llamada AJAX:", error);
            },
        });
    }

    // Función para cargar los pisos en función de la sede seleccionada
    function cargarAmbientes(piso_id) {
        $.ajax({
            url: "/cargarAmbientes/" + piso_id,
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    // Limpiar el select de bloques actual
                    $("#ambiente_id").empty();

                    // Agregar la opción predeterminada
                    $("#ambiente_id").append(
                        $("<option>", {
                            value: "",
                            text: "Selecciona un ambiente",
                            disabled: true,
                            selected: true,
                        })
                    );

                    // Recorrer los bloques y agregarlos al select
                    $.each(response.ambientes, function (index, ambiente) {
                        $("#ambiente_id").append(
                            $("<option>", {
                                value: ambiente.id,
                                text: ambiente.title,
                            })
                        );
                    });
                } else {
                    console.error(response.message);
                }
            },
            error: function (error) {
                console.error("Error en la llamada AJAX:", error);
            },
        });
    }

    $("#departamento_id").on("change", function () {
        var departamento_id = $(this).val();
        // alert('¡seleccionaste la !'  + sede_id);

        if (departamento_id) {
            // Llamar a la función para cargar los bloques
            cargarMunicipios(departamento_id);
        }
    });
    $("#municipio_id").on("change", function () {
        var municipio_id = $(this).val();
        // alert('¡seleccionaste la !'  + sede_id);

        if (municipio_id) {
            // Llamar a la función para cargar los bloques
            cargarSedesByMunicipio(municipio_id);
        }
    });
    $("#regional_id").on("change", function () {
        var regional_id = $(this).val();
        // alert('¡seleccionaste la !'  + regional_id);

        if (regional_id) {
            // Llamar a la función para cargar los bloques
            cargarSedesByRegional(regional_id);
        }
    });

    // Manejar el cambio en el select de sedes para cargar los bloques correspondientes
    $("#sede_id").on("change", function () {
        var sede_id = $(this).val();
        // alert('¡seleccionaste la !'  + sede_id);

        if (sede_id) {
            // Llamar a la función para cargar los bloques
            cargarBloques(sede_id);
        }
    });

    $("#bloque_id").on("change", function () {
        var bloque_id = $(this).val();

        if (bloque_id) {
            cargarPisos(bloque_id);
        }
    });

    $("#piso_id").on("change", function () {
        var piso_id = $(this).val();

        if (piso_id) {
            cargarAmbientes(piso_id);
        }
    });

    // alert('¡jQuery está funcionando!');
});
