<?php

require_once "conexion.php";

class ModeloEscribanosDB{

// Método para buscar folios en la base de datos `escribanos`
static public function mdlBuscarFolioEscribanos($folio) {
    try {
        $stmt = Conexion::conectarEscribanos()->prepare("
            SELECT 
                ca.idctaart,
                ca.idcta,
                ca.idproducto,
                ca.nombre AS producto_nombre,
                ca.cantidad,
                ca.importe,
                ca.folio1,
                ca.folio2,
                c.fecha AS fecha_escribano,
                c.nombreescribano
            FROM cta_art ca
            INNER JOIN cta c ON ca.idcta = c.idcta
            WHERE ca.folio1 <= :folio AND ca.folio2 >= :folio
        ");
        $stmt->bindParam(":folio", $folio, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}



}