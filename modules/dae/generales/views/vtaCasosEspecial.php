<!-- <?php
include './header_constancias.php';
include './sidebar_constancias.php';
?> -->
<main class="container py-4" id="constanciaCasoEspecial">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <p class="text-uppercase text-muted small mb-1">Casos Extraordinarios</p>
            <h1 class="h3 mb-0">Registro y configuración de Casos extraordinarios</h1>
        </div>
        
            
        <div class="d-flex gap-2" >
            <div>
                <input type="search" class="form-control" id="input-search" placeholder="Buscar no. cuenta">
            </div>    

            <button type="button" class="btn btn-primary" id="btnNuevoCasoEspecial">
                <i class="bi bi-plus-lg"></i> Nuevo caso extraordinario
            </button>
        </div>
</div>

    <div class="card border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaCasoEspecial">
                    <thead class="table-light">
                        <tr>
                            <th>No. Cuenta</th>
                            <th>Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2" class="text-center text-muted py-4">
                                Cargando configuraciones...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="modalCasoEspecial" tabindex="-1" aria-labelledby="tituloModalCasoEspecial" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title h5" id="tituloModalCasoEspecial">Nuevo caso especial</h2>
                    <p class="small text-muted mb-0" id="subtituloModalCasoEspecial">Paso 1 de 1</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formCasoEspecial" novalidate>
                <div class="modal-body">
                    <div id="mensajeCasoEspecial" class="alert d-none" role="alert"></div>
                    <input type="hidden" name="id" id="CasoEspecialId">
                    <input type="hidden" name="tipo" id="CasoEspecialTipo">

                    <section>
                        <h3 class="h6">Escribe el número de cuenta</h3>
                        <!--
                        <label for="NumeroCuenta" class="form-label">Buscar No. de cuenta</label>
                        -->
                        <input type="search" class="form-control mb-3" id="NumeroCuenta" placeholder="Ej. 250122">
                        <div id="resultadosNumeroCuenta" class="list-group"></div>
                        <input type="hidden" name="nombre" id="CasoEspecialNombre">
                        <div class="invalid-feedback d-block" id="errorCasoEspecial"></div>
                    </section>

                    <!-- Estatus -->
                    <section>
                        <div>
                            <input type="form-check-input" type="checkbox" role="switch" id="Estatus" name="Estatus" value="S">
                            <label class="form-check-label" for="Estatus" id="labelEstatus"> Desbloqueado</label>
                        </div>
                    </section>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success d-none" id="btnGuardar">Guardar</button>
                    <button type="button" class="btn btn-secondary" id="btnCancelar" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="../controllers/ajxCasoEspecial.js"></script>
<?php include './footer_constancias.php'; ?>
