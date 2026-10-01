<?php
include './header_constancias.php';
include './sidebar_constancias.php';
?>
<main class="container ciclos-page my-4 my-md-5" id="constanciaCiclos">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 ciclos-page__header">
        <div>
            <p class="ciclos-page__eyebrow mb-2">Emisión de constancias</p>
            <h1 class="h3 mb-0">Planeación de Ciclos Escolares</h1>
        </div>
        <a href="./vtaCiclosNuevo.php" class="btn btn-primary-uaeh" id="btnNuevoCiclo">
            <i class="bi bi-plus-lg"></i> Nuevo ciclo
        </a>
    </div>

    <div id="mensajeCiclos" class="alert d-none" role="alert"></div>
    <div class="table-responsive ciclos-table-wrap">
        <table class="table table-hover align-middle mb-0 ciclos-table" id="tablaCiclos">
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
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Cargando configuraciones...</td>
                </tr>
            </tbody>
        </table>
    </div>
</main>
<script src="../controllers/ajxCiclos.js"></script>
<?php include './footer_constancias.php'; ?>