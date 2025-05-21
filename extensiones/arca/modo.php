<?php

$prueba = "PRODUCCION"; //DEVELOPER o PRODUCCION
$item = "nombre";
$valor = "ventas";
$registro = ControladorComprobantes::ctrMostrarComprobantes($item, $valor);
$puntoVenta = $registro["cabezacomprobante"];
$PTOVTA = intval($puntoVenta); 
if($prueba=="PRODUCCION"){

	// MODO DE PRODUCCION

	$CUIT = 30584197680;
	$MODO = Wsaa::MODO_PRODUCCION;
	

}

if($prueba=="DEVELOPER"){

	// MODO DE PRUEBA
	$CUIT = 20241591310;
	$MODO = Wsaa::MODO_HOMOLOGACION;
}

?>