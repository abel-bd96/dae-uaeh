<?php
include './header_constancias.php';
include './sidebar_constancias.php';
?>
<main class="container-fluid container-80 ciclos-page my-4 my-md-5" id="constanciaCiclos">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 ciclos-page__header">
        <div>
            <p class="ciclos-page__eyebrow mb-2 fs-1">Emisión de constancias</p>
            <h1 class="h3 mb-0 fs-2">Planeación de Ciclos Escolares</h1>
        </div>
        <a href="./vtaCiclosNuevo.php" class="btn btn-primary-uaeh fs-5 fw-bold" id="btnNuevoCiclo">
            <i class="bi bi-plus-lg"></i> Nuevo ciclo
        </a>
    </div>

    <div id="mensajeCiclos" class="alert d-none" role="alert"></div>
    <form id="formFiltrosCiclos" class="ciclos-filtros mb-4 pb-3" novalidate>
        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-4 col-lg-2">
                <label class="form-label fs-5" for="filtroAnio">Año del ciclo</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text fs-5" aria-hidden="true"><i class="bi bi-calendar3"></i></span>
                    <input type="text" class="form-control fs-5" id="filtroAnio" inputmode="numeric" pattern="[0-9]{4}" maxlength="4" placeholder="Ej. <?php echo date('Y'); ?>" autocomplete="off" aria-describedby="errorFiltrosCiclos">
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-2">
                <label class="form-label fs-5" for="filtroTipo">Tipo</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text fs-5" aria-hidden="true"><i class="bi bi-tags"></i></span>
                    <select class="form-select fs-5" id="filtroTipo">
                        <option value="">Todos los tipos</option>
                        <option value="GENERAL">GENERAL</option>
                        <option value="ESPECIFICO">ESPECIFICO</option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-2">
                <label class="form-label fs-5" for="filtroEstado">Estado</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text fs-5" aria-hidden="true"><i class="bi bi-toggle-on"></i></span>
                    <select class="form-select fs-5" id="filtroEstado">
                        <option value="">Todos los estados</option>
                        <option value="ACTIVO">ACTIVO</option>
                        <option value="INACTIVO">INACTIVO</option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-lg-6 ciclos-filtros__acciones">
                <button type="submit" class="btn btn-primary-uaeh btn-sm fs-5" id="btnBuscarCiclos"><i class="bi bi-search"></i> Buscar</button>
                <button type="reset" class="btn btn-outline-secondary btn-sm fs-5" id="btnLimpiarCiclos"><i class="bi bi-eraser-fill"></i> Limpiar búsqueda</button>
            </div>
            <div class="col-12 ciclos-filtros__error">
                <div id="errorFiltrosCiclos" class="small text-danger d-none" role="alert" aria-live="polite"></div>
            </div>
        </div>
    </form>
    <div class="table-responsive ciclos-table-wrap">
        <table class="table table-hover align-middle mb-0 ciclos-table fs-5" id="tablaCiclos">
            <thead>
                <tr>
                    <th scope="col"><button type="button" class="btn btn-link p-0 text-reset text-decoration-none btn-ordenar" data-ordenar="nombre" aria-label="Ordenar por ciclo">Ciclo <span class="indicador-orden" aria-hidden="true"></span></button></th>
                    <th scope="col"><button type="button" class="btn btn-link p-0 text-reset text-decoration-none btn-ordenar" data-ordenar="tipo" aria-label="Ordenar por tipo">Tipo <span class="indicador-orden" aria-hidden="true"></span></button></th>
                    <th scope="col">Programas educativos</th>
                    <th scope="col">Periodo de solicitud</th>
                    <th scope="col"><button type="button" class="btn btn-link p-0 text-reset text-decoration-none btn-ordenar" data-ordenar="estado" aria-label="Ordenar por estado">Estado <span class="indicador-orden" aria-hidden="true"></span></button></th>
                    <th scope="col" class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="modal fade" id="modalEditarCiclo" tabindex="-1" aria-labelledby="tituloModalEditarCiclo" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="formFechasCiclo" novalidate>
                    <div class="modal-header">
                        <div>
                            <h2 class="modal-title h5 mb-1" id="tituloModalEditarCiclo">Editar fechas</h2>
                            <p class="small text-muted mb-0" id="detalleModalEditarCiclo"></p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editarCicloId">
                        <input type="hidden" id="editarCicloTipo">
                        <div id="errorFechasEdicion" class="alert d-none" role="alert"></div>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label" for="editarFechaPeriodoEstudiosInicio">Inicio periodo de estudios</label><input type="date" class="form-control" name="fechaPeriodoEstudiosInicio" id="editarFechaPeriodoEstudiosInicio" required></div>
                            <div class="col-md-6"><label class="form-label" for="editarFechaPeriodoEstudiosTermino">Fin periodo de estudios</label><input type="date" class="form-control" name="fechaPeriodoEstudiosTermino" id="editarFechaPeriodoEstudiosTermino" required></div>
                            <div class="col-md-6"><label class="form-label" for="editarFechaPeriodoVacacionalInicio">Inicio periodo vacacional</label><input type="date" class="form-control" name="fechaPeriodoVacacionalInicio" id="editarFechaPeriodoVacacionalInicio" required></div>
                            <div class="col-md-6"><label class="form-label" for="editarFechaPeriodoVacacionalTermino">Fin periodo vacacional</label><input type="date" class="form-control" name="fechaPeriodoVacacionalTermino" id="editarFechaPeriodoVacacionalTermino" required></div>
                            <div class="col-md-6"><label class="form-label" for="editarFechaSolicitudConstanciaInicio">Inicio periodo de solicitud</label><input type="date" class="form-control" name="fechaSolicitudConstanciaInicio" id="editarFechaSolicitudConstanciaInicio" required></div>
                            <div class="col-md-6"><label class="form-label" for="editarFechaSolicitudConstanciaTermino">Fin periodo de solicitud</label><input type="date" class="form-control" name="fechaSolicitudConstanciaTermino" id="editarFechaSolicitudConstanciaTermino" required></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary-uaeh" id="btnGuardarFechas">Guardar fechas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script src="../controllers/ajxCiclos.js"></script>
<?php include './footer_constancias.php'; ?>