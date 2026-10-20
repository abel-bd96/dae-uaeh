<?php
    $data = file_get_contents('../data/formato_Pago.json');

    $formatoPago = json_decode($data, true);

    if (!empty($formatoPago)) {
        foreach ($formatoPago as $formatoPagos) {

        //Tipografía de Título
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetMargins(4, 4, 4);

        $pdf->AddPage('P', array(215.9, 279.4));

        $txtTituloFormatoPago = '<div style="text-align: center"> Formato de Pago</div> <br> <div style="text-align: center"> Constancia de Estudios</div>';
        $pdf->writeHTMLCell(100, '', 96, 50, $txtTituloFormatoPago, 0, 0, false, true, 'C', false);

        //Tipografía contenido
        $pdf->SetFont('helvetica', '', 10);

        $txtNombreCompletoAlumno = '<div style="text-align: left"> Nombre: ' . $formatoPagos['nombreCompletoAlumno'] . '</div>';
        $pdf->writeHTMLCell(150, '', 60, 60, $txtNombreCompletoAlumno, 0, 1, false, true, 'L', false);

        $txtProgramaEducativo = '<div style="text-align: left"> Programa Educativo: ' . $formatoPagos['programaEducativo'] . '</div>';
        $pdf->writeHTMLCell(150, '', 60, 80, $txtProgramaEducativo, 0, 1, false, true, 'L', false);

        $txtCicloEscolar = '<div style="text-align: left"> Ciclo Escolar: ' . $formatoPagos['cicloEscolar'] . '</div>';
        $pdf->writeHTMLCell(150, '', 60, 100, $txtCicloEscolar, 0, 1, false, true, 'L', false);

        $txtMonto = '<div style="text-align: left"> Monto: ' . $formatoPagos['monto'] . '</div>';
        $pdf->writeHTMLCell(150, '', 60, 120, $txtMonto, 0, 1, false, true, 'L', false);

        $txtFechaLimitePago = '<div style="text-align: left"> Fecha Límite de Pago: ' . $formatoPagos['fechaLimitePago'] . '</div>';
        $pdf->writeHTMLCell(150, '', 60, 140, $txtFechaLimitePago, 0, 1, false, true, 'L', false);
        }
    }
?>
