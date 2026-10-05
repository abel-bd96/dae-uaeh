<main class="container py-4" id="constanciaGenerarSolicitud">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <p class="text-uppercase text-muted small mb-1">Emisión de constancias</p>
            <h1 class="h3 mb-0">Seguimiento de constancias</h1>
        </div>
        <button type="button" class="btn btn-primary" id="btnNuevaSolicitud">
            <i class="bi bi-plus-lg"></i> Crear Solicitud
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaSolicitudes">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha Solicitud</th>
                            <th>Número de Cuenta</th>
                            <th>Dato Adicional</th>
                            <th>Observación</th>
                            <th>Nombre</th>
                            <th>Ap. Paterno</th>
                            <th>Ap. Materno</th>
                            <th>Estatus Solicitud</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Cargando solicitudes...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="modalSolicitud" tabindex="-1" aria-labelledby="tituloModalGenerarSolicitud" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title h5" id="tituloModalGenerarSolicitud">Nueva Solicitud</h2>
                    <p class="small text-muted mb-0" id="subtituloModalGenerarSolicitud">Paso 1 de 3</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <form id="formSolicitud" novalidate>
                <div class="modal-body">
                <div id="mensajeSolicitud" class="alert d-none" role="alert"></div>
                    <input type="hidden" name="id" id="solicitudId">

                    <!-- Datos del alumno (ocultos, se llenan al seleccionar) -->
                    <input type="hidden" name="NumeroCuenta" id="NumeroCuenta">
                    <input type="hidden" name="Nombre" id="Nombre">
                    <input type="hidden" name="ApellidoPaterno" id="ApellidoPaterno">
                    <input type="hidden" name="ApellidoMaterno" id="ApellidoMaterno">
                    <input type="hidden" name="Instituto" id="Instituto">
                    <input type="hidden" name="Semestre" id="Semestre">
                    <input type="hidden" name="ProgramaEducativo" id="ProgramaEducativo">
                    <input type="hidden" name="Periodo" id="Periodo">

                    <div class="progress mb-4" style="height: 5px">
                        <div class="progress-bar" id="barraPaso" style="width: 33%"></div>
                    </div>

                    <!-- PASO 1 -->
                    <section class="paso-solicitud" data-paso="1">
                        <h3 class="h6">Buscar Alumno</h3>
                        <label for="buscarNumeroCuenta" class="form-label">Buscar por número de cuenta</label>
                        <input type="search" class="form-control mb-3" id="buscarNumeroCuenta" placeholder="Ej. 250122">
                        <div id="resultadosNumeroCuenta" class="list-group"></div>
                        <div class="invalid-feedback" id="errorNumeroCuenta"></div>

                        <div id="datosAlumno" class="d-none mt-3">
                            <p class="mb-1"><strong>Número de cuenta:</strong> <span id="mostrarNumeroCuenta"></span></p>
                            <p class="mb-1"><strong>Nombre:</strong> <span id="mostrarNombre"></span></p>
                            <p class="mb-1"><strong>Apellido Paterno:</strong> <span id="mostrarApellidoPaterno"></span></p>
                            <p class="mb-1"><strong>Apellido Materno:</strong> <span id="mostrarApellidoMaterno"></span></p>
                        </div>

                        <div id="datosInstitucional" class="d-none mt-3">
                            <p class="mb-1"><strong>Instituto:</strong> <span id="mostrarInstituto"></span></p>
                            <p class="mb-1"><strong>Semestre:</strong> <span id="mostrarSemestre"></span></p>
                            <p class="mb-1"><strong>Programa Educativo:</strong> <span id="mostrarProgramaEducativo"></span></p>
                            <p class="mb-1"><strong>Periodo:</strong> <span id="mostrarPeriodo"></span></p>
                        </div>
                    </section>

                    <!-- PASO 2 -->
                    <section class="paso-solicitud d-none" data-paso="2">
                        <h3 class="h6 mb-3">Datos de la constancia</h3>

                        <!-- Dato adicional -->
                        <fieldset class="mb-3">
                            <label class="form-label fw-semibold d-block fs-6">¿Requiere datos adicionales?</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="datoAdicionalSi" name="DatoAdicional" value="S">
                                <label class="form-check-label" for="datoAdicionalSi">Sí</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="datoAdicionalNo" name="DatoAdicional" value="N">
                                <label class="form-check-label" for="datoAdicionalNo">No</label>
                            </div>
                    </fieldset>

                        <!-- Tipo de firma -->
                        <fieldset class="mb-3">
                            <label class="form-label fw-semibold d-block">Tipo de Firma</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="firmaDigital" name="TipoFirma" value="S">
                                <label class="form-check-label" for="firmaDigital">Digital (PDF)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="firmaAutografa" name="TipoFirma" value="N">
                                <label class="form-check-label" for="firmaAutografa">Autógrafa</label>
                            </div>
                        </fieldset>

                        <!-- Calificaciones -->
                        <fieldset class="mb-3">
                            <label class="form-label fw-semibold d-block">Calificaciones</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="ConCalificaciones" name="Calificaciones" value="S">
                                <label class="form-check-label" for="ConCalificaciones">Sí</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="SinCalificaciones" name="Calificaciones" value="N">
                                <label class="form-check-label" for="SinCalificaciones">No</label>
                            </div>
                        </fieldset>

                        <!-- Observación -->
                        <div class="mt-3">
                            <label for="Observacion" class="form-label">Observación</label>
                            <select class="form-select" id="Observacion" name="Observacion">
                                <option value="">Seleccione una observación</option>
                            </select>
                        </div>

                        <div class="invalid-feedback" id="errorDatos"></div>
                    </section>

                    <!-- PASO 3 -->
                    <section class="paso-solicitud d-none" data-paso="3">
                        <h3 class="h6">Resumen de la solicitud</h3>
                        <div id="resumenSolicitud" class="mt-3"></div>
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