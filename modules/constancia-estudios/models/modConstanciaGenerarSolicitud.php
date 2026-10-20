<?php

header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'clsConstanciaGenerarSolicitud.php';

$consulta = new clsConstanciaGenerarSolicitud();
$accion = isset($_REQUEST['accion']) ? $_REQUEST['accion'] : 'listar';

try {
    switch ($accion) {
        case 'listar':
            $respuesta = $consulta->listar();
            break;

        case 'consultar':
            $id = isset($_REQUEST['id']) ? $_REQUEST['id']: '';
            $respuesta = $consulta->consultar($id);
            if ($respuesta === null) {
                throw new Exception('No se encontró la solicitud seleccionada.' );
                }
            break;    

        case 'numeroCuenta':
            $respuesta = $consulta->consultarNumeroCuenta(isset($_REQUEST['texto']) ? $_REQUEST['texto'] : '');
            break;
        case 'guardar':
        case 'actualizar':
            $metodo = $accion === 'guardar' ? 'guardar' : 'actualizar';
            $resultado = $consulta->$metodo($_POST);

            if (isset($resultado['ok']) && $resultado['ok'] === false) {
                throw new Exception(
                    isset($resultado['mensaje']) ? $resultado['mensaje'] : 'No fue posible completar la solicitud');
            }
            $respuesta = isset($resultado['datos']) ? $resultado['datos']: $resultado;
            break;
        case 'pagar':
            $resultado = $consulta->pagar($_REQUEST['id']);
            if (isset($resultado['ok']) && $resultado['ok'] === false) throw new Exception($resultado['mensaje']);
            $respuesta = $resultado;
            break;
        case 'elaborar':
            $resultado = $consulta->elaborar($_REQUEST['id']);
            if (isset($resultado['ok']) && $resultado['ok'] === false) throw new Exception($resultado['mensaje']);
            $respuesta = $resultado;
            break;
        case 'mandarFirma':
            $resultado = $consulta->mandarFirma($_REQUEST['id']);
            if (isset($resultado['ok']) && $resultado['ok'] === false) throw new Exception($resultado['mensaje']);
            $respuesta = $resultado;
            break;
        case 'emitir':
            $resultado = $consulta->emitir($_REQUEST['id']);
            if (isset($resultado['ok']) && $resultado['ok'] === false) throw new Exception($resultado['mensaje']);
            $respuesta = $resultado;
            break;
        case 'entregar':
            $resultado = $consulta->entregar($_REQUEST['id']);
            if (isset($resultado['ok']) && $resultado['ok'] === false) throw new Exception($resultado['mensaje']);
            $respuesta = $resultado;
            break;
        case 'cancelar':
            $resultado = $consulta->cancelar($_REQUEST['id']);
            if (isset($resultado['ok']) && $resultado['ok'] === false) throw new Exception($resultado['mensaje']);
            $respuesta = $resultado;
            break;
        case 'estatusValidos':
            $respuesta = $consulta->estatusValidos();
            break;
        case 'observacion':
            $respuesta = $consulta->consultarObservacion();
            break;
        case 'estatus':
            $respuesta =  $consulta->consultarEstatusSolicitud();
            break;
        case 'datoAdicional':
            $respuesta = $consulta->consultarDatoAdicional();
            break;
        case 'cambiarEstatusMasivo':
            $resultado = $consulta->cambiarEstatusMasivo($_POST);
            if (isset($resultado['ok']) && $resultado['ok'] === false) {
                throw new Exception(
                    isset($resultado['mensaje']) ? $resultado['mensaje'] : 'No fue posible actualizar los estatus.'
                );
            }
            $respuesta = $resultado;
            break;
        case 'contenido':
            $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
            $respuesta = $consulta->consultarContenido($id);
            if ($respuesta === null) {
                throw new Exception('No se encontró el contenido de la constancia.');
            }
            break;
        default:
            throw new Exception('La acción solicitada no es válida.');
    }

    echo json_encode(array('ok' => true, 'datos' => $respuesta), JSON_UNESCAPED_UNICODE);
} catch (Throwable $excepcion) {
    http_response_code(400);
    echo json_encode(array('ok' => false, 'mensaje' => $excepcion->getMessage()), JSON_UNESCAPED_UNICODE);
}