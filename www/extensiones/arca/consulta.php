<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
include_once (__DIR__ . '/wsfev1.php');
include_once (__DIR__ . '/wsfexv1.php');
include_once (__DIR__ . '/wsaa.php');
include ('modo.php');

// Ruta al archivo de log
$log = __DIR__ . '/log_afip.txt';
file_put_contents($log, "=== CONSULTA COMPROBANTE [" . date('Y-m-d H:i:s') . "] ===\n"); // Borra contenido previo

$afip = new Wsfev1($CUIT, $MODO);
$ultimo = $afip->consultarUltimoComprobanteAutorizado($PTOVTA, 11) ;
file_put_contents($log, "Último comprobante: $ultimo\n", FILE_APPEND);
file_put_contents($log, "Preparando voucher...\n", FILE_APPEND);

