<?php

require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";

if (isset($_POST["fechaInicio"]) && isset($_POST["fechaFin"])) {
    $fechaInicio = $_POST["fechaInicio"];
    $fechaFin = $_POST["fechaFin"];

    // Llama al controlador para obtener los datos
    $respuesta = ControladorVentas::ctrResumenVentasPorFecha($fechaInicio, $fechaFin);

    // Devuelve la respuesta como una tabla HTML
    if (!empty($respuesta)) {
        echo '<table class="table table-bordered table-striped">';
        echo '<thead><tr><th>Producto</th><th>Total Cantidad</th><th>Total Ventas</th></tr></thead>';
        echo '<tbody>';
        foreach ($respuesta as $row) {
            echo "<tr>
                    <td>{$row['descripcion']}</td>
                    <td>{$row['total_cantidad']}</td>
                    <td>{$row['total_ventas']}</td>
                  </tr>";
        }
        echo '</tbody></table>';
    } else {
        echo "<div class='alert alert-info'>No se encontraron resultados para el rango seleccionado.</div>";
    }
}
