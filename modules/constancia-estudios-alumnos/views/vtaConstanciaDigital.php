<?php
    $data = file_get_contents('../data/ae_Datos_Constancia.json');

    $constancia = json_decode($data, true);

    if(!empty($constancia)){
        foreach ($constancia as $constancias) {

        //Tipografía de Título
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->AddPage('P', array(215.9, 279.4));

        $txtTituloConstancia = '<div style="text-align: center"> Constancia de Estudios </div>';
            
        $pdf->writeHTMLCell(46, '', 80, 40, $txtTituloConstancia, 0, 0, false, true, 'C', false);


            $txtNumeroActa = '<div style="text-align: left">N&Uacute;MERO&nbsp;'.$constancias['numeroActa'].'/'.$constancias['anioActa'].'</div>';
            $pdf->writeHTMLCell(45, '', 160, 40, $txtNumeroActa, 0, 0, false, true, 'L', false);


            $pdf->SetFont('helvetica', '', 10.5);
            $txtCelebracionEvento = '<div style="text-align: justify">'
                .$constancias['celebracionEvento'].
            '</div>';
            $pdf->writeHTMLCell(175.9, '', 20, 55, $txtCelebracionEvento, 0, 0, false, true, 'J', false);


            $pdf->SetFont('helvetica', 'B', 10.5);
            $txtAlumnoNombre = '<div style="text-align: center">'
                .$constancias['alumnoNombre'].
            '</div>';
            $pdf->writeHTMLCell(140, '', 37.9, 85, $txtAlumnoNombre, 0, 0, false, true, 'C', false);
        }
    }
    /*
    Folio - Fecha de Emisión - Estado de la solicitud - Nombre del Alumno - Apellido Paterno García - Apellido Materno - CURP - Número de Cuenta - Unidad Académica - Programa Educativo - Clave General del Centro de Trabajo - Clave Específica del Centro de Trabajo - Condición Escolar (Inscrito o cursando) - Semestre - Duración del Programa Educativo - Tipo de Ingreso - Periodo de Estudios - Periodo Vacacional - Calidad Alumno - Promedio - Avance del Programa Educativo
    */
?>