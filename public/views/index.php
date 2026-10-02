<?php
include '../../views/header.php';
include '../../views/sidebar.php';

$vta = $_GET['vta'] ?? 'index';

if ($vta === 'solicitudNueva') {
    include '../../modules/constancia-estudios-alumnos/views/vtaSolicitudNueva.php';
} else {
    include '../../modules/constancia-estudios-alumnos/views/vtaAlumnoSolicitudes.php';
}

include '../../views/footer.php';
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!--
<script src="../../modules/constancia-estudios/controllers/ajxCiclos.js"></script>
<script src="../../modules/constancia-estudios/controllers/ajxListaNegra.js"></script>
-->
<script src="../../modules/constancia-estudios-alumnos/controllers/ajxAlumnoSolicitudes.js"></script>
<script src="../../modules/constancia-estudios-alumnos/controllers/ajxSolicitudNueva.js"></script>