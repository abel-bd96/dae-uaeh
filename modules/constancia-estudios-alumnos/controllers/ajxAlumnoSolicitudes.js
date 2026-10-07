(function () {
    "use strict";

    var endpoint = "../../modules/constancia-estudios-alumnos/models/modAlumnoSolicitudes.php";
    var modal;

    function solicitar(datos) {
        var parametros = new URLSearchParams();

        Object.keys(datos).forEach(function (clave) {
            if (Array.isArray(datos[clave])) {
                datos[clave].forEach(function (valor) {
                    parametros.append(clave + "[]", valor);
                });
            } else {
                parametros.append(clave, datos[clave]);
            }
        });

        return fetch(endpoint, {
            method: "POST",
            body: parametros,
            headers: {
                Accept: "application/json",
            },
        }).then(function (respuesta) {
            return respuesta.json().then(function (cuerpo) {
                if (!respuesta.ok || !cuerpo.ok) {
                    throw new Error(cuerpo.mensaje || "No fue posible completar la operación.");
                }

                return cuerpo.datos;
            });
        });
    }

    //Trae los datos que llenarán el modal
    function consultar(accion, parametros) {
        var valores = new URLSearchParams();

        valores.append("accion", accion);

        Object.keys(parametros || {}).forEach(function (clave) {
            valores.append(clave, parametros[clave]);
        });

        return fetch(endpoint + "?" + valores.toString(), {
            method: "GET",
            headers: {
                Accept: "application/json",
            },
        }).then(function (respuesta) {
            return respuesta.json().then(function (cuerpo) {
                if (!respuesta.ok || !cuerpo.ok) {
                    throw new Error(cuerpo.mensaje || "No fue posible consultar la información.");
                }

                return cuerpo.datos;
            });
        });
    }

    function mostrarMensaje(texto, tipo) {
        var configuracion = {
            icon: tipo || "info",
            title: "",
            text: texto || "",
            confirmButtonText: "Aceptar",
            confirmButtonColor: "#28a745",
        };

        switch (tipo) {
            case "success":
                configuracion.title = "Operación exitosa";
                break;

            case "error":
                configuracion.title = "Ocurrió un error";
                break;

            case "warning":
                configuracion.title = "Advertencia";
                break;

            case "info":
            default:
                configuracion.title = "Información";
                break;
        }

        Swal.fire(configuracion);
    }

    function escapeHtml(texto) {
        var div = document.createElement("div");
        div.textContent = texto === null || texto === undefined ? "" : String(texto);
        return div.innerHTML;
    }

    //Llena la tabla del historial de solicitudes
    function cargarRegistros() {
        solicitar({ accion: "listar" })
            .then(function (registros) {
                var tbody = document.querySelector("#tablaHistorialSolicitudes tbody");

                if (!tbody) {
                    throw new Error("No se encontró la tabla del historial.");
                }

                if (!Array.isArray(registros) || !registros.length) {
                    tbody.innerHTML =
                        '<tr><td colspan="4" class="text-center text-muted py-4">' +
                        "No hay historial de solicitudes." +
                        "</td></tr>";
                    return;
                }

                tbody.innerHTML = registros
                    .map(function (r) {
                        return (
                            "<tr>" +
                            "<td>" +
                            escapeHtml(r.FechaRegistro) +
                            "</td>" +
                            "<td>" +
                            escapeHtml(r.Descripción) +
                            "</td>" +
                            "<td>" +
                            escapeHtml(r.Estado) +
                            "</td>" +
                            '<td class="text-end text-nowrap">' +
                            '<button name="SeguimientoCorroborar" title="Detalles de la Solicitud" type="button" class="btn btn-sm btn-outline-primary" data-id="' +
                            escapeHtml(r.Folio) +
                            '"><i class="bi bi-eye"></i> Detalles de la Solicitud</button> ' +
                            '<button name="CancelarSolicitud" title="Cancelar Solicitud" type="button" class="btn btn-sm btn-outline-danger" data-id="' +
                            escapeHtml(r.Folio) +
                            '" ' +
                            'data-estado="' +
                            escapeHtml(r.Estado) +
                            '">' +
                            '<i class="bi bi-x-circle-fill"></i> Cancelar Solicitud</button>' +
                            "</td>" +
                            "</tr>"
                        );
                    })
                    .join("");
            })
            .catch(function (e) {
                mostrarMensaje(e.message || "No fue posible cargar el historial.", "error");
            });
        limpiarModal();
        //Cada vez que se actualizan los registros, muestra datos actualizados en el modal.
    }

    //Previene que se "sobrepongan" datos al abrir y cerrar varios modal seguidos.
    function limpiarModal() {
        var formulario = document.getElementById("formSeguimientoConstancia");

        if (formulario) {
            formulario.reset();
        }

        var barra = document.getElementById("barraProgresoEstado");

        if (barra) {
            barra.textContent = "Pendiente de Pago";
            barra.style.width = "20%";
            barra.setAttribute("aria-valuenow", "20");
        }

        [
            "subtituloModalSeguimientoConstancia",
            "nombreAlumno",
            "unidadAcademica",
            "apellidoPaterno",
            "programaEducativo",
            "apellidoMaterno",
            "cctUAEH",
            "curp",
            "cctUA",
            "numeroCuenta",
            "tipoIngreso",
            "condicion",
            "duracionPE",
            "calidadAlumno",
            "semestre",
            "promedio",
            "avancePE",
            "periodoEstudios",
            "periodoVacacional",
            "fechaEmision",
        ].forEach(function (id) {
            var elemento = document.getElementById(id);

            if (elemento) {
                elemento.textContent = "";
            }
        });

        var btnFormatoPago = document.getElementById("btnFormatoPago");

        if (btnFormatoPago) {
            btnFormatoPago.classList.add("d-none");
        }
    }

    function corregirProgreso(estado, datos) {
        var barra = document.getElementById("barraProgresoEstado");
        var btnFormatoPago = document.getElementById("btnFormatoPago");

        if (!barra) {
            return;
        }

        var porcentaje = 20;

        if (btnFormatoPago) {
            btnFormatoPago.classList.add("d-none");
        }

        switch (estado) {
            case "Pendiente de Pago":
                porcentaje = 20;

                if (btnFormatoPago) {
                    btnFormatoPago.classList.remove("d-none");
                }
                break;

            case "Pago Recibido":
                porcentaje = 40;
                break;

            case "En Elaboración":
                porcentaje = 60;
                break;

            case "Listo para Entrega":
                porcentaje = 80;
                barra.classList.remove("bg-primary");
                barra.classList.add("bg-success");
                //Ubicación Torre de Posgrado
                break;

            case "Trámite Concluido":
                porcentaje = 100;
                barra.classList.remove("bg-primary");
                barra.classList.add("bg-success");
                /*Revisar campos de ae_ciclo.json (dependiendo del id_kardex):
                fechaSolicitudConstanciaTermino && fechaPeriodoVacacionalTermino cuál es mayor + 14 días, 23 horas y 59 minutos
                deshabilitar btnConstanciaDigital*/
                break;

            case "Solicitud Cancelada":
                porcentaje = 100;
                barra.classList.remove("bg-primary");
                barra.classList.add("bg-danger");
                break;

            default:
                porcentaje = 0;
                break;
        }

        barra.textContent = estado || "Pendiente de Pago";
        barra.style.width = porcentaje + "%";
        barra.setAttribute("aria-valuenow", String(porcentaje));

        var fechaEmision = document.getElementById("fechaEmision");

        if (fechaEmision && datos) {
            fechaEmision.textContent = datos.FechaEmision || "";
        }
    }

    function abrirModal(datos) {
        limpiarModal();

        if (!datos) {
            mostrarMensaje("No se recibió información de la solicitud.", "error");
            modal.show();
            return;
        }

        document.getElementById("subtituloModalSeguimientoConstancia").textContent = datos.Folio || "";

        document.getElementById("nombreAlumno").textContent = datos.Nombre || "";

        document.getElementById("unidadAcademica").textContent = datos.UnidadAcademica || "";

        document.getElementById("apellidoPaterno").textContent = datos.ApellidoPaterno || "";

        document.getElementById("programaEducativo").textContent = datos.ProgramaEducativo || "";

        document.getElementById("apellidoMaterno").textContent = datos.ApellidoMaterno || "";

        document.getElementById("cctUAEH").textContent = datos.CCTUAEH || "";

        document.getElementById("curp").textContent = datos.CURP || "";

        document.getElementById("cctUA").textContent = datos.CCTUA || "";

        document.getElementById("numeroCuenta").textContent = datos.Cuenta || "";

        document.getElementById("tipoIngreso").textContent = datos.TipoIngreso || "";

        document.getElementById("condicion").textContent = datos.CondicionEscolar || "";

        document.getElementById("duracionPE").textContent = datos.DuracionPE || "";

        document.getElementById("calidadAlumno").textContent = datos.CalidadAlumno || "";

        document.getElementById("semestre").textContent = datos.Semestre || "";

        document.getElementById("promedio").textContent = datos.Promedio || "";

        document.getElementById("avancePE").textContent = datos.AvancePE || "";

        document.getElementById("periodoEstudios").textContent = datos.PeriodoEstudios || "";

        document.getElementById("periodoVacacional").textContent = datos.PeriodoVacacional || "";

        corregirProgreso(datos.Estado, datos);

        modal.show();
    }

    function cargarDetallePorFolio(folio) {
        if (!folio) {
            mostrarMensaje("No existe el folio de la solicitud.", "error");
            return;
        }

        consultar("consultar", {
            folio: folio,
        })
            .then(function (datos) {
                abrirModal(datos);
            })
            .catch(function (e) {
                mostrarMensaje(e.message || "No fue posible consultar la solicitud.", "error");
            });
    }

    function cancelarSolicitud(folio, estado) {
        if (!folio) {
            mostrarMensaje("No puedes cancelar una solicitud sin folio.", "error");
            return;
        } // Si ya está pagada o emitida no se debería poder cancelar
        if (estado !== "Pendiente de Pago") {
            mostrarMensaje(
                "No puedes cancelar esta solicitud con estado: " + (estado || "Desconocido") + ".",
                "warning",
                setTimeout(function () {
                    //Para que no se quede ahí el mensaje de advertencia
                    mensaje.classList.add("d-none");
                }, 5000),
            );

            return;
        }

        Swal.fire({
            title: "¿Cancelar solicitud?",
            html:
                "¿Estás seguro de cancelar esta solicitud de Constancia?<br><br>" +
                "<strong>Folio:</strong> " +
                escapeHtml(folio),
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, cancelar",
            cancelButtonText: "No",
            reverseButtons: true,
            focusCancel: true,
            confirmButtonColor: "#DC3545",
        }).then(function (resultado) {
            if (!resultado.isConfirmed) {
                return;
            }

            Swal.fire({
                title: "Cancelando solicitud...",
                text: "Por favor espera.",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: function () {
                    Swal.showLoading();
                },
            });

            solicitar({ accion: "cancelar", folio: folio })
                .then(function (respuesta) {
                    cargarRegistros();
                    Swal.fire({
                        title: "Solicitud cancelada",
                        text: respuesta.mensaje || "La solicitud fue cancelada correctamente.",
                        icon: "success",
                        confirmButtonText: "Aceptar",
                    });
                })
                .catch(function (e) {
                    mostrarMensaje(e.message || "No fue posible cancelar la solicitud.", "error");
                });
        });
        //
    }

    document.addEventListener("DOMContentLoaded", function () {
        var vistaCorrecta = document.getElementById("solicitudesConstancia");
        if (!vistaCorrecta) {
            return;
        }

        var elementoModal = document.getElementById("modalSeguimientoConstancia");

        if (!elementoModal) {
            //Verifica que esté el modal
            mostrarMensaje("No se encontró el modal de la solicitud.", "error");
            return;
        }

        //Va sin "var" para poder funcionar correctamente
        modal = new bootstrap.Modal(elementoModal);

        cargarRegistros();

        document.addEventListener("click", function (evento) {
            var botonSeguimiento = evento.target.closest('[name="SeguimientoCorroborar"]');

            if (botonSeguimiento) {
                var folio = botonSeguimiento.getAttribute("data-id");

                limpiarModal();
                modal.show();

                cargarDetallePorFolio(folio);
                return;
            }

            var botonCancelar = evento.target.closest('[name="CancelarSolicitud"]');
            if (botonCancelar) {
                var folioCancelar = botonCancelar.getAttribute("data-id");
                var estadoCancelar = botonCancelar.getAttribute("data-estado");
                cancelarSolicitud(folioCancelar, estadoCancelar);
                return;
            }
        });
    });
})();
