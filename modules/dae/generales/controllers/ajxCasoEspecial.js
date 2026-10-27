(function () {
    "use strict";

    var endpoint = "../../modules/constancia-estudios/models/modConstanciaCasoEspecial.php";
    var modal;
    var registrosCache = [];

    // ---------------------------------------------------------------
    // Utilidades HTTP
    // ---------------------------------------------------------------
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
        return fetch(endpoint, { method: "POST", body: parametros }).then(function (respuesta) {
            return respuesta.json().then(function (cuerpo) {
                if (!respuesta.ok || !cuerpo.ok) {
                    throw new Error(cuerpo.mensaje || "No fue posible completar la operación.");
                }
                return cuerpo.datos;
            });
        });
    }

    function consultar(accion, texto) {
        var url = endpoint + "?accion=" + encodeURIComponent(accion) + "&texto=" + encodeURIComponent(texto || "");
        return fetch(url).then(function (respuesta) {
            return respuesta.json().then(function (cuerpo) {
                if (!respuesta.ok || !cuerpo.ok) {
                    throw new Error(cuerpo.mensaje || "Error en la consulta");
                }
                return cuerpo.datos || [];
            });
        });
    }

    // Utilidades UI
    function escapeHtml(texto) {
        var div = document.createElement("div");
        div.textContent = texto == null ? "" : texto;
        return div.innerHTML;
    }

    function mostrarMensaje(texto, tipo) {
        var mensaje = document.getElementById("mensajeCasoEspecial");
        mensaje.textContent = texto;
        mensaje.className = "alert alert-" + tipo;
        mensaje.classList.remove("d-none");
        // Autoocultar después de 4 segundos si es éxito
        if (tipo === "success") {
            setTimeout(function () {
                mensaje.classList.add("d-none");
            }, 4000);
        }
    }

    function mostrarMensajeModal(texto, tipo) {
        var mensaje = document.getElementById("mensajeModalCasoEspecial");
        mensaje.textContent = texto;
        mensaje.className = "alert alert-" + tipo;
        mensaje.classList.remove("d-none");
    }

    function limpiarMensajeModal() {
        var mensaje = document.getElementById("mensajeModalCasoEspecial");
        mensaje.textContent = "";
        mensaje.className = "alert d-none";
    }

    // Render de la tabla con Toggle Switch
    function renderTabla(registros) {
        var tbody = document.querySelector("#tablaCasoEspecial tbody");
        if (!registros || !registros.length) {
            tbody.innerHTML =
                "<tr>" + '<td colspan="2" class="text-center text-muted py-4">Sin registros.</td>' + "</tr>";
            return;
        }
        tbody.innerHTML = registros
            .map(function (r) {
                var activo = String(r.Estatus).toUpperCase() === "S";
                var etiqueta = activo ? "Bloqueado" : "Desbloqueado";
                var claseEtiqueta = activo ? "text-danger" : "text-success";
                var idSwitch = "switch_" + String(r.NumeroCuenta).replace(/[^a-zA-Z0-9]/g, "_");

                return (
                    "<tr>" +
                    "<td>" +
                    escapeHtml(r.NumeroCuenta) +
                    "</td>" +
                    "<td>" +
                    '<div class="form-check form-switch d-flex align-items-center gap-2 mb-0">' +
                    '<input class="form-check-input switch-estatus" type="checkbox" role="switch" ' +
                    'id="' +
                    idSwitch +
                    '" ' +
                    'data-cuenta="' +
                    escapeHtml(r.NumeroCuenta) +
                    '" ' +
                    (activo ? "checked" : "") +
                    ">" +
                    '<label class="form-check-label ' +
                    claseEtiqueta +
                    '" for="' +
                    idSwitch +
                    '">' +
                    etiqueta +
                    "</label>" +
                    "</div>" +
                    "</td>" +
                    "</tr>"
                );
            })
            .join("");
    }

    // Carga de registros
    function cargarRegistros() {
        solicitar({ accion: "listar" })
            .then(function (registros) {
                registrosCache = registros || [];
                renderTabla(registrosCache);
            })
            .catch(function (e) {
                mostrarMensaje(e.message, "danger");
            });
    }

    // Filtro local (buscador dentro de la página)
    function filtrarTabla(texto) {
        texto = (texto || "").trim().toLowerCase();
        if (texto === "") {
            renderTabla(registrosCache);
            return;
        }
        var filtrados = registrosCache.filter(function (r) {
            return String(r.NumeroCuenta).toLowerCase().indexOf(texto) !== -1;
        });
        renderTabla(filtrados);
    }

    // ---------------------------------------------------------------
    // Autocompletado de número de cuenta (para el modal)
    // ---------------------------------------------------------------
    function pintarNumeroCuenta(texto) {
        consultar("numeroCuenta", texto)
            .then(function (lista) {
                document.getElementById("resultadosNumeroCuenta").innerHTML =
                    lista
                        .map(function (item) {
                            var numero = String(item.NumeroCuenta);
                            return (
                                '<button type="button" class="list-group-item list-group-item-action" ' +
                                'data-nombre="' +
                                escapeHtml(numero) +
                                '">' +
                                escapeHtml(numero) +
                                (item.nombre ? " — " + escapeHtml(item.nombre) : "") +
                                "</button>"
                            );
                        })
                        .join("") || '<div class="text-muted small">No se encontró el número de cuenta.</div>';
            })
            .catch(function (e) {
                mostrarMensaje(e.message, "danger");
            });
    }

    // ---------------------------------------------------------------
    // Modal: solo para NUEVOS registros
    // ---------------------------------------------------------------
    function abrirModalNuevo() {
        document.getElementById("formCasoEspecial").reset();
        document.getElementById("NumeroCuenta").value = "";
        document.getElementById("Estatus").checked = false;
        document.getElementById("labelEstatus").textContent = "Desbloqueado";
        document.getElementById("labelEstatus").className = "form-check-label text-success";
        document.getElementById("resultadosNumeroCuenta").innerHTML = "";
        limpiarMensajeModal();
        modal.show();
    }

    // ---------------------------------------------------------------
    // Inicialización (ÚNICO DOMContentLoaded, al nivel del IIFE)
    // ---------------------------------------------------------------
    document.addEventListener("DOMContentLoaded", function () {
        modal = new bootstrap.Modal(document.getElementById("modalCasoEspecial"));
        cargarRegistros();

        // ---- Cambio de switch en la tabla (actualiza directo al backend) ----
        document.querySelector("#tablaCasoEspecial tbody").addEventListener("change", function (e) {
            var input = e.target.closest(".switch-estatus");
            if (!input) return;

            var numeroCuenta = input.dataset.cuenta;
            var nuevoEstatus = input.checked ? "S" : "N";

            input.disabled = true;

            solicitar({
                accion: "actualizar",
                NumeroCuenta: numeroCuenta,
                Estatus: nuevoEstatus,
            })
                .then(function () {
                    var reg = registrosCache.find(function (item) {
                        return String(item.NumeroCuenta) === String(numeroCuenta);
                    });
                    if (reg) reg.Estatus = nuevoEstatus;

                    renderTabla(registrosCache);
                    mostrarMensaje("Estatus actualizado correctamente.", "success");
                })
                .catch(function (err) {
                    input.checked = !input.checked;
                    input.disabled = false;
                    mostrarMensaje(err.message, "danger");
                });
        });

        // ---- Botón Nuevo ----
        document.getElementById("btnNuevoCasoEspecial").addEventListener("click", function () {
            abrirModalNuevo();
        });

        // ---- Buscador en vivo (ya no es prompt) ----
        document.getElementById("buscarCasoEspecial").addEventListener("input", function () {
            filtrarTabla(this.value);
        });

        document.getElementById("btnLimpiarBusqueda").addEventListener("click", function () {
            document.getElementById("buscarCasoEspecial").value = "";
            filtrarTabla("");
        });

        // ---- Switch de Estatus dentro del modal (etiqueta dinámica) ----
        document.getElementById("Estatus").addEventListener("change", function () {
            var label = document.getElementById("labelEstatus");
            if (this.checked) {
                label.textContent = "Bloqueado";
                label.className = "form-check-label text-danger";
            } else {
                label.textContent = "Desbloqueado";
                label.className = "form-check-label text-success";
            }
        });

        // ---- Autocompletado en el modal ----
        document.getElementById("NumeroCuenta").addEventListener("input", function () {
            pintarNumeroCuenta(this.value);
        });

        document.getElementById("resultadosNumeroCuenta").addEventListener("click", function (e) {
            var btn = e.target.closest("button[data-nombre]");
            if (!btn) return;
            document.getElementById("NumeroCuenta").value = btn.dataset.nombre;
            document.getElementById("resultadosNumeroCuenta").innerHTML = "";
        });

        // ---- Submit del modal (solo guardar nuevos) ----
        document.getElementById("formCasoEspecial").addEventListener("submit", function (e) {
            e.preventDefault();

            var datos = {
                accion: "guardar",
                NumeroCuenta: document.getElementById("NumeroCuenta").value,
                Estatus: document.getElementById("Estatus").checked ? "S" : "N",
            };

            solicitar(datos)
                .then(function () {
                    limpiarMensajeModal();
                    modal.hide();
                    mostrarMensaje("Registro guardado correctamente.", "success");
                    cargarRegistros();
                })
                .catch(function (err) {
                    mostrarMensajeModal(err.message, "danger");
                });
        });
    });
})();
