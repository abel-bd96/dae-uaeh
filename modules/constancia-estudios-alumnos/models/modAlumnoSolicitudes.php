<?php

header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'clsAlumnoSolicitudes.php';

$consulta = new clsAlumnoSolicitudes();
$accion = isset($_REQUEST['accion']) ? trim((string) $_REQUEST['accion']) : 'listar';

try {
    switch ($accion) {
        case 'listar':
            $respuesta = $consulta->listar();
            break;

        case 'consultar':
            $folio = isset($_REQUEST['folio']) ? $_REQUEST['folio'] : '';
            $respuesta = $consulta->consultarPorFolio($folio);
            break;

        case 'cancelar':
            $folio = isset($_POST['folio']) ? $_POST['folio'] : '';

            $respuesta = $consulta->cancelar($folio);
            break;

        case 'guardar':
            $tipoFirma = isset($_POST['tipoFirma']) ? trim($_POST['tipoFirma']) : '';
            $calificaciones = isset($_POST['calificaciones']) ? trim($_POST['calificaciones']) : '';
            if ($tipoFirma === '') {
                throw new InvalidArgumentException('El tipo de firma es obligatorio.');
            }
            if ($calificaciones === '') {
                throw new InvalidArgumentException('La selección de calificaciones es obligatoria.');
            }
            $respuesta = $consulta->guardar($tipoFirma, $calificaciones);
            break;

        default:
            throw new Exception('La acción solicitada no es válida.');
    }

    echo json_encode(
        array('ok' => true, 'datos' => $respuesta),
        JSON_UNESCAPED_UNICODE
    );
} catch (Exception $excepcion) {
    http_response_code(400);

    echo json_encode(
        array(
            'ok' => false,
            'mensaje' => $excepcion->getMessage()
        ),
        JSON_UNESCAPED_UNICODE
    );
}