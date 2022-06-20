<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Administrar Apostillas
    
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
              <li><a href="#"><b>31/06/2022</b></a></li>
              <li><a href="#"><b>0004-00000164</b> </a></li>
              <li><a href="#"><b>AGUIRRE HNOS</b></a></li>
              <li><a href="#"><b>$ 45000</b> </a></li>
              <li><a href="#"><b>Apostillas</b>  <span class="pull-right badge bg-red">5</span></a></li>
            </ul>

          </div>

        </div>

      </div>
      
      <div class="col-md-10">

        <div class="box box-success">

          <div class="box-body">

            <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
              
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

                <tr>
                  <td>1</td>
                  <td>10006</td>
                  <td>Juan Perez </td>
                  <td>nacionalidad</td>
                  <td>
                    <div class="btn-group">
                    
                      <button class="btn btn-danger"  data-toggle="modal" data-target="#modalAgregarApostilla" title="modificar o agregar datos en la apostilla"><i class="fa fa-edit"></i>
                      <button class="btn btn-primary "><i class="fa fa-print" title="imprimir apostilla"></i>
                  </td>
                </tr>

                <tr>
                  <td>1</td>
                  <td>10006</td>
                  <td>Juan Perez </td>
                  <td>nacionalidad</td>
                  <td>
                    <div class="btn-group">
                      <button class="btn btn-info"><i class="fa fa-eye"></i>
                      <button class="btn btn-info"><i class="fa fa-eye"></i>
                  </td>
                </tr>

                <tr>
                  <td>1</td>
                  <td>10006</td>
                  <td>Juan Perez </td>
                  <td>nacionalidad</td>
                  <td>
                    <div class="btn-group">
                      <button class="btn btn-info"><i class="fa fa-eye"></i>
                      <button class="btn btn-info"><i class="fa fa-eye"></i>
                  </td>
                </tr>

                <tr>
                  <td>1</td>
                  <td>10006</td>
                  <td>Juan Perez </td>
                  <td>nacionalidad</td>
                  <td>
                    <div class="btn-group">
                      <button class="btn btn-info"><i class="fa fa-eye"></i>
                      <button class="btn btn-info"><i class="fa fa-eye"></i>
                  </td>
                </tr>
                <tr>
                  <td>1</td>
                  <td>10006</td>
                  <td>Juan Perez </td>
                  <td>nacionalidad</td>
                  <td>
                    <div class="btn-group">
                      <button class="btn btn-info"><i class="fa fa-eye"></i>
                      <button class="btn btn-info"><i class="fa fa-eye"></i>
                  </td>
                </tr>
               

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

            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-thumb-tack"></i></span> 

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
                  value='Ariel Bernardo' 
                  autocomplete="off">  

              </div>

            </div>

          <!-- ENTRADA PARA EL NOMBRE -->
            
          <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-star-half-full"></i></span> 

                <input type="text" 
                  class="form-control" 
                  placeholder="Descripcion...." 
                  id='idDescripcionApostilla' 
                  name='idDescripcionApostilla' 
                  value='Por nacimiento' 
                  autocomplete="off">     

              </div>

          </div>
          <!-- ENTRADA PARA EL NOMBRE -->
            
          <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-dollar"></i></span> 

                <input type="text" 
                  class="form-control" 
                  placeholder="Importe...." 
                  id='idImporteApostilla' 
                  name='idImporteApostilla' 
                  value='3500' 
                  autocomplete="off">     

              </div>

          </div>
          <?php

            $realizarPago = new ControladorVentas();
            $realizarPago -> ctrRealizarPagoVenta();

          ?>
           
  
          </div>  

        </div>

        <div class="modal-footer">

          <button type="button" class="btn btn-danger" id="seleccionarClienteDni">Ingresar Datos</button>

        </div>
        
    </div>

  </div>

</div>


       

