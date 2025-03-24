<?php

require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";

if (isset($_POST['folio'])) {
    $folio = intval($_POST['folio']);
    $resultado = ControladorVentas::ctrBuscarFolio($folio);

    if (isset($resultado['error'])) {
        echo "<div class='alert alert-danger'>{$resultado['error']}</div>";
    } else {
        $venta = $resultado['venta'];
        $producto = $resultado['producto'];

        // Generar enlace a la factura
        $linkFactura = "http://localhost/colegio/extensiones/fpdf/pdf/facturaElectronica.php?id={$venta['id']}";

        echo "
            <div class='alert alert-success'>
                <h4>Folio encontrado</h4>
                <p><strong>Venta ID:</strong> {$venta['id']}</p>
                <p><strong>Fecha:</strong> {$venta['fecha']}</p>
                <p><strong>Cliente:</strong> {$venta['nombre']} ({$venta['documento']})</p>
                <p><strong>Producto:</strong> {$producto['descripcion']}</p>
                <p><strong>Rango de folios:</strong> {$producto['folio1']} - {$producto['folio2']}</p>
                <p><strong>Total:</strong> {$producto['total']}</p>
                <p><strong>Factura:</strong> <a href='{$linkFactura}' target='_blank' class='btn btn-success'>Ver Factura</a></p>
            </div>
        ";
    }
}
?>
