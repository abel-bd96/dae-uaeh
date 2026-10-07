<?php
include '../../views/header.php';
include '../../views/sidebar.php';

$vta = $_GET['vta'] ?? 'index';

if ($vta === 'CrearSolicitud') {
    include '../../modules/constancia-estudios/views/vtaCrearSolicitud.php';
} else {
    include '../../modules/constancia-estudios/views/vtaGenerarSolicitud.php';
}
// include '../../modules/constancia-estudios/views/vtaCiclos.php'; 
// include '../../modules/constancia-estudios/views/vtaListaNegra.php';
// include '../../modules/constancia-estudios/views/vtaGenerarSolicitud.php';
// include '../../modules/constancia-estudios/views/vtaCrearSolicitud.php';

?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../modules/constancia-estudios/controllers/ajxCiclos.js"></script>
<script src="../../modules/constancia-estudios/controllers/ajxListaNegra.js"></script>
<script src="../../modules/constancia-estudios/controllers/ajxGenerarSolicitud.js"></script>

<?php include '../../views/footer.php';?>