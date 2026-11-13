<?php

class clsAlumnoSolicitudes
{
    private $rutaHistorial;
    private $rutaDatosConstancia;
    private $rutaCiclo;
    private $rutaSiaeAlumno;
    private $rutaListaNegra;

    public function __construct()
    {
        $directorioDatos = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
        $directorioDatosExternos = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'dae' . DIRECTORY_SEPARATOR . 'constancia-estudios' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
        $directorioDatosGenerales = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'dae' . DIRECTORY_SEPARATOR . 'generales' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;

        $this->rutaHistorial = $directorioDatos . 'ae_Solicitud_Referencia.json';
        $this->rutaDatosConstancia = $directorioDatos . 'ae_Constancia_Contenido.json';
        $this->rutaCiclo = $directorioDatosExternos . 'ae_ciclo.json';
        $this->rutaSiaeAlumno = $directorioDatosExternos . 'vta_siae_datos_alumno.json';
        $this->rutaListaNegra = $directorioDatosGenerales . 'constancia_lista_negra.json';
    }

    public function listar()
    {
        //Se necesita verificar que haya una sesión activa, en caso de venir de vtaSolicitudNueva
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $idKardexUsuario = $_SESSION['IdKardex'] ?? null;

        //Se previene el acceso externo a las vistas
        if (!is_string($idKardexUsuario) || trim($idKardexUsuario) === '') {
            throw new RuntimeException(
                'Debes ingresar desde Servicios Escolares para visualizar tu Historial de Solicitudes o crear una Solicitud Nueva.'
            );
        }

        // Identificar cuando no se necesiten las solicitudes
        $tipoListado = $_POST['tipoListado'] ?? 'solicitudes';

        //Es revisada la vista mostrada para definir si se muestra los datos personales del alumno o el historial de solicitudes
        if ($tipoListado === 'datosAlumno') {
            $datosAlumno = $this->leer($this->rutaSiaeAlumno);
            
            if (empty($datosAlumno)) {
                return [];
            }

            if (isset($datosAlumno['IdKardex'])) {
                $datosAlumno = [$datosAlumno];
            }

            //Filtrar sólo el alumno de la sesión
            $datosAlumno = array_filter(
                $datosAlumno,
                function ($datoAlumno) use ($idKardexUsuario) {

                    return is_array($datoAlumno) && isset($datoAlumno['IdKardex'])&& (string) $datoAlumno['IdKardex'] === (string) $idKardexUsuario;
                }
            );

            // Son reorganizados los índices del arreglo, para asegurar su manejo y despliegue adecuado
            $datosAlumno = array_values($datosAlumno);

            return $datosAlumno[0] ?? [];
        }

        // Leer historial de solicitudes
        $solicitudes = $this->leer($this->rutaHistorial);

        if (empty($solicitudes)) {
            return [];
        }

        // Leer ciclos escolares
        $ciclos = $this->leer($this->rutaCiclo);

        if (empty($ciclos)) {
            throw new RuntimeException(
                'No se encontraron registros de ciclos escolares.'
            );
        }

        // Normalizar registros individuales
        if (isset($solicitudes['idSolicitud'])) {
            $solicitudes = [$solicitudes];
        }

        if (isset($ciclos['idCiclo'])) {
            $ciclos = [$ciclos];
        }

        // Se muestra solamente las solicitudes que corresponden con base en el IdKardex
        $solicitudes = array_filter(
            $solicitudes,
            function ($solicitud) use ($idKardexUsuario) {

                return is_array($solicitud) && isset($solicitud['idKardex']) && (string) $solicitud['idKardex'] === (string) $idKardexUsuario;
            }
        );

        // Son reorganizados los índices del arreglo, para asegurar su manejo y despliegue adecuado
        $solicitudes = array_values($solicitudes);

        if (empty($solicitudes)) {
            return [];
        }

        // Crear índice de ciclos escolares
        $indiceCiclos = [];

        foreach ($ciclos as $ciclo) {

            if (!is_array($ciclo) || !isset($ciclo['idCiclo'])) {
                continue;
            }

            $indiceCiclos[(string) $ciclo['idCiclo']] =
                $ciclo['nombre'] ?? '';
        }

        // Vincular nombre del ciclo a cada solicitud
        foreach ($solicitudes as $key => $solicitud) {

            if (!is_array($solicitud)) {
                continue;
            }

            $idCicloEscolar = $solicitud['idCicloEscolar'] ?? null;

            //Con base en el índice, verifica que haya un identificador para asignarle su nombre al ciclo
            $solicitudes[$key]['nombreCiclo'] = $idCicloEscolar !== null ? ($indiceCiclos[(string) $idCicloEscolar] ?? 'Ciclo no encontrado') : 'Sin ciclo asignado';
        }

        return $solicitudes;
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
        $constanciaEncontrada['tipoFirma'] = $solicitudEncontrada['tipoFirma'] ?? '';
        return $constanciaEncontrada;
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

    //AlumnoSolicitudes ↑ SolicitudNueva ↓    
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

            $fechaRegistro = date('d/m/Y');

            $descripcion = 'Julio-Diciembre ' . date("Y") . '. ' . $tipoFirma;

            if ($calificacionesNormalizadas === 'no') {
                // stdClass() genera JSONs con {} en vez de []
                $calificacionesJson = new stdClass();
            } else {
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
                'mensaje' => 'La solicitud fue registrada correctamente.'
            ];
        } catch (Exception $excepcion) {
        }
    }

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

    //Funciones privadas, utilizadas por amabs vistas
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