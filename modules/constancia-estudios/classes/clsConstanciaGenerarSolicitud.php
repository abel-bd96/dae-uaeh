<?php

class clsConstanciaGenerarSolicitud
{
    private $rutaNumeroCuenta;
    private $rutaNombreAlumno;
    private $rutaDatoAdicional;
    private $rutaEstatusSolicitud;
    private $rutaObservacion;
    private $rutaConfiguraciones;

    public function __construct(){
        $directorioDatos = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;

        $this->rutaNumeroCuenta = $directorioDatos . 'vta_siae_numero_cuenta.json';
        $this->rutaNombreAlumno = $directorioDatos . 'vta_siae_nombre_alumno.json';
        $this->rutaDatoAdicional = $directorioDatos . 'ae_dato_adicional.json';
        $this->rutaEstatusSolicitud = $directorioDatos . 'ae_estatus_solicitud.json';
        $this->rutaObservacion = $directorioDatos . 'ae_observacion.json';
        $this->rutaConfiguraciones = $directorioDatos . 'constancia_generar_solicitud.json';
    }

    //Consultar numero de cuenta
    public function consultarNumeroCuenta($texto = '') {
        $cuentas = $this->leer($this->rutaNumeroCuenta);
        $alumnos = $this->leer($this->rutaNombreAlumno);

        $texto = $this->normalizar($texto);
        $resultado = array();

        foreach ($cuentas as $cuenta) {

            $numeroCuenta = isset($cuenta['NumeroCuenta'])? $this->normalizar($cuenta['NumeroCuenta']) : '';
            
            if ($texto !== '' && strpos($numeroCuenta, $texto) === false) {
                continue;
            }
                foreach ($alumnos as $alumno) {
                $numeroAlumno = isset($alumno['NumeroCuenta']) ? $this->normalizar($alumno['NumeroCuenta']) : '';

                if ($numeroAlumno === $numeroCuenta) {
                    foreach ($alumno as $campo => $valor) {
                        $cuenta[$campo] = $valor;
                    }
                    break;
                }
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

                $registros[$indice]['Estatus']         = 'Cancelado';
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

    //Busca una solicitud por número de cuenta.
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
    private function leer($ruta){
        if (!file_exists($ruta)) return array();
        $contenido = file_get_contents($ruta);
        if ($contenido === false || trim($contenido) === '') return array();
        $datos = json_decode($contenido, true);
        if (!is_array($datos)) return array();
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
