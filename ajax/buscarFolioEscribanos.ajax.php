<?php


require_once "../controladores/escribanos.controlador.php";
require_once "../modelos/escribanosDB.modelo.php";

if (isset($_POST['folio'])) {
    $folio = intval($_POST['folio']);
    $resultados = ModeloEscribanosDB::mdlBuscarFolioEscribanos($folio);

    $fechaInicioBusqueda = "2017-08-30";
    $fechaFinBusqueda = "2018-08-28";

    if (empty($resultados)) {
        echo "<div class='alert alert-info'>No se encontraron folios en la base de datos de escribanos desde <strong>{$fechaInicioBusqueda}</strong> hasta <strong>{$fechaFinBusqueda}</strong>.</div>";
    } else {
        echo "<div class='alert alert-info'>";
        echo "<h4>Resultados en la base de datos de escribanos:</h4>";
        echo "<p><strong>Rango de búsqueda:</strong> Desde <strong>{$fechaInicioBusqueda}</strong> hasta <strong>{$fechaFinBusqueda}</strong></p></div>";

        foreach ($resultados as $resultado) {
            echo "<br><div class='alert alert-info'>
                <div style='margin-bottom: 15px; border: 1px solid #ccc; padding: 10px; border-radius: 5px;'>
                    <p><strong>ID:</strong> {$resultado['idctaart']}</p>
                    <p><strong>Fecha:</strong> {$resultado['fecha_escribano']}</p>
                    <p><strong>Persona:</strong> {$resultado['nombreescribano']}</p>
                    <p><strong>Producto:</strong> {$resultado['producto_nombre']}</p>
                    <p><strong>Rango de folios:</strong> {$resultado['folio1']} - {$resultado['folio2']}</p>
                    <p><strong>Cantidad:</strong> {$resultado['cantidad']}</p>
                    <p><strong>Importe:</strong> {$resultado['importe']}</p>
                </div>
                </div>
            ";
        }

        echo "</div>";
    }
}
