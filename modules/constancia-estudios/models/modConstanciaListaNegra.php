<?php

header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'clsConstanciaListaNegra.php';

$consulta = new clsConstanciaListaNegra();
$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'listar';

try {
    if (in_array($accion, ['guardar', 'actualizar', 'validarCuenta']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new Exception('Esta acción requiere POST.');}
    switch ($accion) {

        case 'numeroCuenta':
            $respuesta = $consulta->consultarNumeroCuenta(isset($_REQUEST['texto']) ? $_REQUEST['texto'] : '');
            break;
        case 'estatus':
            $respuesta = $consulta->consultarEstatus(isset($_REQUEST['texto']) ? $_REQUEST['texto'] : '');
            break;
        case 'listar':
            $respuesta = $consulta->listar();
            break;   
        case 'validarCuenta':
            $respuesta = $consulta->validarCuenta(isset($_REQUEST['NumeroCuenta']) ? $_REQUEST['NumeroCuenta']: '');
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
