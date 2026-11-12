<?php
header('Content-Type: text/plain; charset=utf-8');
echo "Fecha: " . date('Y-m-d H:i:s') . "\n";
echo "PHP: " . PHP_VERSION . "\n";
$ruta = __DIR__ . '/data/ae_ciclo.json';
echo "Ruta JSON: $ruta\n";
echo "Existe: " . (file_exists($ruta) ? 'SI' : 'NO') . "\n";
echo "Contenido:\n" . file_get_contents($ruta) . "\n";