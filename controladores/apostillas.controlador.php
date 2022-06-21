<?php

class ControladorApostillas{
    /*=============================================
	MOSTRAR APOSTILLAS
	=============================================*/

	static public function ctrMostrarApostillas($item, $valor){

		$tabla = "apostillas";

		$respuesta = ModeloApostillas::mdlMostrarApostillas($tabla, $item, $valor);

		return $respuesta;

	}
    /*=============================================
	MOSTRAR TODAS LAS APOSTILLAS
	=============================================*/

	static public function ctrMostrarTodasApostillasVenta($item, $valor){

		$tabla = "apostillas";

		$respuesta = ModeloApostillas::mdlMostrarTodasApostillasVenta($tabla, $item, $valor);

		return $respuesta;

	}
    /*=============================================
	GUARDAR APOSTILLAS
	=============================================*/

	static public function ctrGuardarDatosApostilla(){

		if(isset($_POST["idApostillaVenta"])){

			if(preg_match('/^[a-zA-ZñÑ0-9 ]+$/', $_POST["idNombreApostilla"])||preg_match('/^[a-zA-ZñÑ0-9 ]+$/', $_POST["idDescripcionApostilla"])){

				$tabla = "apostillas";

				$datos = array("id"=> $_POST['idApostillaVenta'],
							"descripcion"=> strtoupper($_POST['idDescripcionApostilla']),
							"nombre" => strtoupper($_POST["idNombreApostilla"]),
							"importe" => $_POST["idImporteApostilla"]);

				$respuesta = ModeloApostillas::mdlGuardarDatosApostilla($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>

					swal({
						type: "success",
						title: "los datos se han guardado correctamente",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
									if (result.value) {

									window.location = "index.php?ruta=apostilla-items&id="+'.$_POST['idVentaA'].'

									}
								})

					</script>';

				}
			}else{
				
				echo'<script>

					swal({
						  type: "error",
						  title: "¡Los datos no pueden ir vacios o con caracteres invalidos!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

								window.location = "index.php?ruta=apostilla-items&id="+'.$_POST['idVentaA'].'

							}
						})

			  	</script>';
			}
		}
		

	}

}