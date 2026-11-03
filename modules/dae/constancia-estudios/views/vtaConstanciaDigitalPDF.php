<?php
// Tipografía de Título
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetMargins(2, 2, 2);

$pdf->AddPage('P', array(215.9, 279.4));

// Ruta del logo
$logoUAEH = dirname(__DIR__, 5) . DIRECTORY_SEPARATOR . 'dae-uaeh' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'logo_uaeh.png';
if (!file_exists($logoUAEH)) {
    $logoUAEH = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'logo_uaeh.png';
}

if (file_exists($logoUAEH)) {
    $pdf->Image($logoUAEH, 10, 10, 80, 0, 'PNG');
}

$txtTituloConstancia = '<div style="text-align: center"> Constancia de Estudios</div>';            
$pdf->writeHTMLCell(100, '', 60, 20, $txtTituloConstancia, 0, 0, false, true, 'C', false);

// Tipografía contenido
$pdf->SetFont('helvetica', '', 10);

$folioCompleto = (strpos($constancias['folio'], 'CE/') !== false) ? $constancias['folio'] : 'CE/' . $constancias['folio'] . '/' . date("Y");

$txtfolio = '<div style="text-align: left"> Folio: ' . $folioCompleto . '</div>';
$pdf->writeHTMLCell(100, '', 10, 62, $txtfolio, 0, 1, false, true, 'L', false);

$txtMensajePredeterminado = '<div style="text-align: left"> <b> A quien corresponda: Con fundamento en lo estipulado por la UAEH, se hace constar la siguiente información: </b> </div>';
$pdf->writeHTMLCell(860, '', 10, 68, $txtMensajePredeterminado, 0, 0, false, true, 'J', false);

$txtApellidoPaterno = '<div style="text-align: justify"> Apellido Paterno: ' . (isset($constancias['apellidoPaterno']) ? $constancias['apellidoPaterno'] : '') . '</div>';
$pdf->writeHTMLCell(130, '', 10, 78, $txtApellidoPaterno, 0, 1, false, true, 'J', false);

$txtApellidoMaterno = '<div style="text-align: justify"> Apellido Materno: ' . (isset($constancias['apellidoMaterno']) ? $constancias['apellidoMaterno'] : '') . '</div>';
$pdf->writeHTMLCell(130, '', 10, 84, $txtApellidoMaterno, 0, 1, false, true, 'J', false);

$txtAlumnoNombre = '<div style="text-align: justify"> Nombre: ' . (isset($constancias['nombre']) ? $constancias['nombre'] : '') . '</div>';
$pdf->writeHTMLCell(130, '', 10, 90, $txtAlumnoNombre, 0, 1, false, true, 'J', false);

$txtCURP = '<div style="text-align: justify"> CURP: ' . (isset($constancias['CURP']) ? $constancias['CURP'] : '') . '</div>';
$pdf->writeHTMLCell(130, '', 10, 96, $txtCURP, 0, 1, false, true, 'J', false);

$txtNumeroCuenta = '<div style="text-align: left"> Número de Cuenta: ' . (isset($constancias['numeroCuenta']) ? $constancias['numeroCuenta'] : '') . '</div>';
$pdf->writeHTMLCell(100, '', 10, 102, $txtNumeroCuenta, 0, 1, false, true, 'L', false);

$txtProgramaEducativo = '<div style="text-align: justify"> Programa Educativo: ' . (isset($constancias['programaEducativo']) ? $constancias['programaEducativo'] : '') . '</div>';
$pdf->writeHTMLCell(130, '', 10, 108, $txtProgramaEducativo, 0, 1, false, true, 'J', false);

$txtUnidadAcademica = '<div style="text-align: justify"> Unidad Académica: ' . (isset($constancias['unidadAcademica']) ? $constancias['unidadAcademica'] : '') . '</div>';
$pdf->writeHTMLCell(130, '', 10, 114, $txtUnidadAcademica, 0, 1, false, true, 'J', false);

$txtCCTUnidadAcademica = '<div style="text-align: left"> CCT Unidad Académica: ' . (isset($constancias['cctUnidadAcademica']) ? $constancias['cctUnidadAcademica'] : '') . '</div>';
$pdf->writeHTMLCell(130, '', 10, 120, $txtCCTUnidadAcademica, 0, 1, false, true, 'L', false);

$txtCCTUniversidad = '<div style="text-align: left"> CCT UAEH: ' . (isset($constancias['cctUniversidad']) ? $constancias['cctUniversidad'] : '') . '</div>';
$pdf->writeHTMLCell(130, '', 10, 126, $txtCCTUniversidad, 0, 1, false, true, 'L', false);

$txtEstatusAlumno = '<div style="text-align: justify"> Estatus del Alumno: ' . (isset($constancias['estatusAlumno']) ? $constancias['estatusAlumno'] : '') . '</div>';
$pdf->writeHTMLCell(130, '', 10, 132, $txtEstatusAlumno, 0, 1, false, true, 'J', false);

$txtCalidadAlumno = '<div style="text-align: justify"> Calidad del Alumno: ' . (isset($constancias['calidadAlumno']) ? $constancias['calidadAlumno'] : '') . '</div>';
$pdf->writeHTMLCell(150, '', 10, 138, $txtCalidadAlumno, 0, 1, false, true, 'J', false);

$txtTipoIngreso = '<div style="text-align: justify"> Tipo de Ingreso: ' . (isset($constancias['tipoIngreso']) ? $constancias['tipoIngreso'] : '') . '</div>';
$pdf->writeHTMLCell(130, '', 10, 144, $txtTipoIngreso, 0, 1, false, true, 'J', false);

$txtSemestre = '<div style="text-align: justify"> Semestre: ' . (isset($constancias['semestre']) ? $constancias['semestre'] : '') . '</div>';
$pdf->writeHTMLCell(130, '', 10, 150, $txtSemestre, 0, 1, false, true, 'J', false);

$txtDuracionProgramaEducativo = '<div style="text-align: justify"> Duración del Programa Educativo: ' . (isset($constancias['duracionProgramaEducativo']) ? $constancias['duracionProgramaEducativo'] : '') . '</div>';
$pdf->writeHTMLCell(130, '', 10, 156, $txtDuracionProgramaEducativo, 0, 1, false, true, 'J', false);

$txtFechaIngreso = '<div style="text-align: justify"> Fecha de Ingreso: ' . (isset($constancias['fechaIngreso']) ? $constancias['fechaIngreso'] : '') . '</div>';
$pdf->writeHTMLCell(130, '', 10, 162, $txtFechaIngreso, 0, 1, false, true, 'J', false);

$txtFechaEgreso = '<div style="text-align: justify"> Fecha de Egreso: ' . (isset($constancias['fechaEgreso']) ? $constancias['fechaEgreso'] : '') . '</div>';
$pdf->writeHTMLCell(100, '', 10, 168, $txtFechaEgreso, 0, 1, false, true, 'J', false);

$txtCicloEscolarConstancia = '<div style="text-align: justify"> Ciclo Escolar: ' . (isset($constancias['cicloEscolarConstancia']) ? $constancias['cicloEscolarConstancia'] : '') . '</div>';
$pdf->writeHTMLCell(130, '', 10, 174, $txtCicloEscolarConstancia, 0, 1, false, true, 'J', false);

$txtPeriodoClases = '<div style="text-align: justify"> Periodo de Clases: ' . (isset($constancias['periodoClases']) ? $constancias['periodoClases'] : '') . '</div>';
$pdf->writeHTMLCell(130, '', 10, 180, $txtPeriodoClases, 0, 1, false, true, 'J', false);

$txtPeriodoVacacional = '<div style="text-align: justify"> Periodo Vacacional: ' . (isset($constancias['periodoVacacional']) ? $constancias['periodoVacacional'] : '') . '</div>';
$pdf->writeHTMLCell(130, '', 10, 186, $txtPeriodoVacacional, 0, 1, false, true, 'J', false);

$txtPromedioGeneral = '<div style="text-align: left"> Promedio General: ' . (isset($constancias['promedioGeneral']) ? $constancias['promedioGeneral'] : '') . '</div>';
$pdf->writeHTMLCell(130, '', 10, 192, $txtPromedioGeneral, 0, 1, false, true, 'L', false);

$txtAvanceProgramaEducativo = '<div style="text-align: justify"> Avance en el Programa Educativo: ' . (isset($constancias['avanceProgramaEducativo']) ? $constancias['avanceProgramaEducativo'] : '') . '</div>';
$pdf->writeHTMLCell(130, '', 10, 198, $txtAvanceProgramaEducativo, 0, 1, false, true, 'J', false);

$txtCampoAdicional = '<div style="text-align: justify">' . (isset($constancias['campoAdicional']) ? $constancias['campoAdicional'] : '') . '</div>';
$pdf->writeHTMLCell(150, '', 10, 204, $txtCampoAdicional, 0, 1, false, true, 'J', false);

$txtInformacionAdicional = '<div style="text-align: justify">: ' . (isset($constancias['informacionAdicional']) ? $constancias['informacionAdicional'] : '') . '</div>';
$pdf->writeHTMLCell(150, '', 50, 204, $txtInformacionAdicional, 0, 1, false, true, 'J', false);

$txtFirmaDigital = '<div style="text-align: center"> Firma: </div>';
$pdf->writeHTMLCell(180, 40, 20, 214, $txtFirmaDigital, 1, 0, false, true, 'C', false);

$pdf->SetFont('helvetica', 'B', 12);
$pdf->AddPage('P', array(215.9, 279.4));

$txtTituloCalificaciones = '<div style="text-align: justify"> Calificaciones: </div>';
$pdf->writeHTMLCell(100, '', 60, 20, $txtTituloCalificaciones, 1, 0, false, true, 'J', false);
?>