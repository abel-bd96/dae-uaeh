<?php

$rutaTCPDF = dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'generalesDIyS' . DIRECTORY_SEPARATOR . '_pdf' . DIRECTORY_SEPARATOR . '_tcpdf_6.2.1' . DIRECTORY_SEPARATOR . 'tcpdf.php';

if (!is_file($rutaTCPDF)) {
    throw new RuntimeException('No se encontró TCPDF en la ruta: ' . $rutaTCPDF);
}

require_once $rutaTCPDF;

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdfNombre = "Formato de Pago";

require('../views/vtaFormatoPago.php');

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle($pdfNombre);

$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);

$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetAutoPageBreak(TRUE, 1);

$pdf->Output('Formato de Pago.pdf', 'D')
?>