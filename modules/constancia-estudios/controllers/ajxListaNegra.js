(function () {
    "use strict";

    var endpoint = "../../modules/constancia-estudios/models/modConstanciaListaNegra.php";
    var modoEdicion = false;
    var modal;
    var registrosCache = [];
    var todosLosRegistros = [];

    // ENVIAR DATOS AL PHP
    function solicitar(datos) {
        var parametros = new URLSearchParams();

        Object.keys(datos).forEach(function (clave) {
            parametros.append(clave, datos[clave]);
        });

        return fetch(endpoint, {
            method: "POST",
            body: parametros,
        }).then(function (respuesta) {
            return respuesta.json().then(function (cuerpo) {
                if (!respuesta.ok || !cuerpo.ok) {
                    throw new Error(cuerpo.mensaje || "No fue posible completar la operación.");
                }

                return cuerpo.datos;
            });
        });
    }

    // CONSULTAR DATOS AL PHP
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

    // MOSTRAR MENSAJES
    function mostrarMensaje(texto, tipo) {
        var mensaje = document.getElementById("mensajeListaNegra");

        mensaje.textContent = texto;
        mensaje.className = "alert alert-" + tipo;
    }

    // ESCAPAR HTML
    function escapeHtml(texto) {
        var div = document.createElement("div");
        div.textContent = texto == null ? "" : texto;
        return div.innerHTML;
    }

    // BUSCADOR DE LA TABLA
    function buscador() {
        var texto = document.getElementById("input-search").value.toString().toLowerCase();
        var tbody = document.getElementById("tablaListaNegra");
        var filas = tbody.getElementsByTagName("tr");
        for (var i = 0; i < filas.length; i++) {
            var numeroCuenta = filas[i].cells[0].textContent.toString().toLowerCase();
            filas[i].style.display = numeroCuenta.indexOf(texto) === -1 ? "none" : "";
        }
    }

    // VALIDAR CUENTA
    function validarCuenta(numeroCuenta) {
        return solicitar({
            accion: "validarCuenta",
            NumeroCuenta: numeroCuenta,
        });
    }

    // MOSTRAR REGISTROS EN TABLA
    function renderTabla(registros) {
        var tbody = document.querySelector("#tablaListaNegra tbody");

        if (!registros || !registros.length) {
            tbody.innerHTML =
                "<tr>" + '<td colspan="2" class="text-center text-muted py-4">' + "Sin registros." + "</td>" + "</tr>";
            return;
        }

        tbody.innerHTML = registros
            .map(function (registro) {
                return (
                    "<tr>" +
                    "<td>" +
                    escapeHtml(registro.NumeroCuenta) + "</td>" + "<td>" + '<button type="button" ' + 'class="btn btn-sm btn-outline-primary btn-editar" ' + 'data-id="' +
                    escapeHtml(registro.NumeroCuenta) + '">' + escapeHtml(registro.Estatus) + "</button>" +
                    "</td>" +
                    "</tr>"
                );
            })
            .join("");
    }

    // CARGAR REGISTROS
    function cargarRegistros() {
        solicitar({
            accion: "listar",
        })
            .then(function (registros) {
                // Guardamos todos los registros
                todosLosRegistros = registros || [];

                // Solo mostrar los que tienen estatus S
                registrosCache = todosLosRegistros.filter(function (registro) {
                    return String(registro.Estatus).trim().toUpperCase() === "S";
                });

                renderTabla(registrosCache);
            })

            .catch(function (error) {
                mostrarMensaje(error.message, "danger");
            });
    }

    // BUSCAR REGISTRO POR NÚMERO DE CUENTA
    function buscarRegistro(numeroCuenta) {
        return todosLosRegistros.find(function (registro) {
            return String(registro.NumeroCuenta).trim() === String(numeroCuenta).trim();
        });
    }

    // AUTOCOMPLETADO DE NÚMERO DE CUENTA
    function pintarNumeroCuenta(texto) {
        if (texto.length === 6) {
            consultar("numeroCuenta", texto)
                .then(function (lista) {
                    document.getElementById("resultadosNumeroCuenta").innerHTML =
                        lista
                            .map(function (item) {
                                var numero = String(item.NumeroCuenta);

                                return (
                                    '<button type="button" ' + 'class="list-group-item list-group-item-action" ' + 'data-nombre="' +
                                    escapeHtml(numero) + '">' + escapeHtml(numero) + "</button>"
                                );
                            })
                            .join("") ||
                        '<div class="text-muted small">' + "No se encontró el número de cuenta." + "</div>";
                })

                .catch(function (error) {
                    mostrarMensaje(error.message, "danger");
                });
        } else {
            return;
        }
    }

    // ABRIR MODAL
    function abrirModal(edicion, datos) {
        modoEdicion = !!edicion;
        document.getElementById("tituloModalListaNegra").textContent = edicion ? "Editar caso" : "Nuevo caso especial";
        document.getElementById("subtituloModalListaNegra").textContent = "Paso 1 de 1";
        document.getElementById("formListaNegra").reset();

        // Limpiar mensaje
        var mensaje = document.getElementById("mensajeListaNegra");
        mensaje.textContent = "";
        mensaje.className = "alert d-none";

        // Limpiar resultados
        document.getElementById("resultadosNumeroCuenta").innerHTML = "";
        document.getElementById("resultadosEstatus").innerHTML = "";

        // Si es edición, cargar datos
        if (datos) {
            document.getElementById("NumeroCuenta").value = datos.NumeroCuenta || "";
            document.getElementById("Estatus").value = datos.Estatus || "";
        }

        document.getElementById("btnGuardar").classList.remove("d-none");
        modal.show();
    }

        // INICIALIZAR PÁGINA
        document.addEventListener("DOMContentLoaded", function () {
        modal = new bootstrap.Modal(document.getElementById("modalListaNegra"));

        cargarRegistros();

        // EDITAR REGISTRO
        document.querySelector("#tablaListaNegra tbody").addEventListener("click", function (e) {
            var boton = e.target.closest(".btn-editar");
            if (!boton) {
                return;
            }

            var numeroCuenta = boton.dataset.id;
            var registro = buscarRegistro(numeroCuenta);

            if (registro) {
                abrirModal(true, registro);
            }
        });

        // NUEVO REGISTRO
        document.getElementById("btnNuevoListaNegra").addEventListener("click", function () {
            abrirModal(false);
        });

        // AUTOCOMPLETADO
        document.getElementById("NumeroCuenta").addEventListener("input", function () {
            pintarNumeroCuenta(this.value);
        });

        // SELECCIONAR NÚMERO DE CUENTA
        document.getElementById("resultadosNumeroCuenta").addEventListener("click", function (e) {
            var boton = e.target.closest("button[data-nombre]");
            if (!boton) {
                return;
            }

            var numeroCuenta = boton.dataset.nombre;

            validarCuenta(numeroCuenta)
                .then(function (resultado) {
                    // Ya existe en nuestra lista
                    if (resultado.enLocal) {
                        abrirModal(true, resultado.registro);
                        return;
                    }

                    // Existe en SIAE pero todavía
                    // no está en nuestra lista
                    if (resultado.enSIAE) {
                        document.getElementById("NumeroCuenta").value = numeroCuenta;
                        document.getElementById("resultadosNumeroCuenta").innerHTML = "";
                        return;
                    }

                    // No existe
                    mostrarMensaje("El número de cuenta no existe", "danger");
                })

                .catch(function (error) {
                    mostrarMensaje(error.message, "danger");
                });
        });

        // GUARDAR / ACTUALIZAR
        document.getElementById("formListaNegra").addEventListener("submit", function (e) {
            e.preventDefault();

            var datos = {
                accion: modoEdicion ? "actualizar" : "guardar",
                NumeroCuenta: document.getElementById("NumeroCuenta").value,
                Estatus: document.getElementById("Estatus").value,
            };

            solicitar(datos)
                .then(function () {
                    mostrarMensaje("Registro exitoso", "success");
                    cargarRegistros();
                })

                .catch(function (error) {
                    mostrarMensaje(error.message, "danger");
                });
        });

        // BUSCADOR
        document.getElementById("input-search").addEventListener("input", buscador);
    });
})();
