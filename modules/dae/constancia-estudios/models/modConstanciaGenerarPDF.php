<?php
// 1. Cargar la librería TCPDF
$rutaTCPDF = dirname(__DIR__, 5) . DIRECTORY_SEPARATOR . 'generalesDIyS' . DIRECTORY_SEPARATOR . '_pdf' . DIRECTORY_SEPARATOR . '_tcpdf_6.2.1' . DIRECTORY_SEPARATOR . 'tcpdf.php';

if (!file_exists($rutaTCPDF)) {
    die('No se encontró TCPDF en: ' . $rutaTCPDF);
}
require_once $rutaTCPDF;

// 2. Obtener los IDs (puede venir uno solo o varios separados por coma)
$idSolicitudParam = isset($_GET['idSolicitud']) ? $_GET['idSolicitud'] : null;
if (!$idSolicitudParam) {
    die("No se especificó la solicitud a imprimir.");
}

$idsSolicitudes = explode(',', $idSolicitudParam);

// 3. Leer el archivo JSON (Ajusta la ruta a donde realmente está tu JSON)
$rutaContenido = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'alumnos' . DIRECTORY_SEPARATOR . 'constancia-estudios-alumnos' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'ae_Constancia_Contenido.json';

if (!file_exists($rutaContenido)) {
    // Fallback por si la ruta cambia
    $rutaContenido = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'ae_Constancia_Contenido.json';
}

$data = file_get_contents($rutaContenido);
$todasLasConstancias = json_decode($data, true);

// 4. Inicializar TCPDF (solo una vez)
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Constancias de Estudios');
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetAutoPageBreak(TRUE, 1);

$constanciasEncontradas = 0;

// 5. Iterar sobre cada ID y dibujar su constancia
foreach ($idsSolicitudes as $idSolicitud) {
    $constancias = null;

    if (!empty($todasLasConstancias)) {
        foreach ($todasLasConstancias as $c) {
            if (isset($c['idSolicitud']) && (string)$c['idSolicitud'] === (string)$idSolicitud) {
                $constancias = $c;
                break;
            }
        }
    }

    // Si encontramos la constancia, incluimos la vista que dibuja el PDF
    if ($constancias) {
        $constanciasEncontradas++;
        include dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'vtaConstanciaGenerarPDF.php';
    }
}

if ($constanciasEncontradas === 0) {
    die("No se encontraron constancias en el JSON para los IDs: " . $idSolicitudParam);
}

// 6. Salida del PDF final
$pdf->Output('Constancias_' . date('Ymd_His') . '.pdf', 'I');