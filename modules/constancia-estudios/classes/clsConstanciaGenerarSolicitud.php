<?php

class clsConstanciaGenerarSolicitud
{
    private $rutaNumeroCuenta;
    private $rutaNombreAlumno;
    private $rutaEstatusSolicitud;
    private $rutaDatoAdicional;
    private $rutaObservacion;
    private $rutaSolicitud;
    private $rutaConstancia;
    private $rutaContenido;
    private $rutaFolio;

    private $listaEstatusSolicitud = array('Solicitado', 'Pagado', 'Cancelado');
    private $listadoEstatusConstancia = array('En elaboración', 'En firma', 'Emitida', 'Entregado', 'Finalizado', 'Cancelado');

    public function __construct(){
    $directorioDatos = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;    
    $directorioDatosExternos = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;

        $this->rutaNumeroCuenta = $directorioDatos . 'vta_siae_numero_cuenta.json';
        $this->rutaNombreAlumno = $directorioDatos . 'vta_siae_nombre_alumno.json';
        $this->rutaDatoAdicional = $directorioDatos . 'ae_dato_adicional.json';
        $this->rutaEstatusSolicitud = $directorioDatos . 'ae_estatus_solicitud.json';
        $this->rutaObservacion = $directorioDatos . 'ae_observacion.json';
        $this->rutaSolicitud = $directorioDatos . 'ae_Solicitud_Referencia.json';
        $this->rutaConstancia = $directorioDatos . 'ae_Constancia_Referencia.json';
        $this->rutaContenido = $directorioDatosExternos . 'ae_Constancia_Contenido.json';
        $this->rutaFolio = $directorioDatosExternos . 'ae_Folio.json';
    }

    // CATÁLOGOS Y ESTATUS
    public function estatusValidados(){
        return array_merge($this->listaEstatusValidos, $this->listaEstatusConstancia);
    }
    public function consultarDatoAdicional(){
        return $this->leer($this->rutaDatoAdicional);
    }

    public function consultarEstatusSolicitud(){
        return $this->leer($this->rutaEstatusSolicitud);
    }

    public function consultarObservacion(){
        return $this->leer($this->rutaObservacion);
    }

    // CONSULTAS DE ALUMNOS
    public function consultarNumeroCuenta($texto = '') {
        $cuentas = $this->leer($this->rutaNumeroCuenta);
        $alumnos = $this->leer($this->rutaNombreAlumno);

        $mapaAlumnos = array();
        foreach ($alumnos as $alumno){
            $nc = isset($alumno['NumeroCuenta']) ? $this->normalizar($alumno['NumeroCuenta']) : '';
            if ($nc !== '') $mapaAlumnos[$nc] = $alumno;
        }

        $texto = $this->normalizar($texto);
        $resultado = array();

        foreach ($cuentas as $cuenta) {
            $numeroCuenta = isset($cuenta['NumeroCuenta']) ? $this->normalizar($cuenta['NumeroCuenta']) : '';

            if ($texto !== '' && strpos($numeroCuenta, $texto) === false) continue;
        

                if (isset($mapaAlumnos[$numeroCuenta])) {
                    foreach ($mapaAlumnos[$numeroCuenta] as $campo => $valor) {
                        $cuenta[$campo] = $valor;
                    }
                }
                    if (empty($cuenta['NombreCompleto'])) {
                        $cuenta['NombreCompleto'] = trim(
                            (isset($cuenta['Nombre']) ? $cuenta['Nombre'] : '') . ' ' .
                            (isset($cuenta['ApellidoPaterno']) ? $cuenta['ApellidoPaterno'] : '') . ' ' .
                            (isset($cuenta['ApellidoMaterno']) ? $cuenta['ApellidoMaterno'] : '')
                        );
                    }
                    $resultado[] = $cuenta;
                }
                return $resultado;
            }

    public function consultarAlumno($numeroCuenta) {
        $numeroCuenta = $this->normalizar($numeroCuenta);
        if ($numeroCuenta === '') { return null; }

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
                foreach ($registro as $campo => $valor) {$alumno[$campo] = $valor;}
                break;
            }
        }
        if (empty($alumno)) { return null; }

        return $alumno;
    }

    // CONSULTAS DE CATÁLOGOS Y SOLICITUDES
    public function consultar($idSolicitud){
        if ($idSolicitud === null || trim((string)$id) === '') return null;
        $solicitudes = $this->leer($this->rutaSolicitud);
        foreach ($solicitudes as $s) {
            if (isset($s['idSolicitud']) && (string)$s['idSolicitud'] === (string)$idSolicitud) {
            $s['Constancia'] 
            $s['']    
            return $registro;
            }
        }
        return null;
    }

    

    public function listar(){
        $solicitudes = $this->leer($this->rutaConfiguraciones);
        $contenidos = $this->leer($this->rutaConstanciaContenido);

        $mapaContenidos = array();
        foreach ($contenidos as $c){
            if(isset($c['idSolicitud'])){
                $mapaContenidos[(string)$c['idSolicitud']] = $c;
            }
        }
        foreach($solicitudes as $i => $s){
            $idSol = isset($s['id']) ? (string)$s['id'] : '';
            $solicitudes[$i]['Contenido'] = isset($mapaContenidos[$idSol]) ? $mapaContenidos[$idSol] : null;
        }
        return $solicitudes;
    }

    // GUARDAR / ACTUALIZAR
    public function guardar($datos){
        $registros = $this->leer($this->rutaConfiguraciones);

        if (!isset($datos['NumeroCuenta']) || trim($datos['NumeroCuenta']) === '') {
            return array('ok' => false, 'mensaje' => 'El número de cuenta es obligatorio.');
        }
        
        $datos = $this->normalizarObservaciones($datos);

        $estatusActivos = array('Solicitado', 'Pagado', 'En elaboración', 'En firma', 'Emitida', 'Atendido');

        foreach ($registros as $registro){
            $mismaCuenta = isset($registro['NumeroCuenta']) &&
                $this->normalizar($registro['NumeroCuenta']) === $this->normalizar($datos['NumeroCuenta']);
            if ($mismaCuenta){
                $estatusActual = isset($registro['Estatus']) ? $registro['Estatus'] : '';
                if (in_array($estatusActual, $estatusActivos, true)){
                    return array(
                        'ok' => false,
                        'mensaje' => 'El número de cuenta ya tiene una solicitud activa (estatus: ' . $estatusActual . ').'
                    );
                }
            }
        }

        $ultimoId = 0;
        foreach ($registros as $registro) {
            if (isset($registro['id']) && $registro['id'] > $ultimoId) {
                $ultimoId = $registro['id'];
            }
        }

        $datos['id'] = $ultimoId + 1;
        if (!isset($datos['fechaRegistro'])) $datos['fechaRegistro'] = date('Y-m-d');
        if (!isset($datos['Estatus'])) $datos['Estatus'] = 'Solicitado';
        
        //generar folio
        $folioInfo = $this->generarFolio();
        
        $datos['idConstancia'] = $datos['id'];   
        $datos['folio']        = $folioInfo['folio'];
        $datos['idFolio']      = $folioInfo['idFolio'];

        //guardar solicitud
        $registros[] = $datos;

        if (!$this->guardarJson($this->rutaConfiguraciones, $registros)) {
            return array('ok' => false, 'mensaje' => 'No fue posible guardar la solicitud.');
        }
        //calcular el proximo id 
        $contenidos = $this->leer($this->rutaConstanciaContenido);
        $ultimoIdContenido = 0;
        foreach($contenidos as $c){                                          // ← corregido
            if(isset($c['idConstanciaContenido']) && (int)$c['idConstanciaContenido'] > $ultimoIdContenido){
                $ultimoIdContenido = (int)$c['idConstanciaContenido'];
            }
        }
        $nuevoIdContenido = $ultimoIdContenido + 1;

        //construir y guardar 
        $contenido = $this->construirContenido($nuevoIdContenido, $datos['id'], $datos, $folioInfo);
        $contenidos[] = $contenido;
        $this->guardarJson($this->rutaConstanciaContenido, $contenidos);

        return array(
            'ok' => true,
            'mensaje' => 'La solicitud se guardó correctamente.',
            'datos' => $datos
        );
    }

    public function actualizar($datos) {
        if (!isset($datos['id'])) {
            return array('ok' => false, 'mensaje' => 'No se recibió el identificador de la solicitud.');
        }
        $datos = $this->normalizarObservaciones($datos);
        $registros = $this->leer($this->rutaConfiguraciones);

        foreach ($registros as $indice => $registro) {
            if (isset($registro['id']) && (string)$registro['id'] === (string)$datos['id']) {

                $estatusActual = isset($registro['Estatus']) ? $registro['Estatus'] : '';

                if ($estatusActual === 'Cancelado') {
                    return array('ok' => false, 'mensaje' => 'No se puede editar una solicitud cancelada.');
                }

                if (in_array($estatusActual, array('Emitida', 'Entregado', 'Finalizado'), true)) {
                    return array('ok' => false, 'mensaje' => 'No se puede editar una solicitud que ya fue emitida o finalizada.');
                }

                foreach ($datos as $campo => $valor) {
                    $registros[$indice][$campo] = $valor;
                }

                $registros[$indice]['fechaActualizacion'] = date('Y-m-d');

                if (!$this->guardarJson($this->rutaConfiguraciones, $registros)) {
                    return array('ok' => false, 'mensaje' => 'No fue posible actualizar la solicitud.');
                }

                return array(
                    'ok' => true,
                    'mensaje' => 'La solicitud se actualizó correctamente.',
                    'datos' => $registros[$indice]
                );
            }
        }

        return array('ok' => false, 'mensaje' => 'No se encontró la solicitud.');
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

                if (isset($registro['Estatus']) && in_array($registro['Estatus'], array('Entregado', 'Finalizado'), true)) {
                    return array('ok' => false, 'mensaje' => 'No se puede cancelar una solicitud ya entregada o finalizada.');
                }

                $registros[$indice]['Estatus'] = 'Cancelado';
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

    // MÉTODOS DE TRANSICIÓN (cada uno definido UNA sola vez)

    // Flujo A - Paso 2: Pagado (guarda tipo de firma)
    public function pagar($id, $tipoFirma){
        if (!in_array($tipoFirma, array('S', 'N'), true)) {
            return array('ok' => false, 'mensaje' => 'El tipo de firma no es válido.');
        }
        return $this->cambiarEstatusInterno(
            $id,
            'Pagado',
            'La solicitud se marcó como pagada.',
            array('Solicitado'),
            array('TipoFirma' => $tipoFirma)
        );
    }

    // Flujo A y B - Paso 3/4: En elaboración (permite desde Pagado)
    public function elaborar($id){
        return $this->cambiarEstatusInterno(
            $id,
            'En elaboración',
            'La solicitud se marcó en elaboración correctamente.',
            array('Pagado')
        );
    }

    // Flujo B - Paso 5: En firma
    public function mandarFirma($id){
        return $this->cambiarEstatusInterno(
            $id,
            'En firma',
            'La solicitud se mandó a firma correctamente.',
            array('En elaboración')
        );
    }

    // Flujo B - Paso 6: Emitida
    public function emitir($id){
        return $this->cambiarEstatusInterno(
            $id,
            'Emitida',
            'La constancia se emitió correctamente.',
            array('En firma')
        );
    }

    // Flujo B - Paso 8: Entregado
    public function entregar($id){
        return $this->cambiarEstatusInterno(
            $id,
            'Entregado',
            'La constancia se marcó como entregada.',
            array('Emitida')
        );
    }

    // Flujo A - Paso 9: Atendido
    public function atender($id){
        return $this->cambiarEstatusInterno(
            $id,
            'Atendido',
            'El trámite se marcó como atendido.',
            array('En elaboración')
        );
    }

    // Flujo A y B - Paso 10: Finalizado
    public function finalizar($id){
        return $this->cambiarEstatusInterno(
            $id,
            'Finalizado',
            'La solicitud se finalizó correctamente.',
            array('Atendido', 'Entregado')
        );
    }
    
    private function generarFolio(){
        $anio = date('Y');
        $registros = $this->leer($this->rutaFolio);

        //buscar el consecutivo 
        $maxConsecutivo = 0;
        $indiceAnio = -1;
        foreach ($registros as $i => $reg){
            if(isset($reg['anio']) && (string)$reg['anio'] === (string)$anio){
                $indiceAnio = $i;
                $ultimo = isset($reg['ultimoConsecutivo'])? (int)$reg['ultimoConsecutivo'] : 0;
                if($ultimo > $maxConsecutivo) $maxConsecutivo = $ultimo;
            }
        }
        $nuevoConsecutivo = $maxConsecutivo + 1;
        $folio = 'CE/' . str_pad($nuevoConsecutivo, 2, '0', STR_PAD_LEFT) . '/' . $anio;

        $registros[] = array(
        'idFolio' => (string)$nuevoConsecutivo,
        'folio'   => (string)$nuevoConsecutivo,
        'anio'    => $anio,
        'estatus' => 'usado'
        );

        $this->guardarJson($this->rutaFolio, $registros);

        return array(
            'idFolio'     => $nuevoConsecutivo,
            'folio'       => $folio,
            'anio'        => $anio,
            'consecutivo' => $nuevoConsecutivo
        );
    }  
    
    public function consultarContenido($idSolicitud){
        $contenidos = $this->leer($this->rutaConstanciaContenido);
        foreach($contenidos as $c){
            if(isset($c['idSolicitud']) && (string)$c['idSolicitud'] === (string)$idSolicitud){
                return $c;
            }
        }
        return null;
    }

    private function construirContenido($idConstanciaContenido, $idConstancia, $datos, $folioInfo){
        // Observaciones -> campoAdicional / informacionAdicional
    $campoAdicional      = '';
    $informacionAdicional = '';
    if (!empty($datos['Observaciones']) && is_array($datos['Observaciones'])) {
        $primerPar = $datos['Observaciones'][0];
        if (isset($primerPar['nombre']))  $campoAdicional      = $primerPar['nombre'];
        if (isset($primerPar['valor']))   $informacionAdicional = $primerPar['valor'];
    }
    $alumno = $this->consultarAlumno($datos['NumeroCuenta']);

    $apellidoPaterno = isset($alumno['ApellidoPaterno']) ? $alumno['ApellidoPaterno'] : '';
    $apellidoMaterno = isset($alumno['ApellidoMaterno']) ? $alumno['ApellidoMaterno'] : '';
    $nombre          = isset($alumno['Nombre']) ? $alumno['Nombre'] : '';

        return array(
            'idConstanciaContenido'   => $idConstanciaContenido,
            'idConstancia'            => $idConstancia,
            'idSolicitud'             => isset($datos['id']) ? $datos['id'] : null,
            'folio'                   => $folioInfo['folio'],
            'apellidoPaterno'         => $apellidoPaterno,
            'apellidoMaterno'         => $apellidoMaterno,
            'nombre'                  => $nombre,
            'CURP'                    => isset($datos['CURP']) ? $datos['CURP'] : '',
            'numeroCuenta'            => isset($datos['NumeroCuenta']) ? $datos['NumeroCuenta'] : '',
            'programaEducativo'       => isset($datos['ProgramaEducativo']) ? $datos['ProgramaEducativo'] : '',
            'unidadAcademica'         => isset($datos['Instituto']) ? $datos['Instituto'] : '',
            'cctUnidadAcademica'      => '',
            'cctUniversidad'          => '',
            'estatusAlumno'           => 'Cursando',
            'calidadAlumno'           => 'Regular',
            'tipoIngreso'             => '',
            'semestre'                => isset($datos['Semestre']) ? $datos['Semestre'] : '',
            'duracionProgramaEducativo' => '',
            'fechaIngreso'            => '',
            'fechaEgreso'             => '',
            'cicloEscolarConstancia'  => isset($datos['CicloEscolar']) ? $datos['CicloEscolar'] : '',
            'periodoClases'           => '',
            'periodoVacacional'       => '',
            'promedioGeneral'         => '',
            'avanceProgramaEducativo' => '',
            'campoAdicional'          => $campoAdicional,
            'informacionAdicional'    => $informacionAdicional,
            'calificaciones'          => array()
        );
    }
    
    private function normalizarObservaciones($datos) {
    if (isset($datos['Observaciones']) && is_string($datos['Observaciones'])) {
        $decoded = json_decode($datos['Observaciones'], true);
        $datos['Observaciones'] = is_array($decoded) ? $decoded : array();
    } elseif (!isset($datos['Observaciones'])) {
        $datos['Observaciones'] = array();
    }
    return $datos;
}
    // Cambiar estado manualmente (sin restricción de estatus origen)
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

    public function cambiarEstatusMasivo($ids, $nuevoEstatus) {
    if (!is_array($ids) || empty($ids)) {
        return array('ok' => false, 'mensaje' => 'No se recibieron solicitudes.');
    }
    if (!in_array($nuevoEstatus, $this->listaEstatusValidos, true)) {
        return array('ok' => false, 'mensaje' => 'El estatus destino no es válido.');
    }

    // Normalizar los ids a strings para comparar siempre igual
    $idsStr = array_map('strval', $ids);

    $transiciones = self::transicionesPermitidas();
    $registros    = $this->leer($this->rutaConfiguraciones);
    $procesados   = array();
    $omitidos     = array();

    // Alias por si el front manda "Emitir" o "Emitido"
    $aliasEmitida = array('Emitida', 'Emitido', 'Emitir');
    $estatusFinal = in_array($nuevoEstatus, $aliasEmitida, true) ? 'Emitida' : $nuevoEstatus;

    foreach ($registros as $indice => $registro) {
        if (!isset($registro['id'])) continue;

        $idStr = (string)$registro['id'];
        if (!in_array($idStr, $idsStr, true)) continue;

        $estatusActual = isset($registro['Estatus']) ? $registro['Estatus'] : '';
        $permitidos    = isset($transiciones[$estatusActual]) ? $transiciones[$estatusActual] : array();

        $esValido = in_array($estatusFinal, $permitidos, true);

        if (!$esValido) {
            $omitidos[] = array(
                'id'      => $registro['id'],
                'estatus' => $estatusActual,
                'motivo'  => 'No se puede pasar de "' . $estatusActual . '" a "' . $estatusFinal . '".'
            );
            continue;
        }

        $registros[$indice]['Estatus']            = $estatusFinal;
        $registros[$indice]['fechaActualizacion'] = date('Y-m-d');
        $procesados[] = $registro['id'];
    }

    if (empty($procesados)) {
        return array(
            'ok'         => false,
            'mensaje'    => 'Ninguna de las solicitudes seleccionadas puede pasar a "' . $estatusFinal . '".',
            'procesados' => array(),
            'omitidos'   => $omitidos
        );
    }

    if (!$this->guardarJson($this->rutaConfiguraciones, $registros)) {
        return array('ok' => false, 'mensaje' => 'No fue posible guardar los cambios.');
    }

    $total = count($procesados);
    return array(
        'ok'         => true,
        'mensaje'    => $total . ' solicitud(es) actualizada(s) correctamente.',
        'procesados' => $procesados,
        'omitidos'   => $omitidos
    );
}

    // CAMBIO DE ESTATUS INTERNO (con $datosExtra)
    private function cambiarEstatusInterno($id, $nuevoEstatus, $mensajeOk, $estatusPermitidos = null, $datosExtra = array()){
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

                if ($estatusActual === 'Finalizado') {
                    return array('ok' => false, 'mensaje' => 'La solicitud ya está finalizada.');
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

                $registros[$indice]['Estatus'] = $nuevoEstatus;
                $registros[$indice]['fechaActualizacion'] = date('Y-m-d');

                // $datosExtra 
                if (is_array($datosExtra)) {
                    foreach ($datosExtra as $campo => $valor) {
                        $registros[$indice][$campo] = $valor;
                    }
                }

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

    // LECTURA / ESCRITURA JSON
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
            error_log("ERROR JSON: " . $ruta . " | " . json_last_error_msg());
            return array();
        }

        if (!is_array($datos)) {
            error_log("JSON NO ES UN ARRAY: " . $ruta);
            return array();
        }
        return $datos;
    }

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

    private function normalizar($texto) {
        $texto = trim((string)$texto);
        if ($texto === '') {
            return '';
        }
        return mb_strtolower($texto, 'UTF-8');
    }
}