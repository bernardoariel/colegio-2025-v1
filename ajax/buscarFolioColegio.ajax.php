<?php

require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";

if (isset($_POST['folio'])) {
    $folio = intval($_POST['folio']);
    $resultados = ControladorVentas::ctrBuscarFolio($folio);

    $fechaInicioBusqueda = "2018-08-01";
    $fechaFinBusqueda = (new DateTime())->format('Y-m-d');

    echo "<div class='alert alert-success'>";
    echo "<h4>Resultados en la base de datos de colegio:</h4>";
    echo "<p><strong>Rango de búsqueda:</strong> Desde <strong>{$fechaInicioBusqueda}</strong> hasta <strong>{$fechaFinBusqueda}</strong></p></div>";

    // Verificar si el resultado es un string (mensaje de error) o un array
    if (is_string($resultados)) {
        // Mostrar el mensaje de error
        echo "<p>Error: {$resultados}</p>";
    } elseif (is_array($resultados) && !empty($resultados)) {
        // Mostrar resultados
        foreach ($resultados as $resultado) {
            if (is_array($resultado)) {
                $venta = isset($resultado['venta']) ? $resultado['venta'] : null;
                $producto = isset($resultado['producto']) ? $resultado['producto'] : null;
                $factura = isset($resultado['factura']) ? $resultado['factura'] : null;
        
                if ($venta && $producto) {
                    echo "
                    <br>
                    <div class='alert alert-success'>
                        <div style='margin-bottom: 15px; border: 1px solid #ccc; padding: 10px; border-radius: 5px;'>
                            <p><strong>Venta ID:</strong> {$venta['id']}</p>
                            <p><strong>Fecha:</strong> {$venta['fecha']}</p>
                            <p><strong>Cliente:</strong> {$venta['nombre']}</p>
                            <p><strong>Producto:</strong> {$producto['descripcion']}</p>
                            <p><strong>Rango de folios:</strong> {$producto['folio1']} - {$producto['folio2']}</p>
                            <p><strong>Total:</strong> {$producto['total']}</p>
                            <p><strong>Factura:</strong> {$factura}</p>
                        </div>
                    </div>
                    ";
                } else {
                    echo "<p>Datos incompletos para este resultado.</p>";
                }
            } else {
                echo "<p>Sin Resultados.</p>";
            }
        }
        
    } else {
        // Caso donde no se encontraron resultados
        echo "<p>No se encontraron folios en la base de datos de colegio desde <strong>{$fechaInicioBusqueda}</strong> hasta <strong>{$fechaFinBusqueda}</strong>.</p>";
    }

    echo "</div>";
}
