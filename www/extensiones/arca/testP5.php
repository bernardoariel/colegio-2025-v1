<?php

include_once (__DIR__ . '/wssrpadrona5.php');
include_once (__DIR__ . '/wsaa.php');

//$CUIT = "30644259486"; // CUIT del emisor
$CUIT = "20150659303"; // CUIT del emisor
//$MODO = Wsaa::MODO_HOMOLOGACION;

$MODO = Wsaa::MODO_PRODUCCION;

echo "----------Script de prueba de AFIP WsSrPadronA5----------\n";
try {
    $afip = new WsSrPadronA5($CUIT,$MODO);
	$cuitejem = 27184742859; //
	$result = $afip->getPersona($cuitejem);
	var_dump($result);
	echo "<br>";
	echo "<br>";
	$cuitejem = 30644259486; //
	$result = $afip->getPersona($cuitejem);
	var_dump($result);
	
	
	/*
	echo  "<br>";
	$result = $afip->getPersonaByDocumento(15065930);
	print_r($result);
	*/
} catch (Exception $e) {
    echo 'Falló la ejecución: ' . $e->getMessage();
}