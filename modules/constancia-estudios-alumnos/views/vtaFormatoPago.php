<?php
$data = file_get_contents('../data/finanzas_Pago.json');

$formatoPago = json_decode($data, true);

if (!empty($formatoPago)) {
    foreach ($formatoPago as $formatoPagos) {

        //Tipografía de Título
        $pdf->SetFont('helvetica', 'B', 12);

        $pdf->AddPage('P', array(215.9, 279.4));

        $txtTituloFormatoPago = '<div style="text-align: center"> Formato de Pago</div>';

        $pdf->writeHTMLCell(100, '', 96, 40, $txtTituloFormatoPago, 0, 0, false, true, 'C', false);

        //Tipografía contenido
        $pdf->SetFont('helvetica', '', 10);

        $txtfolio = '<div style="text-align: left"> Folio: CE/' . $formatoPagos['folio'] . '/' . $formatoPagos['anio'] . '</div>';
        $pdf->writeHTMLCell(100, '', 18, 40, $txtfolio, 0, 1, false, true, 'L', false);

        $txtFechaEmision = '<div style="text-align: justify">' . $formatoPagos['fechaEmision'] . '</div>';
        $pdf->writeHTMLCell(150, '', 60, 60, $txtFechaEmision, 0, 1, false, true, 'J', false);

        $txtApellidoPaterno = '<div style="text-align: justify"> Apellido Paterno: ' . $formatoPagos['apellidoPaterno'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 80, $txtApellidoPaterno, 0, 1, false, true, 'J', false);

        $txtApellidoMaterno = '<div style="text-align: justify"> Apellido Materno: ' . $formatoPagos['apellidoMaterno'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 100, $txtApellidoMaterno, 0, 1, false, true, 'J', false);

        $txtAlumnoNombre = '<div style="text-align: justify"> Nombre: ' . $formatoPagos['nombre'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 120, $txtAlumnoNombre, 0, 1, false, true, 'J', false);

        //Tipografía de Título
        $pdf->SetFont('helvetica', 'B', 12);
    }
}
?>
