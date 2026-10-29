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
    private $rutaCiclo;

    private $listaEstatusSolicitud  = array('Solicitado', 'Pagado', 'Cancelado');
    private $listaEstatusConstancia = array('En elaboración', 'En firma', 'Emitida', 'Entregado', 'Finalizado', 'Cancelado');

    public function __construct(){
        $directorioDatos          = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
        $directorioDatosExternos  = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'alumnos' . DIRECTORY_SEPARATOR . 'constancia-estudios-alumnos' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;

        $this->rutaNumeroCuenta    = $directorioDatos . 'vta_siae_numero_cuenta.json';
        $this->rutaNombreAlumno    = $directorioDatos . 'vta_siae_nombre_alumno.json';
        $this->rutaDatoAdicional   = $directorioDatos . 'ae_dato_adicional.json';
        // $this->rutaEstatusSolicitud = $directorioDatos . 'ae_estatus_solicitud.json';
        $this->rutaObservacion     = $directorioDatos . 'ae_observacion.json';
        $this->rutaSolicitud       = $directorioDatosExternos . 'ae_Solicitud_Referencia.json';
        $this->rutaConstancia      = $directorioDatosExternos . 'ae_Constancia_Referencia.json';
        $this->rutaContenido       = $directorioDatosExternos . 'ae_Constancia_Contenido.json';
        $this->rutaFolio           = $directorioDatosExternos . 'ae_Folio.json';
        error_log("RUTA SOLICITUD: " . $this->rutaSolicitud);
        $this->rutaCiclo = $directorioDatos . 'ae_ciclo.json';
    }

    // CATÁLOGOS
    public function estatusValidados() {
        return array_merge($this->listaEstatusSolicitud, $this->listaEstatusConstancia);
    }

    public function consultarDatoAdicional()    { return $this->leer($this->rutaDatoAdicional); }
    public function consultarEstatusSolicitud() { return $this->leer($this->rutaEstatusSolicitud); }
    public function consultarObservacion()      { return $this->leer($this->rutaObservacion); }

    // CONSULTAS DE ALUMNOS
    
    public function consultarNumeroCuenta($texto = '') {

    $cuentas = $this->leer($this->rutaNumeroCuenta);
    $alumnos = $this->leer($this->rutaNombreAlumno);

    $texto = $this->normalizar($texto);
    $resultado = array();

    foreach ($cuentas as $cuenta) {

        $numeroCuenta = isset($cuenta['NumeroCuenta'])
            ? $this->normalizar($cuenta['NumeroCuenta'])
            : '';

        if ($numeroCuenta === '') {
            continue;
        }

        // Filtrar por número de cuenta si se proporcionó texto.
        if (
            $texto !== '' &&
            strpos($numeroCuenta, $texto) === false
        ) {
            continue;
        }

        // Buscar todas las asociaciones académicas de esta cuenta.
        $encontroAsociacion = false;

        foreach ($alumnos as $alumno) {

            $numeroAlumno = isset($alumno['NumeroCuenta'])
                ? $this->normalizar($alumno['NumeroCuenta'])
                : '';

            if ($numeroAlumno !== $numeroCuenta) {
                continue;
            }

            // Combinar datos generales con esta asociación académica.
            $registro = array_merge($cuenta, $alumno);

            // Construir el nombre completo si no existe.
            if (empty($registro['NombreCompleto'])) {
                $registro['NombreCompleto'] = trim(
                    (isset($registro['Nombre'])
                        ? $registro['Nombre'] : '') . ' ' .
                    (isset($registro['ApellidoPaterno'])
                        ? $registro['ApellidoPaterno'] : '') . ' ' .
                    (isset($registro['ApellidoMaterno'])
                        ? $registro['ApellidoMaterno'] : '')
                );
            }

            $resultado[] = $registro;
            $encontroAsociacion = true;
        }

        // Si la cuenta no tiene asociaciones en el JSON de nombres,
        // devolver al menos el registro general de la cuenta.
        if (!$encontroAsociacion) {

            $registro = $cuenta;

            if (empty($registro['NombreCompleto'])) {
                $registro['NombreCompleto'] = trim(
                    (isset($registro['Nombre'])
                        ? $registro['Nombre'] : '') . ' ' .
                    (isset($registro['ApellidoPaterno'])
                        ? $registro['ApellidoPaterno'] : '') . ' ' .
                    (isset($registro['ApellidoMaterno'])
                        ? $registro['ApellidoMaterno'] : '')
                );
            }

            $resultado[] = $registro;
        }
    }

    return $resultado;
}

    public function consultarAlumno($numeroCuenta) {
        $numeroCuenta = $this->normalizar($numeroCuenta);
        if ($numeroCuenta === '') return null;

        $cuentas = $this->leer($this->rutaNumeroCuenta);
        $nombres = $this->leer($this->rutaNombreAlumno);
        $alumno  = array();

        foreach ($cuentas as $registro) {
            $cuenta = isset($registro['NumeroCuenta']) ? $this->normalizar($registro['NumeroCuenta']) : '';
            if ($cuenta === $numeroCuenta) { $alumno = $registro; break; }
        }
        foreach ($nombres as $registro) {
            $cuenta = isset($registro['NumeroCuenta']) ? $this->normalizar($registro['NumeroCuenta']) : '';
            if ($cuenta === $numeroCuenta) {
                foreach ($registro as $campo => $valor) { $alumno[$campo] = $valor; }
                break;
            }
        }
        return empty($alumno) ? null : $alumno;
    }

    public function consultarAlumnoPorKardex($idKardex){
    $idKardex = $this->normalizar($idKardex);

    if ($idKardex === '') {
        return null;
    }

    $alumnos = $this->leer($this->rutaNombreAlumno);

    foreach ($alumnos as $alumno) {
        $kardex = isset($alumno['idKardex'])
            ? $this->normalizar($alumno['idKardex'])
            : '';

        if ($kardex !== '' && $kardex === $idKardex) {
            return $alumno;
        }
    }

    return null;
}

    
public function consultarSolicitudExistente($idKardex, $idCicloEscolar)
{
    // 1. Normalizar identificadores
    $idKardex = $this->normalizar($idKardex);
    $idCicloEscolar = $this->normalizar($idCicloEscolar);

    if ($idKardex === '' || $idCicloEscolar === '') {
        throw new Exception(
            'Falta el Kardex o el ciclo escolar para validar la solicitud.'
        );
    }

    // 2. Leer solicitudes registradas
    $solicitudes = $this->leer($this->rutaSolicitud);

    // 3. Estados que impiden registrar otra solicitud
    $estatusActivos = array('solicitado', 'pagado');

    // 4. Buscar coincidencia por Kardex y ciclo escolar
    foreach ($solicitudes as $solicitud) {
        $kardex = isset($solicitud['idKardex'])
            ? $this->normalizar($solicitud['idKardex'])
            : '';

        $ciclo = isset($solicitud['idCicloEscolar'])
            ? $this->normalizar($solicitud['idCicloEscolar']) : (isset($solicitud['CicloEscolar']) ? $this->normalizar($solicitud['CicloEscolar']) : '');

        $estatus = isset($solicitud['estatus'])
            ? strtolower(trim((string)$solicitud['estatus']))
            : '';

        if (
            $kardex === $this->normalizar($idKardex) &&
            $ciclo === $this->normalizar($idCicloEscolar) &&
            in_array($estatus, $estatusActivos, true)
        ) {
            return array(
                'existe' => true,
                'mensaje' => 'Este alumno ya tiene una solicitud de constancia registrada para el ciclo escolar seleccionado.'
            );
        }
    }

    // 5. No existe una solicitud activa para esa combinación
    return array(
        'existe' => false,
        'mensaje' => ''
    );
}

    // CONSULTAS DE SOLICITUDES Y CONSTANCIAS
    public function consultar($idSolicitud) {
        if ($idSolicitud === null || trim((string)$idSolicitud) === '') return null;
        $solicitudes = $this->leer($this->rutaSolicitud);
        foreach ($solicitudes as $s) {
            if (isset($s['idSolicitud']) && (string)$s['idSolicitud'] === (string)$idSolicitud) {
                $s['Constancia'] = $this->consultarConstanciaPorSolicitud($idSolicitud);
                $s['Contenido']  = $this->consultarContenido($idSolicitud);
                return $s;
            }
        }
        return null;
    }

    public function consultarConstanciaPorSolicitud($idSolicitud) {
        $constancias = $this->leer($this->rutaConstancia);
        foreach ($constancias as $c) {
            if (isset($c['idSolicitud']) && (string)$c['idSolicitud'] === (string)$idSolicitud) {
                return $c;
            }
        }
        return null;
    }

    public function consultarContenido($idSolicitud) {
        $contenidos = $this->leer($this->rutaContenido);
        foreach ($contenidos as $c) {
            if (isset($c['idSolicitud']) && (string)$c['idSolicitud'] === (string)$idSolicitud) {
                return $c;
            }
        }
        return null;
    }
    
    public function validarSolicitudActivaPorKardex($idKardex)
    {
        $idKardex = $this->normalizar($idKardex);

        if ($idKardex === '') {
            throw new Exception('No se recibió el idKardex del alumno.');
        }

        $cicloActivo = $this->obtenerCicloActivo();

        if (
            empty($cicloActivo) ||
            empty($cicloActivo['idCiclo'])
        ) {
            throw new Exception(
                'No se encontró un ciclo escolar activo para solicitar constancias.'
            );
        }

        $resultado = $this->consultarSolicitudExistente(
            $idKardex,
            $cicloActivo['idCiclo']
        );

        return array(
            'tieneActiva' => $resultado['existe'],
            'mensaje' => $resultado['mensaje']
        );
    }
    public function obtenerCicloActivo()
{
    $ciclos = $this->leer($this->rutaCiclo);
    $hoy = date('Y-m-d');

    foreach ($ciclos as $ciclo) {
        $estado = isset($ciclo['estado'])
            ? strtoupper(trim((string) $ciclo['estado']))
            : '';

        $fechaInicio = isset($ciclo['fechaSolicitudConstanciaInicio'])
            ? trim((string) $ciclo['fechaSolicitudConstanciaInicio'])
            : '';

        $fechaTermino = isset($ciclo['fechaSolicitudConstanciaTermino'])
            ? trim((string) $ciclo['fechaSolicitudConstanciaTermino'])
            : '';

        $idCiclo = isset($ciclo['idCiclo'])
            ? trim((string) $ciclo['idCiclo'])
            : '';

        if (
            $estado === 'ACTIVO' &&
            $idCiclo !== '' &&
            $fechaInicio !== '' &&
            $fechaTermino !== '' &&
            $hoy >= $fechaInicio &&
            $hoy <= $fechaTermino
        ) {
            return $ciclo;
        }
    }

    return null;
}
    // LISTAR — Join de Solicitud + Constancia + Contenido
    public function listar() {
        $solicitudes = $this->leer($this->rutaSolicitud);
        $constancias = $this->leer($this->rutaConstancia);
        $contenidos  = $this->leer($this->rutaContenido);

        $mapaConst = array();
        foreach ($constancias as $c) {
            if (isset($c['idSolicitud'])) $mapaConst[(string)$c['idSolicitud']] = $c;
        }

        $mapaCont = array();
        foreach ($contenidos as $c) {
            if (isset($c['idSolicitud'])) $mapaCont[(string)$c['idSolicitud']] = $c;
        }

        $resultado = array();
        foreach ($solicitudes as $s) {
            $idSol = isset($s['idSolicitud']) ? (string)$s['idSolicitud'] : '';
            $const = isset($mapaConst[$idSol]) ? $mapaConst[$idSol] : null;
            $cont  = isset($mapaCont[$idSol])  ? $mapaCont[$idSol]  : null;

            $estatusInterno = $const && isset($const['estatus'])
                ? $const['estatus']
                : (isset($s['estatus']) ? $s['estatus'] : '');

            $s['EstatusInterno'] = $estatusInterno;
            $s['EstatusAlumno']  = $this->mapearEstatusAlumno($estatusInterno);
            $s['Folio']          = $cont && isset($cont['folio']) ? $cont['folio'] : '';
            $s['Constancia']     = $const;
            $s['Contenido']      = $cont;

            $resultado[] = $s;
        }
        return $resultado;
    }

    private function mapearEstatusAlumno($estatusInterno) {
        switch ($estatusInterno) {
            case 'Solicitado':      return 'Solicitado';
            case 'Pagado':          return 'Pagado';
            case 'En elaboración':  return 'En elaboración';
            case 'En firma':        return 'En elaboración';
            case 'Emitida':         return 'Atendido';
            case 'Entregado':       return 'Finalizado';
            case 'Finalizado':      return 'Finalizado';
            case 'Cancelado':       return 'Cancelado';
        }
        return $estatusInterno;
    }
    public function actualizarObservaciones($idSolicitud, $observacionesJson) {
        $solicitudes = $this->leer($this->rutaSolicitud);
        $obs = json_decode($observacionesJson, true);
        if (!is_array($obs)) $obs = array();

        foreach ($solicitudes as $i => $s) {
            if (isset($s['idSolicitud']) && (string)$s['idSolicitud'] === (string)$idSolicitud) {
                $solicitudes[$i]['Observaciones'] = $obs;
                $this->guardarJson($this->rutaSolicitud, $solicitudes);
                return array('ok' => true, 'mensaje' => 'Observaciones guardadas.');
            }
        }
        return array('ok' => false, 'mensaje' => 'No se encontró la solicitud.');
    }
            
    public function cambiarEstatusMasivo($datos){
        // 1. Validar que lleguen los IDs
        if (!isset($datos['ids']) || empty($datos['ids'])) {
            throw new Exception('No se recibieron los registros a modificar.');
        }
        
        // 2. Validar que llegue el estatus
        $nuevoEstatus = isset($datos['estatus']) ? trim($datos['estatus']) : '';
        if ($nuevoEstatus === '') {
            throw new Exception('El nuevo estatus es obligatorio.');
        }

        $ids = (array)$datos['ids'];
        $procesados = 0;
        $omitidos = 0;

        // 3. Iterar sobre los IDs seleccionados
        foreach ($ids as $idSolicitud) {
            
            // Si es elaborar, usamos la función especial que genera el folio
            if ($nuevoEstatus === 'En elaboración') {
                $resultado = $this->elaborar($idSolicitud);
                if (isset($resultado['ok']) && $resultado['ok'] === true) {
                    $procesados++;
                } else {
                    $omitidos++;
                }
                continue;
            }

            // Para los demás estatus, usamos las funciones específicas para que actualicen la Constancia
            if ($nuevoEstatus === 'En firma') {
                $resultado = $this->mandarFirma($idSolicitud);
            } elseif ($nuevoEstatus === 'Emitida') {
                $resultado = $this->emitir($idSolicitud);
            } elseif ($nuevoEstatus === 'Entregado') {
                $resultado = $this->entregar($idSolicitud);
            } elseif ($nuevoEstatus === 'Cancelado') {
                $resultado = $this->cancelar($idSolicitud);
            } else {
                // Por si se agrega otro estatus, actualizamos solo la solicitud
                $resultado = $this->cambiarEstatusSolicitud($idSolicitud, $nuevoEstatus, 'Estatus actualizado.');
            }

            // Contabilizar si se actualizó o se omitió
            if (isset($resultado['ok']) && $resultado['ok'] === true) {
                $procesados++;
            } else {
                $omitidos++;
            }
        }
        return array(
            'procesados' => $procesados,
            'omitidos'   => $omitidos
        );
    }
    // GUARDAR (crear solicitud nueva)
    public function guardar($datos) {
        //validar el número de cuenta 
        if (!isset($datos['NumeroCuenta']) || trim($datos['NumeroCuenta']) === '') {
            return array('ok' => false, 'mensaje' => 'El número de cuenta es obligatorio.');
        }
        //validar seleccion de kardex 
        if(!isset($datos['idKardex']) || trim((string)$datos['idKardex']) === ''){
            return array(
            'ok' => false,
            'mensaje' => 'Debes seleccionar una carrera del alumno.'
        );
        }
        $idKardex = $this->normalizar($datos['idKardex']);
        //buscar asociación academica 
        $alumno = $this->consultarAlumnoPorKardex($idKardex);
        if($alumno === null){
            return array(
            'ok' => false,
            'mensaje' => 'No se encontró el kardex seleccionado.'
        );
        }
        //verificar que el kardex tenga cuenta 
        if(!isset($alumno['NumeroCuenta'])|| $this->normalizar($alumno['NumeroCuenta']) !== $this->normalizar($datos['NumeroCuenta'])
        ){
            return array(
            'ok' => false,
            'mensaje' => 'EL Kardex no corresponde al número de cuenta seleccionado.'
        );
        }
        //programa educativo asociado
        if (
        !isset($alumno['idProgramaEducativo']) ||
        trim((string)$alumno['idProgramaEducativo']) === ''
    ) {
        return array(
            'ok' => false,
            'mensaje' => 'La asociación académica no tiene un programa educativo asociado.'
        );
    }
        $idProgramaEducativo = $alumno['idProgramaEducativo'];
        //Leer ciclos escolares 
        $ciclos = $this->leer($this->rutaCiclo);
        $fechaHoy = date('Y-m-d');
        $cicloActivo = null;
        foreach($ciclos as $ciclo){
            $estado = isset($ciclo['estado']) ? strtoupper(trim($ciclo['estado'])) : '';
            //ciclo activo
            if($estado !== 'ACTIVO'){
                continue;
            }
            $fechaInicio = isset($ciclo['fechaSolicitudConstanciaInicio']) ? $ciclo['fechaSolicitudConstanciaInicio'] : '';
            $fechaTermino = isset(
                $ciclo['fechaSolicitudConstanciaTermino']) ? $ciclo['fechaSolicitudConstanciaTermino'] : '';
            //periodo de solicitud 
            if($fechaInicio !== '' &&
            $fechaTermino !== '' &&
            $fechaHoy >= $fechaInicio &&
            $fechaHoy <= $fechaTermino
        ) {
            $cicloActivo = $ciclo;
            break;
                }
            }

            if ($cicloActivo === null) {
                return array(
                    'ok' => false,
                    'mensaje' =>
                        'No existe un ciclo escolar activo dentro del periodo permitido para solicitar constancias.'
                );
            }
            $idCicloEscolar = $cicloActivo['idCiclo'];

            //validar solicitud 
            $existente = $this->consultarSolicitudExistente(
                $idKardex,
                $idCicloEscolar
            );
            if (!empty($existente['existe'])) {
        return array(
            'ok' => false,
            'mensaje' => 'El alumno ya tiene una solicitud activa para este ciclo escolar.'
        );
    }

        //Leer solicitudes existentes 
        $solicitudes = $this->leer($this->rutaSolicitud);

        $ultimoId = 0;

        foreach ($solicitudes as $solicitudExistente) {
            if (isset($solicitudExistente['idSolicitud']) && (int)$solicitudExistente['idSolicitud'] > $ultimoId) {
                $ultimoId = (int)$solicitudExistente['idSolicitud'];
            }
        }
        $nuevoId = $ultimoId + 1;

        $solicitud = array(
            'idSolicitud'              => $nuevoId,
            'idKardex'                 => isset($datos['idKardex']) ? $datos['idKardex'] : '',
            'idProgramaEducativo'      => isset($datos['idProgramaEducativo']) ? $datos['idProgramaEducativo'] : '',
            'fechaSolicitud'           => date('Y-m-d'),
            'estatus'                  => 'Solicitado',
            'tipoFirma'                => isset($datos['TipoFirma']) ? $datos['TipoFirma'] : '',
            'cantidadConstancias'      => '1',
            'contieneCalificaciones'   => isset($datos['Calificaciones']) ? $datos['Calificaciones'] : 'N',
            'informacionAdicional'     => isset($datos['DatoAdicional']) ? $datos['DatoAdicional'] : 'N',
            'idCicloEscolar'           => isset($datos['idCicloEscolar']) ? $datos['idCicloEscolar'] : ''
        );
        $solicitudes[] = $solicitud;

        if (!$this->guardarJson($this->rutaSolicitud, $solicitudes)) {
            return array('ok' => false, 'mensaje' => 'No fue posible guardar la solicitud.');
        }

        return array('ok' => true, 'mensaje' => 'La solicitud se guardó correctamente.', 'datos' => $solicitud);
    }

    // ACTUALIZAR
    public function actualizar($datos) {
        if (!isset($datos['id'])) {
            return array('ok' => false, 'mensaje' => 'No se recibió el identificador de la solicitud.');
        }
        $datos = $this->normalizarObservaciones($datos);
        $solicitudes = $this->leer($this->rutaSolicitud);

        foreach ($solicitudes as $i => $s) {
            if (isset($s['idSolicitud']) && (string)$s['idSolicitud'] === (string)$datos['id']) {
                if (in_array($s['estatus'], array('Emitida', 'Entregado', 'Finalizado', 'Cancelado'), true)) {
                    return array('ok' => false, 'mensaje' => 'No se puede editar una solicitud en este estatus.');
                }
                $solicitudes[$i]['tipoFirma']              = isset($datos['TipoFirma']) ? $datos['TipoFirma'] : $s['tipoFirma'];
                $solicitudes[$i]['contieneCalificaciones'] = isset($datos['Calificaciones']) ? $datos['Calificaciones'] : $s['contieneCalificaciones'];
                $solicitudes[$i]['informacionAdicional']   = isset($datos['DatoAdicional']) ? $datos['DatoAdicional'] : $s['informacionAdicional'];
                $solicitudes[$i]['Observaciones']          = isset($datos['Observaciones']) ? $datos['Observaciones'] : $s['Observaciones'];
                $solicitudes[$i]['fechaActualizacion']     = date('Y-m-d');

                if (!$this->guardarJson($this->rutaSolicitud, $solicitudes)) {
                    return array('ok' => false, 'mensaje' => 'No fue posible actualizar la solicitud.');
                }
                return array('ok' => true, 'mensaje' => 'La solicitud se actualizó.', 'datos' => $solicitudes[$i]);
            }
        }
        return array('ok' => false, 'mensaje' => 'No se encontró la solicitud.');
    }

    // PAGAR
    public function pagar($id) {
        return $this->cambiarEstatusSolicitud($id, 'Pagado', 'La solicitud se marcó como pagada.');
    }

    // ELABORAR
    public function elaborar($id) {
        $solicitudes = $this->leer($this->rutaSolicitud);

        $indice = -1;
        foreach ($solicitudes as $i => $s) {
            if (isset($s['idSolicitud']) && (string)$s['idSolicitud'] === (string)$id) { $indice = $i; break; }
        }
        if ($indice === -1) return array('ok' => false, 'mensaje' => 'No se encontró la solicitud.');

        $solicitud = $solicitudes[$indice];
        if ($solicitud['estatus'] !== 'Pagado') {
            return array('ok' => false, 'mensaje' => 'La solicitud debe estar en estatus Pagado.');
        }

        // 1) Generar folio
        $folioInfo = $this->generarFolio();

        // 2) Crear Constancia
        $constancias = $this->leer($this->rutaConstancia);
        $ultimoIdConst = 0;
        foreach ($constancias as $c) {
            if (isset($c['idConstancia']) && (int)$c['idConstancia'] > $ultimoIdConst) {
                $ultimoIdConst = (int)$c['idConstancia'];
            }
        }
        $idConstancia = $ultimoIdConst + 1;

        $constancia = array(
            'idConstancia'         => $idConstancia,
            'idSolicitud'          => $solicitud['idSolicitud'],
            'idKardex'             => $solicitud['idKardex'],
            'anio'                 => date('Y'),
            'estatus'              => 'En elaboración',
            'fechaEmision'         => '',
            'fechaEntrega'         => '',
            'fechaCaducidad'       => '',
            'fechaLimiteDescarga'  => '',
            'idFolio'              => $folioInfo['idFolio'],
        );
        $constancias[] = $constancia;

        // Crear Contenido
        $contenidos = $this->leer($this->rutaContenido);
        $ultimoIdCont = 0;
        foreach ($contenidos as $c) {
            if (isset($c['idConstanciaContenido']) && (int)$c['idConstanciaContenido'] > $ultimoIdCont) {
                $ultimoIdCont = (int)$c['idConstanciaContenido'];
            }
        }
        $idContenido = $ultimoIdCont + 1;

        $contenido = $this->construirContenido($idContenido, $idConstancia, $solicitud, $folioInfo);
        $contenidos[] = $contenido;

        // Actualizar la Solicitud
        $solicitudes[$indice]['idConstancia'] = $idConstancia;

        // Guardar todo
        $this->guardarJson($this->rutaConstancia, $constancias);
        $this->guardarJson($this->rutaContenido, $contenidos);
        $this->guardarJson($this->rutaSolicitud, $solicitudes);

        // Si es digital, cadena automática: En firma → Emitida
        $tipoFirma = isset($solicitud['tipoFirma']) ? $solicitud['tipoFirma'] : '';
        $esDigital = ($tipoFirma === 'S');

        if ($esDigital) {
            foreach ($constancias as $i => $c) {
                if ((string)$c['idConstancia'] === (string)$idConstancia) {
                    $constancias[$i]['estatus'] = 'En firma';
                    break;
                }
            }
            $this->guardarJson($this->rutaConstancia, $constancias);

            foreach ($constancias as $i => $c) {
                if ((string)$c['idConstancia'] === (string)$idConstancia) {
                    $constancias[$i]['estatus'] = 'Emitida';
                    $constancias[$i]['fechaEmision'] = date('Y-m-d');
                    break;
                }
            }
            $this->guardarJson($this->rutaConstancia, $constancias);
        }

        return array('ok' => true, 'mensaje' => 'La constancia se creó correctamente.');
    }

    public function mandarFirma($id) {
        return $this->cambiarEstatusConstancia($id, 'En firma', 'La solicitud se mandó a firma.');
    }

    public function emitir($id) {
        return $this->cambiarEstatusConstancia($id, 'Emitida', 'La constancia se emitió.', array('fechaEmision' => date('Y-m-d')));
    }

    public function entregar($id) {
        return $this->cambiarEstatusConstancia($id, 'Entregado', 'La constancia se entregó.', array('fechaEntrega' => date('Y-m-d')));
    }

    // CANCELAR
    public function cancelar($id) {
        $solicitudes = $this->leer($this->rutaSolicitud);
        foreach ($solicitudes as $i => $s) {
            if (isset($s['idSolicitud']) && (string)$s['idSolicitud'] === (string)$id) {
                if (isset($s['estatus']) && in_array($s['estatus'], array('Finalizado', 'Cancelado'), true)) {
                    return array('ok' => false, 'mensaje' => 'La solicitud ya no se puede cancelar.');
                }
                $solicitudes[$i]['estatus'] = 'Cancelado';
                $this->guardarJson($this->rutaSolicitud, $solicitudes);

                $constancias = $this->leer($this->rutaConstancia);
                foreach ($constancias as $j => $c) {
                    if (isset($c['idSolicitud']) && (string)$c['idSolicitud'] === (string)$id) {
                        $constancias[$j]['estatus'] = 'Cancelado';
                        break;
                    }
                }
                $this->guardarJson($this->rutaConstancia, $constancias);

                return array('ok' => true, 'mensaje' => 'La solicitud se canceló.');
            }
        }
        return array('ok' => false, 'mensaje' => 'No se encontró la solicitud.');
    }

    // HELPERS INTERNOS
    private function cambiarEstatusSolicitud($id, $nuevoEstatus, $mensaje) {
        $solicitudes = $this->leer($this->rutaSolicitud);
        foreach ($solicitudes as $i => $s) {
            if (isset($s['idSolicitud']) && (string)$s['idSolicitud'] === (string)$id) {
                if ($s['estatus'] === 'Cancelado' || $s['estatus'] === 'Finalizado') {
                    return array('ok' => false, 'mensaje' => 'No se puede modificar en ese estatus.');
                }
                $solicitudes[$i]['estatus'] = $nuevoEstatus;
                $this->guardarJson($this->rutaSolicitud, $solicitudes);
                return array('ok' => true, 'mensaje' => $mensaje, 'datos' => $solicitudes[$i]);
            }
        }
        return array('ok' => false, 'mensaje' => 'No se encontró la solicitud.');
    }

    private function cambiarEstatusConstancia($idSolicitud, $nuevoEstatus, $mensaje, $extra = array()) {
        $constancias = $this->leer($this->rutaConstancia);
        foreach ($constancias as $i => $c) {
            if (isset($c['idSolicitud']) && (string)$c['idSolicitud'] === (string)$idSolicitud) {
                $constancias[$i]['estatus'] = $nuevoEstatus;
                foreach ($extra as $k => $v) { $constancias[$i][$k] = $v; }
                $this->guardarJson($this->rutaConstancia, $constancias);
                return array('ok' => true, 'mensaje' => $mensaje, 'datos' => $constancias[$i]);
            }
        }
        return array('ok' => false, 'mensaje' => 'No se encontró la constancia.');
    }

    private function generarFolio() {
        $anio = date('Y');
        $registros = $this->leer($this->rutaFolio);

        $maxConsecutivo = 0;

        foreach ($registros as $reg) {
            $anioRegistro = isset($reg['anio']) ? (string)$reg['anio'] : '';
                if ($anioRegistro !== (string)$anio) {
                    continue;
            }
        
        $consecutivo =0;
        if(isset($reg['folio'])){
            if (preg_match('/^CE\/(\d+)\/\d{4}$/', $folio, $coincidencias)) {
                $consecutivo = (int)$coincidencias[1];
            } elseif (ctype_digit($folio)) {
                $consecutivo = (int)$folio;
            }
        }
        if ($consecutivo === 0 && isset($reg['idFolio'])){
            $consecutivo = (int)$reg['idFolio'];
        }
        if($consecutivo > $maxConsecutivo){
            $maxConsecutivo = $consecutivo;
        }
        }
        $nuevo = $maxConsecutivo +1;
        $folio = 'CE/' . str_pad((string)$nuevo, 2, '0', STR_PAD_LEFT) . '/' . $anio;

        $registros[] = array(
            'idFolio' => (string)$nuevo,
            'folio'   => $folio,
            'anio'    =>(string)$anio,
            'estatus' => usado,
        );

        if(!$this->guardarJson($this->rutaFolio, $registros)){
            throw new Exception('NO fue posible guardar el folio');
        }

        return array(
            'idFolio' => (string)$nuevo,
            'folio'   => $folio,
            'anio'    => (string)$anio,
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

    // LECTURA / ESCRITURA JSON
    private function leer($ruta) {
        if (!file_exists($ruta)) return array();
        $contenido = file_get_contents($ruta);
        if ($contenido === false || trim($contenido) === '') return array();
        $datos = json_decode($contenido, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($datos)) return array();
        return $datos;
    }

    private function guardarJson($ruta, $datos) {
        $json = json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) return false;
        return file_put_contents($ruta, $json) !== false;
    }

    private function normalizar($texto) {
        $texto = trim((string)$texto);
        if ($texto === '') return '';
        return mb_strtolower($texto, 'UTF-8');
    }
}