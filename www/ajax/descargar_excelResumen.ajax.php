<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=ResumenVentas.xls");

// Validar los parámetros recibidos
if (!isset($_GET['fechaInicio']) || !isset($_GET['fechaFin'])) {
    echo "Parámetros inválidos.";
    exit;
}

$fechaInicio = $_GET['fechaInicio'];
$fechaFin = $_GET['fechaFin'];

// Importar controladores y modelos
require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";

// Consultar datos de la base de datos
$respuesta = ControladorVentas::ctrResumenVentasPorFecha($fechaInicio, $fechaFin);

// Verificar si hay datos
if (!$respuesta || count($respuesta) == 0) {
    echo "No se encontraron datos para el rango de fechas seleccionado.";
    exit;
}

// Generar la tabla en formato HTML para Excel
echo "<table border='1'>
        <thead>
          <tr>
            <th>Producto</th>
            <th>Total Cantidad</th>
            <th>Total Ventas</th>
          </tr>
        </thead>
        <tbody>";

foreach ($respuesta as $row) {
    echo "<tr>
            <td>{$row['descripcion']}</td>
            <td>{$row['total_cantidad']}</td>
            <td>{$row['total_ventas']}</td>
          </tr>";
}

echo "  </tbody>
      </table>";
?>
