<?php
include './header_constancias.php';
include './sidebar_constancias.php';

$mesActual = (int) date('n');
$anioActual = date('Y');
$placeholderCiclo = $mesActual <= 6 ? "Ej. ENERO-JUNIO $anioActual" : "Ej. JULIO-DICIEMBRE $anioActual";

?>
<main class="container ciclos-page ciclos-form-page my-4 my-md-5" id="constanciaCicloFormulario">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 ciclos-page__header">
        <div>
            <p class="ciclos-page__eyebrow mb-2 fs-1">Emisión de constancias</p>
            <h1 class="h3 mb-0 fs-2" id="tituloModalCiclo">Nuevo ciclo</h1>
            <p class="small text-muted mb-0 mt-2 fs-5" id="subtituloModalCiclo">Paso 1 de 4</p>
        </div>
        <a href="./vtaCiclos.php" class="btn btn-outline-danger fs-5"><i class="bi bi-x-circle"></i> Cancelar</a>
    </div>

    <div id="mensajeCiclos" class="alert d-none" role="alert"></div>
    <form id="formCiclo" class="ciclos-form__panel" novalidate>
        <input type="hidden" name="id" id="cicloId">
        <input type="hidden" name="tipo" id="cicloTipo">
        <div class="progress ciclos-form__progress mb-4" aria-label="Progreso del formulario">
            <div class="progress-bar" id="barraPaso" role="progressbar" style="width: 25%" aria-valuemin="0" aria-valuemax="100" aria-valuenow="25"></div>
        </div>
        <div class="ciclos-form__cycle-context d-none" id="cicloContexto" aria-live="polite">
            <i class="bi bi-calendar2-week" aria-hidden="true"></i>
            <span class="ciclos-form__cycle-label fs-5">Ciclo en configuración</span>
            <strong id="nombreCicloContexto" class="fs-5"></strong>
        </div>
        <div class="ciclos-form__notice d-none" id="leyendaTipoCiclo" role="status" aria-live="polite">
            <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
            <span id="textoLeyendaTipoCiclo" class="fs-5"></span>
        </div>

        <section class="paso-ciclo" data-paso="1">
            <h2 class="h5 fs-4">Seleccionar ciclo de SIAE</h2>
            <label for="buscarCiclo" class="form-label fs-5">Nombre exacto del ciclo</label>
            <div class="input-group mb-3">
                <input type="search" class="form-control form-control-lg fs-5" id="buscarCiclo" placeholder="<?php echo htmlspecialchars($placeholderCiclo, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" aria-describedby="ayudaBusquedaCiclo">
                <button type="button" class="btn btn-primary-uaeh fs-5" id="btnBuscarCiclo"><i class="bi bi-search" aria-hidden="true"></i> Buscar</button>
            </div>
            <p class="form-tex fs-5" id="ayudaBusquedaCiclo">Escribe el nombre completo. La búsqueda no distingue mayúsculas y minúsculas.</p>
            <div id="resultadosCiclos" class="list-group" aria-live="polite"></div>
            <input type="hidden" name="nombre" id="cicloNombre">
            <div class="invalid-feedback d-block" id="errorCiclo"></div>
        </section>

        <section class="paso-ciclo d-none" data-paso="2">
            <h2 class="h5 fs-4">Programas educativos</h2>
            <p class="small text-muted fs-5" id="ayudaPlanes">Selecciona uno o varios programas educativos para asociarlos a esta configuración.</p>
            <div id="selectorPlanes" class="d-none">
                <label for="buscarPlan" class="form-label fs-5">Buscar por nombre</label>
                <input type="search" class="form-control mb-3 fs-5" id="buscarPlan" placeholder="Ej. Derecho" autocomplete="off">
                <div id="resultadosPlanes" class="list-group"></div>
                <div class="invalid-feedback d-block" id="errorPlanes"></div>
            </div>
        </section>

        <section class="paso-ciclo d-none" data-paso="3">
            <h2 class="h5 mb-3 fs-4">Configurar fechas</h2>
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label fs-5" for="fechaPeriodoEstudiosInicio">Inicio periodo de estudios</label><input type="date" class="form-control fecha-ciclo fs-5" name="fechaPeriodoEstudiosInicio" id="fechaPeriodoEstudiosInicio"></div>
                <div class="col-md-6"><label class="form-label fs-5" for="fechaPeriodoEstudiosTermino">Fin periodo de estudios</label><input type="date" class="form-control fecha-ciclo fs-5" name="fechaPeriodoEstudiosTermino" id="fechaPeriodoEstudiosTermino"></div>
                <div class="col-md-6"><label class="form-label fs-5" for="fechaPeriodoVacacionalInicio">Inicio periodo vacacional</label><input type="date" class="form-control fecha-ciclo fs-5" name="fechaPeriodoVacacionalInicio" id="fechaPeriodoVacacionalInicio"></div>
                <div class="col-md-6"><label class="form-label fs-5" for="fechaPeriodoVacacionalTermino">Fin periodo vacacional</label><input type="date" class="form-control fecha-ciclo fs-5" name="fechaPeriodoVacacionalTermino" id="fechaPeriodoVacacionalTermino"></div>
                <div class="col-md-6"><label class="form-label fs-5" for="fechaSolicitudConstanciaInicio">Inicio periodo de solicitud</label><input type="date" class="form-control fecha-ciclo fs-5" name="fechaSolicitudConstanciaInicio" id="fechaSolicitudConstanciaInicio"></div>
                <div class="col-md-6"><label class="form-label fs-5" for="fechaSolicitudConstanciaTermino">Fin periodo de solicitud</label><input type="date" class="form-control fecha-ciclo fs-5" name="fechaSolicitudConstanciaTermino" id="fechaSolicitudConstanciaTermino"></div>
            </div>
            <div class="invalid-feedback d-block" id="errorFechas"></div>
        </section>

        <section class="paso-ciclo d-none" data-paso="4">
            <h2 class="h5 fs-4">Estado inicial</h2>
            <p class="small text-muted fs-5">La opción predeterminada es <strong>Inactivo</strong>.</p>
            <div class="form-check"><input class="form-check-input" type="radio" name="estado" id="estadoInactivo" value="INACTIVO" checked><label class="form-check-label fs-5" for="estadoInactivo">Inactivo</label></div>
            <div class="form-check"><input class="form-check-input" type="radio" name="estado" id="estadoActivo" value="ACTIVO"><label class="form-check-label fs-5" for="estadoActivo">Activo</label></div>
            <div class="mt-4 p-3 bg-light rounded" id="resumenCiclo"></div>
        </section>

        <div class="d-flex flex-wrap justify-content-between gap-2 mt-4 ciclos-form__actions">
            <button type="button" class="btn btn-outline-secondary fs-5" id="btnAnterior">Anterior</button>
            <div class="d-flex gap-2 ms-auto">
                <button type="button" class="btn btn-primary-uaeh fs-5" id="btnSiguiente">Siguiente <i class="bi bi-arrow-right"></i></button>
                <button type="submit" class="btn btn-primary-uaeh fs-5 d-none" id="btnGuardar"><i class="bi bi-check-lg"></i> Guardar</button>
            </div>
        </div>
    </form>
</main>
<script src="../controllers/ajxCiclos.js"></script>
<?php include './footer_constancias.php'; ?>