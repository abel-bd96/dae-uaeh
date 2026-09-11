(function () {
    'use strict';

    var endpoint = '../../modules/constancia-estudios/models/modConstanciaCiclo.php';
    var paso = 1;
    var modoEdicion = false;
    var modal;
    var planesSeleccionados = [];
    var camposFecha = ['fechaPeriodoEstudiosInicio', 'fechaPeriodoEstudiosTermino', 'fechaPeriodoVacacionalInicio', 'fechaPeriodoVacacionalTermino', 'fechaSolicitudConstanciaInicio', 'fechaSolicitudConstanciaTermino'];

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
        var url = endpoint + '?accion=' + encodeURIComponent(accion) + '&texto=' + encodeURIComponent(texto || '');
        return fetch(url).then(function (respuesta) { return respuesta.json(); }).then(function (cuerpo) { return cuerpo.datos || []; });
    }

    function mostrarMensaje(texto, tipo) {
        var mensaje = document.getElementById('mensajeCiclos');
        mensaje.textContent = texto;
        mensaje.className = 'alert alert-' + tipo;
        window.scrollTo(0, 0);
    }

    function escapeHtml(texto) {
        var div = document.createElement('div');
        div.textContent = texto || '';
        return div.innerHTML;
    }

    function cargarConfiguraciones() {
        consultar('listar').then(function (configuraciones) {
            var cuerpo = document.querySelector('#tablaCiclos tbody');
            if (!configuraciones.length) {
                cuerpo.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No hay configuraciones registradas.</td></tr>';
                return;
            }
            cuerpo.innerHTML = configuraciones.map(function (configuracion) {
                var planes = configuracion.planes && configuracion.planes.length ? configuracion.planes.slice(0, 3).map(escapeHtml).join(', ') : 'Todos los programas';
                var mas = configuracion.planes && configuracion.planes.length > 3 ? ' +' + (configuracion.planes.length - 3) : '';
                var estadoClase = configuracion.estado === 'ACTIVO' ? 'success' : 'secondary';
                return '<tr><td><strong>' + escapeHtml(configuracion.nombre || '') + '</strong></td>' +
                    '<td><span class="badge text-bg-' + (configuracion.tipo === 'GENERAL' ? 'primary' : 'info') + '">' + configuracion.tipo + '</span></td>' +
                    '<td>' + planes + escapeHtml(mas) + '</td>' +
                    '<td>' + escapeHtml(configuracion.fechaPeriodoVacacionalInicio) + ' a ' + escapeHtml(configuracion.fechaPeriodoVacacionalTermino) + '</td>' +
                    '<td><button type="button" class="btn btn-sm btn-' + estadoClase + ' btn-estado" data-tipo="' + configuracion.tipo + '" data-id="' + (configuracion.tipo === 'GENERAL' ? configuracion.idCiclo : configuracion.idCicloFechaPlan) + '" data-estado="' + configuracion.estado + '">' + configuracion.estado + '</button></td>' +
                    '<td class="text-end"><button type="button" class="btn btn-sm btn-outline-primary btn-editar" data-tipo="' + configuracion.tipo + '" data-id="' + (configuracion.tipo === 'GENERAL' ? configuracion.idCiclo : configuracion.idCicloFechaPlan) + '"><i class="bi bi-pencil"></i> Editar</button></td></tr>';
            }).join('');
        }).catch(function (error) { mostrarMensaje(error.message, 'danger'); });
    }

    function pintarCiclos(texto) {
        consultar('ciclos', texto).then(function (ciclos) {
            document.getElementById('resultadosCiclos').innerHTML = ciclos.map(function (ciclo) {
                var seleccionado = ciclo.nombre === document.getElementById('cicloNombre').value;
                return '<button type="button" class="list-group-item list-group-item-action ' + (seleccionado ? 'active' : '') + '" data-nombre="' + escapeHtml(ciclo.nombre) + '">' + escapeHtml(ciclo.nombre) + '</button>';
            }).join('') || '<div class="text-muted small">No se encontraron ciclos.</div>';
        });
    }

    function pintarPlanes(texto) {
        consultar('planes', texto).then(function (planes) {
            document.getElementById('resultadosPlanes').innerHTML = planes.map(function (plan) {
                var id = String(plan.id_plan);
                var marcado = planesSeleccionados.indexOf(id) !== -1;
                return '<label class="list-group-item d-flex gap-2"><input class="form-check-input plan-ciclo" type="checkbox" value="' + id + '" ' + (marcado ? 'checked' : '') + '><span>' + escapeHtml(plan.nombre) + '</span></label>';
            }).join('') || '<div class="text-muted small">No se encontraron programas.</div>';
        });
    }

    function actualizarTipoDelCiclo(nombre) {
        consultar('listar').then(function (configuraciones) {
            var existe = configuraciones.some(function (configuracion) {
                return configuracion.nombre === nombre;
            });
            var selector = document.getElementById('selectorPlanes');
            var ayuda = document.getElementById('ayudaPlanes');

            document.getElementById('cicloTipo').value = existe ? 'ESPECIFICO' : 'GENERAL';
            selector.classList.toggle('d-none', !existe);
            ayuda.textContent = existe
                ? 'El ciclo ya tiene una configuración general. Seleccione uno o varios programas para registrar una configuración ESPECÍFICA.'
                : 'La primera configuración del ciclo es GENERAL y no requiere programas.';

            if (existe) {
                pintarPlanes('');
            } else {
                planesSeleccionados = [];
            }
        }).catch(function (error) {
            document.getElementById('errorCiclo').textContent = error.message;
        });
    }

    function mostrarPaso(numero) {
        paso = numero;
        document.querySelectorAll('.paso-ciclo').forEach(function (seccion) { seccion.classList.toggle('d-none', Number(seccion.dataset.paso) !== paso); });
        document.getElementById('subtituloModalCiclo').textContent = 'Paso ' + paso + ' de 4';
        document.getElementById('barraPaso').style.width = (paso * 25) + '%';
        document.getElementById('btnAnterior').classList.toggle('invisible', paso === 1);
        document.getElementById('btnSiguiente').classList.toggle('d-none', paso === 4);
        document.getElementById('btnGuardar').classList.toggle('d-none', paso !== 4);
        if (paso === 4) { actualizarResumen(); }
    }

    function validarPaso() {
        var mensaje = '';
        if (paso === 1 && !document.getElementById('cicloNombre').value) { mensaje = 'Debe seleccionar un ciclo existente en SIAE.'; document.getElementById('errorCiclo').textContent = mensaje; }
        if (paso === 2) {
            for (var i = 0; i < camposFecha.length; i++) { if (!document.getElementById(camposFecha[i]).value) { mensaje = 'Todas las fechas son obligatorias.'; break; } }
            if (!mensaje && (document.getElementById('fechaPeriodoEstudiosInicio').value > document.getElementById('fechaPeriodoEstudiosTermino').value || document.getElementById('fechaPeriodoVacacionalInicio').value > document.getElementById('fechaPeriodoVacacionalTermino').value || document.getElementById('fechaSolicitudConstanciaInicio').value > document.getElementById('fechaSolicitudConstanciaTermino').value)) { mensaje = 'Cada fecha de inicio debe ser menor o igual a su término.'; }
            document.getElementById('errorFechas').textContent = mensaje;
        }
        if (paso === 3 && document.getElementById('selectorPlanes').classList.contains('d-none') === false && !planesSeleccionados.length) { mensaje = 'Seleccione al menos un programa educativo.'; document.getElementById('errorPlanes').textContent = mensaje; }
        return !mensaje;
    }

    function actualizarResumen() {
        var nombres = planesSeleccionados.length ? planesSeleccionados.join(', ') : 'Configuración general';
        document.getElementById('resumenCiclo').innerHTML = '<strong>' + escapeHtml(document.getElementById('cicloNombre').value) + '</strong><br><span class="small">' + escapeHtml(nombres) + '</span>';
    }

    function abrirNuevo() {
        modoEdicion = false; planesSeleccionados = []; document.getElementById('formCiclo').reset(); document.getElementById('cicloId').value = ''; document.getElementById('cicloTipo').value = ''; document.getElementById('tituloModalCiclo').textContent = 'Nuevo ciclo'; document.getElementById('selectorPlanes').classList.add('d-none'); document.getElementById('ayudaPlanes').textContent = 'La primera configuración del ciclo es GENERAL y no requiere programas.'; document.getElementById('errorCiclo').textContent = ''; document.getElementById('errorFechas').textContent = ''; document.getElementById('errorPlanes').textContent = ''; pintarCiclos(''); mostrarPaso(1); modal.show();
    }

    function abrirEdicion(tipo, id) {
        fetch(endpoint + '?accion=obtener&tipo=' + encodeURIComponent(tipo) + '&id=' + encodeURIComponent(id)).then(function (respuesta) { return respuesta.json(); }).then(function (cuerpo) {
            if (!cuerpo.ok) { throw new Error(cuerpo.mensaje); }
            var configuracion = cuerpo.datos; modoEdicion = true; planesSeleccionados = tipo === 'ESPECIFICO' ? [String(configuracion.idPlan)] : [];
            document.getElementById('cicloId').value = id; document.getElementById('cicloTipo').value = tipo; document.getElementById('cicloNombre').value = configuracion.nombre; document.getElementById('tituloModalCiclo').textContent = 'Editar configuración ' + tipo; document.getElementById('selectorPlanes').classList.toggle('d-none', tipo === 'GENERAL'); document.getElementById('ayudaPlanes').textContent = tipo === 'GENERAL' ? 'La configuración general aplica a los programas sin configuración específica.' : 'Puede modificar los programas asociados a esta configuración.';
            camposFecha.forEach(function (campo) { document.getElementById(campo).value = configuracion[campo]; }); document.querySelector('input[name="estado"][value="' + configuracion.estado + '"]').checked = true; pintarPlanes(''); mostrarPaso(2); modal.show();
        }).catch(function (error) { mostrarMensaje(error.message, 'danger'); });
    }

    function datosFormulario() {
        var datos = { accion: modoEdicion ? 'actualizar' : 'guardar', id: document.getElementById('cicloId').value, tipo: document.getElementById('cicloTipo').value, nombre: document.getElementById('cicloNombre').value, estado: document.querySelector('input[name="estado"]:checked').value };
        camposFecha.forEach(function (campo) { datos[campo] = document.getElementById(campo).value; }); datos.planes = planesSeleccionados; return datos;
    }

    document.addEventListener('DOMContentLoaded', function () {
        modal = new bootstrap.Modal(document.getElementById('modalCiclo')); cargarConfiguraciones(); document.getElementById('btnNuevoCiclo').addEventListener('click', abrirNuevo); document.getElementById('buscarCiclo').addEventListener('input', function () { pintarCiclos(this.value); }); document.getElementById('buscarPlan').addEventListener('input', function () { pintarPlanes(this.value); });
        document.getElementById('resultadosCiclos').addEventListener('click', function (evento) { if (evento.target.dataset.nombre) { document.getElementById('cicloNombre').value = evento.target.dataset.nombre; document.getElementById('errorCiclo').textContent = ''; actualizarTipoDelCiclo(evento.target.dataset.nombre); pintarCiclos(document.getElementById('buscarCiclo').value); } });
        document.getElementById('resultadosPlanes').addEventListener('change', function (evento) { if (evento.target.classList.contains('plan-ciclo')) { var id = evento.target.value; if (evento.target.checked && planesSeleccionados.indexOf(id) === -1) { planesSeleccionados.push(id); } if (!evento.target.checked) { planesSeleccionados = planesSeleccionados.filter(function (plan) { return plan !== id; }); } } });
        document.getElementById('btnSiguiente').addEventListener('click', function () { if (validarPaso()) { mostrarPaso(Math.min(4, paso + 1)); if (paso === 3 && !document.getElementById('selectorPlanes').classList.contains('d-none')) { pintarPlanes(''); } } }); document.getElementById('btnAnterior').addEventListener('click', function () { mostrarPaso(Math.max(1, paso - 1)); });
        document.getElementById('formCiclo').addEventListener('submit', function (evento) { evento.preventDefault(); if (!validarPaso()) { return; } solicitar(datosFormulario()).then(function () { modal.hide(); mostrarMensaje('La configuración se guardó correctamente.', 'success'); cargarConfiguraciones(); }).catch(function (error) { mostrarMensaje(error.message, 'danger'); }); });
        document.getElementById('tablaCiclos').addEventListener('click', function (evento) { var editar = evento.target.closest('.btn-editar'); var estado = evento.target.closest('.btn-estado'); if (editar) { abrirEdicion(editar.dataset.tipo, editar.dataset.id); } if (estado && window.confirm('¿Desea cambiar el estado de esta configuración?')) { fetch(endpoint + '?accion=obtener&tipo=' + encodeURIComponent(estado.dataset.tipo) + '&id=' + encodeURIComponent(estado.dataset.id)).then(function (respuesta) { return respuesta.json(); }).then(function (cuerpo) { if (!cuerpo.ok) { throw new Error(cuerpo.mensaje); } var datos = cuerpo.datos; datos.accion = 'actualizar'; datos.id = estado.dataset.id; datos.tipo = estado.dataset.tipo; datos.estado = estado.dataset.estado === 'ACTIVO' ? 'INACTIVO' : 'ACTIVO'; datos.planes = estado.dataset.tipo === 'ESPECIFICO' ? [String(datos.idPlan)] : []; return solicitar(datos); }).then(function () { mostrarMensaje('El estado se actualizó correctamente.', 'success'); cargarConfiguraciones(); }).catch(function (error) { mostrarMensaje(error.message, 'danger'); }); } });
    });
}());
