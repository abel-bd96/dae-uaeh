<?php
include './header_constancias.php';
include './sidebar_constancias.php';
?>
<main class="container-fluid container-md py-3 py-md-4" id="solicitudNueva">
    <div class="container">
        <div class="row align-items-start g-3">
            <div class="col-12 col-md-auto text-center text-md-start">
                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="var(--primary-color)" class="bi bi-plus-square-fill" viewBox="0 0 16 16" aria-hidden="true">
                    <path
                        d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm6.5 4.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3a.5.5 0 0 1 1 0" />
                </svg>
            </div>

            <div class="col-12 col-md">
                <h1 class="h3 mb-1">
                    Nueva Solicitud de Constancia
                </h1>
                <h2 class="text-uppercase text-muted small mb-0 text-break">
                    Programa Educativo: Licenciatura en Ciencias Computacionales
                </h2>
            </div>

            <hr>

            <div>
                <form id="formNuevaConstancia">
                    <div class="mb-3">
                        <div class="row">
                            <label class="form-label fw-bold">Elige el tipo de firma:</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class=" form-check-input" type="radio" name="tipoFirma" id="firmaDigital"
                                value="digital" required>
                            <label class="form-check-label" for="firmaDigital">
                                <i class="bi bi-card-text"></i> Digital
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipoFirma" id="firmaAutografa"
                                value="autografa">
                            <label class="form-check-label" for="firmaAutografa">
                                <i class="bi bi-pencil-square"></i> Autógrafa
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="row">
                            <label class="form-label fw-bold">¿Requieres que contenga las calificaciones que has obtenido a lo largo de tu trayectoria académica?</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="calificaciones" id="calificacionesSi"
                                value="sí" required>
                            <label class="form-check-label" for="calificacionesSi">
                                Sí
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="calificaciones" id="calificacionesNo"
                                value="no">
                            <label class="form-check-label" for="calificacionesNo">
                                No
                            </label>
                        </div>
                    </div>

                    <div id="contenedorTablaDatos" class="mb-3 d-none">
                        <label class="form-label fw-bold">La constancia contendrá los siguientes datos:</label>
                        <table id="tablaCorroborarDatos" class="table table-striped-columns table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Campo</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row" class="mark">Nombre</th>
                                    <td id="nombreAlumno"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Primer Apellido</th>
                                    <td id="apellidoPaterno"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Segundo Apellido</th>
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
                                    <th scope="row" class="mark">Número de cuenta</th>
                                    <td id="numeroCuenta"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Semestre</th>
                                    <td id="semestre"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Calidad del Alumno</th>
                                    <td id="calidadAlumno"> </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Promedio</th>
                                    <td id="promedio"> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-danger" onclick="alert('Si la información mostrada es incorrecta o requiere algún dato adicional, comuníquese al correo validacionesdae@uaeh.edu.mx o al teléfono 771 71 72 000, ext. 48514, para solicitar su constancia')"> Mi información es incorrecta </button>
                        <button id="EnvioNuevaConstancia" type="submit" class="btn btn-primary"> Solicitar Constancia</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../controllers/ajxMensaje&Datos.js" window.endpointSolicitudes="../modAlumnoSolicitudes.php"></script>
<script src=" ../controllers/ajxSolicitudNueva.js"></script>
<?php include './footer_constancias.php'; ?>