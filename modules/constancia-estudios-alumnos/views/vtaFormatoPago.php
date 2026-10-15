<?php
$data = file_get_contents('../data/ae_Datos_Constancia.json');

$constancia = json_decode($data, true);

if (!empty($constancia)) {
    foreach ($constancia as $constancias) {

        //Tipografía de Título
        $pdf->SetFont('helvetica', 'B', 12);

        $pdf->AddPage('P', array(215.9, 279.4));

        $txtTituloConstancia = '<div style="text-align: center"> Constancia de Estudios</div>';

        $pdf->writeHTMLCell(100, '', 96, 40, $txtTituloConstancia, 0, 0, false, true, 'C', false);

        //Tipografía contenido
        $pdf->SetFont('helvetica', '', 10);

        $txtfolio = '<div style="text-align: left"> Folio: CE/' . $constancias['folio'] . '/' . $constancias['anio'] . '</div>';
        $pdf->writeHTMLCell(100, '', 18, 40, $txtfolio, 0, 1, false, true, 'L', false);

        $txtFechaEmision = '<div style="text-align: justify">' . $constancias['fechaEmision'] . '</div>';
        $pdf->writeHTMLCell(150, '', 60, 60, $txtFechaEmision, 0, 1, false, true, 'J', false);

        $txtApellidoPaterno = '<div style="text-align: justify"> Apellido Paterno: ' . $constancias['apellidoPaterno'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 80, $txtApellidoPaterno, 0, 1, false, true, 'J', false);

        $txtApellidoMaterno = '<div style="text-align: justify"> Apellido Materno: ' . $constancias['apellidoMaterno'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 100, $txtApellidoMaterno, 0, 1, false, true, 'J', false);

        $txtAlumnoNombre = '<div style="text-align: justify"> Nombre: ' . $constancias['nombre'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 120, $txtAlumnoNombre, 0, 1, false, true, 'J', false);

        //Tipografía de Título
        $pdf->SetFont('helvetica', 'B', 12);

        //Reverso para Calificaciones
        $pdf->AddPage('P', array(215.9, 279.4));

        $txtTituloCalificaciones = '<div style="text-align: justify"> Calificaciones: </div>';
        $pdf->writeHTMLCell(100, '', 60, 60, $txtTituloCalificaciones, 1, 0, false, true, 'J', false);

        //calificaciones, pendiente
        /*
        $txtCalificaciones = '<div style="text-align: justify"> </div>';
        $pdf->writeHTMLCell(180, '', 96, 175, $txtFirmaDigital, 1, 0, false, true, 'J', false);
        */
    }
}
