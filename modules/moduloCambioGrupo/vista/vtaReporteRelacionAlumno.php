<?php
    $alumnos = json_decode($_POST['reporteExcel']);
    $arrAlumnos = json_decode(json_encode($alumnos), true);

    $hoy = date("d-m-Y");

    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=reporteSeguimientoInscripcionReinscripcion_".$hoy.".xls");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);

    $htmlTabla = '';#E3FBFD

    $htmlTabla .= '<table style="font-size:20px">
        <thead>
            <tr height="42">
                <td colspan="2"><img src="https://www.uaeh.edu.mx/images/uaeh_logo_color.png" width="20%"></td>
                <td colspan="3" align="center" style="font-size: 31px"><b>'.utf8_decode("UNIVERSIDAD AUTÓNOMA DEL ESTADO DE HIDALGO").'</b></td>
            </tr>
            <tr height="40">
                <td colspan="2"></td>
                <td colspan="3" align="center" style="font-size: 28px"><b>'.utf8_decode("SECRETARÍA GENERAL").'</b></td>
            </tr>
            <tr height="38">
                <td colspan="2"></td>
                <td colspan="3" align="center" valign="middle" style="font-size: 25px"><b>'.utf8_decode("DIRECCIÓN DE ADMINISTRACIÓN ESCOLAR").'</b></td>
            </tr>
            <tr height="10"></tr>
            <tr>
                <td colspan="2"></td>
                <td><b>'.utf8_decode("Reporte: Seguimiento de Inscripción/Reinscripción").'</b></td>
                <td><b>Fecha de corte:&nbsp;'.$hoy.'</b></td>
            </tr>
            <tr height="10"></tr>
            <tr height="35" style="color: white;">
                <th style="background-color: #2196F3" scope="col" width="40">#</th>
                <th style="background-color: #2196F3" scope="col" width="210">CICLO ESCOLAR</th>
                <th style="background-color: #2196F3" scope="col">'.utf8_decode("UNIDAD ACADÉMICA").'</th>
                <th style="background-color: #2196F3" scope="col">'.utf8_decode("PROGRAMA EDUCATIVO").'</th>
                <th style="background-color: #2196F3" scope="col" width="100">SEMESTRE</th>
                <th style="background-color: #2196F3" scope="col" width="80">GRUPO</th>
                <th style="background-color: #2196F3" scope="col" width="210">NUMERO CUENTA</th>
                <th style="background-color: #2196F3" scope="col">NOMBRE ALUMNO</th>
                <th style="background-color: #2196F3" scope="col" width="210">'.utf8_decode("GENERACIÓN ALUMNO").'</th>
                <th style="background-color: #2196F3" scope="col" width="185">TIPO INGRESO</th>
                <th style="background-color: #2196F3" scope="col" width="170">ESTATUS ALUMNO</th>
                <th style="background-color: #2196F3" scope="col" width="205">'.utf8_decode("INSCRIPCIÓN PAGADA").'</th>
                <th style="background-color: #2196F3" scope="col" width="170">CALIDAD ALUMNO</th>
                <th style="background-color: #2196F3" scope="col">'.utf8_decode("SEMESTRES DE ANTIGÜEDAD").'</th>
                <th style="background-color: #2196F3" scope="col" width="110">PROMEDIO</th>
                <th style="background-color: #2196F3" scope="col" width="295">ASIGNATURAS ACREDITADAS</th>
                <th style="background-color: #2196F3" scope="col" width="200">'.utf8_decode("CRÉDITOS CURSADOS").'</th>
                <th style="background-color: #2196F3" scope="col" width="280">ASIGNATURAS REPROBADAS</th>
                <th style="background-color: #2196F3" scope="col" width="280">ASIGNATURAS POR ACREDITAR</th>
                <th style="background-color: #2196F3" scope="col" width="350">ASIGNATURAS PROGRAMA EDUCATIVO</th>
                <th style="background-color: #2196F3" scope="col" width="212">'.utf8_decode("CRÉDITOS POR CURSAR").'</th>
                <th style="background-color: #2196F3" scope="col" width="320">'.utf8_decode("CRÉDITOS PROGRAMA EDUCATIVO").'</th>
                <th style="background-color: #2196F3" scope="col">CURP</th>
                <th style="background-color: #2196F3" scope="col" width="215">FECHA NACIMIENTO</th>
                <th style="background-color: #2196F3" scope="col">'.utf8_decode("GÉNERO ALUMNO").'</th>
                <th style="background-color: #2196F3" scope="col">CALLE</th>
                <th style="background-color: #2196F3" scope="col">COLONIA</th>
                <th style="background-color: #2196F3" scope="col">MUNICIPIO</th>
                <th style="background-color: #2196F3" scope="col" width="210">ENTIDAD FEDERATIVA</th>
                <th style="background-color: #2196F3" scope="col" width="160">'.utf8_decode("CÓDIGO POSTAL").'</th>
                <th style="background-color: #2196F3" scope="col">'.utf8_decode("TELÉFONO").'</th>
                <th style="background-color: #2196F3" scope="col">CORREO PERSONAL</th>
                <th style="background-color: #2196F3" scope="col" width="225">CORREO INSTITUCIONAL</th>
                <th style="background-color: #2196F3" scope="col">NOMBRE PADRE O TUTOR</th>
                <th style="background-color: #2196F3" scope="col">NOMBRE MADRE</th>
            </tr>
        </thead>
    <tbody>';

    $orden = 1;
    $colorFila = '';
    $insPg = '';

    foreach ($arrAlumnos as $alumno){
        if (($orden % 2) == 0) $colorFila = 'style="background-color: #E3F2FD"';
        else $colorFila = 'style="background-color: #FFFFFD"';

        if ($alumno['inscripcionPagada'] == "Si") $insPg = "SI";
        else $insPg = "NO";

        $htmlTabla .= '<tr>
            <th '.$colorFila.' scope="row">'.$orden.'</th>
            <td '.$colorFila.'>'.$alumno['cicloEscolar'].'</td>
            <td '.$colorFila.'>'.utf8_decode($alumno['dependencia']).'</td>
            <td '.$colorFila.'>'.utf8_decode($alumno['programaEducativoVersionAnio']).'</td>
            <td '.$colorFila.' style="text-align: center">'.$alumno['periodo'].'</td>
            <td '.$colorFila.' style="text-align: center">'.$alumno['grupo'].'</td>
            <td '.$colorFila.' style="text-align: center">'.$alumno['numeroCuenta'].'</td>
            <td '.$colorFila.'>'.utf8_decode($alumno['alumnoNombreCompleto']).'</td>
            <td '.$colorFila.' style="text-align: center">'.$alumno['cohorteGeneracional'].'</td>
            <td '.$colorFila.'>'.utf8_decode($alumno['tipoIngreso']).'</td>
            <td '.$colorFila.'>'.$alumno['estatus'].'</td>
            <td '.$colorFila.' style="text-align: center">'.$insPg.'</td>
            <td '.$colorFila.'>'.$alumno['calidadAcademica'].'</td>
            <td '.$colorFila.' style="text-align: center">'.$alumno['antiguedadSemestresFechaIngresoFechaCicloEscolar'].'</td>
            <td '.$colorFila.' style="text-align: center">'.$alumno['promedio'].'</td>
            <td '.$colorFila.' style="text-align: center">'.$alumno['numeroAsignaturasAcreditadas'].'</td>
            <td '.$colorFila.' style="text-align: center">'.$alumno['totalCreditosCursados'].'</td>
            <td '.$colorFila.' style="text-align: center">'.$alumno['numeroAsignaturasReprobadas'].'</td>
            <td '.$colorFila.' style="text-align: center">'.$alumno['numeroAsignaturasPendientes'].'</td>
            <td '.$colorFila.' style="text-align: center">'.$alumno['numeroAsignaturasEgresar'].'</td>
            <td '.$colorFila.' style="text-align: center">'.$alumno['totalCreditosPendientes'].'</td>
            <td '.$colorFila.' style="text-align: center">'.$alumno['totalCreditosEgresar'].'</td>
            <td '.$colorFila.'>'.$alumno['alumnoCurp'].'</td>
            <td '.$colorFila.' style="text-align: center">'.$alumno['alumnoFechaNacimientoStr'].'</td>
            <td '.$colorFila.'>'.$alumno['alumnoGenero'].'</td>
            <td '.$colorFila.'>'.utf8_decode($alumno['domicilioCalle']).'</td>
            <td '.$colorFila.'>'.utf8_decode($alumno['domicilioColonia']).'</td>
            <td '.$colorFila.'>'.utf8_decode($alumno['domicilioMunicipio']).'</td>
            <td '.$colorFila.'>'.utf8_decode($alumno['domicilioEntidadFederativa']).'</td>
            <td '.$colorFila.' style="text-align: center">'.$alumno['domicilioCodigoPostal'].'</td>
            <td '.$colorFila.' style="text-align: left">'.$alumno['telefono'].'</td>
            <td '.$colorFila.'>'.utf8_decode($alumno['correoElectronico']).'</td>
            <td '.$colorFila.'>'.$alumno['correoElectronicoInstitucional'].'</td>
            <td '.$colorFila.'>'.$alumno['tutorNombreCompleto'].'</td>
            <td '.$colorFila.'>'.$alumno['madreNombreCompleto'].'</td>
        </tr>';

        $orden++;
    }

    $htmlTabla .= '</tbody>
            </table>';
    
    echo $htmlTabla;
?>