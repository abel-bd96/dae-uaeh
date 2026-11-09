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
    private $rutaFinanzasPago;
    private $rutaFinanzasSolicitudPago;

    private $listaEstatusSolicitud  = array('Solicitado', 'Pagado', 'Cancelado');
    private $listaEstatusConstancia = array('En elaboración', 'En firma', 'Emitida', 'Entregado', 'Finalizado', 'Cancelado');

    public function __construct(){
        $directorioDatos          = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
        $directorioDatosExternos  = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'alumnos' . DIRECTORY_SEPARATOR . 'constancia-estudios-alumnos' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;

        $this->rutaNumeroCuenta    = $directorioDatos . 'vta_siae_numero_cuenta.json';
        $this->rutaNombreAlumno    = $directorioDatos . 'vta_siae_nombre_alumno.json';
        $this->rutaDatoAdicional   = $directorioDatos . 'ae_dato_adicional.json';
        $this->rutaObservacion     = $directorioDatos . 'ae_observacion.json';
        $this->rutaSolicitud       = $directorioDatosExternos . 'ae_Solicitud_Referencia.json';
        $this->rutaConstancia      = $directorioDatosExternos . 'ae_Constancia_Referencia.json';
        $this->rutaContenido       = $directorioDatosExternos . 'ae_Constancia_Contenido.json';
        $this->rutaFolio           = $directorioDatosExternos . 'ae_Folio.json';
        $this->rutaCiclo = $directorioDatos . 'ae_ciclo.json';
        // ARCHIVOS DEL MÓDULO DE FINANZAS (MÓDULO DEL ALUMNO)
        $this->rutaFinanzasPago = $directorioDatosExternos . 'finanzas_Pago.json';
        $this->rutaFinanzasSolicitudPago = $directorioDatosExternos . 'finanzas_Solicitud_Pago.json';
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

    public function consultarAlumnoPorKardex($IdKardex){
    $IdKardex = $this->normalizar($IdKardex);

    if ($IdKardex === '') {
        return null;
    }

    $alumnos = $this->leer($this->rutaNombreAlumno);

    foreach ($alumnos as $alumno) {
        $kardex = isset($alumno['IdKardex'])
            ? $this->normalizar($alumno['IdKardex'])
            : '';

        if ($kardex !== '' && $kardex === $IdKardex) {
            return $alumno;
        }
    }

    return null;
}

    
public function consultarSolicitudExistente($IdKardex, $IdCicloEscolar)
{
    // 1. Normalizar identificadores
    $IdKardex = $this->normalizar($IdKardex);
    $IdCicloEscolar = $this->normalizar($IdCicloEscolar);

    if ($IdKardex === '' || $IdCicloEscolar === '') {
        throw new Exception(
            'Falta el Kardex o el ciclo escolar para validar la solicitud.'
        );
    }

    // 2. Leer solicitudes registradas
    $solicitudes = $this->leer($this->rutaSolicitud);
    // 3. Estados que impiden registrar otra solicitud
    $estatusActivos = array('Solicitado', 'En elaboración', 'En firma');

    // 4. Buscar coincidencia por Kardex y ciclo escolar
    foreach ($solicitudes as $solicitud) {
        $kardex = isset($solicitud['IdKardex'])
            ? $this->normalizar($solicitud['IdKardex'])
            : '';

        $ciclo = isset($solicitud['IdCicloEscolar'])
            ? $this->normalizar($solicitud['IdCicloEscolar']) : (isset($solicitud['CicloEscolar']) ? $this->normalizar($solicitud['CicloEscolar']) : '');

        $estatus = isset($solicitud['estatus'])
            ? strtolower(trim((string)$solicitud['estatus']))
            : '';

        if ($kardex === $IdKardex && $ciclo === $IdCicloEscolar && in_array($estatus, $estatusActivos, true)
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
    //Consultar estado de Pago en finanzas 
    public function consultarEstadoPago($idSolicitud){
        $idSolicitud = trim((string)$idSolicitud);
        if($idSolicitud === ''){
            throw new Exception('No se recibio el identificador de la solicitud');
        }
        //Leer la solicitud y pago
        $relaciones = $this->leer($this->rutaFinanzasSolicitudPago);
        //La relacion correspondiente 
        $idPago = '';
        
        foreach ($relaciones as $relacion){
            $idSolicitudRelacion = isset($relacion['idSolicitud']) ? trim((string)$relacion['idSolicitud']) : '';

        if($idSolicitudRelacion === $idSolicitud){
            $idPago = isset($relacion['idPago']) ? trim((string)$relacion['idPago']) : '';
            break;
        }
    }
    if ($idPago === ''){
        return array(
            'encontrado' => false,
            'idSolicitud' => $idSolicitud,
            'idPago' => '',
            'estatusPago' => 'No registrado',
            'mensaje' => 'No existe un registro de pago relacionado con esta solicitud.'
        );
    }
    //Leer los pagos registrados 
    $pagos = $this->leer($this->rutaFinanzasPago);
    // 4. Buscar el pago relacionado.
    foreach ($pagos as $pago) {

        $idPagoRegistro = isset($pago['idPago'])
            ? trim((string)$pago['idPago'])
            : '';

        if ($idPagoRegistro === $idPago) {

            $estatusPago = isset($pago['estatusPago'])
                ? trim((string)$pago['estatusPago'])
                : '';

            if ($estatusPago === '') {
                $estatusPago = 'Pendiente';
            }

            return array(
                'encontrado' => true,
                'idSolicitud' => $idSolicitud,
                'idPago' => $idPago,
                'estatusPago' => $estatusPago,
                'mensaje' => 'Se encontró el registro de pago.'
            );
        }
    }

    // Existe la relación, pero no se encontró el pago.
    return array(
        'encontrado' => false,
        'idSolicitud' => $idSolicitud,
        'idPago' => $idPago,
        'estatusPago' => 'No registrado',
        'mensaje' => 'Existe la relación financiera, pero no se encontró el pago.'
    );
}

// PROCESAR SOLICITUD CUANDO FINANZAS CONFIRME EL PAGO
public function procesarPagoConfirmado($idSolicitud)
{
    $idSolicitud = trim((string)$idSolicitud);

    if ($idSolicitud === '') {
        throw new Exception('No se recibió el identificador de la solicitud.');
    }

    // 1. Consultar el estado del pago en Finanzas.
    $pago = $this->consultarEstadoPago($idSolicitud);

    if (!$pago['encontrado']) {
        throw new Exception($pago['mensaje']);
    }

    // 2. Validar que Finanzas haya confirmado el pago.
    if (strtolower(trim($pago['estatusPago'])) !== 'pagada') {
        return array(
            'procesado' => false,
            'idSolicitud' => $idSolicitud,
            'estatusPago' => $pago['estatusPago'],
            'mensaje' => 'El pago todavía no está confirmado por Finanzas.'
        );
    }

    // 3. Leer las solicitudes registradas.
    $solicitudes = $this->leer($this->rutaSolicitud);

    $indiceSolicitud = null;

    foreach ($solicitudes as $indice => $solicitud) {
        $idRegistro = isset($solicitud['idSolicitud'])
            ? trim((string)$solicitud['idSolicitud'])
            : '';

        if ($idRegistro === $idSolicitud) {
            $indiceSolicitud = $indice;
            break;
        }
    }

    if ($indiceSolicitud === null) {
        throw new Exception('No se encontró la solicitud en el sistema.');
    }

    // 4. Verificar si ya fue procesada.
    $estatusActual = isset($solicitudes[$indiceSolicitud]['estatus'])
        ? trim((string)$solicitudes[$indiceSolicitud]['estatus'])
        : '';

    if (strtolower($estatusActual) === 'pagado') {
        return array(
            'procesado' => true,
            'repetido' => true,
            'idSolicitud' => $idSolicitud,
            'estatusPago' => 'Pagada',
            'mensaje' => 'La solicitud ya estaba marcada como Pagado.'
        );
    }

    // 5. Cambiar el estatus de la solicitud.
    $solicitudes[$indiceSolicitud]['estatus'] = 'Pagado';

    $this->guardarJson($this->rutaSolicitud, $solicitudes);

    return array(
        'procesado' => true,
        'repetido' => false,
        'idSolicitud' => $idSolicitud,
        'estatusPago' => 'Pagada',
        'estatusSolicitud' => 'Pagado',
        'mensaje' => 'El pago fue confirmado y la solicitud se actualizó correctamente.'
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
    
    public function validarSolicitudActivaPorKardex($IdKardex)
    {
        $IdKardex = $this->normalizar($IdKardex);

        if ($IdKardex === '') {
            throw new Exception('No se recibió el IdKardex del alumno.');
        }

        $cicloActivo = $this->obtenerCicloActivo();

        if (
            empty($cicloActivo) ||
            empty($cicloActivo['IdCiclo'])
        ) {
            throw new Exception(
                'No se encontró un ciclo escolar activo para solicitar constancias.'
            );
        }

        $resultado = $this->consultarSolicitudExistente(
            $IdKardex,
            $cicloActivo['IdCiclo']
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

        $IdCiclo = isset($ciclo['IdCiclo'])
            ? trim((string) $ciclo['IdCiclo'])
            : '';

        if (
            $estado === 'ACTIVO' &&
            $IdCiclo !== '' &&
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
                : (isset($s['estatus']) ? $s['estatus'] : 'Solicitado'); // Si está vacío, por defecto es Solicitado

            $s['EstatusInterno'] = $estatusInterno;
            $s['EstatusAlumno']  = $this->mapearEstatusAlumno($estatusInterno);
            $s['Folio']          = $cont && isset($cont['folio']) ? $cont['folio'] : '';
            $s['Constancia']     = $const;
            $s['Contenido']      = $cont;

            //Recuperar al alumno x kardex 
            $alumno = null;
            if(!empty($s['IdKardex'])){
                $alumnoKardex = $this->consultarAlumnoPorKardex(
                    $s['IdKardex']
                );
                if($alumnoKardex){
                    $numeroCuenta = isset($alumnoKardex['NumeroCuenta']) ? $alumnoKardex['NumeroCuenta'] : '';

                    if($numeroCuenta !== ''){
                        $datosGenerales = $this->consultarAlumno($numeroCuenta);
                        $alumno = array_merge($alumnoKardex, $datosGenerales ?: array());
                    }else{
                        $alumno = $alumnoKardex;
                    }
                }
            }
            if($alumno){
                $s['NumeroCuenta'] = $alumno['NumeroCuenta'] ?? '';
                $camposAlumno = array(
                    'NombreCompleto',
                    'Instituto',
                    'Semestre',
                    'ProgramaEducativo',
                    'CicloEscolar',
                    'IdProgramaEducativo'
                );
                foreach ($camposAlumno as $campo){
                    if(isset($alumno[$campo]) && $alumno[$campo] !== ''){
                        $s[$campo] = $alumno[$campo];
                    }
            }
            }else{
            $s['EstatusInterno'] = '';
            $s['EstatusAlumno']  = '';
            $s['Folio']          = '';
            $s['Constancia']     = '';
            $s['Contenido']      = '';
        }
        // Estatus de la solicitud.
        $estatusInterno = $const && isset($const['estatus'])
            ? $const['estatus']
            : (isset($s['estatus']) ? $s['estatus'] : '');

        $s['EstatusInterno'] = $estatusInterno;

        $s['EstatusAlumno'] = $this->mapearEstatusAlumno(
            $estatusInterno
        );

        $s['Folio'] = $cont && isset($cont['folio'])
            ? $cont['folio']
            : '';

        $s['Constancia'] = $const;
        $s['Contenido'] = $cont;

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
                    'existe' => true, 
                    'mensaje' => 'Este alumno ya tiene una solicitud activa para este Kardex en este ciclo.'
                );
    }
    // GUARDAR (crear solicitud nueva)
    public function guardar($datos) {
        if (!isset($datos['NumeroCuenta']) || trim($datos['NumeroCuenta']) === '') {
            return array('ok' => false, 'mensaje' => 'El número de cuenta es obligatorio.');
        }

        $datos = $this->normalizarObservaciones($datos);
        $solicitudes = $this->leer($this->rutaSolicitud);

        // Por Número de Cuenta y TODOS los estatus activos
        $estatusActivos = array('Solicitado', 'En elaboración', 'En firma');
        
        foreach ($solicitudes as $s) {
            $kardexExistente = isset($s['IdKardex']) ? trim((string)$s['IdKardex']) : '';
            $kardexNuevo = isset($datos['IdKardex']) ? trim((string)$datos['IdKardex']) : '';
            $estatusExistente = isset($s['estatus']) ? $s['estatus'] : '';
            
            // Si la cuenta coincide y el estatus está activo, bloqueamos
            if ($kardexNuevo !== '' && $kardexExistente === $kardexNuevo && in_array($estatusExistente, $estatusActivos, true)) {
                return array('ok' => false, 'mensaje' => 'El alumno ya tiene una solicitud activa (' . $estatusExistente . ').');
            }
        }

        $ultimoId = 0;
        foreach ($solicitudes as $s) {
            if (isset($s['idSolicitud']) && (int)$s['idSolicitud'] > $ultimoId) {
                $ultimoId = (int)$s['idSolicitud'];
            }
        }
        $nuevoId = $ultimoId + 1;

        $solicitud = array(
            'idSolicitud'              => $nuevoId,
            'IdKardex'                 => isset($datos['IdKardex']) ? $datos['IdKardex'] : '',
            'IdProgramaEducativo'      => isset($datos['IdProgramaEducativo']) ? $datos['IdProgramaEducativo'] : '',
            'fechaSolicitud'           => date('Y-m-d'),
            'estatus'                  => 'Solicitado',
            'tipoFirma'                => isset($datos['TipoFirma']) ? $datos['TipoFirma'] : 'N',
            'cantidadConstancias'      => '1',
            'contieneCalificaciones'   => isset($datos['Calificaciones']) ? $datos['Calificaciones'] : 'N',
            'informacionAdicional'     => isset($datos['DatoAdicional']) ? $datos['DatoAdicional'] : 'N',
            'IdCicloEscolar'           => isset($datos['IdCicloEscolar']) ? $datos['IdCicloEscolar'] : '',
            'NumeroCuenta'             => isset($datos['NumeroCuenta']) ? $datos['NumeroCuenta'] : '', 
            'NombreCompleto'           => isset($datos['NombreCompleto']) ? $datos['NombreCompleto'] : '', 
            'CicloEscolar'             => isset($datos['CicloEscolar']) ? $datos['CicloEscolar'] : '',
            'Instituto'                => isset($datos['Instituto']) ? $datos['Instituto'] : '',
            'Semestre'                 => isset($datos['Semestre']) ? $datos['Semestre'] : '',
            'ProgramaEducativo'        => isset($datos['ProgramaEducativo']) ? $datos['ProgramaEducativo'] : '',
            'Observaciones'            => isset($datos['Observaciones']) ? (is_string($datos['Observaciones']) ? json_decode($datos['Observaciones'], true) : $datos['Observaciones']) : array(),
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

        // Buscamos la solicitud por su ID
        $indice = -1;
        foreach ($solicitudes as $i => $s) {
            if (isset($s['idSolicitud']) && (string)$s['idSolicitud'] === (string)$id) { 
                $indice = $i; 
                break; 
            }
        }
        if ($indice === -1) return array('ok' => false, 'mensaje' => 'No se encontró la solicitud.');

        $solicitud = $solicitudes[$indice];
        if (!isset($solicitud['estatus'] || strtolower(trim($solicitud['estatus'])) !== 'pagado') {
            return array('ok' => false, 'mensaje' => 'La solicitud debe estar en estatus Pagado.');
        }

        $constancias = $this->leer($this->rutaConstancia);
        //evitar generar una constancia duplicada 
        foreach ($constancias as $constanciaExistente) {

        $mismaSolicitud =
            isset($constanciaExistente['idSolicitud']) &&
            (string)$constanciaExistente['idSolicitud'] === (string)$id;
            $estatusConstancia = isset($constanciaExistente['estatus'])
        ? strtolower(trim($constanciaExistente['estatus']))
        : '';

        if ($mismaSolicitud && $estatusConstancia !== 'cancelado') {
        

            return array(
                'ok' => true,
                'repetido' => true,
                'idConstancia' => $idConstanciaExistente,
                'mensaje' => 'La solicitud ya tiene una constancia generada.'
            );
        }
    }

        //  Generar folio
        $folioInfo = $this->generarFolio();
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
            'IdKardex'             => isset($solicitud['IdKardex']) ? $solicitud['IdKardex'] : '',
            'anio'                 => date('Y'),
            'estatus'              => 'En elaboración',
            'fechaEmision'         => '',
            'fechaEntrega'         => '',
            'fechaCaducidad'       => '',
            'fechaLimiteDescarga'  => '',
            'idFolio'              => $folioInfo['idFolio'],
        );
        $constancias[] = $constancia;

        // crear Contenido 
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

        // 4) Actualizar la Solicitud
        $solicitudes[$indice]['idConstancia'] = $idConstancia;

        // 5) Guardar todo
        $this->guardarJson($this->rutaConstancia, $constancias);
        $this->guardarJson($this->rutaContenido, $contenidos);
        $this->guardarJson($this->rutaSolicitud, $solicitudes);

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
            $folioReg = $reg['folio'];
            if (preg_match('/^CE\/(\d+)\/\d{4}$/', $folioReg, $coincidencias)) {
                $consecutivo = (int)$coincidencias[1];
            } elseif (ctype_digit($folioReg)) {
                $consecutivo = (int)$folioReg;
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
        $nuevoFolio = 'CE/' . str_pad((string)$nuevo, 2, '0', STR_PAD_LEFT) . '/' . $anio;

        $registros[] = array(
            'idFolio' => (string)$nuevo,
            'folio' => $nuevoFolio,
            'anio'    =>(string)$anio,
            'estatus' => 'usado',
        );

        if(!$this->guardarJson($this->rutaFolio, $registros)){
            throw new Exception('NO fue posible guardar el folio');
        }

        return array(
            'idFolio' => (string)$nuevo,
            'folio' => $nuevoFolio, 
            'anio'    => (string)$anio,
        );
    }
        private function construirContenido($idContenido, $idConstancia, $solicitud, $folioInfo) {
        $camposAdicionales = array();
        $informacionesAdicionales = array();

        if (!empty($solicitud['Observaciones']) && is_array($solicitud['Observaciones'])) {

            foreach ($solicitud['Observaciones'] as $observacion) {

                $nombre = isset($observacion['nombre'])
                    ? trim((string)$observacion['nombre'])
                    : '';

                $valor = isset($observacion['valor'])
                    ? trim((string)$observacion['valor'])
                    : '';

                if ($nombre !== '') {
                    $camposAdicionales[] = $nombre;
                }

                if ($valor !== '') {
                    $informacionesAdicionales[] = $valor;
                }
            }
        }

    $campoAdicional = implode("\n", $camposAdicionales);
    $infoAdicional = implode("\n", $informacionesAdicionales);

    $numCuenta = isset($solicitud['NumeroCuenta'])
        ? $solicitud['NumeroCuenta']
        : '';

    $alumno = $this->consultarAlumno($numCuenta);

        return array(
            'idConstanciaContenido'    => $idContenido,
            'idConstancia'             => $idConstancia,
            'idSolicitud'              => $solicitud['idSolicitud'],
            'folio'                    => $folioInfo['folio'], 
            'apellidoPaterno'          => isset($alumno['ApellidoPaterno']) ? $alumno['ApellidoPaterno'] : '',
            'apellidoMaterno'          => isset($alumno['ApellidoMaterno']) ? $alumno['ApellidoMaterno'] : '',
            'nombre'                   => isset($alumno['Nombre']) ? $alumno['Nombre'] : '',
            'CURP'                     => isset($alumno['CURP']) ? $alumno['CURP'] : '',
            'numeroCuenta'             => $numCuenta,
            'programaEducativo'        => isset($solicitud['ProgramaEducativo']) ? $solicitud['ProgramaEducativo'] : '',
            'unidadAcademica'          => isset($solicitud['Instituto']) ? $solicitud['Instituto'] : '',
            'cctUnidadAcademica'       => '',
            'cctUniversidad'           => '',
            'estatusAlumno'            => 'Cursando',
            'calidadAlumno'            => 'Regular',
            'tipoIngreso'              => '',
            'semestre'                 => isset($solicitud['Semestre']) ? $solicitud['Semestre'] : '',
            'duracionProgramaEducativo' => '',
            'fechaIngreso'             => '',
            'fechaEgreso'              => '',
            'cicloEscolarConstancia'   => isset($solicitud['CicloEscolar']) ? $solicitud['CicloEscolar'] : '',
            'periodoClases'            => '',
            'periodoVacacional'        => '',
            'promedioGeneral'          => '',
            'avanceProgramaEducativo'  => '',
            'campoAdicional'           => $campoAdicional,
            'informacionAdicional'     => $infoAdicional,
            'calificaciones'           => array()
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