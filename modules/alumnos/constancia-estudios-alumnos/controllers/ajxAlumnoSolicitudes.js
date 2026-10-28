(function () {
    "use strict";

    var endpoint = "../models/modAlumnoSolicitudes.php";
    var modal;
    
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
                            escapeHtml(r.fechaSolicitud) +
                            "</td>" +
                            "<td>" +
                            escapeHtml(r.cicloEscolar) +
                            ". " +
                            escapeHtml(r.tipoFirma) +
                            "</td>" +
                            "<td>" +
                            escapeHtml(r.estatus) +
                            "</td>" +
                            '<td class="text-end text-nowrap">' +
                            '<button name="SeguimientoCorroborar" title="Detalles de la Solicitud" type="button" class="btn btn-sm btn-outline-primary" data-id="' +
                            escapeHtml(r.idSolicitud) +
                            '"><i class="bi bi-eye"></i> Detalles de la Solicitud</button> ' +
                            '<button name="CancelarSolicitud" title="Cancelar Solicitud" type="button" class="btn btn-sm btn-outline-danger" data-id="' +
                            escapeHtml(r.idSolicitud) +
                            '" ' +
                            'data-estado="' +
                            escapeHtml(r.estatus) +
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
            barra.textContent = "Solicitada";
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

    function corregirProgreso(estatus, datos) {
        var barra = document.getElementById("barraProgresoEstado");
        var btnFormatoPago = document.getElementById("btnFormatoPago");
        var btnConstanciaDigital = document.getElementById("btnConstanciaDigital");

        if (!barra) {
            return;
        }

        var porcentaje = 20;

        //Vuelve a ocultar los botones al modificarse el estatus
        if (btnFormatoPago && btnConstanciaDigital) {
            btnFormatoPago.classList.add("d-none");
            btnConstanciaDigital.classList.add("d-none");
        }

        switch (estatus) {
            case "Solicitada":
                porcentaje = 20;

                if (btnFormatoPago) {
                    btnFormatoPago.classList.remove("d-none");
                }
                break;

            case "Pagada":
                porcentaje = 40;
                break;

            case "En Elaboración":
                porcentaje = 60;

                break;

            case "Atendida":
                porcentaje = 80;
                barra.classList.remove("bg-primary");
                barra.classList.add("bg-success");
                if (datos.tipoFirma === "D" && btnConstanciaDigital) {
                    btnConstanciaDigital.classList.remove("d-none");
                }
                else{
                    mostrarMensaje("Puedes recoger tu Constancia en el primer piso de la Torre de Posgrado, ubicada en Carretera Pachuca-Tulancingo Km 4.5, Col. Carboneras, Mineral de la Reforma, Hidalgo, C.P. 42184.", "info",);
                }                
                break;

            case "Finalizada":
                porcentaje = 100;
                barra.classList.remove("bg-primary");
                barra.classList.add("bg-success");
                /*Revisar campos de ae_ciclo.json (dependiendo del id_kardex): fechaSolicitudConstanciaTermino && fechaPeriodoVacacionalTermino cuál es mayor + 14 días, 23 horas y 59 minutos
                 */
                break;

            case "Cancelada":
                porcentaje = 100;
                barra.classList.remove("bg-primary");
                barra.classList.add("bg-danger");
                break;

            default:
                porcentaje = 0;
                break;
        }

        barra.textContent = estatus || "Solicitada";
        barra.style.width = porcentaje + "%";
        barra.setAttribute("aria-valuenow", String(porcentaje));
    }

    function abrirModal(datos) {
        limpiarModal();

        if (!datos) {
            mostrarMensaje("No se recibió información de la solicitud.", "error");
            return;
        }

        document.getElementById("subtituloModalSeguimientoConstancia").textContent = datos.folio || "";

        document.getElementById("nombreAlumno").textContent = datos.nombre || "";

        document.getElementById("unidadAcademica").textContent = datos.unidadAcademica || "";

        document.getElementById("apellidoPaterno").textContent = datos.apellidoPaterno || "";

        document.getElementById("programaEducativo").textContent = datos.programaEducativo || "";

        document.getElementById("cicloEscolarConstancia").textContent = datos.cicloEscolarConstancia || "";

        document.getElementById("apellidoMaterno").textContent = datos.apellidoMaterno || "";

        document.getElementById("cctUniversidad").textContent = datos.cctUniversidad || "";

        document.getElementById("curp").textContent = datos.CURP || "";

        document.getElementById("cctUnidadAcademica").textContent = datos.cctUnidadAcademica || "";

        document.getElementById("numeroCuenta").textContent = datos.numeroCuenta || "";

        document.getElementById("tipoIngreso").textContent = datos.tipoIngreso || "";

        document.getElementById("fechaIngreso").textContent = datos.fechaIngreso || "";

        document.getElementById("fechaEgreso").textContent = datos.fechaEgreso || "";

        document.getElementById("estatusAlumno").textContent = datos.estatusAlumno || "";

        document.getElementById("duracionPE").textContent = datos.duracionProgramaEducativo || "";

        document.getElementById("calidadAlumno").textContent = datos.calidadAlumno || "";

        document.getElementById("semestre").textContent = datos.semestre || "";

        document.getElementById("promedioGeneral").textContent = datos.promedioGeneral || "";

        document.getElementById("avanceProgramaEducativo").textContent = datos.avanceProgramaEducativo || "";

        document.getElementById("periodoClases").textContent = datos.periodoClases || "";

        document.getElementById("periodoVacacional").textContent = datos.periodoVacacional || "";

        corregirProgreso(datos.estatus, datos);

        modal.show();
    }

    //Buscar el detalle por Id
    function cargarDetallePorId(idSolicitud) {
        if (!idSolicitud) {
            mostrarMensaje("No existe la referencia a la solicitud.", "error");
            return;
        }

        consultar("consultar", {
            idSolicitud: idSolicitud,
        })
            .then(function (datos) {
                abrirModal(datos);
            })
            .catch(function (e) {
                mostrarMensaje(e.message || "No fue posible consultar la solicitud.", "error");
            });
    }

    function cancelarSolicitud(idSolicitud, estatus) {
        if (!idSolicitud) {
            mostrarMensaje("No puedes cancelar esta solicitud.", "error");
            return;
        } // Si ya está pagada o emitida no se debería poder cancelar
        if (estatus !== "Solicitada") {
            mostrarMensaje(
                "No puedes cancelar esta solicitud con estado: " + (estatus || "Desconocido") + ".",
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
            html: "¿Estás seguro de cancelar esta solicitud de Constancia?<br><br>",
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

            solicitar({ accion: "cancelar", idSolicitud: idSolicitud })
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
    }

    document.addEventListener("DOMContentLoaded", function () {

        var elementoModal = document.getElementById("modalSeguimientoConstancia");

        if (!elementoModal) {
            //Verifica que esté el modal
            mostrarMensaje("No se puede mostrar el detalle de la solicitud.", "error");
            return;
        }

        //Va sin "var" para poder funcionar correctamente
        modal = new bootstrap.Modal(elementoModal);

        cargarRegistros();

        document.addEventListener("click", function (evento) {
            var botonSeguimiento = evento.target.closest('[name="SeguimientoCorroborar"]');
            if (botonSeguimiento) {
                var idSolicitud = botonSeguimiento.getAttribute("data-id");

                limpiarModal();
                modal.show();

                cargarDetallePorId(idSolicitud);
                return;
            }

            var botonCancelar = evento.target.closest('[name="CancelarSolicitud"]');
            if (botonCancelar) {
                var idCancelar = botonCancelar.getAttribute("data-id");
                var estadoCancelar = botonCancelar.getAttribute("data-estado");
                cancelarSolicitud(idCancelar, estadoCancelar);
                return;
            }

            /*var botonFormatoPago = evento.target.closest('[name="DescargarFPago"]');
            if (botonFormatoPago) {
                estatus = "Pagada";
                return;
            }*/
        });
    });
})();
