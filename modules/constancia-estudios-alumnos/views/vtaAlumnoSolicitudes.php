<main class="container-fluid container-md py-3 py-md-4" id="solicitudesConstancia">
    <div class="container">
        <div class="row align-items-start g-3">
            <div class="col-12 col-md-auto text-center text-md-start">
                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="var(--primary-color)"
                    class="bi bi-file-text" viewBox="0 0 16 16" aria-hidden="true">
                    <path
                        d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5M5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1z" />
                    <path
                        d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1" />
                </svg>
            </div>

            <div class="col-12 col-md">
                <h1 class="h3 mb-1">
                    Solicitud de Constancia de Estudios
                </h1>

                <p class="text-uppercase text-muted small mb-0 text-break">
                    Programa Educativo: Licenciatura en Ciencias Computacionales
                </p>
            </div>

            <div class="col-12 col-md-auto">
                <a id="btnSolicitarConstancia" class="btn btn-primary w-100" href="http://localhost:8080/dae-uaeh/public/views/index.php?vta=solicitudNueva" role="button">
                    <i class="bi bi-plus-lg"></i> Trámite Nuevo
                </a>
            </div>
        </div>
    </div>

    <hr>

    <div class="card border-0 mt-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <h2 class="h3 mb-3 px-2 px-md-0">
                    Historial de Solicitudes
                </h2>

                <table class="table table-hover align-middle mb-0" id="tablaHistorialSolicitudes">
                    <thead class="table-light">
                        <tr>
                            <th class="text-nowrap">Fecha de Registro</th>
                            <th>Descripción</th>
                            <th class="text-nowrap">Estado</th>
                            <th class="text-end text-nowrap">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                Cargando historial...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="modalSeguimientoConstancia" tabindex="-1" aria-labelledby="tituloModalSeguimientoConstancia"
    aria-hidden="true">

    <div class="modal-dialog modal-fullscreen-sm-down modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header align-items-start">
                <div class="pe-3">
                    <h2 class="modal-title h5" id="tituloModalSeguimientoConstancia">
                        Seguimiento de Solicitud
                    </h2>
                    <p class="small text-muted mb-0" id="subtituloModalSeguimientoConstancia">
                        Folio
                    </p>
                </div>

                <button type="button" class="btn-close flex-shrink-0" data-bs-dismiss="modal" aria-label="Cerrar">
                </button>
            </div>

            <form id="formSeguimientoConstancia" novalidate>
                <div class="modal-body">

                    <div class="progress mb-4" style="height: 10px;">
                        <div class="progress-bar" id="barraProgresoEstado" role="progressbar" aria-valuemin="0"
                            aria-valuemax="100" aria-valuenow="20" style="width: 20%;">
                            Pendiente de Pago
                        </div>
                    </div>

                    <section class="paso-solicitud">

                        <table class="table table-striped-columns align-middle mb-0">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-center">
                                        Contiene los siguientes datos:
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <th scope="row" class="mark">Fecha de Emisión</th>
                                    <td id="fechaEmision"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Nombre</th>
                                    <td id="nombreAlumno"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Apellido Paterno</th>
                                    <td id="apellidoPaterno"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Apellido Materno</th>
                                    <td id="apellidoMaterno"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">CURP</th>
                                    <td id="curp"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Unidad Académica</th>
                                    <td id="unidadAcademica"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Programa Educativo</th>
                                    <td id="programaEducativo"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">CCT UAEH</th>
                                    <td id="cctUAEH"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">CCT UA</th>
                                    <td id="cctUA"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Número de cuenta</th>
                                    <td id="numeroCuenta"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Semestre</th>
                                    <td id="semestre"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Tipo de Ingreso</th>
                                    <td id="tipoIngreso"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Condición Escolar</th>
                                    <td id="condicion"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Duración del Programa Educativo</th>
                                    <td id="duracionPE"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Calidad del Alumno</th>
                                    <td id="calidadAlumno"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Promedio</th>
                                    <td id="promedio"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Avance en el Programa Educativo</th>
                                    <td id="avancePE"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Periodo de Estudios</th>
                                    <td id="periodoEstudios"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Periodo Vacacional</th>
                                    <td id="periodoVacacional"> </td>
                                </tr>
                            </tbody>
                        </table>
                    </section>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btnCancelar" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success d-none" id="btnFormatoPago" data-bs-dismiss="modal">
                        <i class="bi bi-cash-coin"></i> Descargar Formato de Pago
                    </button>
                    <button type="button" class="btn btn-success d-none" id="btnConstanciaDigital" data-bs-dismiss="modal">
                        <i class="bi bi-file-earmark-arrow-down-fill"></i> Descargar Constancia Digital
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>