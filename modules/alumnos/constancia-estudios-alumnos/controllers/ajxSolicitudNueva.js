(function () {
    "use strict";

    var endpoint = "../models/modAlumnoSolicitudes.php";
    
    var enviando = false;

    function mostrarcontenedorTablaDatos() {
        var tipoFirmaSeleccionado = document.querySelector('input[name="tipoFirma"]:checked');
        var calificacionesSeleccionado = document.querySelector('input[name="calificaciones"]:checked');
        var tabla = document.getElementById("contenedorTablaDatos");

        if (!tabla) {
            return;
        }

        if (tipoFirmaSeleccionado && calificacionesSeleccionado) {
            tabla.classList.remove("d-none");
        } else {
            tabla.classList.add("d-none");
        }
    }

    //Se asegura de que el historial de solicitudes en ajxAlumnoSolicitudes muestre la solicitud nueva
    function actualizarHistorial() {
        if (typeof window.cargarRegistros === "function") {
            window.cargarRegistros();
        }
    }
    
    function cargarDatosCorroborar() {
        solicitar({ accion: "mostrarDatosPersonales" })
            .then(function (datos) {
                if (!datos) {
                    mostrarMensaje("No se recibió información de la solicitud.", "error");
                    return;
                }

                document.getElementById("nombreAlumno").textContent = datos.Nombre || "";

                document.getElementById("apellidoPaterno").textContent = datos.ApellidoPaterno || "";

                document.getElementById("apellidoMaterno").textContent = datos.ApellidoMaterno || "";

                document.getElementById("curp").textContent = datos.CURP || "";

                document.getElementById("unidadAcademica").textContent = datos.UnidadAcademica || "";

                document.getElementById("programaEducativo").textContent = datos.ProgramaEducativo || "";

                document.getElementById("numeroCuenta").textContent = datos.Cuenta || "";

                document.getElementById("semestre").textContent = datos.Semestre || "";

                document.getElementById("calidadAlumno").textContent = datos.CalidadAlumno || "";

                document.getElementById("promedio").textContent = datos.Promedio || "";
            })
            .catch(function (e) {
                mostrarMensaje(e.message || "No fue posible cargar tus datos.", "error");
            });
        }

    function guardarNuevaConstancia() {
        // Evita cambios simultáneos o que se produzcan errores si se "traba" la página
        if (enviando) {
            return;
        }

        var formulario = document.getElementById("formNuevaConstancia");

        if (!formulario) {
            mostrarMensaje("No se encuentra disponible este formulario.", "error");
            return;
        }

        var tipoFirmaSeleccionado = formulario.querySelector('input[name="tipoFirma"]:checked');

        var calificacionesSeleccionado = formulario.querySelector('input[name="calificaciones"]:checked');

        if (!tipoFirmaSeleccionado) {
            mostrarMensaje("Debes seleccionar el tipo de firma de la constancia.", "warning");
            return;
        }

        if (!calificacionesSeleccionado) {
            Swal.fire({
                title: "Falta información",
                text: "Selecciona si deseas incluir las calificaciones.",
                icon: "warning",
                confirmButtonText: "Aceptar",
            });
            return;
        }

        var tipoFirma = tipoFirmaSeleccionado.value;
        var calificaciones = calificacionesSeleccionado.value;

        Swal.fire({
            title: "¿Generar nueva solicitud?",
            html:
                "<strong>Firma:</strong> " +
                escapeHtml(tipoFirma) +
                "<br><strong>Calificaciones:</strong> " +
                escapeHtml(calificaciones),
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Sí, generar",
            confirmButtonColor: "#28a745",
            cancelButtonText: "Cancelar",
            reverseButtons: true,
            focusCancel: true,
        }).then(function (resultado) {
            if (!resultado.isConfirmed) {
                return;
            }
            enviando = true;

            Swal.fire({
                title: "Generando solicitud...",
                text: "Por favor espera.",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: function () {
                    Swal.showLoading();
                },
            });

            solicitar({
                accion: "guardar",
                tipoFirma: tipoFirma,
                calificaciones: calificaciones,
            })
                .then(function (respuesta) {
                    formulario.reset();

                    actualizarHistorial();

                    var elementoModal = document.getElementById("modalNuevaConstancia");

                    if (elementoModal) {
                        var instanciaModal = bootstrap.Modal.getInstance(elementoModal);

                        if (instanciaModal) {
                            instanciaModal.hide();
                        }
                    }

                    Swal.fire({
                        title: "Solicitud generada",
                        html:
                            "La solicitud fue registrada correctamente.<br><br>" +
                            "<strong>Folio:</strong> " +
                            escapeHtml(respuesta.Folio || "") +
                            "<br><strong>Estado:</strong> " +
                            escapeHtml(respuesta.Estado || "Solicitada"),
                        icon: "success",
                        confirmButtonColor: "#28a745",
                        confirmButtonText: "Aceptar",
                    });
                })
                .catch(function (e) {
                    mostrarMensaje(e.message || "Ocurrió un error al guardar la solicitud.", "error");
                })
                .finally(function () {
                    // Si todo sale bien o ocurre un error,
                    // permite realizar un nuevo envío.
                    enviando = false;
                });
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        // Se necesita verificar que esté seleccionada alguna opción en ambos input
        document.querySelectorAll('input[name="tipoFirma"], input[name="calificaciones"]').forEach(function (radio) {
            radio.addEventListener("change", mostrarcontenedorTablaDatos);
        });

        mostrarcontenedorTablaDatos();
        cargarDatosCorroborar();

        var formularioNuevaConstancia = document.getElementById("formNuevaConstancia");

        if (formularioNuevaConstancia) {
            formularioNuevaConstancia.addEventListener("submit", function (evento) {
                evento.preventDefault();
                guardarNuevaConstancia();
            });
        }
    });
})();
