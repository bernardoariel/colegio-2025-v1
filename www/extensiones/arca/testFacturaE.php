<?php

include_once (__DIR__ . '/wsfexv1.php');
include_once (__DIR__ . '/wsaa.php');

/**
* Este script sirve para probar el webservice WSFEXV1 con Factura E
* Hay que indicar el CUIT con el cual vamos a realizar las pruebas
* Hay que indicar el número de comprobante correcto
* Se recomienda indicar un IDCARD válido para el receptor del comprobante
* Recordar tener todos los servicios de homologación habilitados en AFIP
* Ejecutar desde consola con "php testFacturaE.php"
*/
$CUIT = "20150659303"; // CUIT del emisor
$MODO = Wsaa::MODO_HOMOLOGACION;
$PTOVTA=11;
$tipocbte=19;
echo "----------Script de prueba de AFIP WSFEXV1----------\n";
try {
    $afip = new Wsfexv1($CUIT,$MODO);
    $cmp = $afip->consultarUltimoComprobanteAutorizado($PTOVTA,$tipocbte);
	//print_r($cmp);
	$ult = $cmp;  //["number"];
	$ult = $ult +1;
	//$ult=3;
	echo 'Nro. comp. a emitir: ' . $ult ."<br>";
	$voucher = Array
	(
		"idVoucher" => 1,
		
		"numeroComprobante" => $ult,
		"numeroPuntoVenta" => $PTOVTA,
		"cae" => 0,
		"letra" => "E",
		"fechaVencimientoCAE" => "",
		"tipoResponsable" => "Cliente del Exterior",
		"nombreCliente" => "The New Corporation",
		"domicilioCliente" => "8200 Ghost Street, #520D",
		"fechaComprobante" => "20230301",
		"codigoTipoComprobante" => $tipocbte,
		"TipoComprobante" => "Factura",
		"codigoConcepto" => 1,
		"codigoMoneda" => "DOL",
		"cotizacionMoneda" => 183.0590,
		"fechaDesde" => "20230301",
		"fechaHasta" => "20230301",
		"fechaVtoPago" => "20230310",
		"fecha_Pago" => "20230310",
		"tipo_expo" => "01",
		"permiso_existente" => "N",
		"codigoTipoDocumento" => 91,
		"TipoDocumento" => "CI Extranjera",
		"numeroDocumento" => 123456789, // IDCARD
		"importeTotal" => 4928.000,
		"importeOtrosTributos" => 0.000,
		"importeGravado" => 0.000,
		"importeNoGravado" => 0.000,
		"importeExento" => 4928.000,
		"importeIVA" => 0.000,
		"codigoPais" => 212,
		"idiomaComprobante" => 2,
		"NroRemito" => 0,
		"CondicionVenta" => "Transferencia",
		"items" => Array
			(
				0 => Array
					(
						"codigo" => 100,
						"scanner" => 100,
						"descripcion" => "TEST PRODUCT",
						"codigoUnidadMedida" => 7,
						"UnidadMedida" => "Unidades",
						"codigoCondicionIVA" => 2,
						"Alic" => 0,
						"cantidad" => 1.00,
						"porcBonif" => 0.000,
						"impBonif" => 0.000,
						"precioUnitario" => 4928.00,
						"importeIVA" => 0.000,
						"importeItem" => 4928.00,
					)
			),
		"subtotivas" => Array(),
		"Tributos" => Array(),
		"CbtesAsoc" => Array(),
	);

	$result = $afip->emitirComprobante($voucher);
    print_r($result);
	
	
}catch (Exception $e) {
    echo 'Falló la ejecución: ' . $e->getMessage();
}	


echo "--------------Ejecución WSFEV1 finalizada-----------------\n";