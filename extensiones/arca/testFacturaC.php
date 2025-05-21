<?php

include_once (__DIR__ . '/wsfev1.php');
include_once (__DIR__ . '/wsaa.php');

/**
* Este script sirve para probar el webservice WSFEV1 con Factura C
* Hay que indicar el CUIT con el cual vamos a realizar las pruebas
* Hay que indicar el número de comprobante correcto
* Hay que indicar un DNI válido para el receptor del comprobante
* Recordar tener todos los servicios de homologación habilitados en AFIP
* Ejecutar desde consola con "php testFacturaC.php"
*/
header('Content-Type: application/json');

if (
    !isset($_GET['cuit']) || !preg_match('/^\d{11}$/', $_GET['cuit']) ||
    !isset($_GET['ptoVta']) || !is_numeric($_GET['ptoVta']) ||
    !isset($_GET['tipoCbte']) || !is_numeric($_GET['tipoCbte'])
) {
    http_response_code(400);
    echo json_encode([
        "error" => "Parámetros requeridos: cuit (11 dígitos), ptoVta y tipoCbte (numéricos)"
    ]);
    exit;
}

$CUIT = $_GET['cuit'];
$PTOVTA = (int)$_GET['ptoVta'];
$tipocbte = (int)$_GET['tipoCbte'];
$MODO = Wsaa::MODO_HOMOLOGACION;


echo "----------Script de prueba de AFIP WSFEV1----------\n";
try {
    $afip = new Wsfev1($CUIT,$MODO);
    $cmp = $afip->consultarUltimoComprobanteAutorizado($PTOVTA,$tipocbte);
	//print_r($cmp);
	$ult = $cmp;  //["number"];
	$ult = $ult +1; 
	
	echo 'Nro. comp. a emitir: ' . $ult ."<br>";
	$voucher = Array
	(
		"idVoucher" => 1,
		"numeroComprobante" => $ult,
		"numeroPuntoVenta" => $PTOVTA,
		"cae" => 0,
		"letra" => "C",
		"fechaVencimientoCAE" => "",
		"tipoResponsable" => "Consumidor Final",
		"nombreCliente" =>  "JUAN PEREZ",
		"domicilioCliente" => "CALLE FALSA 123",
		"fechaComprobante" => "20190303",
		"codigoTipoComprobante" => $tipocbte,
		"TipoComprobante" => "Factura",
		"codigoConcepto" => 1,
		"codigoMoneda" => "PES",
		"cotizacionMoneda" => 1.000,
		"fechaDesde" => 20230717,
		"fechaHasta" => 20230717,
		"fechaVtoPago" => 20230717,
		"codigoTipoDocumento" => 96,
		"CondicionIVAReceptorId" => 5,
		"TipoDocumento" => "DNI",
		"numeroDocumento" => 18474285, // Debe ser diferente al DNI del emisor
		"importeTotal" => 121.000,
		"importeOtrosTributos" => 0.000,
		"importeGravado" => 121.000,
		"importeNoGravado" => 0.000,
		"importeExento" => 0.000,
		"importeIVA" => 0.000,
		"codigoPais" => 200,
		"idiomaComprobante" => 1,
		"NroRemito" => 0,
		"CondicionVenta" => "Efectivo",
		"canmismaMoneda" => "N",
		"items" => Array
			(
				0 => Array
					(
						"codigo" => 112233445566,
						"scanner" => 112233445566,
						"descripcion" => "Producto de prueba",
						"codigoUnidadMedida" => 7,
						"UnidadMedida" => "Unidades",
						"codigoCondicionIVA" => 1,
						"Alic" => 0,
						"cantidad" => 1.00,
						"porcBonif" => 0.000,
						"impBonif" => 0.000,
						"precioUnitario" => 121.00,
						"importeIVA" => 0.000,
						"importeItem" => 121.00,
					)
			),
		"subtotivas" => Array(),
		"Tributos" => Array(),
		"CbtesAsoc" => Array()
	);
	$result = $afip->emitirComprobante($voucher);
	print_r($result);
}catch (Exception $e) {
    echo 'Falló la ejecución: ' . $e->getMessage();
}		

	echo "--------------Ejecución WSFEV1 finalizada-----------------\n";