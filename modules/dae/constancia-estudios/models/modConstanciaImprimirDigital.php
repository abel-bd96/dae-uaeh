<?php

// require_once('../tcpdf_6.2.1/tcpdf.php');
$rutaTCPDF = dirname(__DIR__, 5) . DIRECTORY_SEPARATOR . 'generalesDIyS' . DIRECTORY_SEPARATOR . '_pdf' . DIRECTORY_SEPARATOR . '_tcpdf_6.2.1' . DIRECTORY_SEPARATOR . 'tcpdf.php';

if (!file_exists($rutaTCPDF)) {
    die('No se encontró TCPDF en: ' . $rutaTCPDF);
}

require_once $rutaTCPDF;
// 2. Obtener el ID y validar
$idSolicitud = isset($_GET['idSolicitud']) ? $_GET['idSolicitud'] : null;
if (!$idSolicitud) {
    die("No se especificó la solicitud a imprimir.");
}

// 3. Buscar la constancia en el JSON (Modelo)
$rutaContenido = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'alumnos' . DIRECTORY_SEPARATOR . 'constancia-estudios-alumnos' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'ae_Constancia_Contenido.json';

if (!file_exists($rutaContenido)) {
    die("No se encontró el archivo de contenido.");
}

$data = file_get_contents($rutaContenido);
$todasLasConstancias = json_decode($data, true);
$constancias = null;

if (!empty($todasLasConstancias)) {
    foreach ($todasLasConstancias as $c) {
        if (isset($c['idSolicitud']) && (string)$c['idSolicitud'] === (string)$idSolicitud) {
            $constancias = $c;
            break;
        }
    }
}

if (!$constancias) {
    die("No se encontró la constancia para esta solicitud.");
}

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('ConstanciaEstudios');
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetAutoPageBreak(TRUE, 1);

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'vtaConstanciaDigitalPDF.php';

// 6. Imprimir el PDF
$pdf->Output('Constancia_' . $constancias['numeroCuenta'] . '.pdf', 'I');
// $pdf->Output('Constancia-Estudios.pdf')
?>