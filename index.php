<?php

$vta = $_GET['vta'] ?? 'index';

if ($vta === 'solicitudNueva') {
    header('Location: modules/constancia-estudios-alumnos/views/vtaSolicitudNueva.php');
} else {
    header('Location: modules/constancia-estudios-alumnos/views/vtaAlumnoSolicitudes.php');
}
?>