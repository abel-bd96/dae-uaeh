<?php

class clsAlumnoSolicitudes
{
    private $rutaHistorial;
    private $rutaDatosConstancia;
    //private $rutaBloqueo;

    public function __construct()
    {
        $directorioDatos = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;

        //$directorioDatosListaNegra = pendiente

        $this->rutaHistorial = $directorioDatos . 'ae_Solicitud_Referencia.json';
        $this->rutaDatosConstancia = $directorioDatos . 'ae_Constancia_Contenido.json';
        //$this->rutaBloqueo = $directorioDatos . 'casos_especiales.json'; ???
    }

    public function listar()
    {
        return $this->leer($this->rutaHistorial);
    }


    public function mostrarDatosPersonales()
    {
        $datos = $this->leer($this->rutaDatosConstancia);

        if (!is_array($datos)) {
            throw new RuntimeException(
                'Los datos de la constancia no tienen un formato válido.'
            );
        }

        if (isset($datos['idConstancia'])) {
            $datos = [$datos];
        }

        foreach ($datos as $registro) {
            if (!is_array($registro)) {
                continue;
            }

            if (
                isset($registro['Cuenta']) &&
                trim((string) $registro['Cuenta']) !== ''
            ) {
                return [
                    'nombre' => $registro['nombre'] ?? '',
                    'apellidoPaterno' => $registro['apellidoPaterno'] ?? '',
                    'apellidoMaterno' => $registro['apellidoMaterno'] ?? '',
                    'CURP' => $registro['CURP'] ?? '',
                    'unidadAcademica' => $registro['unidadAcademica'] ?? '',
                    'programaEducativo' => $registro['programaEducativo'] ?? '',
                    'numeroCuenta' => $registro['numeroCuenta'] ?? '',
                    'semestre' => $registro['semestre'] ?? '',
                    'calidadAlumno' => $registro['calidadAlumno'] ?? '',
                    'promedioGeneral' => $registro['promedioGeneral'] ?? ''
                ];
            }
        }

        throw new RuntimeException(
            'No fue posible obtener los datos personales del alumno.'
        );
    }

    public function consultarPorId($idSolicitud)
    {
        $idSolicitud = trim((string) $idSolicitud);
        if ($idSolicitud === '') {
            throw new InvalidArgumentException('La referencia a la Solicitud es obligatoria.');
        }

        //Consulta de datos referentes a la solicitud
        $solicitudes = $this->leer($this->rutaHistorial);

        if (isset($solicitudes['idSolicitud'])) {
            $solicitudes = [$solicitudes];
        }

        $solicitudEncontrada = null;

        foreach ($solicitudes as $solicitud) {
            if (!is_array($solicitud)) {
                continue;
            }

            if (
                isset($solicitud['idSolicitud']) && (string) $solicitud['idSolicitud'] === $idSolicitud
            ) {
                $solicitudEncontrada = $solicitud;
                break;
            }
        }

        if ($solicitudEncontrada === null) {
            throw new RuntimeException(
                'No se encontró la solicitud indicada.'
            );
        }

        //Consulta del contenido de la Constancia
        $constancias = $this->leer($this->rutaDatosConstancia);

        if (isset($constancias['idConstancia'])) {
            $constancias = [$constancias];
        }

        $constanciaEncontrada = null;

        foreach ($constancias as $constancia) {
            if (!is_array($constancia)) {
                continue;
            }

            //Se revisa y supone que idSolicitud y idConstancia sean iguales, obviando momentáneamente el desfase por Solicitudes no pagadas 
            if (isset($constancia['idConstancia']) && (string) $constancia['idConstancia'] === $idSolicitud) {
                $constanciaEncontrada = $constancia;
                break;
            }
        }
        if ($constanciaEncontrada === null) {
            throw new RuntimeException(
                'No se encontraron los datos académicos de la constancia.'
            );
        }

        //Vincular los datos de la solicitud a la constancia, para poder corregir el progreso en la barra de progreso
        $constanciaEncontrada['estatus'] = $solicitudEncontrada['estatus'] ?? '';
        return $constanciaEncontrada;
    }


    public function guardar($tipoFirma, $calificaciones) //pendiente
    {
        $tipoFirma = trim((string) $tipoFirma);
        $calificaciones = trim((string) $calificaciones);

        if ($tipoFirma === '') {
            throw new InvalidArgumentException('El tipo de firma es obligatorio.');
        }

        if ($calificaciones === '') {
            throw new InvalidArgumentException('La selección de calificaciones es obligatoria.');
        }

        $tipoFirmaNormalizado = $this->normalizar($tipoFirma);
        $calificacionesNormalizadas = $this->normalizar($calificaciones);

        if (!in_array($tipoFirmaNormalizado, ['digital', 'autografa'], true)) {
            throw new InvalidArgumentException('El tipo de firma seleccionado no es válido.');
        }

        if ($calificacionesNormalizadas === 'sí') {
            $calificacionesNormalizadas = 'sí';
        }

        if (!in_array($calificacionesNormalizadas, ['si', 'no'], true)) {
            throw new InvalidArgumentException('La opción de calificaciones seleccionada no es válida.');
        }

        try {
            $historial = $this->leer($this->rutaHistorial);
            $datosConstancia = $this->leer($this->rutaDatosConstancia);

            if (!is_array($historial)) {
                $historial = [];
            }

            if (!is_array($datosConstancia)) {
                $datosConstancia = [];
            }

            /*
            $folio = $this->generarSiguienteFolio(
                $historial,
                $datosConstancia
            );
            */


            $fechaRegistro = date('d/m/Y');

            $descripcion = 'Julio-Diciembre ' . date("Y") . '. ' . $tipoFirma;

            if ($calificacionesNormalizadas === 'no') {
                // stdClass() genera JSONs con {} en vez de []
                $calificacionesJson = new stdClass();
            } else {

                $calificacionesJson = $this->obtenerCalificacionesPredefinidas();
            }

            $nuevoHistorial = [
                //'Id' => $idSolicitud,
                'FechaRegistro' => $fechaRegistro,
                'Descripción' => $descripcion,
                'Estado' => 'Solicitada'
            ];

            $nuevaConstancia = $this->obtenerDatosAlumnoBase($datosConstancia);
            //$nuevaConstancia['Folio'] = $folio;
            $nuevaConstancia['FechaEmision'] = 'En proceso...';
            $nuevaConstancia['Estado'] = 'Solicitada';
            $nuevaConstancia['Calificaciones'] = $calificacionesJson;

            // Se necesitan copias para restaurar los datos si falla alguna edición.
            $historialAnterior = $historial;
            $datosConstanciaAnterior = $datosConstancia;

            try {
                // Se intenta guardar los cambios
                $historial[] = $nuevoHistorial;
                $datosConstancia[] = $nuevaConstancia;

                $this->escribir(
                    $this->rutaHistorial,
                    $historial
                );

                $this->escribir(
                    $this->rutaDatosConstancia,
                    $datosConstancia
                );
            } catch (Exception $excepcion) {

                try {
                    $this->escribir(
                        $this->rutaHistorial,
                        $historialAnterior
                    );

                    $this->escribir(
                        $this->rutaDatosConstancia,
                        $datosConstanciaAnterior
                    );
                } catch (Exception $rollbackExcepcion) {
                    /*
         * No sustituir el mensaje de error original
         * por el error del rollback.
         */
                }

                throw new RuntimeException(
                    'No fue posible guardar la nueva solicitud: ' .
                        $excepcion->getMessage()
                );
            }

            return [
                //'Folio' => $folio,
                'FechaRegistro' => $fechaRegistro,
                'Estado' => 'Pendiente de Pago',
                //'mensaje' => 'La solicitud ' . $folio . ' fue registrada correctamente.'
            ];
        } catch (Exception $excepcion) {
        }
    }

    public function cancelar($identificador)
    {
        $identificador = trim((string) $identificador);

        if ($identificador === '') {
            throw new InvalidArgumentException('La referencia a la solicitud es obligatoria.');
        }

        try {
            $registros = $this->leer($this->rutaHistorial);

            if (!is_array($registros)) {
                throw new RuntimeException('El historial de solicitudes no tiene un formato válido.');
            }

            //Inicialización de variables usadas para el manejo de solicitud a cancelar
            $encontrado = false;
            $historialActualizado = [];

            foreach ($registros as $registro) {
                if (
                    is_array($registro) &&
                    isset($registro['idSolicitud']) &&
                    (string) $registro['idSolicitud'] === $identificador
                ) {
                    $encontrado = true;

                    if (!isset($registro['estatus']) || $registro['estatus'] !== 'Solicitada') {
                        throw new RuntimeException(
                            'La solicitud no puede cancelarse porque su estado actual es: ' .
                                ($registro['estatus'] ?? 'Desconocido') . '.'
                        );
                    }

                    $registro['estatus'] = 'Cancelada';
                }
                $historialActualizado[] = $registro;
            }

            if (!$encontrado) {
                throw new RuntimeException('No se encontró la solicitud referenciada.');
            }

            try {
                $this->escribir($this->rutaHistorial, array_values($historialActualizado));
            } catch (Exception $excepcion) {
                try {
                    $this->escribir($this->rutaHistorial, $registros);
                } catch (Exception $rollbackExcepcion) {
                    // Conservar el error original.
                }

                throw new RuntimeException(
                    'No fue posible cancelar la solicitud: ' . $excepcion->getMessage()
                );
            }

            return [
                'mensaje' => 'La solicitud fue cancelada correctamente.'
            ];
        } catch (Exception $excepcion) {
        }
    }

    /* pendiente
    private function generarSiguienteFolio(
        $registrosHistorial,
        $registrosConstancia
    ) {
        $numeroMayor = 0;


        foreach (
            $registrosHistorial
            as $registro
        ) {

            if (
                !is_array($registro) || !isset($registro['Folio'])
            ) {
                continue;
            }

            $folio = trim(
                (string) $registro['Folio']
            );

            if (
                preg_match(
                    '/^(\d+)\/2026D$/',
                    $folio,
                    $coincidencias
                )
            ) {

                $numero =
                    (int) $coincidencias[1];

                if ($numero > $numeroMayor) {
                    $numeroMayor = $numero;
                }
            }
        }


        foreach (
            $registrosConstancia
            as $registro
        ) {

            if (
                !is_array($registro) || !isset($registro['Folio'])
            ) {
                continue;
            }

            $folio = trim(
                (string) $registro['Folio']
            );

            if (
                preg_match(
                    '/^(\d+)\/2026D$/',
                    $folio,
                    $coincidencias
                )
            ) {

                $numero =
                    (int) $coincidencias[1];

                if ($numero > $numeroMayor) {
                    $numeroMayor = $numero;
                }
            }
        }

        $siguienteNumero = $numeroMayor + 1;

        return str_pad(
            (string) $siguienteNumero,
            2,
            '0',
            STR_PAD_LEFT
        ) . '/2026D';
    }
    */

    /**
     * Regresa las calificaciones predefinidas.
     *
     * IMPORTANTE:
     * Sustituir el contenido del arreglo por el arreglo exacto
     * de calificaciones definido para este módulo.
     */
    private function obtenerDatosAlumnoBase($datosConstancia)
    {
        foreach ($datosConstancia as $registro) {
            if (!is_array($registro)) {
                continue;
            }

            if (!isset($registro['Cuenta']) || trim((string) $registro['Cuenta']) === '') {
                continue;
            }

            unset(
                $registro['Folio'],
                $registro['FechaEmision'],
                $registro['Estado'],
                $registro['Calificaciones']
            );

            return $registro;
        }

        throw new RuntimeException(
            'No fue posible obtener los datos base del alumno para crear la solicitud.'
        );
    }

    //Corregir
    private function obtenerCalificacionesPredefinidas()
    {
        return array(
            array(
                'Ciclo' => 'Julio-Diciembre 2026',
                'Materia' => 'LOGROS Y EXPERIENCIAS. LENGUA EXTRANJERA',
                'Calificacion' => '10',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Julio-Diciembre 2026',
                'Materia' => 'ÁLGEBRA LINEAL',
                'Calificacion' => '8',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Julio-Diciembre 2026',
                'Materia' => 'SEXUALIDAD RESPONSABLE',
                'Calificacion' => '10',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Julio-Diciembre 2026',
                'Materia' => 'DISEÑO DE BASES DE DATOS',
                'Calificacion' => '8',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Julio-Diciembre 2026',
                'Materia' => 'PROGRAMACIÓN ORIENTADA A OBJETOS',
                'Calificacion' => '9',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Julio-Diciembre 2026',
                'Materia' => 'FUNDAMENTOS ELECTRÓNICOS PARA LA COMPUTACIÓN',
                'Calificacion' => '9',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Julio-Diciembre 2026',
                'Materia' => 'INGENIERÍA DE SOFTWARE',
                'Calificacion' => '9',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Enero-Junio 2025',
                'Materia' => 'GRAFICACIÓN',
                'Calificacion' => '9',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Enero-Junio 2025',
                'Materia' => 'PROGRAMACIÓN DE MICROPROCESADORES',
                'Calificacion' => '9',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Enero-Junio 2025',
                'Materia' => 'ADMINISTRACIÓN DE BASES DE DATOS',
                'Calificacion' => '9',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Enero-Junio 2025',
                'Materia' => 'GESTIÓN DE PROYECTOS INFORMÁTICOS',
                'Calificacion' => '10',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Enero-Junio 2025',
                'Materia' => 'ESTADÍSTICA Y PROBABILIDAD',
                'Calificacion' => '9',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Enero-Junio 2025',
                'Materia' => 'SALUD FÍSICA Y EMOCIONAL',
                'Calificacion' => '10',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Enero-Junio 2025',
                'Materia' => 'DECISIONES PERSONALES. LENGUA EXTRANJERA',
                'Calificacion' => '10',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Julio-Diciembre 2025',
                'Materia' => 'CAUSA Y EFECTO. LENGUA EXTRANJERA',
                'Calificacion' => '9',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Julio-Diciembre 2025',
                'Materia' => 'ARTES VISUALES',
                'Calificacion' => '9',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Julio-Diciembre 2025',
                'Materia' => 'SISTEMAS MULTIMEDIA',
                'Calificacion' => '9',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Julio-Diciembre 2025',
                'Materia' => 'INTELIGENCIA ARTIFICIAL',
                'Calificacion' => '9',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Julio-Diciembre 2025',
                'Materia' => 'COMUNICACIÓN ORAL',
                'Calificacion' => '10',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Julio-Diciembre 2025',
                'Materia' => 'ORGANIZACIÓN DE COMPUTADORAS',
                'Calificacion' => '9',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Enero-Junio 2024',
                'Materia' => 'BASES DE DATOS DISTRIBUIDAS',
                'Calificacion' => '9',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Enero-Junio 2024',
                'Materia' => 'AUTÓMATAS Y COMPILADORES',
                'Calificacion' => '9',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Enero-Junio 2024',
                'Materia' => 'SISTEMAS BASADOS EN CONOCIMIENTO',
                'Calificacion' => '9',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Enero-Junio 2024',
                'Materia' => 'FUNDAMENTOS DE METODOLOGÍA DE LA INVESTIGACIÓN',
                'Calificacion' => '10',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Enero-Junio 2024',
                'Materia' => 'MÚSICA',
                'Calificacion' => '9',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Enero-Junio 2024',
                'Materia' => 'EN OTRAS PALABRAS... LENGUA EXTRANJERA',
                'Calificacion' => '9',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Julio-Diciembre 2026',
                'Materia' => 'OPTATIVA I (APRENDIZAJE COLABORATIVO ASISTIDO POR COMPUTADORA)',
                'Calificacion' => '9',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Julio-Diciembre 2026',
                'Materia' => 'ADMINISTRACIÓN DE LA FUNCIÓN INFORMÁTICA',
                'Calificacion' => '10',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Julio-Diciembre 2026',
                'Materia' => 'SISTEMAS DE REALIDAD VIRTUAL',
                'Calificacion' => '10',
                'Creditos' => '5'
            ),
            array(
                'Ciclo' => 'Julio-Diciembre 2026',
                'Materia' => 'PROGRAMACIÓN WEB',
                'Calificacion' => '10',
                'Creditos' => '5'
            )
        );
    }

    /**
     * Lee un archivo JSON.
     */
    private function leer($ruta)
    {
        if (!file_exists($ruta)) {
            return [];
        }

        $contenido = file_get_contents($ruta);

        if ($contenido === false) {
            throw new RuntimeException(
                'No fue posible leer el archivo: ' .
                    $ruta
            );
        }

        $datos = json_decode(
            $contenido,
            true
        );

        if (
            json_last_error() !== JSON_ERROR_NONE
        ) {
            throw new RuntimeException(
                'El archivo JSON no tiene un formato válido: ' .
                    $ruta .
                    '. Error: ' .
                    json_last_error_msg()
            );
        }

        return is_array($datos)
            ? $datos
            : [];
    }

    /**
     * Escribe datos en un archivo JSON.
     */
    private function escribir($ruta, $datos)
    {
        $json = json_encode(
            $datos,
            JSON_PRETTY_PRINT |
                JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES
        );

        if ($json === false) {
            throw new RuntimeException(
                'No fue posible convertir los datos a JSON: ' .
                    json_last_error_msg()
            );
        }

        $resultado = file_put_contents(
            $ruta,
            $json,
            LOCK_EX
        );

        if ($resultado === false) {
            throw new RuntimeException(
                'No fue posible escribir el archivo: ' .
                    $ruta
            );
        }
    }

    /**
     * Normaliza un texto para comparaciones.
     */
    private function normalizar($texto)
    {
        $texto = trim((string) $texto);

        if (function_exists('mb_strtolower')) {
            return mb_strtolower($texto, 'UTF-8');
        }

        return strtolower($texto);
    }
}
?>