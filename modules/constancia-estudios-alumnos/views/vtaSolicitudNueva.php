<main class="container-fluid container-md py-3 py-md-4" id="solicitudNueva">
    <div class="container">
        <div class="row align-items-start g-3">
            <div class="col-12 col-md">
                <h1 class="h3 mb-1">
                    Nueva Solicitud de Constancia
                </h1>
                <h2 class="text-uppercase text-muted small mb-0 text-break">
                    Programa Educativo: Licenciatura en Ciencias Computacionales
                </h2>

                <hr>

                <form id="formNuevaConstancia">
                    <div class="mb-3">
                        <div class="row">
                            <label class="form-label fw-bold">Elige el tipo de firma:</label>
                        </div>
                        <div class="form-check form-check-inline"">
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
                                value="Sí" required>
                            <label class="form-check-label" for="calificacionesSi">
                                Sí
                            </label>
                        </div>
                        <div class="form-check form-check-inline"">
                            <input class=" form-check-input" type="radio" name="calificaciones" id="calificacionesNo"
                            value="No">
                            <label class="form-check-label" for="calificacionesNo">
                                No
                            </label>
                        </div>
                    </div>

                    <div id="tablaCorroborarDatos" class="mb-3 d-none">
                        <label class="form-label fw-bold">La constancia contendrá los siguientes datos:</label>
                        <table class="table table-striped-columns table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Campo</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row" class="mark">Nombre</th>
                                    <td id="nombreAlumno">Sergio</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Apellido Paterno</th>
                                    <td id="apellidoPaterno">García</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Apellido Materno</th>
                                    <td id="apellidoMaterno">León</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">CURP</th>
                                    <td id="curp">GASL031114HHGRNEF6</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Unidad Académica</th>
                                    <td id="unidadAcademica">Instituto de Ciencias Básicas e Ingeniería</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Programa Educativo</th>
                                    <td id="programaEducativo">Licenciatura en Ciencias Computacionales (2010)</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Número de cuenta</th>
                                    <td id="numeroCuenta">401129</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Semestre</th>
                                    <td id="semestre">9</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Calidad del Alumno</th>
                                    <td id="calidadAlumno">Regular</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="mark">Promedio</th>
                                    <td id="promedio">9.02</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary"
                            onclick="alert('Si la información mostrada es incorrecta o requiere algún dato adicional, comuníquese al correo validacionesdae@uaeh.edu.mx o al teléfono 771 71 72 000, ext. 48514, para solicitar su constancia')">
                            Cancelar
                        </button>
                        <button id="EnvioNuevaConstancia" type="submit" class="btn btn-primary">Aceptar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</main>