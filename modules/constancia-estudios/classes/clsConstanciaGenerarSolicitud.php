<?php

class clsConstanciaGenerarSolicitud
{
    private $rutaNumeroCuenta;
    private $rutaNombreAlumno;
    private $rutaEstatusSolicitud;
    private $rutaDatoAdicional;
    private $rutaObservacion;
    private $rutaConfiguraciones;

    private $listaEstatusValidos = array(
        'Solicitado',
        'En elaboración',
        'En firma',
        'Terminado',
        'Cancelado'
    );

    public function __construct(){
        $directorioDatos = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;

        $this->rutaNumeroCuenta = $directorioDatos . 'vta_siae_numero_cuenta.json';
        $this->rutaNombreAlumno = $directorioDatos . 'vta_siae_nombre_alumno.json';
        $this->rutaDatoAdicional = $directorioDatos . 'ae_dato_adicional.json';
        $this->rutaEstatusSolicitud = $directorioDatos . 'ae_estatus_solicitud.json';
        $this->rutaObservacion = $directorioDatos . 'ae_observacion.json';
        $this->rutaConfiguraciones = $directorioDatos . 'constancia_generar_solicitud.json';
    }

    //validación de los estatus
    public function estatusValidados(){
        return $this->listaEstatusValidos;
    }

    //cambios de estatus
    public static function transicionesPermitidas(){
        return array(
            'Solicitado'  => array('En proceso', 'Elaborado', 'Cancelado'),
            'En proceso'  => array('Elaborado', 'En firma', 'Cancelado'),
            'Elaborado'   => array('En firma', 'Terminado', 'Cancelado'),
            'En firma'    => array('Firmado', 'Terminado', 'Cancelado'),
            'Firmado'     => array('Terminado'),
            'Terminado'   => array(),
            'Cancelado'   => array(),
        );
    }
    //Consultar numero de cuenta
    public function consultarNumeroCuenta($texto = '') {
        $cuentas = $this ->leer($this ->rutaNumeroCuenta);
        $alumnos = $this ->leer($this ->rutaNombreAlumno);

        $texto = $this->normalizar($texto);
        $resultado = array();

        foreach ($cuentas as $cuenta) {
            $numeroCuenta = isset($cuenta['NumeroCuenta'])? $this->normalizar($cuenta['NumeroCuenta']) : '';
            
            if ($texto !== '' && strpos($numeroCuenta, $texto) === false) {
                continue;
            }
            //buscar por numero cuenta
            foreach ($alumnos as $alumno) {
                $numeroAlumno = isset($alumno['NumeroCuenta']) ? $this->normalizar($alumno['NumeroCuenta']) : '';

                if ($numeroAlumno === $numeroCuenta) {
                    //fusionar campos del alumno
                    foreach ($alumno as $campo => $valor) {
                        $cuenta[$campo] = $valor;
                    }
                if(empty($cuenta['NombreCompleto'])){
                    $cuenta['NombreCompleto'] = trim(
                        (isset($cuenta['Nombre']) ? $cuenta['Nombre'] : '') . ' ' .
                        (isset($cuenta['ApellidoPaterno']) ? $cuenta['ApellidoPaterno'] : '') . ' ' .
                        (isset($cuenta['ApellidoMaterno']) ? $cuenta['ApellidoMaterno'] : '')
                    );
                }
                    break;
                }
            }
            //si no tenia nombre completo 
            if(empty($cuenta['NombreCompleto'])){
                $cuenta['NombreCompleto'] = isset($cuenta['nombre']) ? $cuenta['nombre'] : '';
            }
            $resultado[] = $cuenta;
        }
        return $resultado;
    }

    public function consultarAlumno($numeroCuenta) {
        $numeroCuenta = $this->normalizar($numeroCuenta);
        if ($numeroCuenta === '') { return null;
        }

        $cuentas = $this->leer($this->rutaNumeroCuenta);
        $nombres = $this->leer($this->rutaNombreAlumno);
        $alumno = array();

        foreach ($cuentas as $registro) {
            $cuenta = isset($registro['NumeroCuenta']) ? $this->normalizar($registro['NumeroCuenta']) : '';
            if ($cuenta === $numeroCuenta) {
                $alumno = $registro;
                break;
            }
        }
        foreach ($nombres as $registro) {
            $cuenta = isset($registro['NumeroCuenta']) ? $this->normalizar($registro['NumeroCuenta']) : '';
            if ($cuenta === $numeroCuenta) {
                foreach ($registro as $campo => $valor) {
                    $alumno[$campo] = $valor;
                }
                break;
            }
        }
        if (empty($alumno)) {return null;
        }

        return $alumno;
    }
    
    //consultar catalogos 
    public function consultar($id){
        if ($id === null || trim((string)$id) === '') return null;
            $registros = $this->leer($this->rutaConfiguraciones);
        foreach ($registros as $registro) {
            if (isset($registro['id']) && (string)$registro['id'] === (string)$id) {
                return $registro;
            }
        }

        return null;
    }

    public function consultarDatoAdicional(){
        return $this->leer($this->rutaDatoAdicional);
    }

    public function consultarEstatusSolicitud(){
        return $this->leer($this->rutaEstatusSolicitud);
    }
    public function consultarObservacion()
    {
        return $this->leer($this->rutaObservacion);
    }

    public function listar(){
        return $this->leer($this->rutaConfiguraciones);
    }

    
    public function guardar($datos){
        $registros = $this->leer($this->rutaConfiguraciones);
        if (!isset($datos['NumeroCuenta']) || trim($datos['NumeroCuenta']) === '') {
            return array('ok' => false,'mensaje' => 'El número de cuenta es obligatorio.');
        }

        foreach ($registros as $registro) {
            if (
                isset($registro['NumeroCuenta']) &&
                $this->normalizar($registro['NumeroCuenta']) ===
                $this->normalizar($datos['NumeroCuenta'])
            ) {
                return array(
                    'ok' => false,
                    'mensaje' => 'El número de cuenta ya tiene una solicitud registrada.'
                );
            }
        }

        /*
         * Generamos un ID para el nuevo registro.
         */
        $ultimoId = 0;

        foreach ($registros as $registro) {
            if (isset($registro['id']) && $registro['id'] > $ultimoId) {
                $ultimoId = $registro['id'];
            }
        }

        $datos['id'] = $ultimoId + 1;
        if (!isset($datos['fechaRegistro'])) $datos['fechaRegistro'] = date('Y-m-d');
        if (!isset($datos['Estatus'])) $datos['Estatus'] = 'Solicitado';
    
        $registros[] = $datos;

        if (!$this->guardarJson($this->rutaConfiguraciones, $registros)) {
            return array(
                'ok' => false,
                'mensaje' => 'No fue posible guardar la solicitud.');
        }

        return array(
            'ok' => true,
            'mensaje' => 'La solicitud se guardó correctamente.',
            'datos' => $datos
        );
    }

    // Actualiza una solicitud existente.
    public function actualizar($datos) {
        if (!isset($datos['id'])) {
            return array(
                'ok' => false,
                'mensaje' => 'No se recibió el identificador de la solicitud.'
            );
        }

        $registros = $this->leer($this->rutaConfiguraciones);

        foreach ($registros as $indice => $registro) {
            if (isset($registro['id']) && (string)$registro['id'] === (string)$datos['id']) {
                if(isset($registro['Estatus']) && $registro['Estatus'] === 'Cancelado'){
                    return array(
                        'ok' => false, 'mensaje' => 'No se puede editar una solicitud cancelada.'
                    );
                }
                foreach ($datos as $campo => $valor) {
                    $registros[$indice][$campo] = $valor;
                }

                $registros[$indice]['fechaActualizacion'] = date('Y-m-d');

                if (!$this->guardarJson($this->rutaConfiguraciones, $registros)) {
                    return array(
                        'ok' => false,
                        'mensaje' => 'No fue posible actualizar la solicitud.'
                    );
                }

                return array(
                    'ok' => true,
                    'mensaje' => 'La solicitud se actualizó correctamente.',
                    'datos' => $registros[$indice]
                );
            }
        }

        return array(
            'ok' => false,
            'mensaje' => 'No se encontró la solicitud.');
    }
    public function cancelar($id){
                if ($id === null || trim((string)$id) === '') {
            return array('ok' => false, 'mensaje' => 'No se recibió el identificador de la solicitud.');
        }

        $registros = $this->leer($this->rutaConfiguraciones);

        foreach ($registros as $indice => $registro) {
            if (isset($registro['id']) && (string)$registro['id'] === (string)$id) {

                if (isset($registro['Estatus']) && $registro['Estatus'] === 'Cancelado') {
                    return array('ok' => false, 'mensaje' => 'La solicitud ya está cancelada.');
                }

                $registros[$indice]['Estatus']          = 'Cancelado';
                $registros[$indice]['fechaCancelacion'] = date('Y-m-d');

                if (!$this->guardarJson($this->rutaConfiguraciones, $registros)) {
                    return array('ok' => false, 'mensaje' => 'No fue posible cancelar la solicitud.');
                }

                return array(
                    'ok' => true,
                    'mensaje' => 'La solicitud se canceló correctamente.',
                    'datos' => $registros[$indice]
                );
            }
        }

        return array('ok' => false, 'mensaje' => 'No se encontró la solicitud.');

        }

    public function elaborar($id){
        return $this->cambiarEstatusInterno(
            $id,
            'En elaboración',
            'La solicitud se marcó en elaboración correctamente.',
            array('Solicitado')
        );
    }
    public function mandarFirma($id){
        return $this->cambiarEstatusInterno(
            $id,
            'En firma',
            'La solicitud se mandó a firma correctamente.',
            array('En elaboración')
        );
    }

// Cambiar estado manualmente
    public function cambiarEstatus($id, $nuevoEstatus){
        if (!in_array($nuevoEstatus, $this->listaEstatusValidos, true)) {
            return array('ok' => false, 'mensaje' => 'El estatus seleccionado no es válido.');
        }
        return $this->cambiarEstatusInterno(
            $id,
            $nuevoEstatus,
            'El estatus de la solicitud se actualizó correctamente.',
            null
        );
    }

    private function cambiarEstatusInterno($id, $nuevoEstatus, $mensajeOk, $estatusPermitidos = null){
        if ($id === null || trim((string)$id) === '') {
            return array('ok' => false, 'mensaje' => 'No se recibió el identificador de la solicitud.');
        }

        $registros = $this->leer($this->rutaConfiguraciones);

        foreach ($registros as $indice => $registro) {
            if (isset($registro['id']) && (string)$registro['id'] === (string)$id) {

                $estatusActual = isset($registro['Estatus']) ? $registro['Estatus'] : '';

                if ($estatusActual === 'Cancelado') {
                    return array('ok' => false, 'mensaje' => 'No se puede modificar una solicitud cancelada.');
                }

                if (is_array($estatusPermitidos) && !in_array($estatusActual, $estatusPermitidos, true)) {
                    return array(
                        'ok' => false,
                        'mensaje' => 'La solicitud no se encuentra en un estatus válido para esta acción. Estatus actual: ' . $estatusActual
                    );
                }

                if ($estatusActual === $nuevoEstatus) {
                    return array('ok' => false, 'mensaje' => 'La solicitud ya se encuentra en ese estatus.');
                }

                $registros[$indice]['Estatus']            = $nuevoEstatus;
                $registros[$indice]['fechaActualizacion'] = date('Y-m-d');

                if (!$this->guardarJson($this->rutaConfiguraciones, $registros)) {
                    return array('ok' => false, 'mensaje' => 'No fue posible actualizar el estatus de la solicitud.');
                }

                return array(
                    'ok' => true,
                    'mensaje' => $mensajeOk,
                    'datos' => $registros[$indice]
                );
            }
        }

        return array('ok' => false, 'mensaje' => 'No se encontró la solicitud.');
    }

    public function buscarSolicitud($numeroCuenta){
        $registros = $this->leer($this->rutaConfiguraciones);
        $numeroCuenta = $this->normalizar($numeroCuenta);
        foreach ($registros as $registro) {
            if (
                isset($registro['NumeroCuenta']) &&
                $this->normalizar($registro['NumeroCuenta']) === $numeroCuenta
            ) {
                return $registro;
            }
        }
        return null;
    }


    // Lee un archivo JSON.
    private function leer($ruta)
    {
        if (!file_exists($ruta)) {
            error_log("JSON NO EXISTE: " . $ruta);
            return array();
        }

        $contenido = file_get_contents($ruta);
        if ($contenido === false) {
            error_log("NO SE PUDO LEER JSON: " . $ruta);
            return array();
        }

        if (trim($contenido) === '') {
            error_log("JSON VACÍO: " . $ruta);
            return array();
        }

        $datos = json_decode($contenido, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log(
                "ERROR JSON: " .
                $ruta .
                " | " .
                json_last_error_msg()
            );

            return array();
        }

        if (!is_array($datos)) {
            error_log("JSON NO ES UN ARRAY: " . $ruta);
            return array();
        }
        return $datos;
    }

    //Guarda información en formato JSON.
    private function guardarJson($ruta, $datos)
    {
        $json = json_encode(
            $datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );

        if ($json === false) {
            return false;
        }

        return file_put_contents($ruta, $json) !== false;
    }

    // Normaliza un texto para realizar búsquedas.
    private function normalizar($texto) {
        $texto = trim((string)$texto);

        if ($texto === '') {
            return '';
        }

        return mb_strtolower($texto, 'UTF-8');
    }
}
