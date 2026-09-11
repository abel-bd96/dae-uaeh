<main class="container py-4" id="constanciaCiclos">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <p class="text-uppercase text-muted small mb-1">Emisión de constancias</p>
            <h1 class="h3 mb-0">Registro y configuración de ciclos</h1>
        </div>
        <button type="button" class="btn btn-primary" id="btnNuevoCiclo">
            <i class="bi bi-plus-lg"></i> Nuevo ciclo
        </button>
    </div>

    <div id="mensajeCiclos" class="alert d-none" role="alert"></div>
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaCiclos">
                    <thead class="table-light">
                        <tr>
                            <th>Ciclo</th>
                            <th>Tipo</th>
                            <th>Programas educativos</th>
                            <th>Periodo vacacional</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Cargando configuraciones...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="modalCiclo" tabindex="-1" aria-labelledby="tituloModalCiclo" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title h5" id="tituloModalCiclo">Nuevo ciclo</h2>
                    <p class="small text-muted mb-0" id="subtituloModalCiclo">Paso 1 de 4</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formCiclo" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="id" id="cicloId">
                    <input type="hidden" name="tipo" id="cicloTipo">
                    <div class="progress mb-4" style="height: 5px">
                        <div class="progress-bar" id="barraPaso" style="width: 25%"></div>
                    </div>

                    <section class="paso-ciclo" data-paso="1">
                        <h3 class="h6">Seleccionar ciclo de SIAE</h3>
                        <label for="buscarCiclo" class="form-label">Buscar por nombre</label>
                        <input type="search" class="form-control mb-3" id="buscarCiclo" placeholder="Ej. 2026">
                        <div id="resultadosCiclos" class="list-group"></div>
                        <input type="hidden" name="nombre" id="cicloNombre">
                        <div class="invalid-feedback d-block" id="errorCiclo"></div>
                    </section>

                    <section class="paso-ciclo d-none" data-paso="2">
                        <h3 class="h6 mb-3">Configurar fechas</h3>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label" for="fechaPeriodoEstudiosInicio">Inicio periodo de estudios</label><input type="date" class="form-control fecha-ciclo" name="fechaPeriodoEstudiosInicio" id="fechaPeriodoEstudiosInicio"></div>
                            <div class="col-md-6"><label class="form-label" for="fechaPeriodoEstudiosTermino">Fin periodo de estudios</label><input type="date" class="form-control fecha-ciclo" name="fechaPeriodoEstudiosTermino" id="fechaPeriodoEstudiosTermino"></div>
                            <div class="col-md-6"><label class="form-label" for="fechaPeriodoVacacionalInicio">Inicio periodo vacacional</label><input type="date" class="form-control fecha-ciclo" name="fechaPeriodoVacacionalInicio" id="fechaPeriodoVacacionalInicio"></div>
                            <div class="col-md-6"><label class="form-label" for="fechaPeriodoVacacionalTermino">Fin periodo vacacional</label><input type="date" class="form-control fecha-ciclo" name="fechaPeriodoVacacionalTermino" id="fechaPeriodoVacacionalTermino"></div>
                            <div class="col-md-6"><label class="form-label" for="fechaSolicitudConstanciaInicio">Inicio periodo de solicitud</label><input type="date" class="form-control fecha-ciclo" name="fechaSolicitudConstanciaInicio" id="fechaSolicitudConstanciaInicio"></div>
                            <div class="col-md-6"><label class="form-label" for="fechaSolicitudConstanciaTermino">Fin periodo de solicitud</label><input type="date" class="form-control fecha-ciclo" name="fechaSolicitudConstanciaTermino" id="fechaSolicitudConstanciaTermino"></div>
                        </div>
                        <div class="invalid-feedback d-block" id="errorFechas"></div>
                    </section>

                    <section class="paso-ciclo d-none" data-paso="3">
                        <h3 class="h6">Programas educativos</h3>
                        <p class="small text-muted" id="ayudaPlanes">La primera configuración del ciclo es GENERAL y no requiere programas.</p>
                        <div id="selectorPlanes" class="d-none">
                            <label for="buscarPlan" class="form-label">Buscar por nombre</label>
                            <input type="search" class="form-control mb-3" id="buscarPlan" placeholder="Ej. Derecho">
                            <div id="resultadosPlanes" class="list-group"></div>
                            <div class="invalid-feedback d-block" id="errorPlanes"></div>
                        </div>
                    </section>

                    <section class="paso-ciclo d-none" data-paso="4">
                        <h3 class="h6">Estado inicial</h3>
                        <p class="small text-muted">La opción predeterminada es INACTIVO.</p>
                        <div class="form-check"><input class="form-check-input" type="radio" name="estado" id="estadoInactivo" value="INACTIVO" checked><label class="form-check-label" for="estadoInactivo">INACTIVO</label></div>
                        <div class="form-check"><input class="form-check-input" type="radio" name="estado" id="estadoActivo" value="ACTIVO"><label class="form-check-label" for="estadoActivo">ACTIVO</label></div>
                        <div class="mt-4 p-3 bg-light rounded" id="resumenCiclo"></div>
                    </section>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" id="btnAnterior">Anterior</button>
                    <button type="button" class="btn btn-primary" id="btnSiguiente">Siguiente</button>
                    <button type="submit" class="btn btn-success d-none" id="btnGuardar">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>