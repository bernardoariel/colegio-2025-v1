<?php
  
  // FECHA DEL DIA DE HOY
  $fecha=date('d/m/Y');

  if(isset($_GET['fechaInicio'])){

    $fechaInicial =  $_GET['fechaInicio'];
  }else{
    
    $fechaInicial =   $fecha;
  }

  if(isset($_GET['fechaFin'])){
    
    $fechaFin= $_GET['fechaFin'];

  }else{
    
    $fechaFin = $fecha;

  }
  
  $legalizacion = 0;
  $apostilla = 0;
  $certificacionFirmas = 0;
  $certificacionFotocopias = 0;
  $concuerdas = 0;
  $testimonio = 0;
  $protocolo = 0;
  $libroIntervenciones = 0;
  $actas = 0;
  $haya = 0;
  $otros= 0;

  $arrayProductos = ['legalizacion','apostilla','certificacionFirmas','certificacionFotocopias','concuerdas','testimonio','protocolo','libroIntervenciones','actas','haya','otros'];
  ?>

<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Administrar ventas
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar ventas</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box cajaPrincipal">

      <div class="box-header with-border">

        <div class="row">

          <div class="col-lg-2">
            
            <div class="form-group">
              
              <label>Fecha Inicio: </label>
              
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" id="datepicker_inicio" value="<?php echo $fechaInicial;?>">
              </div>

            </div>

          </div>

          <div class="col-lg-2">

            <div class="form-group">

              <label>Fecha Fin:</label>

              <div class="input-group date">
                
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>

                <input type="text" class="form-control pull-right" id="datepicker_fin" value="<?php echo $fechaFin; ?>">

              </div>

            </div>

          </div>

          <div class="col-lg-2">
              <br>
              <button class="btn btn-primary " id="bsq_comprobantes">
                
                Buscar

              </button>

          </div>

        </div>

      </div>

      <div class="box-body">
       
      <?php if (isset($_GET['fechaFin'])): ?>
       
       <?php  $respuesta = ControladorVentas::ctrRangoFechasVentas3($_GET['fechaInicio'], $_GET['fechaFin']); ?>
       <h2>Resultados filtrados: <?php echo count($respuesta); ?></h2>
       
       <?php foreach ($respuesta as $key => $value): ?>
            
            <?php

              $listaProductos = json_decode($value['productos'], true);
              // echo '<pre>'; print_r($listaProductos); echo '</pre>';
              if(is_array($listaProductos) || is_object($listaProductos)){
                foreach ($listaProductos as $key => $valueProductos) {
          
                  switch ($valueProductos['idnrocomprobante']) {
                    case 50:
                        $legalizacion++;
                        break;
                    case 51:
                        $apostilla++;
                        break;
                    case 52:
                        $certificacionFirmas++;
                        break;
                    case 53:
                        $certificacionFotocopias++;
                        break;
                    case 54:
                        $concuerdas++;
                        break;
                    case 55:
                        $testimonio++;
                        break;
                    case 56:
                        $protocolo++;
                        break;
                    case 57:
                        $libroIntervenciones++;
                        break;
                    case 58:
                        $actas++;
                        break;
                    case 60:
                        $haya++;
                        break;
                    default:
                      $otros++;
                      break;
                  }
                }
              }

            ?>            

       <?php endforeach ?>

      <?php endif ?>
       <table class="table table-bordered table-striped dt-responsive tablas tablaVentas" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:100px">Comprobante</th>
           <th style="width:60px">Cantidad</th>

         </tr> 

        </thead>

        <tbody>

          <tr>
            <td>Legalizacion</td>
            <td><?php echo $legalizacion; ?></td>
          </tr>
          <tr>
            <td>apostilla</td>
            <td><?php echo $apostilla; ?></td>
          </tr>
          <tr>
            <td>certificacionFirmas</td>
            <td><?php echo $certificacionFirmas; ?></td>
          </tr>
          <tr>
            <td>certificacionFotocopias</td>
            <td><?php echo $certificacionFotocopias; ?></td>
          </tr>
          <tr>
            <td>concuerdas</td>
            <td><?php echo $concuerdas; ?></td>
          </tr>
          <tr>
            <td>testimonio</td>
            <td><?php echo $testimonio; ?></td>
          </tr>
          <tr>
            <td>protocolo</td>
            <td><?php echo $protocolo; ?></td>
          </tr>
          <tr>
            <td>libroIntervenciones</td>
            <td><?php echo $libroIntervenciones; ?></td>
          </tr>
          <tr>
            <td>actas</td>
            <td><?php echo $actas; ?></td>
          </tr>
          <tr>
            <td>haya</td>
            <td><?php echo $haya; ?></td>
          </tr>
         <tr>
            <td>otros</td>
            <td><?php echo $otros; ?></td>
          </tr>


        </tbody>

      </table>
      </div>

    </div>

  </section>

</div>

