<?php
/*=============================================
=     CARGO UN ARRAY DE  LOS ESCRIBANOS      =
=============================================*/
#SE REVISA A LOS DEUDORES
$item = null;
$valor = null;  
$escribanos = ControladorEscribanos::ctrMostrarEscribanos($item, $valor);


#CANTIDAD DE INHABILITADOS
$cantInhabilitados = 0;
#CANTIDAD DE INHABILITADOS
$cantEscribanos = 0;

#HAGO UN FOREACH PARA EVALUAR CUANTOS LIBROS DEBE
foreach ($escribanos as $key => $value) {

  $cantEscribanos++;
  #INHABILITADOS
  $inhabilitado = 0;
  /*=============================================
            INHABILITACION POR LIBROS
  =============================================*/
  #OBTENGO CUANTOS LIBROS... DEBE
  $cantLibros = $value["ultimolibrocomprado"]-$value["ultimolibrodevuelto"];
  
  if ($cantLibros>=$maxLibros){

    $inhabilitado++;//LO SACO DE CERO AL INHABILITADO

  }    
  /*=============================================
            INHABILITACION POR DEUDA
  =============================================*/
  // $item = "id";
  // $valor = $value["id"];
  // $escribanosConDeudaTodos = ControladorCuotas::ctrEscribanosDeuda($item, $valor);
  // if (!empty($escribanosConDeudaTodos)) {
  //   $fecha2 = date("Y-m-j");
  //   $dias = (strtotime($escribanosConDeudaTodos["fecha"]) - strtotime($fecha2)) / 86400;
  //   $dias = abs($dias); 
  //   $dias = floor($dias);   
  //   if ($dias >= $atraso['valor'] && $value['id'] <> 1) {
  //     $inhabilitado++;
  //   }
  // }

  // NUEVA lógica: si el día actual del mes es mayor a 15, inhabilitar
  $diaDelMes = date("j");

  if ($diaDelMes > 16 && $value['id'] <> 1) {
    $inhabilitado++;
  }
  /*=============================================
  GUARDO LOS INHABILITADOS EN LA BD LOCAL
  =============================================*/
  if($inhabilitado>=1){

    $valor=$value['id'];
    $respuesta = ControladorCuotas::ctrEscribanosInhabilitar($valor);
    $cantInhabilitados ++;

    /*===========================================
    = SUBIR INHABILITADOS   A LA TABLA INABILITADOS DEL =
    ===========================================*/
    $datos = array("id"=>$value['id'],
                   "nombre"=>$value['nombre']);
    ControladorEnlace::ctrSubirInhabilitado($datos);

  }
     
}//foreach


?>