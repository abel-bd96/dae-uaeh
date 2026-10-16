(function () {
    "use strict";

    var endpoint = "../../modules/constancia-estudios/models/modConstanciaGenerarSolicitud.php";
    var paso = 1;
    var modoEdicion = false;
    var todosLosRegistros = [];
    var idsSeleccionados = [];
    var observaciones = [];

    var ACCIONES_MASIVAS = {
        elaborar: { estatus: "En elaboración", titulo: "Elaborar" },
        mandarFirma: { estatus: "En firma", titulo: "Mandar a firma" },
        emitir: { estatus: "Emitida", titulo: "Emitir" },
        entregar: { estatus: "Entregado", titulo: "Entregar" },
        finalizar: { estatus: "Finalizado", titulo: "Finalizar" },
        cancelar: { estatus: "Cancelado", titulo: "Cancelar" },
    };
    // ENVIAR DATOS AL PHP (POST)
    function solicitar(datos) {
        var parametros = new URLSearchParams();
        var accion = datos.accion || "guardar";

        Object.keys(datos).forEach(function (clave) {
            if (clave === "accion") return;
            var valor = datos[clave];
            if (Array.isArray(valor)) {
                valor.forEach(function (v) {
                    parametros.append(clave + "[]", v);
                });
            } else {
                parametros.append(clave, valor == null ? "" : valor);
            }
        });
        parametros.append("accion", accion);

        return fetch(endpoint, { method: "POST", body: parametros }).then(function (respuesta) {
            return respuesta.text().then(function (texto) {
                var cuerpo;
                try {
                    cuerpo = JSON.parse(texto);
                } catch (e) {
                    console.error("Respuesta NO es JSON. Contenido recibido:");
                    console.error(texto);
                    throw new Error("El servidor devolvió un error. Revisa la consola.");
                }
                if (!respuesta.ok || !cuerpo.ok) {
                    throw new Error(cuerpo.mensaje || "No fue posible completar la operación.");
                }
                return cuerpo.datos;
            });
        });
    }

    // CONSULTAR DATOS (GET)
    function consultar(accion, texto) {
        var url = endpoint + "?accion=" + encodeURIComponent(accion) + "&texto=" + encodeURIComponent(texto || "");
        return fetch(url).then(function (respuesta) {
            return respuesta.text().then(function (txt) {
                var cuerpo;
                try {
                    cuerpo = JSON.parse(txt);
                } catch (e) {
                    console.error("Respuesta NO es JSON (GET " + accion + "):");
                    console.error(txt);
                    throw new Error("El servidor devolvió un error. Revisa la consola.");
                }
                if (!cuerpo.ok) throw new Error(cuerpo.mensaje || "Error en la consulta");
                return cuerpo.datos || [];
            });
        });
    }

    // UTILIDADES
    function escapeHtml(texto) {
        var div = document.createElement("div");
        div.textContent = texto || "";
        return div.innerHTML;
    }

    function mostrarMensaje(texto, tipo, idContenedor) {
        var contenedor = document.getElementById(idContenedor || "mensajeFormulario");
        if (!contenedor) return;
        contenedor.textContent = texto || "";
        contenedor.className = "alert alert-" + (tipo || "info");
        contenedor.classList.remove("d-none");
        if (contenedor._timeoutId) {
            clearTimeout(contenedor._timeoutId);
        }
        contenedor._timeoutId = setTimeout(
            function () {
                contenedor.classList.add("d-none");
                contenedor.textContent = "";
            },
            tipo === "success" ? 2000 : 8000,
        );
    }

    function limpiarMensaje(idContenedor) {
        var contenedor = document.getElementById(idContenedor || "mensajeFormulario");
        if (!contenedor) return;
        contenedor.className = "alert d-none";
        contenedor.textContent = "";
    }

    function mostrarError(id, texto) {
        var el = document.getElementById(id);
        if (!el) return;
        el.textContent = texto || "";
        el.classList.toggle("d-block", !!texto);
        el.classList.toggle("d-none", !texto);
    }
    //Pantallas
    function esPantallaListado() {
        return !!document.getElementById("tablaSolicitudes");
    }

    function esPantallaFormulario() {
        return !!document.getElementById("formSolicitud");
    }

    // SELECCIÓN MÚLTIPLE
    function actualizarContadorSeleccionadas() {
        var badge = document.getElementById("contadorSeleccionadas");
        var btn = document.getElementById("btnImprimirSeleccionadas");
        if (badge) badge.textContent = idsSeleccionados.length;
        if (btn) {
            if (idsSeleccionados.length > 0) {
                btn.classList.remove("d-none");
                btn.style.display = "inline-block";
            } else {
                btn.classList.add("d-none");
                btn.style.display = "none";
            }
        }
        var barra = document.getElementById("barraAccionesMasivas");
        var cont = document.getElementById("contadorAcciones");
        if (barra) barra.classList.toggle("d-none", idsSeleccionados.length === 0);
        if (cont) cont.textContent = idsSeleccionados.length;
    }

    function actualizarCheckTodos() {
        var checkTodos = document.getElementById("checkTodos");
        if (!checkTodos) return;
        var checks = document.querySelectorAll(".check-solicitud:not([disabled])");
        var marcados = document.querySelectorAll(".check-solicitud:checked");
        checkTodos.checked = checks.length > 0 && marcados.length === checks.length;
        checkTodos.indeterminate = marcados.length > 0 && marcados.length < checks.length;
    }

    function toggleSeleccion(id, marcado) {
        id = String(id);
        var idx = idsSeleccionados.indexOf(id);
        if (marcado === true && idx === -1) idsSeleccionados.push(id);
        else if (marcado === false && idx !== -1) idsSeleccionados.splice(idx, 1);

        actualizarContadorSeleccionadas();
        actualizarCheckTodos();
    }
    function limpiarSeleccion() {
        idsSeleccionados = [];
        document.querySelectorAll(".check-solicitud").forEach(function (chk) {
            chk.checked = false;
        });
        var checkTodos = document.getElementById("checkTodos");
        if (checkTodos) {
            checkTodos.checked = false;
            checkTodos.indeterminate = false;
        }
        actualizarContadorSeleccionadas();
    }

    // FILTROS
    function leerFiltros() {
        return {
            numeroCuenta: (document.getElementById("filtroNumeroCuenta").value || "").toLowerCase().trim(),
            nombre: (document.getElementById("filtroNombre").value || "").toLowerCase().trim(),
            estatus: (document.getElementById("filtroEstatus").value || "").toLowerCase().trim(),
            ciclo: (document.getElementById("filtroCiclo").value || "").toLowerCase().trim(),
            datoAdicional: (document.getElementById("filtroDatoAdicional").value || "").trim(),
            tipoFirma: (document.getElementById("filtroTipoFirma").value || "").trim(),
            fecha: (document.getElementById("filtroFecha").value || "").trim(),
            fechaDesde: (document.getElementById("filtroFechaDesde").value || "").trim(),
            fechaHasta: (document.getElementById("filtroFechaHasta").value || "").trim(),
        };
    }

    function aplicarFiltros() {
        var f = leerFiltros();
        var rango = calcularRangoFechas(f);

        return todosLosRegistros.filter(function (s) {
            var ciclo = String(s.CicloEscolar || "")
                .toLowerCase()
                .trim();
            var nc = String(s.NumeroCuenta || "")
                .toLowerCase()
                .trim();
            var nom = String(s.NombreCompleto || "")
                .toLowerCase()
                .trim();
            var est = String(s.Estatus || s.EstatusSolicitud || "")
                .toLowerCase()
                .trim();
            var fEst = String(f.estatus || "")
                .toLowerCase()
                .trim();
            var da = String(s.DatoAdicional || "").trim();
            var tf = String(s.TipoFirma || "").trim();

            if (f.ciclo && ciclo.indexOf(f.ciclo) === -1) return false;
            if (f.numeroCuenta && nc.indexOf(f.numeroCuenta) === -1) return false;
            if (f.nombre && nom.indexOf(f.nombre) === -1) return false;
            if (fEst && est !== fEst) return false;
            if (f.datoAdicional && da !== f.datoAdicional) return false;
            if (f.tipoFirma && tf !== f.tipoFirma) return false;

            if (rango) {
                var fecha = String(s.fechaRegistro || "").trim();
                if (!fecha) return false;
                if (rango.desde && fecha < rango.desde) return false;
                if (rango.hasta && fecha > rango.hasta) return false;
            }

            return true;
        });
    }

    function calcularRangoFechas(f) {
        if (!f.fecha) return null;

        var hoy = new Date();
        function toISO(d) {
            var y = d.getFullYear();
            var m = String(d.getMonth() + 1).padStart(2, "0");
            var day = String(d.getDate()).padStart(2, "0");
            return y + "-" + m + "-" + day;
        }

        if (f.fecha === "hoy") {
            var iso = toISO(hoy);
            return { desde: iso, hasta: iso };
        }

        if (f.fecha === "semana") {
            var dia = hoy.getDay();
            var diffLunes = dia === 0 ? -6 : 1 - dia;
            var lunes = new Date(hoy);
            lunes.setDate(hoy.getDate() + diffLunes);
            var domingo = new Date(lunes);
            domingo.setDate(lunes.getDate() + 6);
            return { desde: toISO(lunes), hasta: toISO(domingo) };
        }

        if (f.fecha === "mes") {
            var primero = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
            var ultimo = new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0);
            return { desde: toISO(primero), hasta: toISO(ultimo) };
        }

        if (f.fecha === "rango") {
            return { desde: f.fechaDesde || null, hasta: f.fechaHasta || null };
        }
        return null;
    }

    function hayFiltrosActivos() {
        var ids = [
            "filtroNumeroCuenta",
            "filtroNombre",
            "filtroEstatus",
            "filtroCiclo",
            "filtroDatoAdicional",
            "filtroTipoFirma",
            "filtroFecha",
        ];
        for (var i = 0; i < ids.length; i++) {
            var el = document.getElementById(ids[i]);
            if (el && (el.value || "").trim() !== "") return true;
        }
        return false;
    }

    // BOTONES SEGÚN ESTATUS
    function generarBotones(s) {
        var id = escapeHtml(s.id || s.idSolicitud || "");
        var estatus = s.Estatus || s.EstatusSolicitud || "";
        var botones = [];

        // Estados terminales
        if (estatus === "Cancelado") {
            return (
                '<button type="button" class="btn btn-sm btn-outline-secondary" disabled>' +
                '<i class="bi bi-x-circle"></i> Cancelada</button>'
            );
        }

        if (estatus === "Finalizado") {
            return (
                '<button type="button" class="btn btn-sm btn-outline-success" disabled>' +
                '<i class="bi bi-check-circle"></i> Finalizada</button>'
            );
        }

        // --- Flujo A ---
        if (estatus === "Solicitado") {
            botones.push(
                '<button type="button" class="btn btn-sm btn-outline-success btn-pagar" data-id="' +
                    id +
                    '">' +
                    '<i class="bi bi-cash-coin"></i> Pagado</button>',
            );
        }

        if (estatus === "Pagado") {
            botones.push(
                '<button type="button" class="btn btn-sm btn-outline-info btn-elaborar" data-id="' +
                    id +
                    '">' +
                    '<i class="bi bi-gear"></i> Elaborar</button>',
            );
        }

        // --- Flujo B ---
        if (estatus === "En elaboración") {
            botones.push(
                '<button type="button" class="btn btn-sm btn-outline-warning btn-mandar-firma" data-id="' +
                    id +
                    '">' +
                    '<i class="bi bi-pen"></i> Mandar a firma</button>',
            );
            botones.push(
                '<button type="button" class="btn btn-sm btn-outline-secondary btn-atender" data-id="' +
                    id +
                    '">' +
                    '<i class="bi bi-person-check"></i> Atendido</button>',
            );
        }

        if (estatus === "En firma") {
            botones.push(
                '<button type="button" class="btn btn-sm btn-outline-success btn-emitir" data-id="' +
                    id +
                    '">' +
                    '<i class="bi bi-file-earmark-check"></i> Emitida</button>',
            );
        }

        if (estatus === "Emitida") {
            botones.push(
                '<button type="button" class="btn btn-sm btn-outline-success btn-entregar" data-id="' +
                    id +
                    '">' +
                    '<i class="bi bi-box-arrow-right"></i> Entregado</button>',
            );
        }

        if (estatus === "Atendido" || estatus === "Entregado") {
            botones.push(
                '<button type="button" class="btn btn-sm btn-outline-dark btn-finalizar" data-id="' +
                    id +
                    '">' +
                    '<i class="bi bi-flag"></i> Finalizar</button>',
            );
        }

        // Cancelar
        if (!["Entregado", "Finalizado"].includes(estatus)) {
            botones.push(
                '<button type="button" class="btn btn-sm btn-outline-danger btn-cancelar" data-id="' +
                    id +
                    '">' +
                    '<i class="bi bi-x-circle"></i> Cancelar</button>',
            );
        }

        return botones.join(" ");
    }

    // TABLA
    function pintarTabla(registros) {
        var tbody = document.querySelector("#tablaSolicitudes tbody");
        if (!tbody) return;

        if (!registros.length) {
            tbody.innerHTML =
                '<tr><td colspan="9" class="text-center text-muted py-4">' +
                "No se encontraron solicitudes con los filtros aplicados." +
                "</td></tr>";
            actualizarContadorSeleccionadas();
            actualizarCheckTodos();
            return;
        }

        tbody.innerHTML = registros
            .map(function (s) {
                var id = String(s.id || s.idSolicitud || "");
                var nombre =
                    s.NombreCompleto ||
                    [s.Nombre, s.ApellidoPaterno, s.ApellidoMaterno].filter(Boolean).join(" ") ||
                    s.nombre ||
                    "(Sin nombre)";

                var ciclo = s.CicloEscolar || s.Periodo || s.ciclo || "(Sin ciclo)";
                var estatus = s.Estatus || s.EstatusSolicitud || "";

                var checkDisabled = estatus === "Cancelado" ? "disabled" : "";
                var checkChecked = idsSeleccionados.indexOf(id) !== -1 ? "checked" : "";

                return (
                    "<tr>" +
                    '<td><input type="checkbox" class="form-check-input check-solicitud" ' +
                    'data-id="' +
                    escapeHtml(id) +
                    '" ' +
                    checkChecked +
                    " " +
                    checkDisabled +
                    "></td>" +
                    "<td>" +
                    escapeHtml(s.fechaRegistro || "") +
                    "</td>" +
                    "<td>" +
                    escapeHtml(ciclo) +
                    "</td>" +
                    "<td>" +
                    escapeHtml(s.NumeroCuenta || "") +
                    "</td>" +
                    "<td>" +
                    escapeHtml(nombre) +
                    "</td>" +
                    "<td>" +
                    escapeHtml(estatus) +
                    "</td>" +
                    "<td>" +
                    escapeHtml(s.DatoAdicional || "") +
                    "</td>" +
                    "<td>" +
                    escapeHtml(s.TipoFirma === "S" ? "Digital (PDF)" : s.TipoFirma === "N" ? "Autógrafa" : "") +
                    "</td>" +
                    '<td class="text-end">' +
                    generarBotones(s) +
                    "</td>" +
                    "</tr>"
                );
            })
            .join("");

        tbody.querySelectorAll(".check-solicitud").forEach(function (chk) {
            chk.addEventListener("change", function (evento) {
                var checkbox = evento.target;
                toggleSeleccion(checkbox.dataset.id, checkbox.checked);
            });
        });

        actualizarContadorSeleccionadas();
        actualizarCheckTodos();
    }

    function cargarSolicitudes() {
        limpiarMensaje("mensajeSolicitud");

        if (!hayFiltrosActivos()) {
            mostrarMensaje("Seleccione al menos un filtro para poder buscar.", "warning", "mensajeSolicitud");
            document.querySelector("#tablaSolicitudes tbody").innerHTML =
                '<tr><td colspan="9" class="text-center text-muted py-4">' +
                "Seleccione al menos un filtro y presione <strong>Buscar</strong>." +
                "</td></tr>";

            todosLosRegistros = [];
            limpiarSeleccion();
            return;
        }

        consultar("listar")
            .then(function (solicitudes) {
                todosLosRegistros = solicitudes || [];
                pintarTabla(aplicarFiltros());
            })
            .catch(function (error) {
                mostrarMensaje(error.message, "danger", "mensajeSolicitud");
            });
    }

    function limpiarFiltros() {
        [
            "filtroCiclo",
            "filtroNumeroCuenta",
            "filtroNombre",
            "filtroEstatus",
            "filtroDatoAdicional",
            "filtroTipoFirma",
            "filtroFecha",
            "filtroFechaDesde",
            "filtroFechaHasta",
        ].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.value = "";
        });
        var wrapDesde = document.getElementById("wrapFechaDesde");
        var wrapHasta = document.getElementById("wrapFechaHasta");
        if (wrapDesde) wrapDesde.classList.add("d-none");
        if (wrapHasta) wrapHasta.classList.add("d-none");

        todosLosRegistros = [];
        limpiarSeleccion();

        document.querySelector("#tablaSolicitudes tbody").innerHTML =
            '<tr><td colspan="9" class="text-center text-muted py-4">' +
            "Seleccione los filtros y presione <strong>Buscar</strong> para ver resultados" +
            "</td></tr>";

        actualizarContadorSeleccionadas();
        actualizarCheckTodos();
    }

    // MODAL GENÉRICO
    function abrirModal(opciones) {
        if (typeof bootstrap === "undefined" || typeof bootstrap.Modal === "undefined") {
            console.error("Bootstrap 5 no está cargado. Revisa el orden de tus <script>.");
            return;
        }

        var modalEl = document.getElementById("modalConfirmar");
        if (!modalEl) {
            console.error("No existe el modal con id='modalConfirmar' en el DOM.");
            if (confirm(opciones.mensaje || "¿Continuar?")) opciones.onAceptar(null);
            return;
        }

        var titulo = document.getElementById("modalConfirmarTitulo");
        var mensaje = document.getElementById("modalConfirmarMensaje");
        var selectWrap = document.getElementById("modalConfirmarSelectWrap");
        var select = document.getElementById("modalConfirmarSelect");
        var btnOk = document.getElementById("modalConfirmarAceptar");

        if (!titulo || !mensaje || !selectWrap || !select || !btnOk) {
            console.error("Faltan elementos del modal en el HTML");
            return;
        }

        titulo.textContent = opciones.titulo || "Confirmar acción";
        mensaje.textContent = opciones.mensaje || "¿Está seguro?";

        if (opciones.tipo === "select") {
            selectWrap.classList.remove("d-none");
            select.innerHTML = "";
            (opciones.opciones || []).forEach(function (op) {
                var opt = document.createElement("option");
                opt.value = op;
                opt.textContent = op;
                select.appendChild(opt);
            });
            if (opciones.valorInicial) select.value = opciones.valorInicial;
        } else {
            selectWrap.classList.add("d-none");
        }

        btnOk.className = "btn " + (opciones.btnClass || "btn-primary");
        btnOk.textContent = opciones.btnTexto || "Aceptar";

        // Clonar para eliminar listeners previos
        var btnOkNuevo = btnOk.cloneNode(true);
        btnOk.parentNode.replaceChild(btnOkNuevo, btnOk);

        btnOkNuevo.addEventListener("click", function () {
            var valor = opciones.tipo === "select" ? select.value : null;
            if (document.activeElement && typeof document.activeElement.blur === "function") {
                document.activeElement.blur();
            }
            var modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modalInstance.hide();
            if (typeof opciones.onAceptar === "function") opciones.onAceptar(valor);
        });

        var modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modalInstance.show();
    }

    // ACCIONES — cada función definida UNA SOLA VEZ

    // Flujo A - Paso 2: Pagar (con selección de tipo de firma)
    function pagarSolicitud(id) {
        abrirModal({
            titulo: "Registrar pago",
            mensaje: "Seleccione el tipo de firma para esta constancia:",
            tipo: "select",
            opciones: ["Digital (PDF)", "Autógrafa"],
            valorInicial: "Digital (PDF)",
            btnTexto: "Confirmar pago",
            btnClass: "btn-success",
            onAceptar: function (tipoFirmaSeleccionado) {
                var tipoFirma = tipoFirmaSeleccionado === "Digital (PDF)" ? "S" : "N";
                solicitar({ accion: "pagar", id: id, tipoFirma: tipoFirma })
                    .then(function () {
                        mostrarMensaje("Pago registrado correctamente.", "success", "mensajeSolicitud");
                        cargarSolicitudes();
                    })
                    .catch(function (error) {
                        mostrarMensaje(error.message, "danger", "mensajeSolicitud");
                    });
            },
        });
    }

    // Flujo A y B - Paso 3/4: En elaboración
    function elaborarSolicitud(id) {
        abrirModal({
            titulo: "Elaborar solicitud",
            mensaje: "¿Marcar esta solicitud como 'En elaboración'?",
            btnTexto: "Sí, elaborar",
            btnClass: "btn-info",
            onAceptar: function () {
                solicitar({ accion: "elaborar", id: id })
                    .then(function () {
                        mostrarMensaje("La solicitud se marcó en elaboración.", "success", "mensajeSolicitud");
                        cargarSolicitudes();
                    })
                    .catch(function (error) {
                        mostrarMensaje(error.message, "danger", "mensajeSolicitud");
                    });
            },
        });
    }

    // Flujo B - Paso 5: En firma
    function mandarFirmaSolicitud(id) {
        abrirModal({
            titulo: "Mandar a firma",
            mensaje: "¿Mandar esta solicitud a firma?",
            btnTexto: "Sí, mandar",
            btnClass: "btn-warning",
            onAceptar: function () {
                solicitar({ accion: "mandarFirma", id: id })
                    .then(function () {
                        mostrarMensaje("La solicitud se envió a firma.", "success", "mensajeSolicitud");
                        cargarSolicitudes();
                    })
                    .catch(function (error) {
                        mostrarMensaje(error.message, "danger", "mensajeSolicitud");
                    });
            },
        });
    }

    // Flujo B - Paso 6: Emitida
    function emitirSolicitud(id) {
        abrirModal({
            titulo: "Emitir constancia",
            mensaje: "¿Marcar esta constancia como emitida?",
            btnTexto: "Sí, emitir",
            btnClass: "btn-success",
            onAceptar: function () {
                solicitar({ accion: "emitir", id: id })
                    .then(function () {
                        mostrarMensaje("La constancia se emitió correctamente.", "success", "mensajeSolicitud");
                        cargarSolicitudes();
                    })
                    .catch(function (error) {
                        mostrarMensaje(error.message, "danger", "mensajeSolicitud");
                    });
            },
        });
    }

    // Flujo B - Paso 8: Entregado
    function entregarSolicitud(id) {
        abrirModal({
            titulo: "Entregar constancia",
            mensaje: "¿Marcar esta constancia como entregada al alumno?",
            btnTexto: "Sí, entregar",
            btnClass: "btn-success",
            onAceptar: function () {
                solicitar({ accion: "entregar", id: id })
                    .then(function () {
                        mostrarMensaje("La constancia se marcó como entregada.", "success", "mensajeSolicitud");
                        cargarSolicitudes();
                    })
                    .catch(function (error) {
                        mostrarMensaje(error.message, "danger", "mensajeSolicitud");
                    });
            },
        });
    }

    // Flujo A - Paso 9: Atendido
    function atenderSolicitud(id) {
        abrirModal({
            titulo: "Marcar como atendido",
            mensaje: "¿Marcar este trámite como atendido?",
            btnTexto: "Sí, atender",
            btnClass: "btn-secondary",
            onAceptar: function () {
                solicitar({ accion: "atender", id: id })
                    .then(function () {
                        mostrarMensaje("El trámite se marcó como atendido.", "success", "mensajeSolicitud");
                        cargarSolicitudes();
                    })
                    .catch(function (error) {
                        mostrarMensaje(error.message, "danger", "mensajeSolicitud");
                    });
            },
        });
    }

    // Flujo A y B - Paso 10: Finalizado
    function finalizarSolicitud(id) {
        abrirModal({
            titulo: "Finalizar solicitud",
            mensaje: "¿Marcar esta solicitud como finalizada? Esta acción cierra el trámite.",
            btnTexto: "Sí, finalizar",
            btnClass: "btn-dark",
            onAceptar: function () {
                solicitar({ accion: "finalizar", id: id })
                    .then(function () {
                        mostrarMensaje("La solicitud se finalizó correctamente.", "success", "mensajeSolicitud");
                        cargarSolicitudes();
                    })
                    .catch(function (error) {
                        mostrarMensaje(error.message, "danger", "mensajeSolicitud");
                    });
            },
        });
    }

    // Cancelar
    function cancelarSolicitud(id) {
        abrirModal({
            titulo: "Cancelar solicitud",
            mensaje: "¿Está seguro de cancelar esta solicitud? Esta acción no se puede deshacer.",
            btnTexto: "Sí, cancelar",
            btnClass: "btn-danger",
            onAceptar: function () {
                solicitar({ accion: "cancelar", id: id })
                    .then(function () {
                        mostrarMensaje("La solicitud se canceló correctamente.", "success", "mensajeSolicitud");
                        cargarSolicitudes();
                    })
                    .catch(function (error) {
                        mostrarMensaje(error.message, "danger", "mensajeSolicitud");
                    });
            },
        });
    }
    // Editar
    function editarSolicitud(id) {
        limpiarMensaje();
        window.location.href = "?vta=CrearSolicitud&id=" + encodeURIComponent(id);
    }

    //acciones maxivas
    function ejecutarAccionMasiva(accion) {
        if (!idsSeleccionados.length) {
            mostrarMensaje("No hay solicitudes seleccionadas.", "warning", "mensajeSolicitud");
            return;
        }

        // Imprimir no cambia estatus
        if (accion === "imprimir") {
            imprimirSeleccionadas();
            return;
        }

        var cfg = ACCIONES_MASIVAS[accion];
        if (!cfg) {
            mostrarMensaje("Acción desconocida: " + accion, "danger", "mensajeSolicitud");
            return;
        }

        abrirModal({
            titulo: cfg.titulo + " seleccionadas",
            mensaje:
                "Se intentará pasar " +
                idsSeleccionados.length +
                ' solicitud(es) a "' +
                cfg.estatus +
                '". ' +
                "Las que no cumplan con la transición serán omitidas.",
            btnTexto: "Sí, " + cfg.titulo.toLowerCase(),
            btnClass: "btn-primary",
            onAceptar: function () {
                solicitar({
                    accion: "cambiarEstatusMasivo",
                    ids: idsSeleccionados,
                    estatus: cfg.estatus,
                })
                    .then(function (resultado) {
                        var ok = resultado && resultado.procesados ? resultado.procesados.length : 0;
                        var om = resultado && resultado.omitidos ? resultado.omitidos.length : 0;
                        var msg = ok + " actualizada(s).";
                        if (om > 0) msg += " " + om + " omitida(s).";
                        mostrarMensaje(msg, om > 0 ? "warning" : "success", "mensajeSolicitud");
                        limpiarSeleccion();
                        cargarSolicitudes();
                    })
                    .catch(function (error) {
                        mostrarMensaje(error.message, "danger", "mensajeSolicitud");
                    });
            },
        });
    }
    // IMPRIMIR SELECCIONADAS
    function imprimirSeleccionadas() {
        if (!idsSeleccionados.length) {
            mostrarMensaje("No hay solicitudes seleccionadas.", "warning", "mensajeSolicitud");
            return;
        }
        var seleccionadas = todosLosRegistros.filter(function (s) {
            return idsSeleccionados.indexOf(String(s.id || s.idSolicitud)) !== -1;
        });

        if (!seleccionadas.length) {
            mostrarMensaje("No se encontraron los registros seleccionados.", "danger", "mensajeSolicitud");
            return;
        }

        var ventana = window.open("", "_blank");
        if (!ventana) {
            mostrarMensaje("Permite las ventanas emergentes para imprimir.", "danger", "mensajeSolicitud");
            return;
        }

        var html =
            "" +
            "<!DOCTYPE html>" +
            "<html lang='es'>" +
            "<head>" +
            "<meta charset='UTF-8'>" +
            "<title>Constancias seleccionadas</title>" +
            "<style>" +
            "  body { font-family: Arial, sans-serif; padding: 20px; }" +
            "  .constancia { page-break-after: always; padding: 40px 20px; border-bottom: 1px dashed #999; }" +
            "  .constancia:last-child { page-break-after: auto; border-bottom: none; }" +
            "  h2 { margin-top: 0; }" +
            "  .fila { margin: 8px 0; }" +
            "  .label { font-weight: bold; display: inline-block; width: 180px; }" +
            "  @media print { .no-print { display: none; } }" +
            "</style>" +
            "</head>" +
            "<body>" +
            "<div class='no-print' style='margin-bottom:20px;'>" +
            "  <button onclick='window.print()' style='padding:10px 20px; font-size:16px; cursor:pointer;'>" +
            "    🖨️ Imprimir" +
            "  </button>" +
            "  <span style='margin-left:15px; color:#666;'>" +
            "    " +
            seleccionadas.length +
            " constancia(s) lista(s) para imprimir." +
            "  </span>" +
            "</div>";

        seleccionadas.forEach(function (s) {
            var nombre =
                s.NombreCompleto ||
                [s.Nombre, s.ApellidoPaterno, s.ApellidoMaterno].filter(Boolean).join(" ") ||
                s.nombre ||
                "";

            html +=
                "" +
                "<div class='constancia'>" +
                "  <h2>Constancia de Estudios</h2>" +
                "  <div class='fila'><span class='label'>Número de Cuenta:</span>" +
                escapeHtml(s.NumeroCuenta || "") +
                "</div>" +
                "  <div class='fila'><span class='label'>Nombre del Alumno:</span>" +
                escapeHtml(nombre) +
                "</div>" +
                "  <div class='fila'><span class='label'>Ciclo Escolar:</span>" +
                escapeHtml(s.CicloEscolar || "") +
                "</div>" +
                "  <div class='fila'><span class='label'>Instituto:</span>" +
                escapeHtml(s.Instituto || "") +
                "</div>" +
                "  <div class='fila'><span class='label'>Semestre:</span>" +
                escapeHtml(s.Semestre || "") +
                "</div>" +
                "  <div class='fila'><span class='label'>Programa Educativo:</span>" +
                escapeHtml(s.ProgramaEducativo || "") +
                "</div>" +
                "  <div class='fila'><span class='label'>Datos Adicionales:</span>" +
                escapeHtml(s.DatoAdicional === "S" ? "Sí" : "No") +
                "</div>" +
                "  <div class='fila'><span class='label'>Tipo de Firma:</span>" +
                escapeHtml(s.TipoFirma === "S" ? "Digital (PDF)" : "Autógrafa") +
                "</div>" +
                "  <div class='fila'><span class='label'>Calificaciones:</span>" +
                escapeHtml(s.Calificaciones === "S" ? "Sí" : "No") +
                "</div>" +
                "  <div class='fila'><span class='label'>Observación:</span>" +
                escapeHtml(s.Observacion || "") +
                "</div>" +
                "</div>";
        });

        html += "</body></html>";

        ventana.document.open();
        ventana.document.write(html);
        ventana.document.close();
    }

    // Cambiar estatus manual (con la lista COMPLETA)
    function cambiarEstatusSolicitud(id, estatusActual) {
        var TRANSICIONES = {
            Solicitado: ["Pagado", "Cancelado"],
            Pagado: ["En elaboración", "Cancelado"],
            "En elaboración": ["En firma", "Atendido", "Cancelado"],
            "En firma": ["Emitida", "Cancelado"],
            Emitida: ["Entregado", "Cancelado"],
            Entregado: ["Finalizado"],
            Atendido: ["Finalizado"],
            Finalizado: [],
            Cancelado: [],
        };

        var opciones = TRANSICIONES[estatusActual] || [];
        if (!opciones.length) {
            mostrarMensaje(
                "No hay transiciones disponibles desde el estatus actual (" + estatusActual + ").",
                "warning",
                "mensajeSolicitud",
            );
            return;
        }

        abrirModal({
            titulo: "Cambiar estatus",
            mensaje: "Estatus actual: " + estatusActual + ". Seleccione el nuevo estatus:",
            tipo: "select",
            opciones: opciones,
            valorInicial: opciones[0],
            btnTexto: "Cambiar estatus",
            btnClass: "btn-primary",
            onAceptar: function (nuevoEstatus) {
                if (!nuevoEstatus) return;
                solicitar({ accion: "cambiarEstatus", id: id, estatus: nuevoEstatus })
                    .then(function () {
                        mostrarMensaje("El estatus se actualizó correctamente.", "success", "mensajeSolicitud");
                        cargarSolicitudes();
                    })
                    .catch(function (error) {
                        mostrarMensaje(error.message, "danger", "mensajeSolicitud");
                    });
            },
        });
    }

    // NAVEGACIÓN POR PASOS (FORMULARIO)
    function mostrarPaso(numero) {
        if (modoEdicion && numero < 2) numero = 2;

        paso = numero;
        document.querySelectorAll(".paso-solicitud").forEach(function (seccion) {
            seccion.classList.toggle("d-none", Number(seccion.dataset.paso) !== paso);
        });

        var subtitulo = document.getElementById("subtituloFormularioSolicitud");
        if (subtitulo) {
            subtitulo.textContent = modoEdicion ? "Paso " + (paso - 1) + " de 2" : "Paso " + paso + " de 3";
        }
        var barra = document.getElementById("barraPaso");
        if (barra) {
            barra.style.width = modoEdicion ? ((paso - 1) / 2) * 100 + "%" : (paso / 3) * 100 + "%";
        }
        var btnAnterior = document.getElementById("btnAnterior");
        var btnSiguiente = document.getElementById("btnSiguiente");
        var btnGuardar = document.getElementById("btnGuardar");

        if (btnAnterior) {
            btnAnterior.classList.toggle("invisible", paso === 1 || (modoEdicion && paso === 2));
        }
        if (btnSiguiente) btnSiguiente.classList.toggle("d-none", paso === 3);
        if (btnGuardar) btnGuardar.classList.toggle("d-none", paso !== 3);

        mostrarError("errorNumeroCuenta", "");
        mostrarError("errorDatos", "");

        if (paso === 3) {
            actualizarResumen();
            actualizarVisibilidadDatosAdicionales();
        }
    }

    function validarPaso() {
        if (paso === 1) {
            var numero = document.getElementById("NumeroCuenta").value.trim();
            if (numero === "") {
                mostrarError("errorNumeroCuenta", "Seleccione un alumno");
                return false;
            }
        }
        if (paso === 2) {
            var da = document.querySelector('input[name="DatoAdicional"]:checked');
            var tf = document.querySelector('input[name="TipoFirma"]:checked');
            var cal = document.querySelector('input[name="Calificaciones"]:checked');
            if (!da || !tf || !cal) {
                mostrarError("errorDatos", "Complete todas las opciones");
                return false;
            }
        }
        return true;
    }

    // BUSCAR NÚMERO DE CUENTA
    function pintarNumeroCuenta(texto) {
        consultar("numeroCuenta", texto)
            .then(function (alumnos) {
                var resultados = document.getElementById("resultadosNumeroCuenta");
                if (!resultados) return;

                if (!alumnos.length) {
                    resultados.innerHTML = '<div class="texto-muted small p-2">No se encontraron alumnos.</div>';
                    return;
                }
                resultados.innerHTML =
                    alumnos
                        .map(function (alumno) {
                            var numero = String(alumno.NumeroCuenta || "");
                            var nombreCompleto = [
                                alumno.Nombre || alumno.nombre || "",
                                alumno.ApellidoPaterno || "",
                                alumno.ApellidoMaterno || "",
                            ]
                                .join(" ")
                                .trim();

                            var detalle = [
                                alumno.Instituto || "",
                                alumno.Semestre || "",
                                alumno.ProgramaEducativo || "",
                                alumno.CicloEscolar || "",
                            ]
                                .join(" ")
                                .trim();

                            return (
                                '<button type="button" ' +
                                'class="list-group-item list-group-item-action" ' +
                                'data-numero="' +
                                escapeHtml(numero) +
                                '" ' +
                                'data-nombre-completo="' +
                                escapeHtml(alumno.NombreCompleto || "") +
                                '" ' +
                                'data-instituto="' +
                                escapeHtml(alumno.Instituto || "") +
                                '" ' +
                                'data-semestre="' +
                                escapeHtml(alumno.Semestre || "") +
                                '" ' +
                                'data-programa-educativo="' +
                                escapeHtml(alumno.ProgramaEducativo || "") +
                                '" ' +
                                'data-ciclo-escolar="' +
                                escapeHtml(alumno.CicloEscolar || "") +
                                '" ' +
                                ">" +
                                "<strong>" +
                                escapeHtml(numero) +
                                "</strong>" +
                                (nombreCompleto ? "<br><small>" + escapeHtml(nombreCompleto) + "</small>" : "") +
                                (detalle ? '<br><small class="text-muted">' + escapeHtml(detalle) + "</small>" : "") +
                                "</button>"
                            );
                        })
                        .join("") || '<div class="text-muted small">No se encontraron alumnos.</div>';
            })
            .catch(function (error) {
                mostrarMensaje(error.message, "danger", "mensajeFormulario");
            });
    }

    function seleccionarAlumno(elemento) {
        var campos = {
            CicloEscolar: elemento.dataset.cicloEscolar || "",
            NumeroCuenta: elemento.dataset.numero || "",
            NombreCompleto: elemento.dataset.nombreCompleto || "",
            Instituto: elemento.dataset.instituto || "",
            Semestre: elemento.dataset.semestre || "",
            ProgramaEducativo: elemento.dataset.programaEducativo || "",
        };
        Object.keys(campos).forEach(function (k) {
            var input = document.getElementById(k);
            if (input) input.value = campos[k];
            var span = document.getElementById("mostrar" + k);
            if (span) span.textContent = campos[k];
        });
        var datosAlumno = document.getElementById("datosAlumno");
        var datosInstitucional = document.getElementById("datosInstitucional");
        if (datosAlumno) datosAlumno.classList.remove("d-none");
        if (datosInstitucional) datosInstitucional.classList.remove("d-none");
        var resultados = document.getElementById("resultadosNumeroCuenta");
        if (resultados) resultados.innerHTML = "";
        mostrarError("errorNumeroCuenta", "");
    }

    //agregar las observaciones
    function renderObservaciones() {
        var lista = document.getElementById("listaObservaciones");
        if (!lista) return;
        if (!observaciones.length) {
            lista.innerHTML = '<li class="list-group-item text-muted small">Sin observaciones agregadas.</li>';
            return;
        }
        lista.innerHTML = observaciones
            .map(function (o, i) {
                return (
                    '<li class="list-group-item d-flex justify-content-between align-items-center">' +
                    "<div><strong>" +
                    escapeHtml(o.nombre) +
                    ":</strong> " +
                    escapeHtml(o.valor) +
                    "</div>" +
                    '<button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-observacion" data-index="' +
                    i +
                    '"><i class="bi bi-trash"></i></button>' +
                    "</li>"
                );
            })
            .join("");
    }
    function actualizarVisibilidadDatosAdicionales() {
        var wrap = document.getElementById("wrapDatosAdicionales");
        if (!wrap) return;

        var seleccionado = document.querySelector('input[name="DatoAdicional"]:checked');
        var esSi = seleccionado && seleccionado.value === "S";

        wrap.classList.toggle("d-none", !esSi);

        // Si eligen "No", limpiamos lo que hubieran agregado
        if (!esSi && observaciones.length) {
            observaciones = [];
            renderObservaciones();
        }
    }
    function agregarObservacion() {
        var nombreEl = document.getElementById("observacionNombre");
        var valorEl = document.getElementById("observacionValor");
        if (!nombreEl || !valorEl) return;

        var nombre = (nombreEl.value || "").trim();
        var valor = (valorEl.value || "").trim();
        mostrarError("errorObservaciones", "");
        if (!nombre) {
            mostrarError("errorObservaciones", "Ingrese el nombre del campo.");
            nombreEl.focus();
            return;
        }
        if (!valor) {
            mostrarError("errorObservaciones", "Ingrese la información del campo.");
            valorEl.focus();
            return;
        }

        observaciones.push({ nombre: nombre, valor: valor });
        nombreEl.value = "";
        valorEl.value = "";
        nombreEl.focus();
        renderObservaciones();
    }

    // FORMULARIO
    function datosFormulario() {
        var datoAdicional = document.querySelector('input[name="DatoAdicional"]:checked');
        var tipoFirma = document.querySelector('input[name="TipoFirma"]:checked');
        var calificaciones = document.querySelector('input[name="Calificaciones"]:checked');
        return {
            accion: modoEdicion ? "actualizar" : "guardar",
            id: document.getElementById("solicitudId").value,
            CicloEscolar: document.getElementById("CicloEscolar").value,
            NumeroCuenta: document.getElementById("NumeroCuenta").value,
            NombreCompleto: document.getElementById("NombreCompleto").value,
            Instituto: document.getElementById("Instituto").value,
            Semestre: document.getElementById("Semestre").value,
            ProgramaEducativo: document.getElementById("ProgramaEducativo").value,
            DatoAdicional: datoAdicional ? datoAdicional.value : "",
            TipoFirma: tipoFirma ? tipoFirma.value : "",
            Calificaciones: calificaciones ? calificaciones.value : "",
            Observaciones: JSON.stringify(observaciones),
        };
    }

    function actualizarResumen() {
        var datos = datosFormulario();
        var datoAdicional = datos.DatoAdicional === "S" ? "Sí" : "No";
        var firma = datos.TipoFirma === "S" ? "Digital (PDF)" : "Autógrafa";
        var calificaciones = datos.Calificaciones === "S" ? "Sí" : "No";

        var datosInstitucionales = [datos.Instituto, datos.Semestre, datos.ProgramaEducativo, datos.CicloEscolar]
            .filter(Boolean)
            .join(" ")
            .trim();

        var resumen = document.getElementById("resumenSolicitud");
        if (!resumen) return;

        var observacionesHtml = observaciones.length
            ? "<ul class='mb-0'>" +
              observaciones
                  .map(function (o) {
                      return "<li><strong>" + escapeHtml(o.nombre) + ":</strong> " + escapeHtml(o.valor) + "</li>";
                  })
                  .join("") +
              "</ul>"
            : "<em>Sin observaciones</em>";

        resumen.innerHTML =
            "<p><strong>Número de Cuenta:</strong> " +
            escapeHtml(datos.NumeroCuenta) +
            "</p>" +
            "<p><strong>Alumno:</strong> " +
            escapeHtml(datos.NombreCompleto) +
            "</p>" +
            "<p><strong>Datos institucionales:</strong> " +
            escapeHtml(datosInstitucionales) +
            "</p>" +
            "<p><strong>Datos adicionales:</strong> " +
            escapeHtml(datoAdicional) +
            "</p>" +
            "<p><strong>Tipo de firma:</strong> " +
            escapeHtml(firma) +
            "</p>" +
            "<p><strong>Calificaciones:</strong> " +
            escapeHtml(calificaciones) +
            "</p>" +
            "<p class='mb-0'><strong>Observaciones:</strong></p>" +
            observacionesHtml;
    }

    function seleccionarRadio(nombre, valor) {
        var radios = document.querySelectorAll('input[name="' + nombre + '"]');
        radios.forEach(function (radio) {
            radio.checked = String(radio.value) === String(valor);
        });
    }

    function iniciarFormulario() {
        limpiarMensaje("mensajeFormulario");
        var params = new URLSearchParams(window.location.search);
        var id = params.get("id");

        var btnSiguiente = document.getElementById("btnSiguiente");
        if (btnSiguiente) {
            btnSiguiente.addEventListener("click", function () {
                if (validarPaso()) mostrarPaso(Math.min(3, paso + 1));
            });
        }
        var btnAnterior = document.getElementById("btnAnterior");
        if (btnAnterior) {
            btnAnterior.addEventListener("click", function () {
                mostrarPaso(Math.max(1, paso - 1));
            });
        }
        var btnGuardar = document.getElementById("btnGuardar");
        if (btnGuardar) btnGuardar.addEventListener("click", guardarSolicitud);
        var form = document.getElementById("formSolicitud");
        if (form) {
            form.addEventListener("submit", function (e) {
                e.preventDefault();
                guardarSolicitud();
            });
        }
        var buscar = document.getElementById("buscarNumeroCuenta");
        if (buscar) {
            buscar.addEventListener("input", function () {
                var texto = this.value.trim();
                if (texto.length < 6) {
                    var resultados = document.getElementById("resultadosNumeroCuenta");
                    if (resultados) resultados.innerHTML = "";
                    return;
                }
                pintarNumeroCuenta(texto);
            });
        }
        var resultados = document.getElementById("resultadosNumeroCuenta");
        if (resultados) {
            resultados.addEventListener("click", function (evento) {
                var boton = evento.target.closest("button");
                if (boton && boton.dataset.numero) seleccionarAlumno(boton);
            });
        }
        // Escuchar cambios en "¿Desea datos adicionales?"
        document.querySelectorAll('input[name="DatoAdicional"]').forEach(function (radio) {
            radio.addEventListener("change", actualizarVisibilidadDatosAdicionales);
        });

        // Observaciones: agregar / eliminar / Enter
        var btnAgregarObservacion = document.getElementById("btnAgregarObservacion");
        if (btnAgregarObservacion) btnAgregarObservacion.addEventListener("click", agregarObservacion);

        var inputObsNombre = document.getElementById("observacionNombre");
        var inputObsValor = document.getElementById("observacionValor");
        [inputObsNombre, inputObsValor].forEach(function (el) {
            if (!el) return;
            el.addEventListener("keydown", function (e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                    agregarObservacion();
                }
            });
        });

        var listaObservaciones = document.getElementById("listaObservaciones");
        if (listaObservaciones) {
            listaObservaciones.addEventListener("click", function (e) {
                var btn = e.target.closest(".btn-eliminar-observacion");
                if (!btn) return;
                observaciones.splice(Number(btn.dataset.index), 1);
                renderObservaciones();
            });
        }

        renderObservaciones();

        if (id) precargarSolicitud(id);
        else mostrarPaso(1);
    }

    function precargarSolicitud(id) {
        modoEdicion = true;

        solicitar({ accion: "consultar", id: id })
            .then(function (solicitud) {
                if (!solicitud) {
                    mostrarMensaje("No se encontró la solicitud seleccionada.", "danger", "mensajeFormulario");
                    return;
                }

                if (solicitud.Estatus === "Cancelado") {
                    mostrarMensaje("No se puede editar una solicitud cancelada.", "warning", "mensajeFormulario");
                    return;
                }

                document.getElementById("solicitudId").value = solicitud.id || "";
                [
                    "CicloEscolar",
                    "NumeroCuenta",
                    "NombreCompleto",
                    "Instituto",
                    "Semestre",
                    "ProgramaEducativo",
                ].forEach(function (k) {
                    var input = document.getElementById(k);
                    if (input) input.value = solicitud[k] || "";
                    var span = document.getElementById("mostrar" + k);
                    if (span) span.textContent = solicitud[k] || "";
                });

                var datosAlumno = document.getElementById("datosAlumno");
                var datosInstitucional = document.getElementById("datosInstitucional");
                if (datosAlumno) datosAlumno.classList.remove("d-none");
                if (datosInstitucional) datosInstitucional.classList.remove("d-none");

                seleccionarRadio("DatoAdicional", solicitud.DatoAdicional);
                seleccionarRadio("TipoFirma", solicitud.TipoFirma);
                seleccionarRadio("Calificaciones", solicitud.Calificaciones);

                // Cargar observaciones
                observaciones = [];
                if (Array.isArray(solicitud.Observaciones)) {
                    observaciones = solicitud.Observaciones.slice();
                } else if (solicitud.Observacion) {
                    // Compatibilidad con datos anteriores (string simple)
                    observaciones = [{ nombre: "Observación", valor: solicitud.Observacion }];
                }
                renderObservaciones();
                actualizarVisibilidadDatosAdicionales();

                var titulo = document.getElementById("tituloFormularioSolicitud");
                if (titulo) titulo.textContent = "Editar Solicitud de constancia";

                actualizarResumen();
                mostrarPaso(2);
            })
            .catch(function (error) {
                mostrarMensaje(
                    error.message || "Ocurrió un error al consultar la solicitud.",
                    "danger",
                    "mensajeFormulario",
                );
            });
    }

    function guardarSolicitud() {
        limpiarMensaje("mensajeFormulario");
        var datos = datosFormulario();

        if (!datos.NumeroCuenta) {
            mostrarMensaje("Debe seleccionar un alumno.", "warning", "mensajeFormulario");
            mostrarPaso(1);
            return;
        }

        solicitar(datos)
            .then(function () {
                mostrarMensaje(
                    modoEdicion
                        ? "Solicitud actualizada correctamente. Redirigiendo..."
                        : "Solicitud registrada correctamente. Redirigiendo...",
                    "success",
                    "mensajeFormulario",
                );

                setTimeout(function () {
                    window.location.href = "?vta=index";
                }, 1000);
            })
            .catch(function (error) {
                mostrarMensaje(error.message, "danger", "mensajeFormulario");
            });
    }

    // LISTADO
    function iniciarListado() {
        limpiarMensaje("mensajeSolicitud");

        var btnFiltrar = document.getElementById("btnFiltrar");
        if (btnFiltrar) btnFiltrar.addEventListener("click", cargarSolicitudes);

        var filtroFecha = document.getElementById("filtroFecha");
        if (filtroFecha) {
            filtroFecha.addEventListener("change", function () {
                var esRango = this.value === "rango";
                var wrapDesde = document.getElementById("wrapFechaDesde");
                var wrapHasta = document.getElementById("wrapFechaHasta");
                if (wrapDesde) wrapDesde.classList.toggle("d-none", !esRango);
                if (wrapHasta) wrapHasta.classList.toggle("d-none", !esRango);
            });
        }

        var btnLimpiar = document.getElementById("btnLimpiarFiltros");
        if (btnLimpiar) btnLimpiar.addEventListener("click", limpiarFiltros);

        var checkTodos = document.getElementById("checkTodos");
        if (checkTodos) {
            checkTodos.addEventListener("change", function () {
                var marcado = this.checked;
                document.querySelectorAll(".check-solicitud:not([disabled])").forEach(function (chk) {
                    chk.checked = marcado;
                    toggleSeleccion(chk.dataset.id, marcado);
                });
            });
        }
        var barraMasiva = document.getElementById("barraAccionesMasivas");
        if (barraMasiva) {
            barraMasiva.addEventListener("click", function (evento) {
                var btn = evento.target.closest("[data-accion-masiva]");
                if (!btn) return;
                ejecutarAccionMasiva(btn.dataset.accionMasiva);
            });
        }

        var btnLimpiarSel = document.getElementById("btnLimpiarSeleccion");
        if (btnLimpiarSel) btnLimpiarSel.addEventListener("click", limpiarSeleccion);

        var tabla = document.getElementById("tablaSolicitudes");
        if (tabla) {
            tabla.addEventListener("click", function (evento) {
                var acciones = [
                    [".btn-editar", editarSolicitud],
                    [".btn-pagar", pagarSolicitud],
                    [".btn-elaborar", elaborarSolicitud],
                    [".btn-mandar-firma", mandarFirmaSolicitud],
                    [".btn-emitir", emitirSolicitud],
                    [".btn-entregar", entregarSolicitud],
                    [".btn-atender", atenderSolicitud],
                    [".btn-finalizar", finalizarSolicitud],
                    [".btn-cancelar", cancelarSolicitud],
                ];
                for (var i = 0; i < acciones.length; i++) {
                    var btn = evento.target.closest(acciones[i][0]);
                    if (btn) {
                        acciones[i][1](btn.dataset.id);
                        return;
                    }
                }
            });
        }
    }

    // ARRANQUE
    document.addEventListener("DOMContentLoaded", function () {
        if (esPantallaFormulario()) iniciarFormulario();
        else if (esPantallaListado()) iniciarListado();
    });
})();
