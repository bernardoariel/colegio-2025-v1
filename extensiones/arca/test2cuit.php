<?php

include_once (__DIR__ . '/wsfev1.php');
include_once (__DIR__ . '/wsaa.php');

/**
* Este script sirve para probar el webservice WSFEV1 con Factura A
* Hay que indicar el CUIT con el cual vamos a realizar las pruebas
* Hay que indicar el número de comprobante correcto
* Hay que indicar un CUIT válido para el receptor del comprobante
* Recordar tener todos los servicios de homologación habilitados en AFIP
* Ejecutar desde consola con "php testFacturaA.php"
*/
$CUIT = "20241591310"; // CUIT del emisor
$MODO = Wsaa::MODO_HOMOLOGACION;
$PTOVTA=11;
$tipocbte=1;

echo "----------Script de prueba de AFIP WSFEV1----------\n";
try {
    $afip = new Wsfev1($CUIT,$MODO);
    $cmp = $afip->consultarUltimoComprobanteAutorizado($PTOVTA,$tipocbte);
	//print_r($cmp);
	$ult = $cmp;  //["number"];
	$ult = $ult +1;
	
	echo 'Nro. comp. a emitir: ' . $ult ."<br>";
	
	
}catch (Exception $e) {
    echo 'Falló la ejecución: ' . $e->getMessage();
}		
 echo "--------------Ejecución finalizada-----------------\n";