<main class="container py-4" id="constanciaGenerarSolicitud">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <p class="text-uppercase text-muted small mb-1">Emisión de constancias</p>
            <h1 class="h3 mb-0">Seguimiento de constancias</h1>
        </div>
        <a href="?vta=CrearSolicitud" class="btn btn-primary" id="btnNuevaSolicitud">
            <i class="bi bi-plus-lg"></i> Crear Solicitud
        </a>
    </div>

    <div id="mensajeSolicitud" class="alert d-none" role="alert"></div>

    <div class="card-body shadow-sm border-0 mb-4">
        <div class="card-body">
        <h2 class="h6 text-uppercase text-muted mb-3">Filtros de búsqueda</h2>
        <div class="row g-3">
            <div class="col-md-3">
                <label for="filtroNumeroCuenta" class="form-label">Número de cuenta</label>
                <input type="text" class="form-control" id="filtroNumeroCuenta" placeholder="Ej. 250122">
            </div>

            <div class="col-md-3">
                <label for="filtroNombre" class="form-label">Nombre del alumno</label>
                <input type="text" class="form-control" id="filtroNombre" placeholder="Nombre completo">
            </div>

                <div class="col-md-2">
                <label for="filtroEstatus" class="form-label">Estatus</label>
                <select class="form-select" id="filtroEstatus">
                    <option value="">Todos</option>
                    <option value="Solicitado">Solicitado</option>
                    <option value="En elaboración">En elaboración</option>
                    <option value="En firma">En firma</option>
                    <option value="Terminado">Terminado</option>
                    <option value="Cancelado">Cancelado</option>
                </select>
            </div>

            <div class="col-md-2">
                    <label for="filtroCiclo" class="form-label">Ciclo escolar</label>
                    <input type="text" class="form-control" id="filtroCiclo" placeholder="Ej. 2024-2025">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-primary w-100" id="btnFiltrar">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
            </div>

            <div class="mt-2 text-end">
                <button type="button" class="btn btn-link btn-sm" id="btnLimpiarFiltros">
                    Limpiar filtros
                </button>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaSolicitudes">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha Solicitud</th>
                            <th>Ciclo Escolar</th>
                            <th>Número de Cuenta</th>
                            <th>Nombre Completo</th>
                            <th>Estatus Solicitud</th>
                            <th>Dato Adicional</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Seleccione los filtros y presione <strong>Buscar</strong> para ver resultados
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    
        <div class="modal fade" id="modalConfirmar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConfirmarTitulo">Confirmar acción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p id="modalConfirmarMensaje" class="mb-3">¿Está seguro?</p>

                    <div id="modalConfirmarSelectWrap" class="d-none">
                        <label for="modalConfirmarSelect" class="form-label">Seleccione el nuevo estatus</label>
                        <select class="form-select" id="modalConfirmarSelect">
                            <option value="Solicitado">Solicitado</option>
                            <option value="En elaboración">En elaboración</option>
                            <option value="En firma">En firma</option>
                            <option value="Terminado">Terminado</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="modalConfirmarAceptar">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
</main>
