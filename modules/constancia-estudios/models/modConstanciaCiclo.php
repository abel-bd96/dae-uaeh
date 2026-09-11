<?php

header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'clsConstanciaCiclo.php';

$consulta = new clsConstanciaCiclo();
$accion = isset($_REQUEST['accion']) ? $_REQUEST['accion'] : 'listar';

try {
    switch ($accion) {
        case 'ciclos':
            $respuesta = $consulta->consultarCiclos(isset($_REQUEST['texto']) ? $_REQUEST['texto'] : '');
            break;
        case 'planes':
            $respuesta = $consulta->consultarPlanes(isset($_REQUEST['texto']) ? $_REQUEST['texto'] : '');
            break;
        case 'listar':
            $respuesta = $consulta->listarConfiguraciones();
            break;
        case 'obtener':
            $configuracion = $consulta->obtenerConfiguracion($_REQUEST['tipo'], $_REQUEST['id']);
            if ($configuracion === null) {
                throw new Exception('La configuración solicitada no existe.');
            }
            $respuesta = $configuracion;
            break;
        case 'guardar':
            $respuesta = $consulta->guardar($_POST);
            break;
        case 'actualizar':
            $respuesta = $consulta->actualizar($_POST);
            break;
        default:
            throw new Exception('La acción solicitada no es válida.');
    }

    echo json_encode(array('ok' => true, 'datos' => $respuesta), JSON_UNESCAPED_UNICODE);
} catch (Exception $excepcion) {
    http_response_code(400);
    echo json_encode(array('ok' => false, 'mensaje' => $excepcion->getMessage()), JSON_UNESCAPED_UNICODE);
}
