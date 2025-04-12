<?php

class Modo {
    public $nombre;
    public $local;

    public function __construct($nombre, $local = false) {
        $this->nombre = strtoupper($nombre);
        $this->local = $local;
    }

    public function esProduccion() {
        return $this->nombre === "PRODUCCION";
    }

    public function esDeveloper() {
        return $this->nombre === "DEVELOPER";
    }
}

// CONFIGURACIÓN DEL ENTORNO ACTUAL
$modo = new Modo("PRODUCCION", false); // Cambiá a true si estás en entorno local

// Variables globales opcionales
$CUIT = $modo->esProduccion() ? 30584197680 : 20241591310;
$MODO = $modo->esProduccion() ? Wsaa::MODO_PRODUCCION : Wsaa::MODO_HOMOLOGACION;

?>
