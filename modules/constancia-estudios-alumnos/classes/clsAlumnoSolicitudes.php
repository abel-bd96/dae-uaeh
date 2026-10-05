<?php
class clsAlumnoSolicitudes
{
    private $rutaHistorial;
    private $rutaDatosConstancia;

    public function __construct()
    {
        $directorioDatos = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
        $this->rutaHistorial = $directorioDatos . 'constancia_historial_solicitudes.json';
        $this->rutaDatosConstancia = $directorioDatos . 'ae_Datos_Constancia.json';
    }

    public function listar()
    {
        return $this->leer($this->rutaHistorial);
    }

    public function consultarPorFolio($folio)
    {
        $folio = trim((string) $folio);
        if ($folio === '') {
            throw new InvalidArgumentException('El folio de la solicitud es obligatorio.');
        }

        $datos = $this->leer($this->rutaDatosConstancia);

        if (isset($datos['Folio'])) {
            $datos = [$datos];
        }

        foreach ($datos as $registro) {
            if (!is_array($registro)) {
                continue;
            }

            if (isset($registro['Folio']) && (string) $registro['Folio'] === $folio) {
                return $registro;
            }
        }
        throw new RuntimeException('No se encontró información para el folio solicitado.');
    }


    public function guardar($tipoFirma, $calificaciones)
    {
        $tipoFirma = trim((string) $tipoFirma);
        $calificaciones = trim((string) $calificaciones);

        if ($tipoFirma === '') {
            throw new InvalidArgumentException('El tipo de firma es obligatorio.');
        }

        if ($calificaciones === '') {
            throw new InvalidArgumentException('Seleccionar si se necesita la inclusión de calificaciones es obligatoria.');
        }

        $historial = $this->leer($this->rutaHistorial);
        $datosConstancia = $this->leer($this->rutaDatosConstancia);

        if (!is_array($historial)) {
            $historial = [];
        }

        if (!is_array($datosConstancia)) {
            $datosConstancia = [];
        }

        $folio = $this->generarSiguienteFolio(
            $historial,
            $datosConstancia
        );


        $fechaRegistro = '05/10/2026';

        $descripcion = 'Julio-Diciembre 2026. ' . $tipoFirma;

        if ($this->normalizar($calificaciones) === 'No') {
            // stdClass() genera JSONs con {} en vez de []
            $calificacionesJson = new stdClass();
        } else {

            $calificacionesJson = array(
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

        $nuevoHistorial = [
            'Folio' => $folio,
            'FechaRegistro' => $fechaRegistro,
            'Descripción' => $descripcion,
            'Estado' => 'Pendiente de Pago'
        ];

        $nuevaConstancia = [
            'Folio' => $folio,
            'FechaEmision' => 'En proceso...',
            'Estado' => 'Pendiente de Pago',

            'Nombre' => 'Sergio',
            'ApellidoPaterno' => 'García',
            'ApellidoMaterno' => 'León',
            'CURP' => 'GASL031114HHGRNEF6',
            'Cuenta' => '401129',
            'UnidadAcademica' => 'Instituto de Ciencias Básicas e Ingeniería',
            'ProgramaEducativo' => 'Licenciatura en Ciencias Computacionales (2010)',
            'CCTUA' => '13MSU0017T',
            'CCTUAEH' => '13USU3018V',
            'CondicionEscolar' => 'Cursando',
            'Semestre' => '9',
            'DuracionPE' => '9 semestres',
            'TipoIngreso' => 'Examen',
            'PeriodoEstudios' =>
            '03/08/2026 - 02/12/2026',
            'PeriodoVacacional' =>
            '03/12/2026 - 17/01/2026',
            'CalidadAlumno' => 'Regular',
            'Promedio' => '9.02',
            'AvancePE' => '97%',
            'Calificaciones' => $calificacionesJson
        ];

        // Se necesitan copias para restaurar los datos si falla alguna edición.
        $historialAnterior = $historial;
        $datosConstanciaAnterior = $datosConstancia;

        try {
            //Se intenta guardar los cambios
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
            'Folio' => $folio,
            'FechaRegistro' => $fechaRegistro,
            'Estado' => 'Pendiente de Pago',
            'mensaje' =>
            'La solicitud ' .
                $folio .
                ' fue registrada correctamente.'
        ];
    }

    public function cancelar($folio)
    {
        $folio = trim((string) $folio);

        if ($folio === '') {
            throw new InvalidArgumentException('El folio de la solicitud es obligatorio.');
        }


        //Lee el historial de solicitudes    
        $registros = $this->leer($this->rutaHistorial);
        $detalleConstancias = $this->leer($this->rutaDatosConstancia);

        if (!is_array($registros)) {
            throw new RuntimeException('El historial de solicitudes no tiene un formato válido.');
        }

        if (!is_array($detalleConstancias)) {
            throw new RuntimeException('Los datos de la solicitud no tienen un formato válido.');
        }

        $encontrado = false;
        $historialActualizado = [];
        $detalleActualizado = [];

        // Primero es realizado el cambio en el historial de solicitudes
        foreach ($registros as $registro) {
            if (
                is_array($registro) &&
                isset($registro['Folio']) &&
                (string) $registro['Folio'] === $folio
            ) {
                $encontrado = true;

                //Valida que la solicitud esté en un estado cancelable         
                if (!isset($registro['Estado']) || $registro['Estado'] !== 'Pendiente de Pago') {
                    throw new RuntimeException(
                        'La solicitud no puede cancelarse porque su estado actual es: ' .
                            ($registro['Estado'] ?? 'Desconocido') . '.'
                    );
                }

                $registro['Estado'] = 'Solicitud Cancelada';
            }
            $historialActualizado[] = $registro;
        }

        // Actualiza detalles
        foreach ($detalleConstancias as $detalleConstancia) {
            if (
                is_array($detalleConstancia) &&
                isset($detalleConstancia['Folio']) &&
                (string) $detalleConstancia['Folio'] === $folio
            ) {
                $detalleConstancia['Estado'] = 'Solicitud Cancelada';
            }
            $detalleActualizado[] = $detalleConstancia;
        }

        if (!$encontrado) {
            throw new RuntimeException('No se encontró la solicitud con el folio indicado.');
        }

        $this->escribir($this->rutaHistorial, array_values($historialActualizado));
        $this->escribir($this->rutaDatosConstancia, array_values($detalleActualizado));

        return [
            'Folio' => $folio,
            'mensaje' => 'La solicitud fue cancelada correctamente.'
        ];
    }

    private function generarSiguienteFolio(
        $registrosHistorial,
        $registrosConstancia
    ) {
        $numeroMayor = 0;

        /*
         * ==========================================================
         * REVISAR HISTORIAL
         * ==========================================================
         */
        foreach (
            $registrosHistorial
            as $registro
        ) {

            if (
                !is_array($registro) ||
                !isset($registro['Folio'])
            ) {
                continue;
            }

            $folio = trim(
                (string) $registro['Folio']
            );

            /*
             * Solamente considerar folios con formato:
             *
             * NN/2026D
             */
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

        /*
         * ==========================================================
         * REVISAR DATOS DE CONSTANCIA
         * ==========================================================
         */
        foreach (
            $registrosConstancia
            as $registro
        ) {

            if (
                !is_array($registro) ||
                !isset($registro['Folio'])
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

        /*
         * Incrementar el número mayor encontrado.
         */
        $siguienteNumero =
            $numeroMayor + 1;

        /*
         * Mantener dos dígitos:
         *
         * 01
         * 02
         * ...
         * 09
         * 10
         * 11
         */
        return str_pad(
            (string) $siguienteNumero,
            2,
            '0',
            STR_PAD_LEFT
        ) . '/2026D';
    }

    /**
     * Regresa las calificaciones predefinidas.
     *
     * IMPORTANTE:
     * Sustituir el contenido del arreglo por el arreglo exacto
     * de calificaciones definido para este módulo.
     */
    private function obtenerCalificacionesPredefinidas()
    {
        return [
            /*
             * Aquí va el arreglo de aproximadamente 30
             * calificaciones proporcionado para la solicitud.
             */];
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
        return mb_strtolower(
            trim((string) $texto),
            'UTF-8'
        );
    }
}
