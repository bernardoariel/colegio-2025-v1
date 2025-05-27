<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('America/Argentina/Buenos_Aires');

require_once dirname(__DIR__) . "/controladores/clientes.controlador.php";
require_once dirname(__DIR__) . "/modelos/clientes.modelo.php";
require_once dirname(__DIR__) . "/controladores/escribanos.controlador.php";
require_once dirname(__DIR__) . "/modelos/escribanos.modelo.php";

class ControladorVentas{

	/*=============================================
	MOSTRAR VENTAS
	=============================================*/

	static public function ctrMostrarVentas($item, $valor){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);

		return $respuesta;

	}
	static public function ctrMostrarCuotas($item, $valor){

		$tabla = "cuotas";

		$respuesta = ModeloVentas::mdlMostrarCuotas($tabla, $item, $valor);

		return $respuesta;

	}
	public static function ctrBuscarFolio($folio) {
		// Fecha inicial definida por los datos
		$start = new DateTime("2018-08-28"); // Fecha de inicio fija (agosto 2018)
		$end = new DateTime(); // Fecha actual
		$end->modify('last day of this month'); // Extender al final del mes actual
	
		$resultados = []; // Almacenar todos los resultados
	
		while ($start <= $end) {
			// Obtener el mes actual
			$mesInicio = $start->format('Y-m-01');
			$mesFin = $start->format('Y-m-t');
	
			// Llamar al modelo para obtener ventas de este mes
			$ventas = ModeloVentas::mdlRangoFechasVentasNuevo("ventas", $mesInicio, $mesFin);
	
			// Analizar cada venta
			if (!empty($ventas) && is_array($ventas)) {
				foreach ($ventas as $venta) {
					$productos = json_decode($venta['productos'], true);
	
					// Validar que json_decode fue exitoso y devolvió un array
					if (json_last_error() === JSON_ERROR_NONE && is_array($productos)) {
						foreach ($productos as $producto) {
							// Verificar si el folio está dentro del rango
							if (isset($producto['folio1'], $producto['folio2']) &&
								$folio >= $producto['folio1'] && $folio <= $producto['folio2']
							) {
								// Generar el enlace a la factura
								$link = 'http://localhost/colegio/extensiones/fpdf/pdf/facturaElectronica.php?id=' . $venta['id'];
								$facturaLink = "<a href='$link' target='_blank'>Factura</a>";
	
								// Agregar el resultado a la lista
								$resultados[] = [
									'venta' => $venta,
									'producto' => $producto,
									'factura' => $facturaLink
								];
							}
						}
					} else {
						// Log o mensaje de error si el JSON no es válido
						error_log("Error al decodificar JSON en la venta ID: " . $venta['id']);
					}
				}
			}
	
			// Avanzar al siguiente mes
			$start->modify('+1 month');
		}
	
		// Devolver resultados o mensaje de error si no se encontraron
		if (!empty($resultados)) {
			return $resultados;
		} else {
			return ["error" => "No se encontró el folio en el rango de fechas especificado desde agosto 2018 hasta el mes actual."];
		}
	}
	
	static public function ctrMostrarVentasFecha($item, $valor){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlMostrarVentasFecha($tabla, $item, $valor);

		return $respuesta;

	}
	
	static public function ctrMostrarVentasClientes($item, $valor){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlMostrarVentasClientes($tabla, $item, $valor);

		return $respuesta;

	}

	static public function ctrMostrarVentasEscribanos($item, $valor){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlMostrarVentasEscribanos($tabla, $item, $valor);

		return $respuesta;

	}
	
	/*=============================================
	CREAR VENTA
	=============================================*/

	static public function ctrCrearVenta(){

		
		#tomo los productos
		$listaProductos = json_decode($_POST["listaProductos"], true);
		#creo un array del afip
		$items=Array();
		$apostillas=Array();
		
		#recorro $listaproductos para cargarlos en la tabla de comprobantes
		foreach ($listaProductos as $key => $value) {

		    $tablaComprobantes = "comprobantes";

		    $valor = $value["idnrocomprobante"];
		    $datos = $value["folio2"];

		    $actualizarComprobantes = ModeloComprobantes::mdlUpdateComprobante($tablaComprobantes, $valor,$datos);
		    

		    $miItem=$value["descripcion"];

			if ($value['folio1']!=1){

				$miItem.=' del '.$value['folio1'].' al '.$value['folio2'];
			}

			$items[$key]=array('codigo' => $value["id"],'descripcion' => $miItem,'cantidad' => $value["cantidad"],'codigoUnidadMedida'=>7,'precioUnitario'=>$value["precio"],'importeItem'=>$value["total"],'impBonif'=>0 );
			
			
		}
		
		
		include('../extensiones/afip/afip.php');

		/*=============================================
				GUARDAR LA VENTA
		=============================================*/	
		$tabla = "ventas";

		$fecha = date("Y-m-d");
			
		if ($_POST["listaMetodoPago"]=="CTA.CORRIENTE"){
			
			$adeuda=$_POST["totalVenta"];

			$fechapago="0000-00-00";
			
		}else{
			
			$adeuda = 0;

			$fechapago = $fecha;
		}
	
		if($ERRORAFIP==0){
			$regcomp["CondicionIVAReceptorId"] = 1;
			error_log("🧪 REGCOMP => " . print_r($regcomp, true));
			$result = $afip->emitirComprobante($regcomp); //$regcomp debe tener la estructura esperada (ver a continuación de la wiki)

			$observaciones = isset($result["observaciones"]) ? $result["observaciones"] : [];

			if ($result["code"] === Wsfev1::RESULT_OK) {
				
			/*=============================================
			FORMATEO LOS DATOS
			=============================================*/	
			
			$cantCabeza = strlen($PTOVTA); 
			switch ($cantCabeza) {
					case 1:
			          $ptoVenta="000".$PTOVTA;
			          break;
					case 2:
			          $ptoVenta="00".$PTOVTA;
			          break;
				  case 3:
			          $ptoVenta="0".$PTOVTA;
			          break;   
			}

	        $codigoFactura = $ptoVenta .'-'. $ultimoComprobante;
	        $fechaCaeDia = substr($result["fechaVencimientoCAE"],-2);
			$fechaCaeMes = substr($result["fechaVencimientoCAE"],4,-2);
			$fechaCaeAno = substr($result["fechaVencimientoCAE"],0,4);
			
			$afip=1;
	            
	        	
		    if($_POST['listaMetodoPago']=="CTA.CORRIENTE"){

				$adeuda = $_POST['totalVenta'];

			}else{

				$adeuda = 0;

			}
			$totalVenta = $_POST["totalVenta"];
			include('../extensiones/qr/index.php');

	        $datos = array(
				   "id_vendedor"=>1,
				   "fecha"=>date('Y-m-d'),
				   "codigo"=>$codigoFactura,
				   "tipo"=>'FC',
				   "id_cliente"=>$_POST['seleccionarCliente'],
				   "nombre"=>$_POST['nombreCliente'],
				   "documento"=>$_POST['documentoCliente'],
				   "tabla"=>$_POST['tipoCliente'],
				   "productos"=>$_POST['listaProductos'],
				   "impuesto"=>0,
				   "neto"=>0,
				   "total"=>$_POST["totalVenta"],
				   "adeuda"=>$adeuda,
				   "obs"=>'',
				   "cae"=>$result["cae"],
				   "fecha_cae"=>$fechaCaeDia.'/'.$fechaCaeMes.'/'.$fechaCaeAno,
				   "fechapago"=>$fechapago,
				   "metodo_pago"=>$_POST['listaMetodoPago'],
				   "referenciapago"=>$_POST['nuevaReferencia'],
				   "qr"=>$datos_cmp_base_64."="
				   );    
			
			$respuesta = ModeloVentas::mdlIngresarVenta($tabla, $datos);
			
			

	        }
		}
			
        if(isset($respuesta)){

        	if($respuesta == "ok"){

        		if($afip==1){

        			/*=============================================
					AGREGAR EL NUMERO DE COMPROBANTE
					=============================================*/
					
				  	$tabla = "comprobantes";
					$datos = $ult;
					
					ModeloVentas::mdlAgregarNroComprobante($tabla, $datos);
					$nroComprobante = substr($_POST["nuevaVenta"],8);

					//ULTIMO NUMERO DE COMPROBANTE
					$item = "nombre";
					$valor = "FC";

					
        		}
			
			  
			    if ($_POST["listaMetodoPago"]!='CTA.CORRIENTE'){

			  	  //AGREGAR A LA CAJA
				  $item = "fecha";
		          $valor = date('Y-m-d');

		          $caja = ControladorCaja::ctrMostrarCaja($item, $valor);
		         
		          
		          $efectivo = $caja[0]['efectivo'];
		          $tarjeta = $caja[0]['tarjeta'];
		          $cheque = $caja[0]['cheque'];
		          $transferencia = $caja[0]['transferencia'];

		          switch ($_POST["listaMetodoPago"]) {
		          	case 'EFECTIVO':
		          		# code...
		          		$efectivo = $efectivo + $_POST["totalVenta"];
		          		break;
		          	case 'TARJETA':
		          		# code...
		          		$tarjeta = $tarjeta + $_POST["totalVenta"];
		          		break;
		          	case 'CHEQUE':
		          		# code...
		          		$cheque = $cheque + $_POST["totalVenta"];
		          		break;
		          	case 'TRANSFERENCIA':
		          		# code...
		          		$transferencia = $transferencia + $_POST["totalVenta"];
		          		break;
		          }
		          

		          $datos = array("fecha"=>date('Y-m-d'),
		          
					             "efectivo"=>$efectivo,
					             "tarjeta"=>$tarjeta,
					             "cheque"=>$cheque,
					             "transferencia"=>$transferencia);
		          
		          $caja = ControladorCaja::ctrEditarCaja($item, $datos);
			    }
        	}
		
			  
        	if($afip==1){

        		return [
					'code' => 'FE',
					'msg' => 'Factura generada correctamente',
					'observaciones' => $observaciones
				];

			}else{

				return [
					'code' => 'ER',
					'msg' => $result["msg"]
				];

			}

		}



	}
    
	static public function ctrHomologacionVenta() {

		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

		$log = __DIR__ . '/../log_afip.txt';
		$inicio = "=== INICIO HOMOLOGACION [" . date('Y-m-d H:i:s') . "] ===\n";
		file_put_contents($log, $inicio);

		if (!isset($_POST["idVentaHomologacion"])) {
			echo json_encode(["code" => "ER", "msg" => "ID de venta no recibido"]);
			file_put_contents($log, "Falta ID de venta\n", FILE_APPEND);
			return;
		}

		try {
			file_put_contents($log, "Incluyendo: " . __DIR__ . '/../extensiones/arca/wsfev1.php' . "\n", FILE_APPEND);
			require_once __DIR__ . '/../extensiones/arca/wsfev1.php';
			require_once __DIR__ . '/../extensiones/arca/wsaa.php';
			file_put_contents($log, "Clases AFIP incluidas OK\n", FILE_APPEND);
		} catch (Throwable $e) {
			echo json_encode(["code" => "ER", "msg" => "Error al incluir clases: " . $e->getMessage()]);
			file_put_contents($log, "ERROR incluir clases: " . $e->getMessage() . "\n", FILE_APPEND);
			return;
		}

		$item = "id";
		$valor = $_POST["idVentaHomologacion"];
		$ventas = ControladorVentas::ctrMostrarVentas($item, $valor);
		if (!$ventas) {
			echo json_encode(["code" => "ER", "msg" => "No se encontró la venta con ID: $valor"]);
			file_put_contents($log, "Venta no encontrada: $valor\n", FILE_APPEND);
			return;
		}

		switch ($ventas["tabla"]) {
			case 'clientes':
				$codigoTipoDoc = 80;
				$itemCliente = "id";
				$valorCliente = $ventas["id_cliente"];
				$cliente = ControladorClientes::ctrMostrarClientes($itemCliente,$valorCliente);
				file_put_contents($log, "Cliente obtenido:\n" . print_r($cliente, true), FILE_APPEND);

				$iva = trim(strtoupper($cliente['tipoiva']));

				if ($iva == "IVA RESPONSABLE INSCRIPTO") {
					$CondicionIVAReceptorId = 1;
				} elseif ($iva == "RESPONSABLE MONOTRIBUTO") {
					$CondicionIVAReceptorId = 6;
				} elseif ($iva == "IVA SUJETO EXENTO" || $iva == "IVA NO RESPONSABLE" || $iva == "IVA RESPONSABLE NO INSCRIPTO" ) {
					$CondicionIVAReceptorId = 4;
				}
				break;
			case 'escribanos':
				
				$itemEscribano = "id";
				$valorEscribano = $ventas["id_cliente"];
				$escribano = ControladorEscribanos::ctrMostrarEscribanos($itemEscribano,$valorEscribano);
				file_put_contents($log, "ESCRI obtenido:\n" . print_r($escribano, true), FILE_APPEND);
				//id_tipo_iva 
				$CondicionIVAReceptorId =  $escribano["id_tipo_iva"];
				/* if ($escribano["id_tipo_iva"]== 1) { //IVA Responsable Inscripto
					$CondicionIVAReceptorId = 1;
				} elseif ($escribano["id_tipo_iva"]== 5) { //Consumidor Final
					$CondicionIVAReceptorId = 5;
				} elseif ($escribano["id_tipo_iva"]== 6) { //6Responsable Monotributo
					$CondicionIVAReceptorId = 4;
				}
 */
				$codigoTipoDoc = 80;
		
				break;
			case 'casual':
				$codigoTipoDoc = 96;
				$CondicionIVAReceptorId = 5;
				break;
	
			default:
				$codigoTipoDoc = 99;
				$CondicionIVAReceptorId = 5;
				break;
		}

		$items = [];
		$productos = json_decode($ventas["productos"], true);
		foreach ($productos as $p) {
			$items[] = [
				"codigo" => (string) $p["id"],
				"scanner" => (string) $p["id"],
				"descripcion" => $p["descripcion"],
				"codigoUnidadMedida" => 7,
				"UnidadMedida" => "Unidades",
				"codigoCondicionIVA" => 1,
				"Alic" => 0,
				"cantidad" => (float) $p["cantidad"],
				"porcBonif" => 0,
				"impBonif" => 0,
				"precioUnitario" => (float) $p["precio"],
				"importeIVA" => 0,
				"importeItem" => (float) $p["total"]
			];
		}
		include ('../extensiones/arca/modo.php');
		
		$tipocbte = 11; 		#COMPROBANTE C
		$documento = (int) $ventas["documento"];
		$nombre = $ventas["nombre"];
		$total = (float) $ventas["total"];
		$date_raw = date('Y-m-d');
		$desde = date('Ymd', strtotime('-2 day', strtotime($date_raw)));
		$hasta = date('Ymd', strtotime('-1 day', strtotime($date_raw)));

    try {
        $afip = new Wsfev1($CUIT, $MODO);
        $ultimo = $afip->consultarUltimoComprobanteAutorizado($PTOVTA, $tipocbte) + 1;
        file_put_contents($log, "Último comprobante: $ultimo\n", FILE_APPEND);
        file_put_contents($log, "Preparando voucher...\n", FILE_APPEND);

        $voucher = [
            "idVoucher" => 1,
            "numeroComprobante" => $ultimo,
            "numeroPuntoVenta" => $PTOVTA,
            "cae" => 0,
            "letra" => "C",
            "fechaVencimientoCAE" => "",
            "tipoResponsable" => "",
            "nombreCliente" => $nombre,
            "domicilioCliente" => "",
            "fechaComprobante" => date('Ymd'),
            "codigoTipoComprobante" => 11,
            "TipoComprobante" => "Factura",
            "codigoConcepto" => 1,
            "codigoMoneda" => "PES",
            "cotizacionMoneda" => 1.000,
            "fechaDesde" => $desde,
            "fechaHasta" => $hasta,
            "fechaVtoPago" => date('Ymd'),
            "codigoTipoDocumento" => $codigoTipoDoc,
            "CondicionIVAReceptorId" => $CondicionIVAReceptorId,
            "TipoDocumento" => "DNI",
            "numeroDocumento" => $documento,
            "importeTotal" => $ventas["total"],
            "importeOtrosTributos" => 0.000,
            "importeGravado" => $ventas["total"],
            "importeNoGravado" => 0.000,
            "importeExento" => 0.000,
            "importeIVA" => 0,
            "codigoPais" => 200,
            "idiomaComprobante" => 1,
            "NroRemito" => 0,
            "CondicionVenta" => "Efectivo",
            "canmismaMoneda" => "N",
            "items" => $items,
            "subtotivas" => [],
            "Tributos" => [],
            "CbtesAsoc" => []
        ];

        file_put_contents($log, "VOUCHER ARMADO:\n" . json_encode($voucher, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);

        file_put_contents($log, "Llamando a emitirComprobante...\n", FILE_APPEND);
        $result = $afip->emitirComprobante($voucher);
        file_put_contents($log, "Resultado AFIP:\n" . print_r($result, true), FILE_APPEND);

        if (isset($result["cae"]) && isset($result["fechaVencimientoCAE"])) {
            $ptoVenta = str_pad($PTOVTA, 4, "0", STR_PAD_LEFT);
            $ultimoComprobante = str_pad($ultimo, 8, "0", STR_PAD_LEFT);
            $codigoFactura = $ptoVenta . '-' . $ultimoComprobante;
			
            $fechaCae = $result["fechaVencimientoCAE"];
            $fechaCaeFormateada = substr($fechaCae, 6, 2) . '/' . substr($fechaCae, 4, 2) . '/' . substr($fechaCae, 0, 4);

            ModeloVentas::mdlAgregarNroComprobante("comprobantes", $ultimo);
			$totalVenta = $ventas["total"];
			$numeroDoc  = $ventas['documento'];
            include('../extensiones/qr/index.php');

            $datos = [
                "id" => $_POST["idVentaHomologacion"],
                "fecha" => date('Y-m-d'),
                "codigo" => $codigoFactura,
                "nombre" => $nombre,
                "documento" => $documento,
                "cae" => $result["cae"],
                "fecha_cae" => $fechaCaeFormateada,
                "qr" => $datos_cmp_base_64 . "="
            ];

            $respuesta = ModeloVentas::mdlHomologacionVenta("ventas", $datos);

            if ($respuesta == "ok") {
                echo json_encode([
                    "code" => "FE",
                    "msg" => "Factura de homologación generada correctamente",
                    "observaciones" => isset($result["observaciones"]) ? $result["observaciones"] : []
                ]);
                return;
            } else {
                echo json_encode(["code" => "ER", "msg" => "No se pudo guardar la venta en la base de datos"]);
                return;
            }
        }

        echo json_encode(["code" => "ER", "msg" => "No se obtuvo CAE en la respuesta de AFIP"]);
        return;

    } catch (Throwable $e) {
        file_put_contents($log, "❌ EXCEPCIÓN al emitirComprobante:\n" . $e->getMessage() . "\n" . $e->getTraceAsString(), FILE_APPEND);
        echo json_encode(["code" => "ER", "msg" => "Excepción en emitirComprobante: " . $e->getMessage()]);
        return;
    }

    file_put_contents($log, "=== FIN HOMOLOGACION [" . date('Y-m-d H:i:s') . "] ===\n", FILE_APPEND);
}



	

	


	/*=============================================
	ELIMINAR VENTA
	=============================================*/

	static public function ctrEliminarVenta(){

		if(isset($_GET["idVenta"])){

			if(isset($_GET["password"])){
				
				$tabla = "ventas";

				$item = "id";
				$valor = $_GET["idVenta"];

				$traerVenta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);
				echo '<pre>'; print_r($traerVenta); echo '</pre>';

				/*=============================================
				ELIMINAR VENTA
				=============================================*/

				//AGREGAR A LA CAJA
					  $item = "fecha";
			          $valor = $traerVenta['fechapago'];

			          $caja = ControladorCaja::ctrMostrarCaja($item, $valor);
			          echo '<pre>'; print_r($caja); echo '</pre>';
				          
				          
			          $efectivo = $caja[0]['efectivo'];
			          $tarjeta = $caja[0]['tarjeta'];
			          $cheque = $caja[0]['cheque'];
			          $transferencia = $caja[0]['transferencia'];

			          switch ($traerVenta['metodo_pago']){

			          	case 'EFECTIVO':
			          		# code...
			          		$efectivo = $efectivo - $traerVenta["total"];
			          		break;
			          	case 'TARJETA':
			          		# code...
			          		$tarjeta = $tarjeta - $traerVenta["total"];
			          		break;
			          	case 'CHEQUE':
			          		# code...
			          		$cheque = $cheque - $traerVenta["total"];
			          		break;
			          	case 'TRANSFERENCIA':
			          		# code...
			          		$transferencia = $transferencia - $traerVenta["total"];
			          		break;
				        }  
				          
			          	$datos = array("fecha"=>$traerVenta['fechapago'],
						             "efectivo"=>$efectivo,
						             "tarjeta"=>$tarjeta,
						             "cheque"=>$cheque,
						             "transferencia"=>$transferencia);
				          
				        $caja = ControladorCaja::ctrEditarCaja($item, $datos);

				$respuesta = ModeloVentas::mdlEliminarVenta($tabla, $_GET["idVenta"]);

				if($respuesta == "ok"){

					echo'<script>

						swal({
							  type: "success",
							  title: "La venta ha sido borrada correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
										if (result.value) {

										window.location = "ventas";

										}
									})

						</script>';

				}

			}else{

				echo'<script>

					swal({
						  type: "warning",
						  title: "La autenticacion es incorrecta",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "ventas";

									}
								})

					</script>';
			}

		
		}

	}

	/*=============================================
	RANGO FECHAS
	=============================================*/	

	static public function ctrRangoFechasVentas($fechaInicial, $fechaFinal){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlRangoFechasVentas($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;
		
	}
	/*=============================================
    MOSTRAR RESUMEN DE VENTAS POR FECHA
    =============================================*/
    static public function ctrResumenVentasPorFecha($fechaInicio, $fechaFin) {
        // Nombre de la tabla en tu base de datos
        $tabla = "ventas";

        // Llama al modelo para ejecutar el procedimiento almacenado
        $respuesta = ModeloVentas::mdlResumenVentasPorFecha($tabla, $fechaInicio, $fechaFin);

        return $respuesta;
    }

	static public function ctrRangoFechasVentas2($fechaInicial, $fechaFinal){
		
		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlRangoFechasVentas2($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;
		
	}
	static public function ctrRangoFechasVentas3($fechaInicial, $fechaFinal){
		// echo $fechaInicial.'<br>';
		$fechaInicial = explode("/",$fechaInicial); //09-23-2022
		
		$fechaInicial = $fechaInicial[2]."/".$fechaInicial[0]."/".$fechaInicial[1];
		$fechaFinal = explode("/",$fechaFinal); //15/05/2018
  	    $fechaFinal = $fechaFinal[2]."/".$fechaFinal[0]."/".$fechaFinal[1];
		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlRangoFechasVentas3($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;
		
	}
	static public function ctrRangoFechasVentasNuevo($fechaInicial, $fechaFinal){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlRangoFechasVentasNuevo($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;
		
	}

	static public function ctrRangoFechasCtaCorriente($fechaInicial, $fechaFinal){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlRangoFechasCtaCorriente($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;
		
	}

	static public function ctrRangoFechasaFacturar($fechaInicial, $fechaFinal){

		$tabla = "cuotas";

		$respuesta = ModeloVentas::mdlRangoFechasaFacturar($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;
		
	}
	static public function ctrRangoFechasaFacturarVentas($fechaInicial, $fechaFinal){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlRangoFechasaFacturar($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;
		
	}
	static public function ctrTmpVentasCopia($tipo){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlTmpVentasCopia($tabla, $tipo);

		return $respuesta;
		
	}
	/*=============================================
	RANGO FECHAS
	=============================================*/	

	static public function ctrRangoFechasVentasCobrados($fechaInicial, $fechaFinal){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlRangoFechasVentasCobrados($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;
		
	}

	/*=============================================
	RANGO FECHAS
	=============================================*/	

	static public function ctrRangoFechasVentasNroFc($fechaInicial, $fechaFinal, $nrofc){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlRangoFechasVentasNroFc($tabla, $fechaInicial, $fechaFinal, $nrofc);

		return $respuesta;
		
	}

	/*=============================================
	RANGO FECHAS
	=============================================*/	

	static public function ctrRangoFechasVentasMetodoPago($fechaInicial, $fechaFinal, $metodoPago){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlRangoFechasVentasMetodoPago($tabla, $fechaInicial, $fechaFinal, $metodoPago);

		return $respuesta;
		
	}

	/*=============================================
	LISTADO DE ETIQUETAS
	=============================================*/	

	static public function ctrEtiquetasVentas(){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlEtiquetasVentas($tabla);

		return $respuesta;
		
	}

	/*=============================================
	RANGO FECHAS
	=============================================*/	

	static public function ctrRangoFechasVentasCtaCorriente($fechaInicial, $fechaFinal){

		$tabla = "ventas";

		$respuesta = ModeloVentas::RangoFechasVentasCtaCorriente($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;
		
	}

	/*=============================================
	SELECCIONO UNA FACTURA PARA LA ETIQUETA
	=============================================*/
	static public function ctrSeleccionarVenta($item, $valor){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlSeleccionarVenta($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	MUESTRO LAS FACTURAS SELECCIONADAS
	=============================================*/
	static public function ctrMostrarFacturasSeleccionadas($item, $valor){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlMostrarFacturasSeleccionadas($tabla, $item, $valor);

		return $respuesta;

	}
	/*=============================================
	BORRAR LAS FACTURAS SELECCIONADAS
	=============================================*/
	static public function ctrBorrarFacturasSeleccionadas($item, $valor){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlBorrarFacturasSeleccionadas($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	BORRAR PAGO DE LAS FACTURAS
	=============================================*/
	static public function ctrEliminarPago(){

		if(isset($_GET["idPago"])){

			$tabla = "ventas";

			$valor =$_GET["idPago"];

			$respuesta = ModeloVentas::mdlEliminarPago($tabla,$valor);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					  type: "success",
					  title: "El pago ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "ventas";

								}
							})

				</script>';

			}		
		}

	}
	/*=============================================
	DESCARGAR EXCEL
	=============================================*/

	public function ctrDescargarReporte(){

		if(isset($_GET["reporte"])){

			$tabla = $_GET["ruta"];

			if(isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])){

				$ventas = ControladorEnlace::ctrRangoFechasEnlace($tabla, $_GET["fechaInicial"], $_GET["fechaFinal"]);

			}
				// else{

			// 	$item = null;
			// 	$valor = null;

			// 	$ventas = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);

			// }


			/*=============================================
			CREAMOS EL ARCHIVO DE EXCEL
			=============================================*/

			$Name = $_GET["ruta"].'.xls';

			header('Expires: 0');
			header('Cache-control: private');
			header("Content-type: application/vnd.ms-excel"); // Archivo de Excel
			header("Cache-Control: cache, must-revalidate"); 
			header('Content-Description: File Transfer');
			header('Last-Modified: '.date('D, d M Y H:i:s'));
			header("Pragma: public"); 
			header('Content-Disposition:; filename="'.$Name.'"');
			header("Content-Transfer-Encoding: binary");

			echo utf8_decode("<table border='0'> 
					<tr>
					<td style='font-weight:bold; border:1px solid #eee;'>FECHA</td>
					<td style='font-weight:bold; border:1px solid #eee;'>TIPO</td>  
					<td style='font-weight:bold; border:1px solid #eee;'>CÓDIGO</td>
					<td style='font-weight:bold; border:1px solid #eee;'>NOMBRE</td>
					<td style='font-weight:bold; border:1px solid #eee;'>DOCUMENTO</td>
					<td style='font-weight:bold; border:1px solid #eee;'>PRODUCTOS</td>	
					<td style='font-weight:bold; border:1px solid #eee;'>TOTAL</td>				
					</tr>");

			foreach ($ventas as $row => $item){
				
			 echo utf8_decode("<tr>
			 			<td style='border:1px solid #eee;'>".$item["fecha"]."</td> 
			 			<td style='border:1px solid #eee;'>".$item["tipo"]."</td>
			 			<td style='border:1px solid #eee;'>".$item["codigo"]."</td>
			 			<td style='border:1px solid #eee;'>".$item["nombre"]."</td>
			 			<td style='border:1px solid #eee;'>".$item["documento"]."</td>
			 			");

			 	$productos =  json_decode($item["productos"], true);

			 	echo utf8_decode("<td style='border:1px solid #eee;'>");	

		 		foreach ($productos as $key => $valueProductos) {
			 			
		 			echo utf8_decode($valueProductos["descripcion"]." ".$valueProductos["folio1"]."-".$valueProductos["folio2"]."<br>");
		 		
		 		}

		 		echo utf8_decode("</td>
					<td style='border:1px solid #eee;'>$ ".number_format($item["total"],2)."</td>	
		 			</tr>");
			}


			echo "</table>";

		}

	}


	/*=============================================
	SUMA TOTAL VENTAS
	=============================================*/

	public function ctrSumaTotalVentas(){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlSumaTotalVentas($tabla);

		return $respuesta;

	}

	/*=============================================
	SUMA TOTAL VENTAS
	=============================================*/

	public function ctrSumaTotalVentasEntreFechas($fechaInicial,$fechaFinal){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlSumaTotalVentasEntreFechas($tabla,$fechaInicial,$fechaFinal);

		return $respuesta;

	}

	static public function ctrUltimoComprobante($item,$valor){

		$tabla = "comprobantes";

		$respuesta = ModeloVentas::mdlUltimoComprobante($tabla, $item, $valor);
		
		return $respuesta;
				
		
	} 

	#ACTUALIZAR PRODUCTO EN CTA_ART_TMP
	#---------------------------------
	public function ctrAgregarTabla($datos){

		
		echo '<table class="table table-bordered">
                <tbody>
                    <tr>
                      <th style="width: 10px;">#</th>
                      <th style="width: 10px;">Cantidad</th>
                      <th style="width: 400px;">Articulo</th>
                      <th style="width: 70px;">Precio</th>
                      <th style="width: 70px;">Total</th>
                      <th style="width: 10px;">Opciones</th> 
                    </tr>';
		
			echo "<tr>
					
					<td>1.</td>
					<td><span class='badge bg-red'>".$datos['cantidadProducto']."</span></td>
					<td>".$datos['productoNombre']."</td>
					<td style='text-align: right;'>$ ".$datos['precioVenta'].".-</td>
					<td style='text-align: right;'>$ ".$datos['cantidadProducto']*$datos['precioVenta'].".-</td>
					<td><button class='btn btn-link btn-xs' data-toggle='modal' data-target='#myModalEliminarItemVenta'><span class='glyphicon glyphicon-trash'></span></button></td>
					
				  </tr>";
				
		echo '</tbody></table>';
				
		
	}

	/*=============================================
	REALIZAR Pago
	=============================================*/

	static public function ctrRealizarPago($redireccion){

		if(isset($_POST["nuevoPago"])){

			$adeuda = $_POST["adeuda"]-$_POST["nuevoPago"];

			$tabla = "ventas";

			

			$fechaPago = explode("-",$_POST["fechaPago"]); //15-05-2018
  	        $fechaPago = $fecha[2]."-".$fecha[1]."-".$fecha[0];

			

			$datos = array("id"=>$_POST["idPago"],
						   "adeuda"=>$adeuda,
						   "fecha"=>$_POST["fechaPago"]);

		
			
			$respuesta = ModeloVentas::mdlRealizarPago($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				localStorage.removeItem("rango");

				swal({
					  type: "success",
					  title: "La venta ha sido editada correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "'.$redireccion.'";

								}
							})

				</script>';

			}	
		}


	}

	
	/*=============================================
	RANGO FECHAS
	=============================================*/	

	static public function ctrHistorial(){

		

		// FACTURAS
		$tabla = "cta";
		$respuesta = ModeloVentas::mdlHistorial($tabla);
		

		foreach ($respuesta as $key => $value) {

			// veo los items de la factura
			$tabla = "ctaart";
			$repuestos = ModeloVentas::mdlHistorialCta_art($tabla,$value['idcta']);
			
			$productos='';

			for($i = 0; $i < count($repuestos)-1; $i++){
				
				$productos = '{"id":"'.$repuestos[$i]["idarticulo"].'",
			      "descripcion":"'.$repuestos[$i]["nombre"].'",
			      "cantidad":"'.$repuestos[$i]["cantidad"].'",
			      "precio":"'.$repuestos[$i]["precio"].'",
			      "total":"'.$repuestos[$i]["precio"].'"},';
			}

			$productos = $productos . '{"id":"'.$repuestos[count($repuestos)-1]["idarticulo"].'",
			      "descripcion":"'.$repuestos[count($repuestos)-1]["nombre"].'",
			      "cantidad":"'.$repuestos[count($repuestos)-1]["cantidad"].'",
			      "precio":"'.$repuestos[count($repuestos)-1]["precio"].'",
			      "total":"'.$repuestos[count($repuestos)-1]["precio"].'"}';

			$productos ="[".$productos."]";
			
			echo '<pre>'; print_r($productos); echo '</pre>';
			
			// datos para cargar la factura
			$tabla = "ventas";
			
			$datos = array("id_vendedor"=>1,
						   "fecha"=>$value['fecha'],
						   "id_cliente"=>$value["idcliente"],
						   "codigo"=>$key,
						   "nrofc"=>$value["nrofc"],
						   "detalle"=>strtoupper($value["obs"]),
						   "productos"=>$productos,
						   "impuesto"=>0,
						   "neto"=>0,
						   "total"=>$value["importe"],
						   "adeuda"=>$value["adeuda"],
						   "obs"=>"",
						   "metodo_pago"=>$value["detallepago"],
						   "fechapago"=>$value['fecha']);

			$respuesta = ModeloVentas::mdlIngresarVenta($tabla, $datos);
			

		}
		
		return $respuesta;

		
		
	}

	/*=============================================
	INGRESAR DERECHO DE ESCRITURA
	=============================================*/

	static public function ctringresarDerechoEscritura(){

		if(isset($_POST["nuevoPagoDerecho"])){

			$tabla = "ventas";

			$item = "id";
			$valor =$_POST["idPagoDerecho"];

			$respuesta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);

			/*=============================================
			REVISO LOS PRODUCTOS
			=============================================*/	

			$listaProductos = json_decode($respuesta['productos'], true);
			
			$totalFactura = 0;
			foreach ($listaProductos as $key => $value) {
				


			   if($value['id']==19){

			   	//ELIMINO EL ID 19 QUE ES DEL TESTIMONIO
			   	unset($listaProductos[$key]);
			   	
			   }else{
			   	// SUMO EL TOTAL DE LA FACTURA
			   	$totalFactura = $totalFactura +$value['total'];
			   	
			   }

			}
			echo '<pre>'; print_r(count($listaProductos)); echo '</pre>';
			$productosNuevosInicio = '[';

			for($i = 0; $i <= count($listaProductos); $i++){
				
				
			$productosNuevosMedio = '{
			      "id":"'.$listaProductos[0]["id"].'",
			      "descripcion":"'.$listaProductos[0]["descripcion"].'",
			      "idnrocomprobante":"'.$listaProductos[0]["idnrocomprobante"].'",
			      "cantventaproducto":"'.$listaProductos[0]["cantventaproducto"].'",
			      "folio1":"'.$listaProductos[0]["folio1"].'",
			      "folio2":"'.$listaProductos[0]["folio2"].'",
			      "cantidad":"'.$listaProductos[0]["cantidad"].'",
			      "precio":"'.$listaProductos[0]["precio"].'",
			      "total":"'.$listaProductos[0]["total"].'"
			    },';

			}

			$productosNuevosFinal = '{
			      "id":"19",
			      "descripcion":"DERECHO DE ESCRITURA",
			      "idnrocomprobante":"100",
			      "cantventaproducto":"1",
			      "folio1":"1",
			      "folio2":"1",
			      "cantidad":"1",
			      "precio":"'.$_POST["nuevoPagoDerecho"].'",
			      "total":"'.$_POST["nuevoPagoDerecho"].'"
			    }]';


echo $productosNuevosInicio . $productosNuevosMedio . $productosNuevosFinal;
			
		}

	}
	
	

	

	

	static public function ctrNombreMes($mes) {
		$meses = [
			1 => 'Enero',
			2 => 'Febrero',
			3 => 'Marzo',
			4 => 'Abril',
			5 => 'Mayo',
			6 => 'Junio',
			7 => 'Julio',
			8 => 'Agosto',
			9 => 'Septiembre',
			10 => 'Octubre',
			11 => 'Noviembre',
			12 => 'Diciembre'
		];

		return $meses[(int)$mes] ?? 'Mes inválido';
	}

	static public function ctrRealizarPagoVenta(){

		if(isset($_POST["idVentaPago"])){

			$tabla = "ventas";

			$datos = array("id"=>$_POST['idVentaPago'],
						   "metodo_pago"=>$_POST['listaMetodoPago'],
						   "referenciapago"=>$_POST["nuevaReferencia"],
						   "fechapago"=>date('Y-m-d'),
						   "adeuda"=>0);

			$respuesta = ModeloVentas::mdlRealizarPagoVenta($tabla, $datos);

				  

			if($respuesta == "ok"){

				//AGREGAR A LA CAJA
				  $item = "fecha";
		          $valor = date('Y-m-d');

		          $caja = ControladorCaja::ctrMostrarCaja($item, $valor);
		          echo '<pre>'; print_r($caja); echo '</pre>';
			          
			          
		          $efectivo = $caja[0]['efectivo'];
		          $tarjeta = $caja[0]['tarjeta'];
		          $cheque = $caja[0]['cheque'];
		          $transferencia = $caja[0]['transferencia'];

		          switch ($_POST["listaMetodoPago"]) {

		          	case 'EFECTIVO':
		          		# code...
		          		$efectivo = $efectivo + $_POST["totalVentaPago"];
		          		break;
		          	case 'TARJETA':
		          		# code...
		          		$tarjeta = $tarjeta + $_POST["totalVentaPago"];
		          		break;
		          	case 'CHEQUE':
		          		# code...
		          		$cheque = $cheque + $_POST["totalVentaPago"];
		          		break;
		          	case 'TRANSFERENCIA':
		          		# code...
		          		$transferencia = $transferencia + $_POST["totalVentaPago"];
		          		break;
			        }  
			          
		          	$datos = array("fecha"=>date('Y-m-d'),
					             "efectivo"=>$efectivo,
					             "tarjeta"=>$tarjeta,
					             "cheque"=>$cheque,
					             "transferencia"=>$transferencia);
			          
			        $caja = ControladorCaja::ctrEditarCaja($item, $datos);
				  

				    echo '<script>
				
				 			window.location = "ventas";

				 		</script>';
			}

		}
	}

	static public function ctrUltimoId(){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlUltimoId($tabla);

		return $respuesta;

	}

	/*=============================================
	CREAR NC
	=============================================*/

	static public function ctrCrearNc($datos){
		
		$item="id";
		$valor=$datos['idVenta'];
		$ventas=ControladorVentas::ctrMostrarVentas($item,$valor);
		#creo un array del afip
		$items=json_decode($datos["productos"], true);



		#datos para la factura
		$facturaOriginal = $ventas["codigo"];


		#paso los datos al archivo de conexnion de afip
		include('../extensiones/afip/notacredito.php');

		
			/*=============================================
					GUARDAR LA VENTA
			=============================================*/	

			$tabla = "ventas";

			$result = $afip->emitirComprobante($regcomp); 
			

			if ($result["code"] === Wsfev1::RESULT_OK) {

			/*=============================================
			FORMATEO LOS DATOS
			=============================================*/	

				$fecha = date("Y-m-d");
				$adeuda=0;
				$fechapago = $fecha;
			
				$cantCabeza = strlen($PTOVTA); 
				switch ($cantCabeza) {
						case 1:
						$ptoVenta="000".$PTOVTA;
						break;
						case 2:
						$ptoVenta="00".$PTOVTA;
						break;
					case 3:
						$ptoVenta="0".$PTOVTA;
						break;   
				}

				$codigoFactura = $ptoVenta .'-'. $ultimoComprobante;
				$fechaCaeDia = substr($result["fechaVencimientoCAE"],-2);
				$fechaCaeMes = substr($result["fechaVencimientoCAE"],4,-2);
				$fechaCaeAno = substr($result["fechaVencimientoCAE"],0,4);
				$totalVenta = $datos["total"];
				include('../extensiones/qr/index.php');
	            
        		$datos = array(
					   "id_vendedor"=>1,
					   "fecha"=>date('Y-m-d'),
					   "codigo"=>$codigoFactura,
					   "tipo"=>'NC',
					   "id_cliente"=>$datos['idcliente'],
					   "nombre"=>$datos['nombre'],
					   "documento"=>$datos['documento'],
					   "tabla"=>$datos['tabla'],
					   "productos"=>$datos['productos'],
					   "impuesto"=>0,
					   "neto"=>0,
					   "total"=>$datos["total"],
					   "adeuda"=>'0',
					   "obs"=>'FC-'.$ventas['codigo'],
					   "cae"=>$result["cae"],
					   "fecha_cae"=>$fechaCaeDia.'/'.$fechaCaeMes.'/'.$fechaCaeAno,
					   "fechapago"=>$fechapago,
					   "metodo_pago"=>"EFECTIVO",
					   "referenciapago"=>"EFECTIVO",
					   "qr"=>$datos_cmp_base_64."="
					   
					   );

        	#grabo la nota de credito
			$respuesta = ModeloVentas::mdlIngresarVenta($tabla, $datos);
			#resto de la caja
			$item = "id";
			$datos = array(
					   "id"=>$ventas['id'],
					   "obs"=>'NC-'.$codigoFactura);
			$respuesta = ModeloVentas::mdlAgregarNroNotadeCredito($tabla,$datos);

			echo 'FE';

	        } 

	


	}

	static public function ctrMostrarUltimaAVenta(){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlMostrarUltimaVenta($tabla);

		return $respuesta;

	}

	static public function ctrMostrarUltimasVentas($item, $valor){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlMostrarUltimasVentas($tabla, $item, $valor);

		return $respuesta;

	}
	static public function ctrGuardarItem($datos){

		$tabla = "items";

		$respuesta = ModeloVentas::mdlGuardarItem($tabla, $datos);

		return $respuesta;

	}


}



