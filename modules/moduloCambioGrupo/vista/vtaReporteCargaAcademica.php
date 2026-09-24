<?php
    $cargaAcademica = json_decode($_POST['reporteExcel']);
    $arrCargaAcademica = json_decode(json_encode($cargaAcademica), true);

    //$cargaAcademica = file_get_contents('../modelo/cargasEjemplo.json');
    //$arrCargaAcademica = json_decode($alumnos, true); 

    $hoy = date("d-m-Y");

    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=reporteSeguimientoCargasAcademicas_".$hoy.".xls");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);

    $htmlTabla = '';#E3FBFD

    $htmlTabla .= '<table style="font-size:20px">
        <thead>
            <tr height="42">
                <td colspan="2"><img src="https://www.uaeh.edu.mx/images/uaeh_logo_color.png" width="20%"></td>
                <td colspan="5" align="center" style="font-size: 31px"><b>'.utf8_decode("UNIVERSIDAD AUTÓNOMA DEL ESTADO DE HIDALGO").'</b></td>
            </tr>
            <tr height="40">
                <td colspan="2"></td>
                <td colspan="5" align="center" style="font-size: 28px"><b>'.utf8_decode("SECRETARÍA GENERAL").'</b></td>
            </tr>
            <tr height="38">
                <td colspan="2"></td>
                <td colspan="5" align="center" valign="middle" style="font-size: 25px"><b>'.utf8_decode("DIRECCIÓN DE ADMINISTRACIÓN ESCOLAR").'</b></td>
            </tr>
            <tr height="10"></tr>
            <tr>
                <td colspan="2"></td>
                <td><b>'.utf8_decode("Reporte: Seguimiento de Cargas Académicas").'</b></td>
                <td><b>Fecha de corte:&nbsp;'.$hoy.'</b></td>
            </tr>
            <tr height="10"></tr>
            <tr height="50" style="color: white;">
                <th style="background-color: #2196F3" scope="col" width="40">#</th>
                <th style="background-color: #2196F3" scope="col" width="210">CICLO ESCOLAR</th>
                <th style="background-color: #2196F3" scope="col">'.utf8_decode("UNIDAD ACADÉMICA").'</th>
                <th style="background-color: #2196F3" scope="col">'.utf8_decode("PROGRAMA EDUCATIVO").'</th>
                <th style="background-color: #2196F3" scope="col" width="100">SEMESTRE ALUMNO</th>
                <th style="background-color: #2196F3" scope="col" width="90">GRUPO ALUMNO</th>
                <th style="background-color: #2196F3" scope="col" width="210">NUMERO CUENTA</th>
                <th style="background-color: #2196F3" scope="col">NOMBRE ALUMNO</th>
                <th style="background-color: #2196F3" scope="col" width="130">'.utf8_decode("SEMESTRE ASIGNATURA").'</th>
                <th style="background-color: #2196F3" scope="col" width="130">GRUPO ASIGNATURA</th>
                <th style="background-color: #2196F3" scope="col" width="370">NOMBRE ASIGNATURA</th>
                <th style="background-color: #2196F3" scope="col" width="490">'.utf8_decode("UNIDAD ACADÉMICA DONDE CURSA").'</th>
                <th style="background-color: #2196F3" scope="col" width="250">PROGRAMA EDUCATIVO DONDE CURSA</th>
                <th style="background-color: #2196F3" scope="col">'.utf8_decode("CALIDAD ALUMNO").'</th>
                <th style="background-color: #2196F3" scope="col" width="110">PROMEDIO</th>
                <th style="background-color: #2196F3" scope="col" width="157">ASIGNATURAS NO ACREDITADAS</th>
            </tr>
        </thead>
    <tbody>';

    $orden = 1;
    $colorFila = '';
    $nombreAsignatura ='';

    foreach ($arrCargaAcademica as $cargaAcademica){
        if (($orden % 2) == 0) $colorFila = 'style="background-color: #E3F2FD"';
        else $colorFila = 'style="background-color: #FFFFFD"';

        if(strlen(trim($cargaAcademica['asignaturaOpcion']))>0){
            $nombreAsignatura = $cargaAcademica['asignatura'] . " " . $cargaAcademica['asignaturaOpcion'];
		}else {
            $nombreAsignatura = $cargaAcademica['asignatura'];
        }

        if($cargaAcademica['alumnoPromedio'] == 0 ){
            $cargaAcademica['alumnoPromedio'] = 0;
        }

        $htmlTabla .= '<tr>
            <th '.$colorFila.' scope="row">'.$orden.'</th>
            <td '.$colorFila.'>'.$cargaAcademica['cicloEscolar'].'</td>
            <td '.$colorFila.' style="text-align: center">'.utf8_decode($cargaAcademica['dependencia']).'</td>
            <td '.$colorFila.' style="text-align: center">'.utf8_decode($cargaAcademica['programaEducativoVersion']).'</td>
            <td '.$colorFila.' style="text-align: center">'.$cargaAcademica['alumnoPeriodo'].'</td>
            <td '.$colorFila.' style="text-align: center">'.$cargaAcademica['alumnoGrupo'].'</td>
            <td '.$colorFila.' style="text-align: center">'.$cargaAcademica['numeroCuenta'].'</td>
            <td '.$colorFila.' style="text-align: center">'.utf8_decode($cargaAcademica['alumnoNombreCompleto']).'</td>
            <td '.$colorFila.' style="text-align: center">'.$cargaAcademica['asignaturaPeriodo'].'</td>
            <td '.$colorFila.' style="text-align: center">'.utf8_decode($cargaAcademica['ofertaAsignaturaGrupo']).'</td>
            <td '.$colorFila.' style="text-align: center">'.utf8_decode($nombreAsignatura).'</td> 
            <td '.$colorFila.' style="text-align: center">'.utf8_decode($cargaAcademica['ofertaAsignaturaDependencia']).'</td>
            <td '.$colorFila.' style="text-align: center">'.utf8_decode($cargaAcademica['ofertaAsignaturaProgramaEducativoVersion']).'</td>
            <td '.$colorFila.' style="text-align: center">'.$cargaAcademica['calidadAcademica'].'</td>
            <td '.$colorFila.' style="text-align: center">'.$cargaAcademica['alumnoPromedio'].'</td>
            <td '.$colorFila.' style="text-align: center">'.$cargaAcademica['alumnoNumeroAsignaturasReprobadas'].'</td>
        </tr>';

        $orden++;
    }

    $htmlTabla .= '</tbody>
            </table>';
    
    echo $htmlTabla;
?>