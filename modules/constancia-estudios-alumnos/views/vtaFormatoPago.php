<?php
$data = file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'formato_Pago.json');

$formatoPago = json_decode($data, true);

if (!empty($formatoPago)) {
    foreach ($formatoPago as $formatoPagos) {

        //Tipografía de Título
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetMargins(2, 2, 2);

        $pdf->AddPage('P', array(215.9, 279.4));

        $logoUAEH = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'logo_uaeh.png';

        if (file_exists($logoUAEH)) {
            $pdf->Image($logoUAEH, 10, 10, 80, 0, 'PNG'); //0 produce un alto automático
        }

        $txtTituloFormatoPago = '<div style="text-align: center"> Formato de Pago</div> <br> <div style="text-align: center"> Constancia de Estudios</div>';
        $pdf->writeHTMLCell(150, '', 40, 10, $txtTituloFormatoPago, 0, 0, false, true, 'C', false);

        //Tipografía contenido
        $pdf->SetFont('helvetica', '', 10);

        $txtNombreCompletoAlumno = '<div style="text-align: left"> Nombre: ' . $formatoPagos['nombreCompletoAlumno'] . '</div>';
        $pdf->writeHTMLCell(150, '', 40, 60, $txtNombreCompletoAlumno, 0, 1, false, true, 'L', false);

        $txtProgramaEducativo = '<div style="text-align: left"> Programa Educativo: ' . $formatoPagos['programaEducativo'] . '</div>';
        $pdf->writeHTMLCell(150, '', 40, 80, $txtProgramaEducativo, 0, 1, false, true, 'L', false);

        $txtCicloEscolar = '<div style="text-align: left"> Ciclo Escolar: ' . $formatoPagos['cicloEscolar'] . '</div>';
        $pdf->writeHTMLCell(150, '', 40, 100, $txtCicloEscolar, 0, 1, false, true, 'L', false);

        $txtMonto = '<div style="text-align: left"> Monto: ' . $formatoPagos['monto'] . '</div>';
        $pdf->writeHTMLCell(150, '', 40, 120, $txtMonto, 0, 1, false, true, 'L', false);

        $txtFechaLimitePago = '<div style="text-align: left"> Fecha Límite de Pago: ' . $formatoPagos['fechaLimitePago'] . '</div>';
        $pdf->writeHTMLCell(150, '', 40, 140, $txtFechaLimitePago, 0, 1, false, true, 'L', false);
    }
}
