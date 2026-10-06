(function () {
    "use strict";

    var endpoint = "../../modules/constancia-estudios/models/modConstanciaGenerarSolicitud.php";
    var paso = 1;
    var modoEdicion = false;
    var todosLosRegistros = [];

    // ENVIAR DATOS AL PHP
    function solicitar(datos) {
        var parametros = new URLSearchParams();
        var accion = datos.accion || "guardar";

        Object.keys(datos).forEach(function (clave) {
            if (clave === "accion") {
                return;
            }
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
            return respuesta.json().then(function (cuerpo) {
                if (!respuesta.ok || !cuerpo.ok) {
                    throw new Error(cuerpo.mensaje || "No fue posible completar la operación.");
                }
                return cuerpo.datos;
            });
        });
    }
    // CONSULTAR DATOS
    function consultar(accion, texto) {
        var url = endpoint + "?accion=" + encodeURIComponent(accion) + "&texto=" + encodeURIComponent(texto || "");
        return fetch(url)
            .then(function (respuesta) {
                if (!respuesta.ok) {
                    throw new Error("Error al calcular el servidor");
                }
                return respuesta.json();
            })
            .then(function (cuerpo) {
                if (!cuerpo.ok) {
                    throw new Error("Error en la consulta");
                }
                return cuerpo.datos || [];
            });
    }

    // Escapar HTML
    function escapeHtml(texto) {
        var div = document.createElement("div");
        div.textContent = texto || "";
        return div.innerHTML;
    }
    // MOSTRAR MENSAJE
    function mostrarMensaje(texto, tipo, idContenedor) {
        var contenedor = document.getElementById(idContenedor || "mensajeFormulario");
        if (!contenedor) return;
        contenedor.textContent = texto || "";
        contenedor.className = "alert alert-" + (tipo || "info");
        contenedor.classList.remove("d-none");
    }
    //Limpiar mensajes
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
    //en que pantalla estas
    function esPantallaListado() {
        return !!document.getElementById("tablaSolicitudes");
    }
    function esPantallaFormulario() {
        return !!document.getElementById("formSolicitud");
    }

    //filtros
    function leerFiltros() {
        return {
            numeroCuenta: (document.getElementById("filtroNumeroCuenta").value || "").toLowerCase().trim(),
            nombre: (document.getElementById("filtroNombre").value || "").toLowerCase().trim(),
            estatus: (document.getElementById("filtroEstatus").value || "").trim(),
            ciclo: (document.getElementById("filtroCiclo").value || "").toLowerCase().trim(),
        };
    }

    function aplicarFiltros() {
        var f = leerFiltros();
        return todosLosRegistros.filter(function (s) {
            var ciclo = String(s.CicloEscolar || "").toLowerCase();
            var nc = String(s.NumeroCuenta || "").toLowerCase();
            var nom = String(s.NombreCompleto || "").toLowerCase();
            var est = String(s.Estatus || s.EstatusSolicitud || "").toLowerCase();

            if (f.ciclo && ciclo.indexOf(f.ciclo) === -1) return false;
            if (f.numeroCuenta && nc.indexOf(f.numeroCuenta) === -1) return false;
            if (f.nombre && nom.indexOf(f.nombre) === -1) return false;
            if (f.estatus && est !== f.estatus) return false;
            return true;
        });
    }
    function pintarTabla(registros) {
        var tbody = document.querySelector("#tablaSolicitudes tbody");
        if (!tbody) return;

        if (!registros.length) {
            tbody.innerHTML =
                '<tr><td colspan="8" class="text-center text-muted py-4">' +
                "No se encontraron solicitudes con los filtros aplicados." +
                "</td></tr>";
            return;
        }

        tbody.innerHTML = registros
            .map(function (s) {
                var id = escapeHtml(s.id || s.idSolicitud || "");
                var estatus = s.Estatus || s.EstatusSolicitud || "";
                var cancelada = estatus === "Cancelado";
                var botones = "";

                if (cancelada) {
                    botones =
                        '<button type="button" class="btn btn-sm btn-outline-secondary" disabled>' +
                        '<i class="bi bi-pencil"></i> Editar' +
                        "</button> " +
                        '<button type="button" class="btn btn-sm btn-outline-secondary" disabled>' +
                        '<i class="bi bi-x-circle"></i> Cancelada' +
                        "</button>";
                } else {
                    botones =
                        '<button type="button" class="btn btn-sm btn-outline-primary btn-editar" data-id="' +
                        id +
                        '">' +
                        '<i class="bi bi-pencil"></i> Editar' +
                        "</button> " +
                        '<button type="button" class="btn btn-sm btn-outline-danger btn-cancelar" data-id="' +
                        id +
                        '">' +
                        '<i class="bi bi-x-circle"></i> Cancelar' +
                        "</button>";
                }

                return (
                    "<tr>" +
                    "<td>" +
                    escapeHtml(s.fechaRegistro || "") +
                    "</td>" +
                    "<td>" +
                    escapeHtml(s.CicloEscolar || "") +
                    "</td>" +
                    "<td>" +
                    escapeHtml(s.NumeroCuenta || "") +
                    "</td>" +
                    "<td>" +
                    escapeHtml(s.DatoAdicional || "") +
                    "</td>" +
                    "<td>" +
                    escapeHtml(s.Observacion || "") +
                    "</td>" +
                    "<td>" +
                    escapeHtml(s.NombreCompleto || "") +
                    "</td>" +
                    "<td>" +
                    escapeHtml(estatus) +
                    "</td>" +
                    '<td class="text-end">' +
                    botones +
                    "</td>" +
                    "</tr>"
                );
            })
            .join("");
    }

    function cargarSolicitudes() {
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
        document.getElementById("filtroNumeroCuenta").value = "";
        document.getElementById("filtroNombre").value = "";
        document.getElementById("filtroEstatus").value = "";
        document.getElementById("filtroCiclo").value = "";

        todosLosRegistros = [];
        document.querySelector("#tablaSolicitudes tbody").innerHTML =
            '<tr><td colspan="8" class="text-center text-muted py-4">' +
            "Seleccione los filtros y presione <strong>Buscar</strong> para ver resultados." +
            "</td></tr>";
    }

    //  CANCELAR
    function cancelarSolicitud(id) {
        if (!confirm("¿Está seguro de cancelar esta solicitud? Esta acción no se puede deshacer.")) return;
        solicitar({ accion: "cancelar", id: id })
            .then(function () {
                mostrarMensaje("La solicitud se canceló correctamente.", "success", "mensajeSolicitud");
                cargarSolicitudes();
            })
            .catch(function (error) {
                mostrarMensaje(error.message, "danger", "mensajeSolicitud");
            });
    }

    function editarSolicitud(id) {
        limpiarMensaje();
        window.location.href = "vtaCrearSolicitud.php?id=" + encodeURIComponent(id);
    }

    // MOSTRAR navegacion por PASOs
    function mostrarPaso(numero) {
        paso = numero;
        document.querySelectorAll(".paso-solicitud").forEach(function (seccion) {
            seccion.classList.toggle("d-none", Number(seccion.dataset.paso) !== paso);
        });

        var subtitulo = document.getElementById("subtituloModalGenerarSolicitud");
        if (subtitulo) subtitulo.textContent = "Paso " + paso + " de 3";

        var barra = document.getElementById("barraPaso");
        if (barra) barra.style.width = (paso / 3) * 100 + "%";

        var btnAnterior = document.getElementById("btnAnterior");
        var btnSiguiente = document.getElementById("btnSiguiente");
        var btnGuardar = document.getElementById("btnGuardar");

        if (btnAnterior) btnAnterior.classList.toggle("invisible", paso === 1);
        if (btnSiguiente) btnSiguiente.classList.toggle("d-none", paso === 3);
        if (btnGuardar) btnGuardar.classList.toggle("d-none", paso !== 3);

        mostrarError("errorNumeroCuenta", "");
        mostrarError("errorDatos", "");

        if (paso === 3) {
            actualizarResumen();
        }
    }
    // VALIDAR PASO ACTUAL
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
                                '"' +
                                'data-nombrecompleto="' +
                                escapeHtml(alumno.NombreCompleto || "") +
                                '"' +
                                'data-instituto="' +
                                escapeHtml(alumno.Instituto || "") +
                                '"' +
                                'data-semestre="' +
                                escapeHtml(alumno.Semestre || "") +
                                '"' +
                                'data-programa-educativo="' +
                                escapeHtml(alumno.ProgramaEducativo || "") +
                                '"' +
                                'data-cicloescolar="' +
                                escapeHtml(alumno.CicloEscolar || "") +
                                '"' +
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
                mostrarMensaje(error.message, "danger");
            });
    }

    // SELECCIONAR ALUMNO
    function seleccionarAlumno(elemento) {
        var cicloEscolar = elemento.dataset.cicloescolar || "";
        var numero = elemento.dataset.numero || "";
        var nombreCompleto = elemento.dataset.nombreCompleto || "";
        var instituto = elemento.dataset.instituto || "";
        var semestre = elemento.dataset.semestre || "";
        var programaEducativo = elemento.dataset.programaEducativo || "";

        //Guardar datos
        document.getElementById("CicloEscolar").value = cicloEscolar;
        document.getElementById("NumeroCuenta").value = numero;
        document.getElementById("NombreCompleto").value = nombreCompleto;
        document.getElementById("Instituto").value = instituto;
        document.getElementById("Semestre").value = semestre;
        document.getElementById("ProgramaEducativo").value = programaEducativo;

        //Mostrar datos
        document.getElementById("mostrarCicloEscolar").textContent = cicloEscolar;
        document.getElementById("mostrarNumeroCuenta").textContent = numero;
        document.getElementById("mostrarNombreCompleto").textContent = nombreCompleto;
        document.getElementById("mostrarInstituto").textContent = instituto;
        document.getElementById("mostrarSemestre").textContent = semestre;
        document.getElementById("mostrarProgramaEducativo").textContent = programaEducativo;

        //Mostrar por bloques
        var datosAlumno = document.getElementById("datosAlumno");
        var datosInstitucional = document.getElementById("datosInstitucional");

        if (datosAlumno) {
            datosAlumno.classList.remove("d-none");
        }
        if (datosInstitucional) {
            datosInstitucional.classList.remove("d-none");
        }

        document.getElementById("resultadosNumeroCuenta").innerHTML = "";
        mostrarError("errorNumeroCuenta", "");
    }
    // OBTENER DATOS DEL FORMULARIO
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
            Observacion: document.getElementById("Observacion")?.value || "",
        };
    }


    // ACTUALIZAR RESUMEN
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
            "<p><strong>Observación:</strong> " +
            escapeHtml(datos.Observacion) +
            "</p>";
    }

    //Seleccionar radio
    function seleccionarRadio(nombre, valor) {
        var radios = document.querySelectorAll('input[name="' + nombre + '"]');
        radios.forEach(function (radio) {
            radio.checked = String(radio.value) === String(valor);
        });
    }
    //  OBSERVACIONES
    function cargarObservaciones() {
        consultar("observacion")
            .then(function (lista) {
                var select = document.getElementById("Observacion");
                if (!select) return;
                select.innerHTML = '<option value="">Seleccione una observación</option>';
                lista.forEach(function (o) {
                    var opt = document.createElement("option");
                    opt.value = o.Observacion;
                    opt.textContent = o.Observacion;
                    select.appendChild(opt);
                });
            })
            .catch(function (error) {
                console.warn("No se pudieron cargar las observaciones:", error.message);
            });
    }
    function iniciarFormulario() {
        // Leer ?id= de la URL
        var params = new URLSearchParams(window.location.search);
        var id = params.get("id");

        cargarObservaciones();

        // Eventos de pasos
        var btnSig = document.getElementById("btnSiguiente");
        if (btnSig)
            btnSig.addEventListener("click", function () {
                if (validarPaso()) mostrarPaso(Math.min(3, paso + 1));
            });

        var btnAnt = document.getElementById("btnAnterior");
        if (btnAnt)
            btnAnt.addEventListener("click", function () {
                mostrarPaso(Math.max(1, paso - 1));
            });

        var btnGuardar = document.getElementById("btnGuardar");
        if (btnGuardar) btnGuardar.addEventListener("click", guardarSolicitud);

        var form = document.getElementById("formSolicitud");
        if (form)
            form.addEventListener("submit", function (e) {
                e.preventDefault();
                guardarSolicitud();
            });

        var inputBuscar = document.getElementById("buscarNumeroCuenta");
        if (inputBuscar)
            inputBuscar.addEventListener("input", function () {
                var texto = this.value.trim();
                if (texto.length < 6) {
                    document.getElementById("resultadosNumeroCuenta").innerHTML = "";
                    return;
                }
                pintarNumeroCuenta(texto);
            });

        var resultados = document.getElementById("resultadosNumeroCuenta");
        if (resultados)
            resultados.addEventListener("click", function (evento) {
                var boton = evento.target.closest("button");
                if (boton && boton.dataset.numero) seleccionarAlumno(boton);
            });

        // Si viene ?id=, precargar datos
        if (id) {
            precargarSolicitud(id);
        } else {
            mostrarPaso(1);
        }
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

                document.getElementById("CicloEscolar").value = solicitud.CicloEscolar || "";
                document.getElementById("NumeroCuenta").value = solicitud.NumeroCuenta || "";
                document.getElementById("NombreCompleto").value = solicitud.NombreCompleto || "";
                document.getElementById("Instituto").value = solicitud.Instituto || "";
                document.getElementById("Semestre").value = solicitud.Semestre || "";
                document.getElementById("ProgramaEducativo").value = solicitud.ProgramaEducativo || "";

                document.getElementById("mostrarCicloEscolar").textContent = solicitud.CicloEscolar || "";
                document.getElementById("mostrarNumeroCuenta").textContent = solicitud.NumeroCuenta || "";
                document.getElementById("mostrarNombreCompleto").textContent = solicitud.NombreCompleto || "";
                document.getElementById("mostrarInstituto").textContent = solicitud.Instituto || "";
                document.getElementById("mostrarSemestre").textContent = solicitud.Semestre || "";
                document.getElementById("mostrarProgramaEducativo").textContent = solicitud.ProgramaEducativo || "";

                document.getElementById("datosAlumno").classList.remove("d-none");
                document.getElementById("datosInstitucional").classList.remove("d-none");

                seleccionarRadio("DatoAdicional", solicitud.DatoAdicional);
                seleccionarRadio("TipoFirma", solicitud.TipoFirma);
                seleccionarRadio("Calificaciones", solicitud.Calificaciones);

                var observacion = document.getElementById("Observacion");
                if (observacion) observacion.value = solicitud.Observacion || "";

                var titulo = document.getElementById("tituloFormularioSolicitud");
                if (titulo) titulo.textContent = "Editar Solicitud de constancia";

                actualizarResumen();
                mostrarPaso(1);
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
        limpiarMensajes();
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
                    window.location.href = "vtaGenerarSolicitud.php";
                }, 1000);
            })
            .catch(function (error) {
                mostrarMensaje(error.message, "danger", "mensajeFormulario");
            });
    }

    //  INICIALIZACIÓN LISTADO
    function iniciarListado() {
        document.getElementById("btnFiltrar")?.addEventListener("click", cargarSolicitudes);
        document.getElementById("btnLimpiarFiltros")?.addEventListener("click", limpiarFiltros);

        document.getElementById("tablaSolicitudes").addEventListener("click", function (evento) {
                var btnEditar = evento.target.closest(".btn-editar");
                if (btnEditar) {
                    editarSolicitud(btnEditar.dataset.id);
                    return;
                }

                var btnCancelar = evento.target.closest(".btn-cancelar");
                if (btnCancelar) cancelarSolicitud(btnCancelar.dataset.id);
            });

    }

    //  ARRANQUE SEGÚN PANTALLA
    document.addEventListener("DOMContentLoaded", function () {
        if (esPantallaFormulario()) {
            iniciarFormulario();
        } else if (esPantallaListado()) {
            iniciarListado();
        }
    });
})();

