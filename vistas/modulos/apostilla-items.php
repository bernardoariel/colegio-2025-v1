<?php

  //TRAER TODAS LAS APOSTILLAS
  $item = 'idventa' ;
  $valor = isset($_GET['id']) ? $_GET['id']: 1;

  $apostillas = ControladorApostillas::ctrMostrarTodasApostillasVenta($item, $valor);

  //TRAER TODAS LA FACTURA
  $item = 'id' ;
  $valor = isset($_GET['id']) ? $_GET['id']: 1;

  $ventas = ControladorVentas::ctrMostrarVentas($item, $valor);

  
?>

<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      <button class="btn btn-primary" id="btnIrHistoria" style="border-radius:100px"> <i class="fa fa-long-arrow-left"></i></button>  Administrar Apostillas
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar Apostillas</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="row">

      <div class="col-md-2">

        <div class="box box-widget widget-user-2">

          <div class="widget-user-header bg-purple">
          
            <div class="widget-user-image">
              
              <img class="img-circle" src="vistas/img/plantilla/pdf.png" alt="pdf">
            
            </div>

            <h5 class="widget-user-desc">Facturado a</h5> 
            <h3 class="widget-user-username">Nadia Carmichael</h3>

          </div>

          <div class="box-footer no-padding">
            
            
            <ul class="nav nav-stacked">
             
              <li><a href="#"><b><?php echo $ventas['fecha']; ?></b></a></li>
              <li><a href="#"><b><?php echo $ventas['codigo']; ?></b> </a></li>
              <li><a href="#"><b><?php echo $ventas['nombre']; ?></b></a></li>
              <li><a href="#"><b><?php echo $ventas['total']; ?></b> </a></li>
              <li><a href="#"><b>Apostillas</b><span class="pull-right badge bg-red"><?php echo count($apostillas); ?></span></a></li>
              
            </ul>

          </div>

        </div>

      </div>
      
      <div class="col-md-10">

        <div class="box box-success">

          <div class="box-body">

            <table class="table table-bordered table-striped dt-responsive tablas tablasApostillas" width="100%">
              
              <thead>
              
                <tr>
                  
                  <th style="width:10px">#</th>
                  <th style="width:80px">Folio Nro.</th>
                  <th>Nombre</th>
                  <th>Descripcion</th>
                  <th style="width:80px">Acciones</th>

                </tr> 

              </thead>

              <tbody>

              <?php foreach ($apostillas as $key => $value): ?>
                <tr>
                  <td><?php echo $key+1; ?></td>
                  <td><?php echo $value['folio']; ?></td>

                  <td>
                    <?php if(strlen($value['nombre'])>=1){echo $value['nombre'];$btn=true;}else{ echo "<span class='text-danger'>sin registrar</span>";$btn=false;} ?>
                  </td>

                  <td>
                    <?php if(strlen($value['descripcion'])>=1){echo $value['descripcion'];$btn=true;}else{ echo "<span class='text-danger'>sin registrar</span>";$btn=false;} ?>
                  </td>
                  <td>

                    <div class="btn-group">
                    
                      <button class="btn btn-danger btnApostilla"  
                        idApostilla=<?php echo $value['id']; ?>
                        data-toggle="modal" 
                        data-target="#modalAgregarApostilla" 
                        title="modificar o agregar datos en la apostilla">
                        <i class="fa fa-edit"></i></button>
                        <?php if ($btn): ?>
                          
                          <button class="btn btn-primary "><i class="fa fa-print" title="imprimir apostilla"></i></button>

                        <?php endif ?>
                  </td>
                </tr>
              <?php endforeach ?>

              </tbody>

            </table>

          </div>

        </div>

      </div>

    </div>

  </section>

</div>

<!--=====================================
      AGREGAR APOSTILLA
======================================-->
<div id="modalAgregarApostilla" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->
        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Ingresar datos en la Apostilla</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">
              
            <form role="form" method="post">
              <!-- ENTRADA PARA EL NRO DE FOLIO -->
              <div class="form-group">
                
                <div class="input-group">
                
                  <span class="input-group-addon"><i class="fa fa-thumb-tack"></i></span> 
                  
                  <input type="hidden" id="idVentaA" name="idVentaA">
                  <input type="hidden" id="idApostillaVenta" name="idApostillaVenta">
                  <input type="text" 
                    class="form-control" 
                    placeholder="numero de folio" 
                    id='nroFolio' 
                    name='nroFolio' 
                    value='10006' 
                    readonly> 

                </div>

              </div>
              <!-- ENTRADA PARA EL NOMBRE -->
              <div class="form-group">
                
                <div class="input-group">
                
                  <span class="input-group-addon"><i class="fa fa-id-card"></i></span> 

                  <input type="text" 
                    class="form-control" 
                    placeholder="Nombre...." 
                    id='idNombreApostilla' 
                    name='idNombreApostilla' 
                    pattern="[a-zA-Z ]{3,60}"
                    autocomplete="off">  

                </div>

              </div>
              <!-- ENTRADA PARA LA DESCRIPCION -->
              <div class="form-group">
                  
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-star-half-full"></i></span> 

                    <input type="text" 
                      class="form-control" 
                      placeholder="Descripcion...." 
                      id='idDescripcionApostilla' 
                      name='idDescripcionApostilla' 
                      pattern="[a-zA-Z ]{3,60}"
                      autocomplete="off">     

                  </div>

              </div>
              <!-- ENTRADA PARA EL IMPORTE APOSTILLA -->
              <div class="form-group">
                  
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span> 

                    <input type="text" 
                      class="form-control" 
                      placeholder="Importe...." 
                      id='idImporteApostilla' 
                      name='idImporteApostilla' 
                      pattern="[0-9]{3}"
                      autocomplete="off">     

                  </div>

              </div>
             
            
              <?php

                $realizarPago = new ControladorVentas();
                $realizarPago -> ctrRealizarPagoVenta();

              ?>

              <div class="modal-footer">

                <button type="submit" class="btn btn-primary">Guardar Datos Apostillas</button>

              </div>
          
            </form> 
                
            <?php

            $guardarDatosApostilla = new ControladorApostillas();
            $guardarDatosApostilla -> ctrGuardarDatosApostilla();

            ?>
          </div>
        
        </div>

    </div>

  </div>

</div>





       

