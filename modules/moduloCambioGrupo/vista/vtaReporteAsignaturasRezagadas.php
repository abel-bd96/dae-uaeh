<?php
    $asignaturasRezagadas = json_decode($_POST['reporteExcel']);
    $arrAsignaturasRezagadas = json_decode(json_encode($asignaturasRezagadas), true);
    $hoy = date("d-m-Y");

    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=reporteAlumnosConAsignaturasRezagadas_".$hoy.".xls");
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
                <td><b>'.utf8_decode("Reporte: Alumnos con Asignaturas Rezagadas").'</b></td>
                <td><b>Fecha de corte:&nbsp;'.$hoy.'</b></td>
            </tr>
            <tr height="10"></tr>
            <tr height="35" style="color: white;">
                <th style="background-color: #2196F3" scope="col" width="40">#</th>
                <th style="background-color: #2196F3" scope="col" width="210">CICLO ESCOLAR</th>
                <th style="background-color: #2196F3" scope="col" width="400">'.utf8_decode("UNIDAD ACADÉMICA").'</th>
                <th style="background-color: #2196F3" scope="col" width="400">PROGRAMA EDUCATIVO</th>
                <th style="background-color: #2196F3" scope="col" width="200">SEMESTRE ALUMNO</th>
                <th style="background-color: #2196F3" scope="col" width="170">GRUPO ALUMNO</th>
                <th style="background-color: #2196F3" scope="col" width="170">'.utf8_decode("NÚMERO CUENTA").'</th>
                <th style="background-color: #2196F3" scope="col" width="400">NOMBRE ALUMNO</th>
                <th style="background-color: #2196F3" scope="col" width="235">SEMESTRE ASIGNATURA NO CARGADA</th>
                <th style="background-color: #2196F3" scope="col" width="270">ASIGNATURA NO CARGADA</th>
            </tr>
        </thead>
    <tbody>';

    $orden = 1;
    $colorFila = '';

    foreach ($arrAsignaturasRezagadas as $asignaturaRezagada){
        if (($orden % 2) == 0) $colorFila = 'style="background-color: #E3F2FD"';
        else $colorFila = 'style="background-color: #FFFFFD"';

        $htmlTabla .= '<tr>
            <th '.$colorFila.' scope="row">'.$orden.'</th>
            <td '.$colorFila.'>'.$asignaturaRezagada['cicloEscolar'].'</td>
            <td '.$colorFila.'>'.utf8_decode($asignaturaRezagada['dependencia']).'</td>
            <td '.$colorFila.'>'.utf8_decode($asignaturaRezagada['programaEducativoVersion']).'</td>
            <td '.$colorFila.' style="text-align: center">'.$asignaturaRezagada['alumnoPeriodo'].'</td>
            <td '.$colorFila.' style="text-align: center">'.$asignaturaRezagada['alumnoGrupo'].'</td>
            <td '.$colorFila.' style="text-align: center">'.$asignaturaRezagada['numeroCuenta'].'</td>
            <td '.$colorFila.'>'.utf8_decode($asignaturaRezagada['alumnoNombreCompleto']).'</td>
            <td '.$colorFila.' style="text-align: center">'.$asignaturaRezagada['asignaturaPeriodo'].'</td>
            <td '.$colorFila.'>'.utf8_decode($asignaturaRezagada['asignatura']).'</td>
        </tr>';

        $orden++;
    }

    $htmlTabla .= '</tbody>
            </table>';
    
    echo $htmlTabla;
?>