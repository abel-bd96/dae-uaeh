(function () {
    "use strict";

    function escapeHtml(texto) {
        var div = document.createElement("div");
        div.textContent = texto === null || texto === undefined ? "" : String(texto);
        return div.innerHTML;
    }
    
    function mostrarTablaCorroborarDatos() {
        var tipoFirmaSeleccionado = document.querySelector('input[name="tipoFirma"]:checked');
        var calificacionesSeleccionado = document.querySelector('input[name="calificaciones"]:checked');
        var tabla = document.getElementById("tablaCorroborarDatos");

        if (tipoFirmaSeleccionado && calificacionesSeleccionado) {
            tabla.classList.remove("d-none");
        }
    }

    function guardarNuevaConstancia() {
        var formulario = document.getElementById("formNuevaConstancia");
        if (!formulario) {
            mostrarMensaje("No se encontró el formulario de nueva constancia.", "error");
            return;
        }
        var tipoFirmaSeleccionado = formulario.querySelector('input[name="tipoFirma"]:checked');
        var calificacionesSeleccionadas = formulario.querySelector('input[name="calificaciones"]:checked');
        if (!tipoFirmaSeleccionado) {
            mostrarMensaje("Selecciona el tipo de firma de la constancia.", "warning");
            return;
        }
        if (!calificacionesSeleccionadas) {
            mostrarMensaje("Selecciona si deseas incluir las calificaciones.", "warning");
            return;
        }
        var tipoFirma = tipoFirmaSeleccionado.value;
        var calificaciones = calificacionesSeleccionadas.value;
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
            Swal.fire({
                title: "Generando solicitud...",
                text: "Por favor espera.",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: function () {
                    Swal.showLoading();
                },
            });
            solicitar({ accion: "guardar", tipoFirma: tipoFirma, calificaciones: calificaciones })
                .then(function (respuesta) {
                    formulario.reset();
                    cargarRegistros();
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
                .catch(function (e) {
                    Swal.fire({
                        title: "No fue posible generar la solicitud",
                        text: e.message || "Ocurrió un error al guardar la solicitud.",
                        icon: "error",
                        confirmButtonText: "Aceptar",
                    });
                });
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('input[name="tipoFirma"], input[name="calificaciones"]').forEach(function (radio) {
            radio.addEventListener("change", mostrarTablaCorroborarDatos);
        });
        mostrarTablaCorroborarDatos();

        var formularioNuevaConstancia = document.getElementById("formNuevaConstancia");

        if (formularioNuevaConstancia) {
            formularioNuevaConstancia.addEventListener("submit", function (evento) {
                evento.preventDefault();
                guardarNuevaConstancia();
            });
        }
    });
})();
