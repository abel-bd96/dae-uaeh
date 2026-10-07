<main class="container py-4" id="constanciaCrearSolicitud">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-uppercase text-muted small mb-1">Emisión de constancias</p>
            <h1 class="h3 mb-0" id="tituloFormularioSolicitud">Nueva Solicitud</h1>
        </div>
        <a href="?vta=index" class="btn btn-outline-secondary" id="btnRegresarListado">
            <i class="bi bi-arrow-left"></i> Regresar
        </a>
    </div>

    <div id="mensajeFormulario" class="alert d-none" role="alert"></div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <!-- Barra de progreso -->
            <div class="progress mb-4" style="height: 6px;">
                <div class="progress-bar" id="barraPaso" role="progressbar" style="width: 33%;"></div>
            </div>
            <p class="text-muted small text-end mb-3" id="subtituloFormularioSolicitud">Paso 1 de 3</p>

            <form id="formSolicitud" novalidate>

                <input type="hidden" id="solicitudId" value="">
                <input type="hidden" id="CicloEscolar" value="">
                <input type="hidden" id="NumeroCuenta" value="">
                <input type="hidden" id="NombreCompleto" value="">
                <input type="hidden" id="Instituto" value="">
                <input type="hidden" id="Semestre" value="">
                <input type="hidden" id="ProgramaEducativo" value="">

                <!-- PASO 1: ALUMNO          -->
                <div class="paso-solicitud" data-paso="1">
                    <h2 class="h5 mb-3">1. Seleccione al alumno</h2>

                    <div class="mb-3">
                        <label for="buscarNumeroCuenta" class="form-label">Buscar por número de cuenta</label>
                        <input type="text" class="form-control" id="buscarNumeroCuenta" placeholder="Escriba al menos 6 caracteres...">
                        <div class="form-text">Los resultados aparecerán debajo.</div>
                    </div>

                    <div id="resultadosNumeroCuenta" class="list-group mb-3"></div>
                    <div id="errorNumeroCuenta" class="text-danger small mb-3"></div>

                    <!-- Datos del alumno -->
                    <div id="datosAlumno" class="d-none">
                        <h3 class="h6 text-uppercase text-muted">Datos del alumno</h3>
                        <div class="row g-2 mb-3">
                            <div class="col-md-4"><strong>Número de cuenta:</strong>
                                <span id="mostrarNumeroCuenta"></span></div>
                            <div class="col-md-8"><strong>Nombre:</strong>
                                <span id="mostrarNombreCompleto"></span></div>
                        </div>
                    </div>

                    <div id="datosInstitucional" class="d-none">
                        <h3 class="h6 text-uppercase text-muted">Datos institucionales</h3>
                        <div class="row g-2">
                            <div class="col-md-3"><strong>Ciclo:</strong>
                                <span id="mostrarCicloEscolar"></span></div>
                            <div class="col-md-3"><strong>Instituto:</strong>
                                <span id="mostrarInstituto"></span></div>
                            <div class="col-md-3"><strong>Semestre:</strong>
                                <span id="mostrarSemestre"></span></div>
                            <div class="col-md-3"><strong>Programa:</strong>
                                <span id="mostrarProgramaEducativo"></span></div>
                        </div>
                    </div>
                </div>

                <!--  PASO 2: OPCIONES            -->
                <div class="paso-solicitud d-none" data-paso="2">
                    <h2 class="h5 mb-3">2. Opciones de la constancia</h2>

                    <div class="mb-4">
                        <label class="form-label fw-bold">¿Desea datos adicionales?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="DatoAdicional" id="daSi" value="S">
                            <label class="form-check-label" for="daSi">Sí</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="DatoAdicional" id="daNo" value="N">
                            <label class="form-check-label" for="daNo">No</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Tipo de firma</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="TipoFirma"  id="tfDigital" value="S">
                            <label class="form-check-label" for="tfDigital">Digital (PDF)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="TipoFirma"  id="tfAutografa" value="N">
                            <label class="form-check-label" for="tfAutografa">Autógrafa</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">¿Incluir calificaciones?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Calificaciones" id="calSi" value="S">
                            <label class="form-check-label" for="calSi">Sí</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Calificaciones" id="calNo" value="N">
                            <label class="form-check-label" for="calNo">No</label>
                        </div>
                    </div>

                    <div id="errorDatos" class="text-danger small"></div>
                </div>

                <!--  PASO 3: RESUMEN             -->
                <div class="paso-solicitud d-none" data-paso="3">
                    <h2 class="h5 mb-3">3. Resumen y observación</h2>

                    <div id="resumenSolicitud" class="border rounded p-3 bg-light mb-3"></div>

                    <div class="mb-3">
                        <label for="Observacion" class="form-label">Observación</label>
                        <select class="form-select" id="Observacion">
                            <option value="">Seleccione una observación</option>
                        </select>
                    </div>
                </div>

                <!--  BOTONES DE NAVEGACIÓN       -->
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary invisible" id="btnAnterior">
                        <i class="bi bi-arrow-left"></i> Anterior
                    </button>
                    <div>
                        <button type="button" class="btn btn-primary" id="btnSiguiente">
                            Siguiente <i class="bi bi-arrow-right"></i>
                        </button>
                        <button type="button" class="btn btn-success d-none" id="btnGuardar">
                            <i class="bi bi-save"></i> Guardar Solicitud
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

</main>
