<?php

require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";
require_once "../controladores/apostillas.controlador.php";
require_once "../modelos/apostillas.modelo.php";

class AjaxApostilla{

	/*=============================================
	        TRAER APOSTILLAS
	=============================================*/	
	public function ajaxVentasApostilla(){

		$venta = ControladorVentas::ctrMostrarVentas('id', $_POST["idVenta"]);
		
		$listaProductos = json_decode($venta["productos"], true);
		$apostillas=Array();
		$items = 0;

		foreach ($listaProductos as $key => $value) {

			if ($value['idnrocomprobante'] == 51){
				
				if ($value["folio1"] == $value["folio2"]){
					
					$apostillas[$items]=array(
						'idVenta' => $venta["id"],
						'nro_factura'=>$venta['codigo'],
						'cantidad' => 1,
						'descripcion' => $value["descripcion"],
						'folio'=>$value["folio1"]);
					
					$items++;

				}else{
					
					for ($i=0; $i < ($value["folio2"]-$value["folio1"])+1 ; $i++) { 
						
						$apostillas[$items]=array(
							'idVenta' => $venta["id"],
							'nro_factura'=>$venta['codigo'],
							'cantidad' => 1,
							'descripcion' => $value["descripcion"],
							'folio'=>$value["folio1"]+$i);
							$items++;
					}
				}
				
			}
			
		}
		
		echo json_encode($apostillas);
		
	}
	/*=============================================
	        MOSTRAR Apostilla
	=============================================*/	
	public $idApostilla;

	public function ajaxVerApostilla(){
		 //TRAER TODAS LAS APOSTILLAS
		 $item = 'id' ;
		 $valor =  $this->idApostilla;
		 
		 $apostillas = ControladorApostillas::ctrMostrarApostillas($item, $valor);
		 echo json_encode($apostillas);
	}

}

/*=============================================
MOSTRAR VENTA
=============================================*/	
if(isset($_POST["idVenta"])){

	$delegaciones = new AjaxApostilla();
	$delegaciones -> ajaxVentasApostilla();
}
/*=============================================
MOSTRAR Apostilla
=============================================*/	
if(isset($_POST["idApostilla"])){

	$apostillas = new AjaxApostilla();
	$apostillas -> idApostilla = $_POST["idApostilla"];
	$apostillas -> ajaxVerApostilla();
}

