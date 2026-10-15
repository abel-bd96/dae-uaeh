<?php
    $data = file_get_contents('../data/ae_Datos_Constancia.json');

    $constancia = json_decode($data, true);

    if(!empty($constancia)){
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

        //Mensaje predeterminado mostrado al principio de cada constancia
        $txtMensajePredeterminado = '<div style="text-align: left"> A quien corresponda:</div> <br>
                                    <p> Con fundamento en lo estipulado por la Universidad Autónoma del Estado de Hidalgo...
                                    Dra. Ojuky del Rocío Islas Maldonado hace constar la siguiente información:</p>';
        $pdf->writeHTMLCell(100, '', 96, 40, $txtMensajePredeterminado, 0, 0, false, true, 'J', false);

        $txtApellidoPaterno = '<div style="text-align: justify"> Apellido Paterno: ' . $constancias['apellidoPaterno'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 80, $txtApellidoPaterno, 0, 1, false, true, 'J', false);

        $txtApellidoMaterno = '<div style="text-align: justify"> Apellido Materno: ' . $constancias['apellidoMaterno'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 100, $txtApellidoMaterno, 0, 1, false, true, 'J', false);

        $txtAlumnoNombre = '<div style="text-align: justify"> Nombre: ' . $constancias['nombre'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 120, $txtAlumnoNombre, 0, 1, false, true, 'J', false);

        $txtCURP = '<div style="text-align: justify"> CURP: ' . $constancias['CURP'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 122, $txtCURP, 0, 1, false, true, 'J', false);
        
        $txtNumeroCuenta = '<div style="text-align: left"> Número de Cuenta: ' . $constancias['numeroCuenta'] . '</div>';
        $pdf->writeHTMLCell(100, '', 60, 124, $txtNumeroCuenta, 0, 1, false, true, 'L', false);

        $txtProgramaEducativo = '<div style="text-align: justify"> Programa Educativo' . $constancias['programaEducativo'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 126, $txtProgramaEducativo, 0, 1, false, true, 'J', false);

        $txtUnidadAcademica = '<div style="text-align: justify"> Unidad Académica: ' . $constancias['unidadAcademica'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 128, $txtUnidadAcademica, 0, 1, false, true, 'J', false);

        $txtCCTUnidadAcademica = '<div style="text-align: left"> CCT Unidad Académica: ' . $constancias['cctUnidadAcademica'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 130, $txtCCTUnidadAcademica, 0, 1, false, true, 'L', false);

        $txtCCTUniversidad = '<div style="text-align: left"> CCT UAEH: ' . $constancias['cctUniversidad'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 132, $txtCCTUniversidad, 0, 1, false, true, 'L', false);

        //Puede ser Cursando || Inscrito
        $txtEstatusAlumno = '<div style="text-align: justify"> Estatus del Alumno: ' . $constancias['estatusAlumno'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 134, $txtEstatusAlumno, 0, 1, false, true, 'J', false);
        
        $txtCalidadAlumno = '<div style="text-align: justify"> Calidad del Alumno: ' . $constancias['calidadAlumno'] . '</div>';
        $pdf->writeHTMLCell(150, '', 60, 136, $txtCalidadAlumno, 0, 1, false, true, 'J', false);
        
        $txtTipoIngreso = '<div style="text-align: justify"> Tipo de Ingreso: ' . $constancias['tipoIngreso'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 138, $txtTipoIngreso, 0, 1, false, true, 'J', false);

        $txtSemestre = '<div style="text-align: justify"> Semestre: ' . $constancias['semestre'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 140, $txtSemestre, 0, 1, false, true, 'J', false);

        $txtDuracionProgramaEducativo = '<div style="text-align: justify"> Duración del Programa Educativo: ' . $constancias['duracionProgramaEducativo'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 142, $txtDuracionProgramaEducativo, 0, 1, false, true, 'J', false);

        $txtFechaIngreso = '<div style="text-align: justify"> Fecha de Ingreso: ' . $constancias['fechaIngreso'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 144, $txtFechaIngreso, 0, 1, false, true, 'J', false);

        $txtFechaEgreso = '<div style="text-align: justify"> Fecha de Egreso: ' . $constancias['fechaEgreso'] . '</div>';
        $pdf->writeHTMLCell(100, '', 60, 146, $txtFechaEgreso, 0, 1, false, true, 'J', false);

        $txtCicloEscolarConstancia = '<div style="text-align: justify"> Ciclo Escolar: ' . $constancias['cicloEscolarConstancia'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 148, $txtCicloEscolarConstancia, 0, 1, false, true, 'J', false);

        $txtPeriodoClases = '<div style="text-align: justify"> Periodo de Clases: ' . $constancias['periodoClases'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 150, $txtPeriodoClases, 0, 1, false, true, 'J', false);

        $txtPeriodoVacacional = '<div style="text-align: justify"> Periodo Vacacional: ' . $constancias['periodoVacacional'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 152, $txtPeriodoVacacional, 0, 1, false, true, 'J', false);

        $txtPromedioGeneral = '<div style="text-align: left"> Promedio General: ' . $constancias['promedioGeneral'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 154, $txtPromedioGeneral, 0, 1, false, true, 'L', false);

        $txtAvanceProgramaEducativo = '<div style="text-align: justify"> Avance en el Programa Educativo: ' . $constancias['avanceProgramaEducativo'] . '</div>';
        $pdf->writeHTMLCell(130, '', 60, 156, $txtAvanceProgramaEducativo, 0, 1, false, true, 'J', false);

        $txtCampoAdicional = '<div style="text-align: justify">' . $constancias['campoAdicional'] . '</div>';
        $pdf->writeHTMLCell(150, '', 60, 158, $txtCampoAdicional, 0, 1, false, true, 'J', false);

        $txtInformacionAdicional = '<div style="text-align: justify">' . $constancias['informacionAdicional'] . '</div>';
        $pdf->writeHTMLCell(150, '', 110, 158, $txtInformacionAdicional, 0, 1, false, true, 'J', false);

        //Espacio para la firma
        $txtFirmaDigital = '<div style="text-align: center"> Firma: </div>';
        $pdf->writeHTMLCell(180, '', 96, 175, $txtFirmaDigital, 1, 0, false, true, 'C', false);

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