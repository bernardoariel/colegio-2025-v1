<?php

$cuit = '30584197680';
$servicio = 'wsfe';
$base_dir = __DIR__ . "/extensiones/afip/$cuit/$servicio/token";

// Verificamos si existe, y si no, lo creamos
if (!file_exists($base_dir)) {
    if (!mkdir($base_dir, 0775, true)) {
        die("❌ No se pudo crear la carpeta: $base_dir");
    } else {
        echo "✅ Carpeta creada correctamente<br>";
    }
}

// Intentamos escribir el archivo
$archivo = $base_dir . "/prueba.txt";
$contenido = "Esto es una prueba escrita el " . date('Y-m-d H:i:s');

if (file_put_contents($archivo, $contenido)) {
    echo "✅ Archivo escrito correctamente en: $archivo";
} else {
    echo "❌ No se pudo escribir el archivo en: $archivo";
}

