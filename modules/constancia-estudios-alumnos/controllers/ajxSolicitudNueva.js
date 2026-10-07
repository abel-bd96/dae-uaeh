(function () {
    "use strict";

    var enviando = false;

    function escapeHtml(texto) {
        var div = document.createElement("div");
        div.textContent = texto === null || texto === undefined ? "" : String(texto);
        return div.innerHTML;
    }

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

    //Se utilizan los datos enviados
    function solicitar(datos) {
        var parametros = new URLSearchParams();

        Object.keys(datos).forEach(function (clave) {
            parametros.append(clave, datos[clave] === null || datos[clave] === undefined ? "" : datos[clave]);
        });

        return fetch("modAlumnoSolicitudes.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
                "X-Requested-With": "XMLHttpRequest",
            },
            body: parametros.toString(),
        }).then(function (respuesta) {
            return respuesta.text().then(function (texto) {
                var datosRespuesta;

                try {
                    datosRespuesta = JSON.parse(texto);
                } catch (error) {
                    throw new Error("El servidor devolvió una respuesta no válida.");
                }

                if (!respuesta.ok || !datosRespuesta.ok) {
                    throw new Error(datosRespuesta.mensaje || "No fue posible procesar la solicitud.");
                }

                return datosRespuesta.datos;
            });
        });
    }

    //Es necesaria esta función para evitar mostrar datos desactualizados
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
                Swal.fire({
                    title: "Error",
                    text: "No fue posible mostrar tus datos.",
                    icon: "error",
                    confirmButtonText: "Aceptar",
                });
            });
    }

    function guardarNuevaConstancia() {
        // Evita cambios simultáneos o que se produzcan errores
        // si se "traba" la página
        if (enviando) {
            return;
        }

        var formulario = document.getElementById("formNuevaConstancia");

        if (!formulario) {
            Swal.fire({
                title: "Error",
                text: "No se encontró el formulario de nueva constancia.",
                icon: "error",
                confirmButtonText: "Aceptar",
            });
            return;
        }

        var tipoFirmaSeleccionado = formulario.querySelector('input[name="tipoFirma"]:checked');

        var calificacionesSeleccionado = formulario.querySelector('input[name="calificaciones"]:checked');

        if (!tipoFirmaSeleccionado) {
            Swal.fire({
                title: "Falta información",
                text: "Selecciona el tipo de firma de la constancia.",
                icon: "warning",
                confirmButtonText: "Aceptar",
            });
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

            // CORRECCIÓN CRÍTICA:
            // Antes estaba: enviado = true;
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
                            escapeHtml(respuesta.Estado || "Pendiente de Pago"),
                        icon: "success",
                        confirmButtonColor: "#28a745",
                        confirmButtonText: "Aceptar",
                    });
                })
                .catch(function (error) {
                    Swal.fire({
                        title: "No fue posible generar la solicitud",
                        text: error.message || "Ocurrió un error al guardar la solicitud.",
                        icon: "error",
                        confirmButtonText: "Aceptar",
                    });
                })
                .finally(function () {
                    // Si todo sale bien o ocurre un error,
                    // permite realizar un nuevo envío.
                    enviando = false;
                });
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        // Se necesita verificar que esté seleccionada
        // alguna opción en ambos input
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
