
(function () {
    'use strict';

    var endpoint = '../../modules/constancia-estudios/models/modConstanciaListaNegra.php';
    var paso = 1;
    var modoEdicion = false;
    var modal;
    var id = String(estatu.id);
    var numeroCuentaSeleccionados = [];
    var estatusSeleccionados = [];

    function solicitar(datos) {
        var parametros = new URLSearchParams();
        Object.keys(datos).forEach(function (clave) {
            if (Array.isArray(datos[clave])) {
                datos[clave].forEach(function (valor) { parametros.append(clave + '[]', valor); });
            } else {
                parametros.append(clave, datos[clave]);
            }
        });
        return fetch(endpoint, { method: 'POST', body: parametros }).then(function (respuesta) {
            return respuesta.json().then(function (cuerpo) {
                if (!respuesta.ok || !cuerpo.ok) { throw new Error(cuerpo.mensaje || 'No fue posible completar la operación.'); }
                return cuerpo.datos;
            });
        });
    }

    function consultar(accion, texto) {
        var url = endpoint + '?accion=' +encodeURIComponent(accion) + '&texto=' + encodeURIComponent(texto || '');
        return fetch(url).then(function(respuesta){ return respuesta.json(); }).then(function(cuerpo){ return cuerpo.datos || []; });

        }

    function mostrarMensaje(texto, tipo) {
        var mensaje = document.getElementById('mensajeListaNegra');
        mensaje.textContent = 'texto';
        mensaje.className = 'alert alert-' + tipo;
        window.scrollTo(0, 0);
    }

    function escapeHtml(texto) {
        var div = document.createElement('div');
        div.textContent = texto || '';
        return div.innerHTML;
    }

    function cargarRegistros() {
        // Aquí deberías llamar a una acción 'listar' en el backend
        solicitar({ accion: 'listar' }).then(function (registros) {
            var tbody = document.querySelector('#tablaListaNegra tbody');
            if (!registros || !registros.length){
                tbody.innerHTML=
                '<tr> + <td colspan="3" class="text-center text-muted py-4">' + 'Sin registros.' +
                '</td>' + '</tr>';
                return;
            }
            tbody.innerHTML = registros.map(function (r) {
                return '<tr>' +
                '<td>' + escapeHtml(r.NumeroCuenta) + '</td>' +
                '<td>' +
                    '<button type="button" class="btn btn-sm btn-outline-primary btn-editar" data-id="' +
                    escapeHtml(r.NumeroCuenta) + '">' +
                    escapeHtml(r.Estatus) +
                    '</button>' +
                '</td>' +
            '</tr>';
            }).join('') || '<tr><td colspan="4" class="text-center text-muted py-4">Sin registros.</td></tr>';
        }).catch(function (e) { mostrarMensaje(e.message, 'danger'); 
            
    });
}


    function pintarNumeroCuenta(texto) {
        consultar('numeroCuenta', texto).then(function (numeroCuenta) {
            document.getElementById('resultadosNumeroCuenta').innerHTML = numeroCuenta.map(function (numeroCuenta) {
                    var  id= String(numeroCuenta.idNumeroCuenta);
                    var marcado = numeroCuentaSeleccionados.indexOf(id) !==-1;
                return '<label class="list-group-item d-flex gap-2"><input class="form-check-input numeroCuenta" type="checkbox" value="' + id + '" ' + (marcado ? 'checked' : '') + '><span>' + escapeHtml(numeroCuenta.NumeroCuenta) + '</span></label>';
            }).join('') || '<div class="text-muted small">No se encontro el numero de cuenta.</div>';
        });
    }

    function pintarEstatus(texto) 
            { consultar('estatus', texto).then(function (estatus) 
                { document.getElementById('resultadosEstatus').innerHTML = 
                    estatus.map(function (estatu) { 
                    var id = String(estatu.id_numero_cuenta);
                    var marcado = estatusSeleccionados.indexOf(id) !== -1;
                return '<label class="list-group-item d-flex gap-2"><input class="form-check-input estatus" type="checkbox" value="' + id + '" ' + (marcado ? 'checked' : '') + '><span>' + escapeHtml(estatu.nombre) + '</span></label>';
            }).join('') || '<div class="text-muted small">No se encontraron estatus.</div>';
        });
    }

    
    function abrirModal(edicion, datos) {
        modoEdicion = !!edicion;
        paso = 1;
        document.getElementById('tituloModalListaNegra').textContent = edicion ? 'Editar caso' : 'Nuevo caso especial';
        document.getElementById('subtituloModalListaNegra').textContent = 'Paso 1 de 1';
        document.getElementById('formListaNegra').reset();
        var mensaje = document.getElementById('mensajeListaNegra');

    mensaje.textContent = '';
    mensaje.className = 'alert d-none';
//limpiar  

    document.getElementById('resultadosNumeroCuenta').innerHTML = '';
    document.getElementById('resultadosEstatus').innerHTML = '';
        if (datos) {
            document.getElementById('NumeroCuenta').value = datos.NumeroCuenta || '';
            document.getElementById('Estatus').value = datos.Estatus || '';
            
        }
        document.getElementById('btnGuardar').classList.remove('d-none');
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', function () {
        modal = new bootstrap.Modal(document.getElementById('modalListaNegra'));
        cargarRegistros();

        document.querySelector('#tablaListaNegra tbody').addEventListener('click', function (e) {
            var btn = e.target.closest('.btn-editar');
            if (!btn) return;
                var numeroCuenta = btn.dataset.id;
            solicitar({
                        accion: 'listar'
                    }).then(function (registros) {

                var registro = registros.find(function (item) {
                    return String(item.NumeroCuenta) === String(numeroCuenta);
                });

                if (registro) {
                    abrirModal(true, registro);
                }

            }).catch(function (e) {
                mostrarMensaje(e.message, 'danger');
            });

});

        document.getElementById('btnNuevoListaNegra').addEventListener('click', function () { abrirModal(false); });
        document.getElementById('NumeroCuenta').addEventListener('input', function () { pintarNumeroCuenta(this.value); });
        document.getElementById('Estatus').addEventListener('input', function () { pintarEstatus(this.value); });

        document.getElementById('resultadosNumeroCuenta').addEventListener('click', function (e) {
            var btn = e.target.closest('button[data-nombre]');
            if (!btn) return;
            document.getElementById('NumeroCuenta').value = btn.dataset.nombre;
            document.getElementById('resultadosNumeroCuenta').innerHTML = '';
        });

        document.getElementById('resultadosEstatus').addEventListener('click', function (e) {
            var btn = e.target.closest('button[data-nombre]');
            if (!btn) return;
            document.getElementById('Estatus').value = btn.dataset.nombre;
            document.getElementById('resultadosEstatus').innerHTML = '';
        });

        document.getElementById('formListaNegra').addEventListener('submit', function (e) {
            e.preventDefault();
            var datos = {
                accion: modoEdicion ? 'actualizar' : 'guardar',
                NumeroCuenta: document.getElementById('NumeroCuenta').value,
                Estatus: document.getElementById('Estatus').value,
            };
            solicitar(datos).then(function () {
                mostrarMensaje('Registro guardado correctamente.', 'success');
                modal.hide();
                cargarRegistros();
            }).catch(function (err) { mostrarMensaje(err.message, 'danger');
            });
        });
    });
}());