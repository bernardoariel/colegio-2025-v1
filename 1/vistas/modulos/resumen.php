<div class="content-wrapper">

  <section class="content-header">
    <h1>Resumen de Ventas</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Resumen de Ventas</li>
    </ol>
  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
        <h3 class="box-title">Consultar Ventas</h3>
      </div>

      <div class="box-body">

        <!-- Fila para las fechas y el botón -->
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="fechaInicio">Fecha de Inicio:</label>
              <div class="input-group date" id="fechaInicioPicker">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control" id="fechaInicio" placeholder="Seleccionar fecha de inicio">
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label for="fechaFin">Fecha de Fin:</label>
              <div class="input-group date" id="fechaFinPicker">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control" id="fechaFin" placeholder="Seleccionar fecha de fin">
              </div>
            </div>
          </div>

          <div class="col-md-2">
            <div class="form-group">
              <label>&nbsp;</label>
              <div>
                <button id="btnConsultar" class="btn btn-primary" style="width: 100%;" disabled>Consultar</button>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

    <div class="box">

      <div class="box-header with-border">
        <h3 class="box-title">Resultados</h3>
        <a href="#" id="btnDescargarExcel" class="btn btn-success pull-right" style="display: none;">
          <i class="fa fa-file-excel-o"></i> Descargar Excel
        </a>
      </div>

      <div class="box-body">
        <!-- Contenedor para los resultados -->
        <div id="resultado">
          <!-- Aquí se mostrarán los resultados obtenidos -->
        </div>
      </div>

    </div>

  </section>

</div>
<script>
  $(document).ready(function () {
      // Inicializar Date Pickers
      $('#fechaInicioPicker').datepicker({
          autoclose: true,
          format: 'yyyy-mm-dd', // Formato compatible con MySQL
          todayHighlight: true
      });

      $('#fechaFinPicker').datepicker({
          autoclose: true,
          format: 'yyyy-mm-dd', // Formato compatible con MySQL
          todayHighlight: true
      });

      // Habilitar el botón "Consultar" solo si ambos campos tienen valor
      function toggleConsultarButton() {
          const fechaInicio = $("#fechaInicio").val();
          const fechaFin = $("#fechaFin").val();
          if (fechaInicio && fechaFin) {
              $("#btnConsultar").attr("disabled", false);
          } else {
              $("#btnConsultar").attr("disabled", true);
          }
      }

      $("#fechaInicio, #fechaFin").on("change", toggleConsultarButton);

      // Manejar el evento del botón "Consultar"
      $("#btnConsultar").click(function () {
          var fechaInicio = $("#fechaInicio").val();
          var fechaFin = $("#fechaFin").val();

          // Mostrar el spinner y limpiar resultados previos
          $("#resultado").html(`
            <div class="text-center">
              <i class="fa fa-spinner fa-spin fa-3x"></i>
              <p>Consultando...</p>
            </div>
          `);
          $("#btnConsultar").attr("disabled", true);

          // Realizar la solicitud AJAX
          $.ajax({
              url: "ajax/resumen.ajax.php", // Archivo PHP que procesará la solicitud
              method: "POST",
              data: { fechaInicio: fechaInicio, fechaFin: fechaFin },
              success: function (response) {
                  // Mostrar resultados
                  $("#resultado").html(response);

                  // Mostrar el botón "Descargar Excel" si hay datos
                  if ($("#resultado table tr").length > 1) { // Verificar si la tabla tiene filas
                      $("#btnDescargarExcel").show();
                  } else {
                      $("#btnDescargarExcel").hide();
                  }

                  $("#btnConsultar").attr("disabled", false);
              },
              error: function (error) {
                  console.log("Error:", error);
                  $("#resultado").html("<div class='alert alert-danger'>Ocurrió un error al realizar la consulta.</div>");
                  $("#btnDescargarExcel").hide();
                  $("#btnConsultar").attr("disabled", false);
              }
          });
      });

      // Manejar el evento del botón "Descargar Excel"
      $("#btnDescargarExcel").click(function () {
          var fechaInicio = $("#fechaInicio").val();
          var fechaFin = $("#fechaFin").val();

          // Redirigir al archivo PHP para descargar el Excel
          window.location.href = `ajax/descargar_excelResumen.ajax.php?fechaInicio=${fechaInicio}&fechaFin=${fechaFin}`;
      });
  });
</script>
