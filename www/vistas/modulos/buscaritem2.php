<div class="content-wrapper">

<section class="content-header">
  
  <h1>
    
    BUSCAR ITEMS
  
  </h1>

  <ol class="breadcrumb">
    
    <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
    
    <li class="active">Buscar items</li>
  
  </ol>

</section>

<section class="content">

  <div class="box">

    

    <div class="box-body">
      
    <?php


      function buscarFolio($fechaInicial, $fechaFinal,$folio){
          //DATOS DE TODAS LAS VENTAS DEL MES
          $respuesta = ControladorVentas::ctrRangoFechasVentasNuevo($fechaInicial,$fechaFinal);
          
          foreach ($respuesta as $key => $value) {
              #tomo los productos
              
              $listaProductos = json_decode($value["productos"], true);
              #recorro $listaproductos para cargarlos en la tabla de comprobantes
              if (is_array($listaProductos) || is_object($listaProductos)){
    
                foreach ($listaProductos as  $value2) {

                  if($folio >= $value2["folio1"] && $folio <= $value2["folio2"]){
                    echo "<h1>".$value['id']." ".$value['codigo']." ".$value['fecha']."</h1>";
                  }
                      
                }
              }
              
                  
          
          }
              
          
      }
      buscarFolio('2021-01-01','2023-03-03','754447');
      
      echo '<h1 class="text-danger">TERMINADO </h1>';
     
      
   ?>

    </div>

  </div>

</section>

</div>