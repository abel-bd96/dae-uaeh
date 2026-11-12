<?php

header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'clsConstanciaGenerarSolicitud.php';

$consulta = new clsConstanciaGenerarSolicitud();
$accion = isset($_REQUEST['accion']) ? $_REQUEST['accion'] : 'listar';

/**
 * Valida la respuesta de la clase: si trae ok=false lanza excepción;
 * si trae 'datos' devuelve solo eso, si no, el resultado completo.
 */
function validarResultado(array $resultado) {
    if (isset($resultado['ok']) && $resultado['ok'] === false) {
        throw new Exception(
            isset($resultado['mensaje']) ? $resultado['mensaje'] : 'No fue posible completar la operación.'
        );
    }
    return isset($resultado['datos']) ? $resultado['datos'] : $resultado;
}

/** Lee un parámetro de $_REQUEST con trim y default ''. */
function requestId($clave = 'id') {
    return isset($_REQUEST[$clave]) ? trim((string)$_REQUEST[$clave]) : '';
}

try {
    switch ($accion) {
        case 'listar':
            $respuesta = $consulta->listar();
            break;

        case 'consultar':
            $respuesta = $consulta->consultar(requestId());
            if ($respuesta === null) {
                throw new Exception('No se encontró la solicitud seleccionada.');
            }
            break;

        case 'numeroCuenta':
            // Guarda: si el texto viene vacío, no recorremos los JSON completos.
            $texto = isset($_REQUEST['texto']) ? trim((string)$_REQUEST['texto']) : '';
            if ($texto === '') {
                $respuesta = array();
                break;
            }
            $respuesta = $consulta->consultarNumeroCuenta($texto);
            break;

        case 'guardar':
        case 'actualizar':
            $respuesta = validarResultado($consulta->$accion($_POST));
            break;

        case 'pagar':
            $respuesta = validarResultado($consulta->pagar(requestId()));
            break;

        case 'procesarPagoConfirmado':
            $respuesta = $consulta->procesarPagoConfirmado(
                isset($_REQUEST['idSolicitud']) ? trim($_REQUEST['idSolicitud']) : ''
            );
            break;

        case 'elaborar':
            $respuesta = validarResultado($consulta->elaborar(requestId()));
            break;

        case 'mandarFirma':
            $respuesta = validarResultado($consulta->mandarFirma(requestId()));
            break;

        case 'emitir':
            $respuesta = validarResultado($consulta->emitir(requestId()));
            break;

        case 'entregar':
            $respuesta = validarResultado($consulta->entregar(requestId()));
            break;

        case 'cancelar':
            $respuesta = validarResultado($consulta->cancelar(requestId()));
            break;

        case 'observacion':
            $respuesta = $consulta->consultarObservacion();
            break;

        case 'datoAdicional':
            $respuesta = $consulta->consultarDatoAdicional();
            break;

        case 'cambiarEstatusMasivo':
            // La clase devuelve {procesados, omitidos, mensaje} sin 'ok'.
            $respuesta = $consulta->cambiarEstatusMasivo($_POST);
            break;

        case 'contenido':
            $respuesta = $consulta->consultarContenido(requestId());
            if ($respuesta === null) {
                throw new Exception('No se encontró el contenido de la constancia.');
            }
            break;

        case 'actualizarObservaciones':
            $id  = isset($_POST['id']) ? $_POST['id'] : '';
            $obs = isset($_POST['Observaciones']) ? $_POST['Observaciones'] : '[]';
            $respuesta = validarResultado($consulta->actualizarObservaciones($id, $obs));
            break;

        case 'consultarPago':
            $respuesta = $consulta->consultarEstadoPago(
                isset($_REQUEST['idSolicitud']) ? trim($_REQUEST['idSolicitud']) : ''
            );
            break;
        case 'listarCiclosDisponibles':
            $respuesta = $consulta->listarCiclosDisponibles();
            break;
        case 'validarSolicitudActiva':
            // Delegamos a la clase: valida ciclo activo + consultarSolicitudExistente.
            $IdKardex = isset($_REQUEST['IdKardex']) ? trim($_REQUEST['IdKardex']) : '';
            $respuesta = $consulta->validarSolicitudActivaPorKardex($IdKardex);
            break;

        default:
            throw new Exception('La acción solicitada no es válida.');
    }

    echo json_encode(array('ok' => true, 'datos' => $respuesta), JSON_UNESCAPED_UNICODE);
} catch (Throwable $excepcion) {
    http_response_code(400);
    echo json_encode(array('ok' => false, 'mensaje' => $excepcion->getMessage()), JSON_UNESCAPED_UNICODE);
}