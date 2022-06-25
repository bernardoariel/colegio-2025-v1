<?php
session_start();

include('../fpdf.php');


require_once "../../../controladores/apostillas.controlador.php";
require_once "../../../modelos/apostillas.modelo.php";

require_once "../../../controladores/parametros.controlador.php";
require_once "../../../modelos/parametros.modelo.php";

date_default_timezone_set('America/Argentina/Buenos_Aires');



class PDF_JavaScript extends FPDF {
	protected $javascript;
	protected $n_js;
	function IncludeJS($script, $isUTF8=false) {
		if(!$isUTF8)
			$script=utf8_encode($script);
		$this->javascript=$script;
	}
	function _putjavascript() {
		$this->_newobj();
		$this->n_js=$this->n;
		$this->_put('<<');
		$this->_put('/Names [(EmbeddedJS) '.($this->n+1).' 0 R]');
		$this->_put('>>');
		$this->_put('endobj');
		$this->_newobj();
		$this->_put('<<');
		$this->_put('/S /JavaScript');
		$this->_put('/JS '.$this->_textstring($this->javascript));
		$this->_put('>>');
		$this->_put('endobj');
	}

	function _putresources() {
		parent::_putresources();
		if (!empty($this->javascript)) {
			$this->_putjavascript();
		}
	}

	function _putcatalog() {
		parent::_putcatalog();
		if (!empty($this->javascript)) {
			$this->_put('/Names <</JavaScript '.($this->n_js).' 0 R>>');
		}
	}
}


class PDF_AutoPrint extends PDF_JavaScript
{
	function AutoPrint($printer='')
	{
		// Open the print dialog
		if($printer)
		{
			$printer = str_replace('\\', '\\\\', $printer);
			$script = "var pp = getPrintParams();";
			$script .= "pp.interactive = pp.constants.interactionLevel.full;";
			$script .= "pp.printerName = '$printer'";
			$script .= "print(pp);";
		}
		else
			$script = 'print(true);';
		$this->IncludeJS($script);
	}
}

function convertirLetras($texto){

	$texto = iconv('UTF-8', 'windows-1252', $texto);
	return	 $texto;

}

require_once('../../../modelos/conexion.php');


// VENTA
$item = 'id' ;
$valor =  $_GET['id'];
		 
$apostilla = ControladorApostillas::ctrMostrarApostillas($item, $valor);

// PARAMETROS
$item= "id";
$valor = 1;
$parametros = ControladorParametros::ctrMostrarParametros($item,$valor);

$importe = ModeloApostillas::mdlMostrarJsonApostilla('datosjson','nombre','apostilla_importe_STR');

$pdf = new PDF_AutoPrint($parametros['formatopagina1'],$parametros['formatopagina2'],$parametros['formatopagina3']);
$pdf->AddPage('P','A4');

$pdf -> SetFont($parametros['formatofuente1'], $parametros['formatofuente2'], $parametros['formatofuente3']); 
$pdf->Image('../../../vistas/img/afip/apostillas.jpg' , 5 ,5, 200 , 245,'JPG', '');


$nroHaya = str_repeat("0", 8 - strlen($apostilla['haya'])).$apostilla['haya'];


/*=============================================
COPIA 1
=============================================*/
//POSICION DE PUNTO DE VENTA Y NRO DE COMPROBANTE
$pdf -> SetY(18.9);
$pdf -> SetX(160);
$pdf -> SetFont('Arial','B',12);
$pdf->Cell(0,0,convertirLetras($nroHaya));
//FECHA
$pdf -> SetY(26);
$pdf -> SetX(160);
$pdf -> SetFont('Arial','',12);
$pdf->Cell(0,0,date('d/m/Y'));

//NOMBRE DE LA PERSONA
$pdf -> SetY(39);
$pdf -> SetX(75);
$pdf -> SetFont('Arial','',9);
$pdf->Cell(0,0,convertirLetras($apostilla['nombre']));
//SUMA EN LETRAS
$pdf -> SetY(44);
$pdf -> SetX(75);
$pdf -> SetFont('Arial','',9);
$pdf->Cell(0,0,convertirLetras($importe['valor'] ));



/*=============================================
			DETALLE
=============================================*/
//DESCRIPCION
$pdf -> SetY(63);
$pdf -> SetX(30);
$pdf -> SetFont('Arial','',10);
$pdf->Cell(0,0,convertirLetras('Certificación Documento:'));
//DESCRIPCION
$pdf -> SetY(63);
$pdf -> SetX(75);
$pdf -> SetFont('Arial','',10);
$pdf->Cell(0,0,convertirLetras($apostilla['descripcion']));
//DESCRIPCION
$pdf -> SetY(80);
$pdf -> SetX(47);
$pdf -> SetFont('Arial','',10);
$pdf->Cell(0,0,convertirLetras('Intervino:'));
//DESCRIPCION
$pdf -> SetY(80);
$pdf -> SetX(75);
$pdf -> SetFont('Arial','',10);
$pdf->Cell(0,0,convertirLetras($apostilla['intervino']));
//DESCRIPCION
$pdf -> SetY(103);
$pdf -> SetX(44);
$pdf -> SetFont('Arial','',10);
$pdf->Cell(0,0,convertirLetras('Apostilla Nº:'));
//DESCRIPCION
$pdf -> SetY(103);
$pdf -> SetX(80);
$pdf -> SetFont('Arial','',10);
$pdf->Cell(0,0,convertirLetras($apostilla['folio']));


/*=============================================
COPIA 2
=============================================*/
$hoja2=115;
//POSICION DE PUNTO DE VENTA Y NRO DE COMPROBANTE
$pdf -> SetY(18.9+$hoja2);
$pdf -> SetX(160);
$pdf -> SetFont('Arial','B',12);
$pdf->Cell(0,0,convertirLetras($nroHaya));
//FECHA
$pdf -> SetY(26+$hoja2);
$pdf -> SetX(160);
$pdf -> SetFont('Arial','',12);
$pdf->Cell(0,0,date('d/m/Y'));

//NOMBRE DE LA PERSONA
$pdf -> SetY(39+$hoja2);
$pdf -> SetX(75);
$pdf -> SetFont('Arial','',9);
$pdf->Cell(0,0,convertirLetras($apostilla['nombre']));
//SUMA EN LETRAS
$pdf -> SetY(44+$hoja2);
$pdf -> SetX(75);
$pdf -> SetFont('Arial','',9);
$pdf->Cell(0,0,convertirLetras($importe['valor'] ));



/*=============================================
			DETALLE
=============================================*/
//DESCRIPCION
$pdf -> SetY(63+$hoja2);
$pdf -> SetX(30);
$pdf -> SetFont('Arial','',10);
$pdf->Cell(0,0,convertirLetras('Certificación Documento:'));
//DESCRIPCION
$pdf -> SetY(63+$hoja2);
$pdf -> SetX(75);
$pdf -> SetFont('Arial','',10);
$pdf->Cell(0,0,convertirLetras($apostilla['descripcion']));
//DESCRIPCION
$pdf -> SetY(80+$hoja2);
$pdf -> SetX(47);
$pdf -> SetFont('Arial','',10);
$pdf->Cell(0,0,convertirLetras('Intervino:'));
//DESCRIPCION
$pdf -> SetY(80+$hoja2);
$pdf -> SetX(75);
$pdf -> SetFont('Arial','',10);
$pdf->Cell(0,0,convertirLetras($apostilla['intervino']));
//DESCRIPCION
$pdf -> SetY(103+$hoja2);
$pdf -> SetX(44);
$pdf -> SetFont('Arial','',10);
$pdf->Cell(0,0,convertirLetras('Apostilla Nº:'));
//DESCRIPCION
$pdf -> SetY(103+$hoja2);
$pdf -> SetX(80);
$pdf -> SetFont('Arial','',10);
$pdf->Cell(0,0,convertirLetras($apostilla['folio']));


$pdf->AutoPrint();
$pdf->Output();



?>