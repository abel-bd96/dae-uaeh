<?php

$rutaTCPDF = dirname(__DIR__, 5) . DIRECTORY_SEPARATOR . 'generalesDIyS' . DIRECTORY_SEPARATOR . '_pdf' . DIRECTORY_SEPARATOR . '_tcpdf_6.2.1' . DIRECTORY_SEPARATOR . 'tcpdf.php';
//arriba corroborar con Explorador de archivos

if (!is_file($rutaTCPDF)) {
    throw new RuntimeException('No se encontró TCPDF en la ruta: ' . $rutaTCPDF);
}

require_once $rutaTCPDF;

$pdf = new TCPDF('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);

$pdfNombre = "Formato-de-Pago";

require(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'vtaPDFFormatoPago.php');

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle($pdfNombre);

$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);

$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(0, 0, 0);
$pdf->SetAutoPageBreak(false, 0);

$pdf->Output('Formato-de-Pago.pdf', 'D')
?>