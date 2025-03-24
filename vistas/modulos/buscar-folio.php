<div class="content-wrapper">

  <section class="content-header">
    <h1>Buscar Folio</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Buscar Folio</li>
    </ol>
  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
        <h3 class="box-title">Ingrese el Folio</h3>
      </div>

      <div class="box-body">

        <!-- Input para ingresar el folio -->
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="folio">Folio:</label>
              <input type="number" class="form-control" id="folio" placeholder="Ingrese el folio a buscar">
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label>&nbsp;</label>
              <button id="btnBuscarFolio" class="btn btn-primary btn-block" disabled>Buscar</button>
            </div>
          </div>
        </div>

      </div>

    </div>

    <div class="box">

      <div class="box-header with-border">
        <h3 class="box-title">Resultados</h3>
      </div>

      <div class="box-body">
        <!-- Contenedor para mostrar resultados -->
        <div id="resultado" class="text-center">
          <!-- Aquí se mostrarán los resultados -->
        </div>
      </div>

    </div>

  </section>

</div>

<script>
  $(document).ready(function () {
    // Habilitar el botón solo si hay un folio ingresado
    $("#folio").on("input", function () {
      if ($(this).val()) {
        $("#btnBuscarFolio").attr("disabled", false);
      } else {
        $("#btnBuscarFolio").attr("disabled", true);
      }
    });

    // Manejar la búsqueda del folio
    $("#btnBuscarFolio").click(function () {
      var folio = $("#folio").val();

      // Verificar que el folio esté ingresado
      if (!folio) {
        alert("Por favor, ingrese un folio válido.");
        return;
      }

      // Mostrar spinner y deshabilitar el botón
      $("#btnBuscarFolio").attr("disabled", true);
      $("#resultado").html(`<!-- Spinner inicial -->
        <div class="text-center spinner">
          <i class="fa fa-spinner fa-spin fa-3x"></i>
          <p>Buscando en la base de datos de escribanos...</p>
        </div>
      `);

      // Primera búsqueda en la base de datos de escribanos
      $.ajax({
        url: "ajax/buscarFolioEscribanos.ajax.php", // Archivo PHP para buscar en escribanos
        method: "POST",
        data: { folio: folio },
        success: function (response) {
          // Mostrar los resultados de escribanos
          $("#resultado").html(response);

          // Iniciar la búsqueda en la base de datos de colegio
          $("#resultado").append(`
            <div class="text-center spinner">
              <i class="fa fa-spinner fa-spin fa-3x"></i>
              <p>Buscando en la base de datos de colegio...</p>
            </div>
          `);

          $.ajax({
            url: "ajax/buscarFolioColegio.ajax.php", // Archivo PHP para buscar en colegio
            method: "POST",
            data: { folio: folio },
            success: function (response) {
              // Agregar resultados de colegio
              $("#resultado").append(response);

              // Eliminar los spinners
              $(".spinner").remove();
              $("#btnBuscarFolio").attr("disabled", false);
            },
            error: function (error) {
              console.log("Error:", error);

              // Eliminar los spinners y mostrar un error
              $(".spinner").remove();
              $("#resultado").append("<div class='alert alert-danger'>Ocurrió un error en la búsqueda de colegio.</div>");
              $("#btnBuscarFolio").attr("disabled", false);
            }
          });
        },
        error: function (error) {
          console.log("Error:", error);

          // Eliminar el spinner y mostrar un error
          $(".spinner").remove();
          $("#resultado").html("<div class='alert alert-danger'>Ocurrió un error en la búsqueda de escribanos.</div>");
          $("#btnBuscarFolio").attr("disabled", false);
        }
      });
    });
  });
</script>

