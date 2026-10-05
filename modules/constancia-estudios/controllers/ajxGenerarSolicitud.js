(function () {
    "use strict";

    var endpoint = "../../modules/constancia-estudios/models/modConstanciaGenerarSolicitud.php";
    var paso = 1;
    var modoEdicion = false;
    var modal;

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

    // MOSTRAR MENSAJE
    function mostrarMensaje(texto, tipo) {
        var mensaje = document.getElementById("mensajeSolicitud");
        if (!mensaje) return;
        mensaje.textContent = texto;
        mensaje.className = "alert alert-" + tipo;
    }
    function limpiarMensaje() {
        var mensaje = document.getElementById("mensajeSolicitud");

        if (!mensaje) {
            return;
        }

        mensaje.textContent = "";

        mensaje.className = "alert d-none";
    }

    // PROTEGER TEXTO ANTES DE INSERTARLO EN HTML
    function escapeHtml(texto) {
        var div = document.createElement("div");
        div.textContent = texto || "";
        return div.innerHTML;
    }
    function mostrarError(id, texto) {
        var el = document.getElementById(id);
        if (!el) return;
        el.textContent = texto || "";
        if (texto) {
            el.classList.add("d-block");
        } else {
            el.classList.remove("d-block");
        }
    }
    function cargarSolicitudes() {
        consultar("listar")
            .then(function (solicitudes) {
                var tbody = document.querySelector("#tablaSolicitudes tbody");
                if (!tbody) return;
                if (!solicitudes.length) {
                    tbody.innerHTML =
                        '<tr><td colspan="9" class="text-center text-muted py-4">Sin solicitudes registradas.</td></tr>';
                    return;
                }
                tbody.innerHTML = solicitudes
                    .map(function (s) {
                        var id = escapeHtml(s.id || s.idSolicitud || "");
                        return (
                            "<tr>" +
                            "<td>" +
                            escapeHtml(s.fechaRegistro || "") +
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
                            escapeHtml(s.Nombre || "") +
                            "</td>" +
                            "<td>" +
                            escapeHtml(s.ApellidoPaterno || "") +
                            "</td>" +
                            "<td>" +
                            escapeHtml(s.ApellidoMaterno || "") +
                            "</td>" +
                            "<td>" +
                            escapeHtml(s.Estatus || "") +
                            "</td>" +
                            '<td class="text-end">' +
                            '<button type="button" class="btn btn-sm btn-outline-primary btn-editar" data-id="' +
                            id +
                            '">' +
                            '<i class="bi bi-pencil"></i>' +
                            "</button> " +
                            '<button type="button" class="btn btn-sm btn-outline-danger btn-eliminar" data-id="' +
                            id +
                            '">' +
                            '<i class="bi bi-trash"></i>' +
                            "</button>" +
                            "</td>" +
                            "</tr>"
                        );
                    })
                    .join("");
            })
            .catch(function (error) {
                mostrarMensaje(error.message, "danger");
            });
    }
    function eliminarSolicitud(id){
        if(!id){
            mostrarMensaje("No se encontró la solicitud", "danger");
            return;
        }
        if(!confirm("¿Está seguro de eliminar esta solicitud")){
            return;
        }
        solicitar({
            accion: "eliminar", id: id
        }).then(function(){
            mostrarMensaje("La solicitud se elimino correctamente", "success");
            cargarSolicitudes();
        })
        .catch(function(error){
            mostrarMensaje(error.message, "danger");
        });
    }
    // BUSCAR NÚMERO DE CUENTA
    function pintarNumeroCuenta(texto) {
        consultar("numeroCuenta", texto)
            .then(function (alumnos) {
                var resultados = document.getElementById("resultadosNumeroCuenta");
                if (!resultados) return;
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
                                alumno.Periodo || "",
                            ]
                                .join(" ")
                                .trim();

                            return (
                                '<button type="button" ' +
                                'class="list-group-item list-group-item-action" ' +
                                'data-numero="' +
                                escapeHtml(numero) +
                                '"' +
                                'data-nombre="' +
                                escapeHtml(alumno.Nombre || alumno.nombre || "") +
                                '"' +
                                'data-apellido-paterno="' +
                                escapeHtml(alumno.ApellidoPaterno || "") +
                                '"' +
                                'data-apellido-materno="' +
                                escapeHtml(alumno.ApellidoMaterno || "") +
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
                                'data-periodo="' +
                                escapeHtml(alumno.Periodo || "") +
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
        var numero = elemento.dataset.numero || "";
        var nombre = elemento.dataset.nombre || "";
        var apellidoPaterno = elemento.dataset.apellidoPaterno || "";
        var apellidoMaterno = elemento.dataset.apellidoMaterno || "";
        var instituto = elemento.dataset.instituto || "";
        var semestre = elemento.dataset.semestre || "";
        var programaEducativo = elemento.dataset.programaEducativo || "";
        var periodo = elemento.dataset.periodo || "";

        document.getElementById("NumeroCuenta").value = numero;
        document.getElementById("Nombre").value = nombre;
        document.getElementById("ApellidoPaterno").value = apellidoPaterno;
        document.getElementById("ApellidoMaterno").value = apellidoMaterno;
        document.getElementById("Instituto").value = instituto;
        document.getElementById("Semestre").value = semestre;
        document.getElementById("ProgramaEducativo").value = programaEducativo;
        document.getElementById("Periodo").value = periodo;

        document.getElementById("mostrarNumeroCuenta").textContent = numero;
        document.getElementById("mostrarNombre").textContent = nombre;
        document.getElementById("mostrarApellidoPaterno").textContent = apellidoPaterno;
        document.getElementById("mostrarApellidoMaterno").textContent = apellidoMaterno;
        document.getElementById("mostrarInstituto").textContent = instituto;
        document.getElementById("mostrarSemestre").textContent = semestre;
        document.getElementById("mostrarProgramaEducativo").textContent = programaEducativo;
        document.getElementById("mostrarPeriodo").textContent = periodo;

        var datosAlumno = document.getElementById("datosAlumno");
        var datosInstitucional = document.getElementById("datosInstitucional");
        if (datosAlumno) datosAlumno.classList.remove("d-none");
        if (datosInstitucional) datosInstitucional.classList.remove("d-none");

        document.getElementById("resultadosNumeroCuenta").innerHTML = "";
        mostrarError ("errorNumeroCuenta", "");
    }

    // MOSTRAR PASO
    function mostrarPaso(numero) {
        paso = numero;
        document.querySelectorAll(".paso-solicitud").forEach(function (seccion) {
            seccion.classList.toggle("d-none", Number(seccion.dataset.paso) !== paso);
        });

        var subtitulo = document.getElementById("subtituloModalGenerarSolicitud");
        if (subtitulo) subtitulo.textContent = "Paso " + paso + " de 3";

        var barra = document.getElementById("barraPaso");
        if (barra) barra.style.width = (paso / 3) * 100 + "%";

        document.getElementById("btnAnterior").classList.toggle("invisible", paso === 1);
        document.getElementById("btnSiguiente").classList.toggle("d-none", paso === 3);
        document.getElementById("btnGuardar").classList.toggle("d-none", paso !== 3);

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
            var errNum = document.getElementById("errorNumeroCuenta");
            if (numero === "") {
                mostrarError("errorNumeroCuenta", "Seleccione un alumno");
                
            return false;
            }

        }
        if (paso === 2) {
            var da = document.querySelector('input[name="DatoAdicional"]:checked');
            var tf = document.querySelector('input[name="TipoFirma"]:checked');
            var cal = document.querySelector('input[name="Calificaciones"]:checked');
            var errDat = document.getElementById("errorDatos");
            if (!da || !tf || !cal) {
                mostrarError("errorDatos", "Complete todas las opciones");

                return false;
            }

        }
        return true;
    }

    // OBTENER DATOS DEL FORMULARIO
    function datosFormulario() {
        var datoAdicional = document.querySelector('input[name="DatoAdicional"]:checked');
        var tipoFirma = document.querySelector('input[name="TipoFirma"]:checked');
        var calificaciones = document.querySelector('input[name="Calificaciones"]:checked');
        var obsSelect = document.getElementById("Observacion");
        return {
            accion: modoEdicion ? "actualizar" : "guardar",
            id: document.getElementById("solicitudId").value,
            NumeroCuenta: document.getElementById("NumeroCuenta").value,
            Nombre: document.getElementById("Nombre").value,
            ApellidoPaterno: document.getElementById("ApellidoPaterno").value,
            ApellidoMaterno: document.getElementById("ApellidoMaterno").value,
            Instituto: document.getElementById("Instituto").value,
            Semestre: document.getElementById("Semestre").value,
            ProgramaEducativo: document.getElementById("ProgramaEducativo").value,
            Periodo: document.getElementById("Periodo").value,
            DatoAdicional: datoAdicional ? datoAdicional.value : "",
            TipoFirma: tipoFirma ? tipoFirma.value : "",
            Calificaciones: calificaciones ? calificaciones.value : "",
            Observacion: obsSelect ? obsSelect.value : "",
        };
    }

    // ACTUALIZAR RESUMEN
    function actualizarResumen() {
        var datos = datosFormulario();
        var nombreCompleto = [datos.Nombre, datos.ApellidoPaterno, datos.ApellidoMaterno].join(" ").trim();
        var datosInstitucionales = [datos.Instituto, datos.Semestre, datos.ProgramaEducativo, datos.Periodo]
            .join(" ")
            .trim();
        var datoAdicional = datos.DatoAdicional === "S" ? "Sí" : "No";
        var firma = datos.TipoFirma === "S" ? "Digital (PDF)" : "Autógrafa";
        var calificaciones = datos.Calificaciones === "S" ? "Sí" : "No";

        document.getElementById("resumenSolicitud").innerHTML =
            "<p><strong>Número de Cuenta:</strong> " +
            escapeHtml(datos.NumeroCuenta) +
            "</p>" +
            "<p><strong>Alumno:</strong> " +
            escapeHtml(nombreCompleto) +
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

    // ABRIR NUEVA SOLICITUD
    function abrirNuevo() {
        modoEdicion = false;
        paso = 1;
        
        var form = document.getElementById("formSolicitud");
        if (form) {
            form.reset();
            }
            limpiarMensaje();
            [
                "NumeroCuenta",
                "Nombre",
                "ApellidoPaterno",
                "ApellidoMaterno",
                "Instituto",
                "Semestre",
                "ProgramaEducativo",
                "Periodo"
    ].forEach(function (id) {

        var campo = document.getElementById(id);

        if (campo) {
            campo.value = "";
        }
    });

        var datosAlumno = document.getElementById("datosAlumno");
        if (datosAlumno) {datosAlumno.classList.add("d-none");}

        var datosInstitucional = document.getElementById("datosInstitucional");
        if (datosInstitucional){ datosInstitucional.classList.add("d-none");

        }

        [
            "mostrarNumeroCuenta",
            "mostrarNombre",
            "mostrarApellidoPaterno",
            "mostrarApellidoMaterno",
            "mostrarInstituto",
            "mostrarSemestre",
            "mostrarProgramaEducativo",
            "mostrarPeriodo",
        ].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.textContent = "";
        });

        var buscar = document.getElementById("buscarNumeroCuenta");
        if (buscar) {
            buscar.value = "";}

        var resultados = document.getElementById("resultadosNumeroCuenta");
        if (resultados) {
            resultados.innerHTML = "";
        }
            mostrarError("errorNumeroCuenta", "");
            mostrarError("errorDatos", "");

        var resumen = document.getElementById("resumenSolicitud");
        if(resumen){
            resumen.innerHTML= "";
        }

        var observacion = document.getElementById("Observacion");
        if (observacion){
            observacion.selectedIndex = 0;
        }
        var titulo = document.getElementById("tituloModalGenerarSolicitud");

        if (titulo) {
            titulo.textContent = "Nueva Solicitud";
        }
        mostrarPaso(1);

        // Crear o obtener la instancia justo aquí
        if (modal) {
            modal.show();
        } else {
            console.error("No se pudo abrir el modal.");
        }
    }

    function editarSolicitud(id) {
        if (!id) {
            console.error("No se recibió el ID de la solicitud.");
            return;
        }

        modoEdicion = true;
        paso = 1;

        solicitar({
            accion: "listar",
            id: id,
        })
            .then(function (respuesta) {
                if (!respuesta.ok) {
                    mostrarMensaje(respuesta.mensaje || "No se pudo consultar la solicitud.", "danger");
                    return;
                }

                var solicitud = null;

                /*Buscamos la solicitud por ID */
                if (Array.isArray(respuesta.datos)) {
                    solicitud = respuesta.datos.find(function (item) {
                        return String(item.id) === String(id);
                    });
                } else if (respuesta.datos) {
                    solicitud = respuesta.datos;
                }

                if (!solicitud) {
                    mostrarMensaje("No se encontró la solicitud seleccionada.", "danger");
                    return;
                }

                /* Limpiamos primero el formulario
                 */
                var form = document.getElementById("formSolicitud");

                if (form) {
                    form.reset();
                }

                limpiarMensaje();

                /*Guardamos el ID de la solicitud */
                document.getElementById("solicitudId").value = solicitud.id || "";

                //alumnos datos
                document.getElementById("NumeroCuenta").value = solicitud.NumeroCuenta || "";
                document.getElementById("Nombre").value = solicitud.Nombre || "";
                document.getElementById("ApellidoPaterno").value = solicitud.ApellidoPaterno || "";
                document.getElementById("ApellidoMaterno").value = solicitud.ApellidoMaterno || "";
                //datos institucionales
                document.getElementById("Instituto").value = solicitud.Instituto || "";
                document.getElementById("Semestre").value = solicitud.Semestre || "";
                document.getElementById("ProgramaEducativo").value = solicitud.ProgramaEducativo || "";
                document.getElementById("Periodo").value = solicitud.Periodo || "";

                //mostrar datos 
                document.getElementById("mostrarNumeroCuenta").textContent = solicitud.NumeroCuenta || "";
                document.getElementById("mostrarNombre").textContent = solicitud.Nombre || "";
                document.getElementById("mostrarApellidoPaterno").textContent = solicitud.ApellidoPaterno || "";
                document.getElementById("mostrarApellidoMaterno").textContent = solicitud.ApellidoMaterno || "";

                document.getElementById("mostrarInstituto").textContent = solicitud.Instituto || "";
                document.getElementById("mostrarSemestre").textContent = solicitud.Semestre || "";
                document.getElementById("mostrarProgramaEducativo").textContent = solicitud.ProgramaEducativo || "";

                document.getElementById("mostrarPeriodo").textContent = solicitud.Periodo || "";
                document.getElementById("datosAlumno").classList.remove("d-none");
                document.getElementById("datosInstitucional").classList.remove("d-none");

                seleccionarRadio("DatoAdicional", solicitud.DatoAdicional);
                seleccionarRadio("TipoFirma", solicitud.TipoFirma);
                seleccionarRadio("Calificaciones", solicitud.Calificaciones);

                var observacion = document.getElementById("Observacion");

                if (observacion) {
                    observacion.value = solicitud.Observacion || "";
                }

                var titulo = document.getElementById("tituloModalGenerarSolicitud");

                if (titulo) {
                    titulo.textContent = "Editar Solicitud";
                }

                mostrarPaso(1);
                if (modal) {
                    modal.show();
                }
            })
            .catch(function (error) {
                console.error(error);

                mostrarMensaje(error.message || "Ocurrió un error al consultar la solicitud.", "danger");
            });
    }

    function seleccionarRadio(nombre, valor){
        var radios = document.querySelectorAll(
            'input[name"' + nombre +'"]'
        );
        radios.forEach(function(radio){
            radio.checked= String(radio.value) === String(valor);
        });
    }
    // INICIALIZAR
    document.addEventListener("DOMContentLoaded", function () {
        cargarSolicitudes();
        cargarObservaciones();

        // Inicializar modal UNA VEZ
        var modalEl = document.getElementById("modalSolicitud");
        if (modalEl){
            if (typeof bootstrap !== "undefined" && bootstrap.Modal) {
                modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            }else{ console.error("boos no esta cargando correctamente");
        }}
        

        // BOTÓN NUEVA SOLICITUD
        var btnNueva = document.getElementById("btnNuevaSolicitud");
        if (btnNueva) btnNueva.addEventListener("click", abrirNuevo);

        // BUSCAR NÚMERO DE CUENTA
        var inputBuscar = document.getElementById("buscarNumeroCuenta");
        if (inputBuscar) {
            inputBuscar.addEventListener("input", function () {
                var texto = this.value.trim();
                if (texto.length >= 6) {
                    pintarNumeroCuenta(texto);
                } else {
                    var res = document.getElementById("resultadosNumeroCuenta");
                    if (res) res.innerHTML = "";
                }
            });
        }
        //Boton Borrar solicitud
        var tabla = document.getElementById("tablaSolicitudes");
        if(tabla){
            tabla.addEventListener("click", function(evento){
                var botonEliminar = evento.target.closest(".btn-eliminar");
                if(botonEliminar){
                    var id = botonEliminar.dataset.id;
                    eliminarSolicitud(id);
                }
            });
        }

        // SELECCIONAR ALUMNO DE LOS RESULTADOS
        var resultados = document.getElementById("resultadosNumeroCuenta");
        if (resultados) {
            resultados.addEventListener("click", function (evento) {
                var boton = evento.target.closest("button");
                if (boton && boton.dataset.numero) seleccionarAlumno(boton);
            });
        }

        // BOTÓN SIGUIENTE
        var btSig = document.getElementById("btnSiguiente");
        if (btSig) {
            btSig.addEventListener("click", function () {
                if (validarPaso()) mostrarPaso(Math.min(3, paso + 1));
            });
        }

        // BOTÓN ANTERIOR
        var btnAnt = document.getElementById("btnAnterior");
        if (btnAnt) {
            btnAnt.addEventListener("click", function () {
                mostrarPaso(Math.max(1, paso - 1));
            });
        }

        // GUARDAR SOLICITUD
        var form = document.getElementById("formSolicitud");
        if (form) {
            form.addEventListener("submit", function (evento) {
                evento.preventDefault();
                if (!validarPaso()) {
                    return;
                }

                solicitar(datosFormulario())
                    .then(function () {
                            mostrarMensaje("La solicitud se guardó correctamente.", "success");
                            cargarSolicitudes();
                        })
                    .catch(function (error) { mostrarMensaje( error.message, "danger");
    });
    });
} mostrarPaso(1);
    });
})();
