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
                <tr class="ciclos-table__filters">
                    <th scope="col"><label class="visually-hidden" for="filtroCiclo">Filtrar ciclo</label><input type="search" class="form-control form-control-sm" id="filtroCiclo" placeholder="Buscar ciclo..." autocomplete="off"></th>
                    <th scope="col"><label class="visually-hidden" for="filtroTipo">Filtrar tipo</label><select class="form-select form-select-sm" id="filtroTipo">
                            <option value="">Todos</option>
                            <option value="GENERAL">GENERAL</option>
                            <option value="ESPECIFICO">ESPECIFICO</option>
                        </select></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"><label class="visually-hidden" for="filtroEstado">Filtrar estado</label><select class="form-select form-select-sm" id="filtroEstado">
                            <option value="">Todos</option>
                            <option value="ACTIVO">ACTIVO</option>
                            <option value="INACTIVO">INACTIVO</option>
                        </select></th>
                    <th scope="col" class="text-end"><button type="button" class="btn btn-primary-uaeh btn-sm" id="btnBuscarCiclos"><i class="bi bi-search"></i> Buscar</button></th>
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