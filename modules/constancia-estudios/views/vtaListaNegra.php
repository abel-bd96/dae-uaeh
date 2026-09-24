<main class="container py-4 contenido-ListaNegra" id="constanciaListaNegra">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <p class="text-uppercase text-muted small mb-1">Casos Extraordinarios</p>
            <h1 class="h3 mb-0">Registro y configuración de Casos extraordinarios</h1>
        </div>
        
            
        <div class="d-flex gap-2" >
            <div>
                <input type="search" class="form-control" id="input-search" placeholder="Buscar no. cuenta">
            </div>    

            <button type="button" class="btn btn-primary" id="btnNuevoListaNegra">
                <i class="bi bi-plus-lg"></i> Nuevo caso extraordinario
            </button>
        </div>
</div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaListaNegra">
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
<div class="modal fade" id="modalListaNegra" tabindex="-1" aria-labelledby="tituloModalListaNegra" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title h5" id="tituloModalListaNegra">Nuevo caso especial</h2>
                    <p class="small text-muted mb-0" id="subtituloModalListaNegra">Paso 1 de 1</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formListaNegra" novalidate>
                <div class="modal-body">
                    <div id="mensajeListaNegra" class="alert d-none" role="alert"></div>
                    <input type="hidden" name="id" id="listaNegraId">
                    <input type="hidden" name="tipo" id="listaNegraTipo">

                    <!-- No. de cuenta -->
                    <section>
                        <h3 class="h6">Escribe el numero de cuenta</h3>
                        <label for="NumeroCuenta" class="form-label">Buscar No. de cuenta</label>
                        <input type="search" class="form-control mb-3" id="NumeroCuenta" placeholder="Ej. 250122">
                        <div id="resultadosNumeroCuenta" class="list-group"></div>
                        <input type="hidden" name="nombre" id="ListaNegraNombre">
                        <div class="invalid-feedback d-block" id="errorListaNegra"></div>
                    </section>

                    <!-- Estatus -->
                    <section>
                        <h3 class="h6">Seleciona el estatus</h3>
                        <div class="mb-3">
                            <label for="Estatus" class="form-label">Estatus</label>
                            <select id="Estatus" class="form-select" name ="Estatus">
                                <option value="S">Bloqueado</option>    
                                <option value="N">Desbloqueado</option>    
                            </select>
                            <div id="resultadosEstatus" class="list-group mt-1"></div>
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