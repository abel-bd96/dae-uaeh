<?php

//$rutaTCPDF = dirname(__DIR__, 5) . DIRECTORY_SEPARATOR . 'GeneralesDIyS' . DIRECTORY_SEPARATOR . '_pdf' . DIRECTORY_SEPARATOR . '_tcpdf_6.2.1' . DIRECTORY_SEPARATOR . 'tcpdf.php';

$rutaTCPDF = ('..../GeneralesDIyS/_pdf/_tcpdf_6.2.1/tcpdf.php');

require_once $rutaTCPDF;

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdfNombre = "ConstanciaEstudios";

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle($pdfNombre);

$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);

$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetAutoPageBreak(TRUE, 1);

require('../vista/vtaConstanciaDigital.php');

$pdf->Output('Constancia-Estudios.pdf')
?>