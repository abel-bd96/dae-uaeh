<?php
    $data = file_get_contents(dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'ae_Constancia_Contenido.json');

    $constancia = json_decode($data, true);

    if(!empty($constancia)){
        foreach ($constancia as $constancias) {

        //Tipografía de Título
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetMargins(2, 2, 2);

        $pdf->AddPage('P', array(215.9, 279.4));

        $logoUAEH = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'logo_uaeh.png';

        if (file_exists($logoUAEH)) {
            $pdf->Image($logoUAEH, 10, 10, 80, 0, 'PNG'); //0 produce un alto automático
        }

        $txtTituloConstancia = '<div style="text-align: center"> Constancia de Estudios</div>';            
        $pdf->writeHTMLCell(100, '', 96, 50, $txtTituloConstancia, 0, 0, false, true, 'C', false);

        //Tipografía contenido
        $pdf->SetFont('helvetica', '', 10);

        $txtfolio = '<div style="text-align: left"> Folio: CE/' . $constancias['folio'] . '/' . date("Y") . '</div>';
        $pdf->writeHTMLCell(100, '', 18, 80, $txtfolio, 0, 1, false, true, 'L', false);

        //Mensaje predeterminado mostrado al principio de cada constancia
        $txtMensajePredeterminado = '<div style="text-align: left"> A quien corresponda:</div> <br>
                                    <p> Con fundamento en lo estipulado por la Universidad Autónoma del Estado de Hidalgo,
                                    Dra. Ojuky del Rocío Islas Maldonado hace constar la siguiente información:</p>';
        $pdf->writeHTMLCell(860, '', 60, 87, $txtMensajePredeterminado, 0, 0, false, true, 'J', false);

        $txtApellidoPaterno = '<div style="text-align: justify"> Apellido Paterno: ' . $constancias['apellidoPaterno'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 94, $txtApellidoPaterno, 0, 1, false, true, 'J', false);

        $txtApellidoMaterno = '<div style="text-align: justify"> Apellido Materno: ' . $constancias['apellidoMaterno'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 101, $txtApellidoMaterno, 0, 1, false, true, 'J', false);

        $txtAlumnoNombre = '<div style="text-align: justify"> Nombre: ' . $constancias['nombre'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 108, $txtAlumnoNombre, 0, 1, false, true, 'J', false);

        $txtCURP = '<div style="text-align: justify"> CURP: ' . $constancias['CURP'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 115, $txtCURP, 0, 1, false, true, 'J', false);
        
        $txtNumeroCuenta = '<div style="text-align: left"> Número de Cuenta: ' . $constancias['numeroCuenta'] . '</div>';
        $pdf->writeHTMLCell(100, '', 60, 122, $txtNumeroCuenta, 0, 1, false, true, 'L', false);

        $txtProgramaEducativo = '<div style="text-align: justify"> Programa Educativo' . $constancias['programaEducativo'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 129, $txtProgramaEducativo, 0, 1, false, true, 'J', false);

        $txtUnidadAcademica = '<div style="text-align: justify"> Unidad Académica: ' . $constancias['unidadAcademica'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 136, $txtUnidadAcademica, 0, 1, false, true, 'J', false);

        $txtCCTUnidadAcademica = '<div style="text-align: left"> CCT Unidad Académica: ' . $constancias['cctUnidadAcademica'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 143, $txtCCTUnidadAcademica, 0, 1, false, true, 'L', false);

        $txtCCTUniversidad = '<div style="text-align: left"> CCT UAEH: ' . $constancias['cctUniversidad'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 150, $txtCCTUniversidad, 0, 1, false, true, 'L', false);

        //Puede ser Cursando || Inscrito
        $txtEstatusAlumno = '<div style="text-align: justify"> Estatus del Alumno: ' . $constancias['estatusAlumno'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 157, $txtEstatusAlumno, 0, 1, false, true, 'J', false);
        
        $txtCalidadAlumno = '<div style="text-align: justify"> Calidad del Alumno: ' . $constancias['calidadAlumno'] . '</div>';
        $pdf->writeHTMLCell(150, '', 60, 164, $txtCalidadAlumno, 0, 1, false, true, 'J', false);
        
        $txtTipoIngreso = '<div style="text-align: justify"> Tipo de Ingreso: ' . $constancias['tipoIngreso'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 171, $txtTipoIngreso, 0, 1, false, true, 'J', false);

        $txtSemestre = '<div style="text-align: justify"> Semestre: ' . $constancias['semestre'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 178, $txtSemestre, 0, 1, false, true, 'J', false);

        $txtDuracionProgramaEducativo = '<div style="text-align: justify"> Duración del Programa Educativo: ' . $constancias['duracionProgramaEducativo'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 185, $txtDuracionProgramaEducativo, 0, 1, false, true, 'J', false);

        $txtFechaIngreso = '<div style="text-align: justify"> Fecha de Ingreso: ' . $constancias['fechaIngreso'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 192, $txtFechaIngreso, 0, 1, false, true, 'J', false);

        $txtFechaEgreso = '<div style="text-align: justify"> Fecha de Egreso: ' . $constancias['fechaEgreso'] . '</div>';
        $pdf->writeHTMLCell(100, '', 60, 199, $txtFechaEgreso, 0, 1, false, true, 'J', false);

        $txtCicloEscolarConstancia = '<div style="text-align: justify"> Ciclo Escolar: ' . $constancias['cicloEscolarConstancia'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 206, $txtCicloEscolarConstancia, 0, 1, false, true, 'J', false);

        $txtPeriodoClases = '<div style="text-align: justify"> Periodo de Clases: ' . $constancias['periodoClases'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 213, $txtPeriodoClases, 0, 1, false, true, 'J', false);

        $txtPeriodoVacacional = '<div style="text-align: justify"> Periodo Vacacional: ' . $constancias['periodoVacacional'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 220, $txtPeriodoVacacional, 0, 1, false, true, 'J', false);

        $txtPromedioGeneral = '<div style="text-align: left"> Promedio General: ' . $constancias['promedioGeneral'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 227, $txtPromedioGeneral, 0, 1, false, true, 'L', false);

        $txtAvanceProgramaEducativo = '<div style="text-align: justify"> Avance en el Programa Educativo: ' . $constancias['avanceProgramaEducativo'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 234, $txtAvanceProgramaEducativo, 0, 1, false, true, 'J', false);

        $txtCampoAdicional = '<div style="text-align: justify">' . $constancias['campoAdicional'] . '</div>';
        $pdf->writeHTMLCell(150, '', 60, 241, $txtCampoAdicional, 0, 1, false, true, 'J', false);

        $txtInformacionAdicional = '<div style="text-align: justify">' . $constancias['informacionAdicional'] . '</div>';
        $pdf->writeHTMLCell(150, '', 110, 248, $txtInformacionAdicional, 0, 1, false, true, 'J', false);

        //Espacio para la firma
        $txtFirmaDigital = '<div style="text-align: center"> Firma: </div>';
        $pdf->writeHTMLCell(180, '', 96, 265, $txtFirmaDigital, 1, 0, false, true, 'C', false);

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
?>