(function () {
    ("use strict");

    //Funciones derivadas del objeto GLOBAL window. Utilizadas tanto por ajxAlumnoSolicitudes y ajxSolicitudNueva 

    window.mostrarMensaje = function (texto, tipo) {
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
    };

    window.escapeHtml = function (texto) {
        var div = document.createElement("div");
        div.textContent = texto === null || texto === undefined ? "" : String(texto);
        return div.innerHTML;
    }

    window.solicitar = function (datos, endpoint) {
        var parametros = new URLSearchParams();

        Object.keys(datos).forEach(function (clave) {
            var valor = datos[clave];

            if (Array.isArray(valor)) {
                valor.forEach(function (elemento) {
                    parametros.append(clave + "[]", elemento == null ? "" : elemento);
                });
            } else {
                parametros.append(clave, valor == null ? "" : valor);
            }
        });

        var url = endpoint || window.endpointSolicitudes;

        if (!url) {
            return Promise.reject(new Error("No se ha configurado el endpoint de solicitudes."));
        }

        return fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            body: parametros.toString(),
        }).then(function (respuesta) {
            return respuesta.text().then(function (texto) {
                var cuerpo;

                try {
                    cuerpo = JSON.parse(texto);
                } catch (error) {
                    console.error("Respuesta no válida del servidor:", texto);

                    throw new Error("El servidor devolvió una respuesta no válida.");
                }

                if (!respuesta.ok || !cuerpo.ok) {
                    throw new Error(cuerpo.mensaje || "No fue posible completar la operación.");
                }

                return cuerpo.datos;
            });
        });
    };
})();
