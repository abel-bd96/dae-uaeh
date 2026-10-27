<?php
include './header_constancias.php';
include './sidebar_constancias.php';
?>
<main class="container py-4" id="constanciaGenerarSolicitud">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <p class="text-uppercase text-muted small mb-1">Emisión de constancias</p>
            <h1 class="h3 mb-0">Seguimiento de constancias</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="./vtaCrearSolicitud.php" class="btn btn-primary" id="btnNuevaSolicitud">
                <i class="bi bi-plus-lg"></i> Crear Solicitud
            </a>
        </div>
    </div>

    <div id="mensajeSolicitud" class="alert d-none" role="alert"></div>

    <div class="card shadow-sm border-0 mb-4 d-none" id="barraAccionesMasivas">
    <div class="card-body d-flex flex-wrap align-items-center gap-2">
        <span class="fw-bold me-2">
            <i class="bi bi-check2-square"></i>
            <span id="contadorAcciones">0</span> seleccionada(s)
        </span>
        <button type="button" class="btn btn-sm btn-outline-info"    data-accion-masiva="elaborar">     <i class="bi bi-gear"></i> Elaborar</button>
        <button type="button" class="btn btn-sm btn-outline-warning" data-accion-masiva="mandarFirma">  <i class="bi bi-pen"></i> Mandar a firma</button>
        <button type="button" class="btn btn-sm btn-outline-success" data-accion-masiva="emitir">       <i class="bi bi-file-earmark-check"></i> Emitida</button>
        <button type="button" class="btn btn-sm btn-outline-success" data-accion-masiva="entregar">     <i class="bi bi-box-arrow-right"></i> Entregado</button>
        <button type="button" class="btn btn-sm btn-outline-dark"    data-accion-masiva="finalizar">    <i class="bi bi-flag"></i> Finalizar</button>
        <button type="button" class="btn btn-sm btn-outline-danger"  data-accion-masiva="cancelar">     <i class="bi bi-x-circle"></i> Cancelar</button>
        <button type="button" class="btn btn-sm btn-outline-primary" data-accion-masiva="imprimir">     <i class="bi bi-printer"></i> Imprimir</button>
        <button type="button" class="btn btn-sm btn-link ms-auto"    id="btnLimpiarSeleccion">Limpiar selección</button>
    </div>
</div>
        <!-- Filtros -->
    <div class="card shadow-sm border-0 mb-4">
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
                        <option value="">Seleccionar</option>
                        <option value="Solicitado">Solicitado</option>
                        <option value="Pagado">Pagado</option>
                        <option value="En elaboración">En elaboración</option>
                        <option value="En firma">En firma</option>
                        <option value="Emitida">Emitida</option>
                        <option value="Entregado">Entregado</option>
                        <option value="Finalizado">Finalizado</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="filtroCiclo" class="form-label">Ciclo escolar</label>
                    <input type="text" class="form-control" id="filtroCiclo" placeholder="Ej. 2024-2025">
                </div>

                <div class="col-md-2">
                    <label for="filtroDatoAdicional" class="form-label">Dato adicional</label>
                    <select class="form-select" id="filtroDatoAdicional">
                        <option value="">Seleccionar</option>
                        <option value="S">Especiales (Sí)</option>
                        <option value="N">No especiales</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filtroTipoFirma" class="form-label">Tipo de firma</label>
                    <select class="form-select" id="filtroTipoFirma">
                        <option value="">Seleccionar</option>
                        <option value="S">Digital (PDF)</option>
                        <option value="N">Autógrafa</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="filtroFecha" class="form-label">Fecha de solicitud</label>
                    <select class="form-select" id="filtroFecha">
                        <option value="">Seleccionar</option>
                        <option value="hoy">Hoy</option>
                        <option value="semana">Esta semana</option>
                        <option value="mes">Este mes</option>
                        <option value="rango">Fecha</option>
                    </select>
                </div>

                <div class="col-md-3 d-none" id="wrapFechaDesde">
                    <label for="filtroFechaDesde" class="form-label">Desde</label>
                    <input type="date" class="form-control" id="filtroFechaDesde">
                </div>

                <div class="col-md-3 d-none" id="wrapFechaHasta">
                    <label for="filtroFechaHasta" class="form-label">Hasta</label>
                    <input type="date" class="form-control" id="filtroFechaHasta">
                </div>

                <div class="col-md-3 d-flex align-items-end">
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

    <!-- Tabla -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaSolicitudes">
                    <thead class="table-light">
                    <tr>
                        <th style="width: 40px;">
                        <input type="checkbox" class="form-check-input" id="checkTodos" title="Seleccionar todos">
                        </th>
                        <th>Folio</th>
                        <th>Fecha Solicitud</th>
                        <th>Ciclo Escolar</th>
                        <th>Número de Cuenta</th>
                        <th>Nombre Completo</th>
                        <th>Estatus Solicitud</th>
                        <th>Dato Adicional</th>
                        <th>Tipo de Firma</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                    <tbody>
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                Seleccione los filtros y presione <strong>Buscar</strong> para ver resultados
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal fuera de la card -->
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
                            <option value="En firma">En firma</option>
                            <option value="Terminado">Emitido</option>
                            <option value="Finalizado">Entregado</option>
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
        <!-- Modal para capturar Datos Adicionales al Elaborar -->
    <div class="modal fade" id="modalDatosAdicionales" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Datos Adicionales Requeridos</h5>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        La solicitud <strong id="daNumeroCuenta"></strong> requiere datos adicionales. 
                        Por favor ingréselos para continuar con la elaboración.
                    </p>
                    <div id="mensajeModalDA" class="alert alert-warning d-none py-2" role="alert"></div>
                    
                    <input type="hidden" id="daSolicitudId">
                    <input type="hidden" id="daAccionMasiva">
                    <input type="hidden" id="daEstatusMasivo">

                    <div id="listaCamposAdicionales" class="mb-3">
                        <!-- Aquí se inyectarán los campos dinámicamente si quieres más de uno -->
                        <div class="row g-2 mb-2 campo-adicional">
                            <div class="col-md-5">
                                <input type="text" class="form-control da-nombre" placeholder="Ej. Promedio">
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control da-valor" placeholder="Ej. 9.5">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btnAgregarCampoDA">
                        <i class="bi bi-plus-lg"></i> Agregar otro campo
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarDA">Guardar y Elaborar</button>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="../controllers/ajxGenerarSolicitud.js"></script>

<?php
include './footer_constancias.php'; ?>
