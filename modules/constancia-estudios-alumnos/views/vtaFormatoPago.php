<?php
/*
declare(strict_types=1);
require __DIR__ . '/vendor/autoload.php';

use TCPDF;

class MyTCPDF extends TCPDF
{
    public $headerImg = '';
    public $footerImg = '';

    public function Header()
    {
        if ($this->headerImg && file_exists($this->headerImg)) {
            $this->Image($this->headerImg, 15, 8, 170, 0, '', '', 'T');
        }
        // Bloque institucional
        $this->SetY(30);
        $this->SetFont('dejavusans', 'B', 12);
        $this->SetTextColor(0, 51, 102); // azul oscuro
        $this->Cell(0, 10, 'Instituto de Ciencias Básicas e Ingeniería', 0, 1, 'C');
        $this->Ln(5);
    }

    public function Footer()
    {
        $this->SetY(-25);
        if ($this->footerImg && file_exists($this->footerImg)) {
            $this->Image($this->footerImg, 15, $this->GetY(), 170, 0, '', '', 'T');
        }
        $this->SetFont('dejavusans', '', 8);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

// --- Crear PDF ---
$pdf = new MyTCPDF();
$pdf->headerImg = __DIR__ . '/assets/header.png';
$pdf->footerImg = __DIR__ . '/assets/footer.png';
$pdf->SetMargins(15, 50, 15);
$pdf->SetAutoPageBreak(true, 25);
$pdf->SetFont('dejavusans', '', 10);
$pdf->AddPage();

// --- Leer JSON ---
$data = json_decode(file_get_contents(__DIR__ . '/ae_Datos_Constancia.json'), true);
$record = $data[0];

// --- Contenido con diseño ---
$html = '<h2 style="color:#003366; text-align:center;">Constancia de Estudios</h2>';
$html .= '<table cellpadding="6" cellspacing="0" border="1" style="width:100%; border-color:#003366;">
<tr style="background-color:#e6f0ff;">
<td><strong>Folio</strong></td><td>' . htmlspecialchars($record['Folio']) . '</td>
<td><strong>Estado</strong></td><td>' . htmlspecialchars($record['Estado']) . '</td>
</tr>
<tr>
<td><strong>Nombre</strong></td><td colspan="3">' . htmlspecialchars($record['Nombre'] . ' ' . $record['ApellidoPaterno'] . ' ' . $record['ApellidoMaterno']) . '</td>
</tr>
<tr>
<td><strong>CURP</strong></td><td>' . htmlspecialchars($record['CURP']) . '</td>
<td><strong>Cuenta</strong></td><td>' . htmlspecialchars($record['Cuenta']) . '</td>
</tr>
</table>';

$pdf->writeHTML($html, true, false, true, false, '');

// --- Campos rellenables ---
$pdf->Ln(10);
$pdf->SetFont('dejavusans', '', 10);
$pdf->Cell(40, 10, 'Firma del alumno:', 0, 0);
$pdf->TextField('firma_alumno', 80, 10); // campo de texto para firma

$pdf->Ln(15);
$pdf->Cell(40, 10, 'Observaciones:', 0, 0);
$pdf->TextField('observaciones', 120, 30); // campo de texto grande

// --- Firma digital ---
$certificate = 'file://' . __DIR__ . '/mi_certificado.p12';
$certPassword = '123456'; // tu contraseña
$pdf->setSignature($certificate, $certificate, $certPassword, '', 2, ['Name' => 'Instituto UAEH', 'Location' => 'Pachuca', 'Reason' => 'Validación de constancia']);

// Añadir imagen de firma visible
$pdf->Image(__DIR__ . '/assets/firma.png', 150, 220, 40, 20, 'PNG');
$pdf->setSignatureAppearance(150, 220, 40, 20);

// --- Salida ---
$pdf->Output(__DIR__ . '/constancia_firmada.pdf', 'F');
*/