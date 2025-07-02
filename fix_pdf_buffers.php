<?php
/**
 * Script para corregir problemas de buffer en archivos PDF
 * Este script revisa y corrige automáticamente todos los archivos PDF
 * que puedan tener problemas con la salida de datos antes de generar el PDF
 */

// Configurar manejo de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Directorio de archivos PDF
$pdfDir = 'www/extensiones/fpdf/pdf/';

// Función para agregar manejo de buffer al inicio de un archivo
function addBufferHandling($filePath) {
    $content = file_get_contents($filePath);
    
    // Verificar si ya tiene manejo de buffer
    if (strpos($content, 'ob_clean()') !== false || strpos($content, 'ob_start()') !== false) {
        echo "✅ $filePath ya tiene manejo de buffer\n";
        return false;
    }
    
    // Buscar la primera línea después de <?php
    $lines = explode("\n", $content);
    $newLines = [];
    $added = false;
    
    foreach ($lines as $line) {
        $newLines[] = $line;
        
        // Agregar manejo de buffer después de <?php
        if (!$added && trim($line) === '<?php') {
            $newLines[] = '';
            $newLines[] = '// Limpiar cualquier salida previa y configurar buffer';
            $newLines[] = 'ob_clean();';
            $newLines[] = 'ob_start();';
            $newLines[] = '';
            $newLines[] = '// Configurar headers para PDF';
            $newLines[] = 'header(\'Content-Type: application/pdf\');';
            $newLines[] = 'header(\'Cache-Control: no-cache, no-store, must-revalidate\');';
            $newLines[] = 'header(\'Pragma: no-cache\');';
            $newLines[] = 'header(\'Expires: 0\');';
            $newLines[] = '';
            $added = true;
        }
    }
    
    // Escribir el archivo modificado
    file_put_contents($filePath, implode("\n", $newLines));
    echo "✅ Agregado manejo de buffer a $filePath\n";
    return true;
}

// Función para agregar manejo de buffer al final de un archivo
function addBufferEndHandling($filePath) {
    $content = file_get_contents($filePath);
    
    // Verificar si ya tiene manejo de buffer al final
    if (strpos($content, 'ob_end_flush()') !== false) {
        echo "✅ $filePath ya tiene manejo de buffer al final\n";
        return false;
    }
    
    // Buscar la línea de $pdf->Output();
    if (strpos($content, '$pdf->Output();') !== false) {
        $content = str_replace(
            '$pdf->Output();',
            '$pdf->Output();' . "\n\n" . '// Limpiar y enviar el buffer' . "\n" . 'ob_end_flush();' . "\n" . 'exit();',
            $content
        );
        
        file_put_contents($filePath, $content);
        echo "✅ Agregado manejo de buffer al final de $filePath\n";
        return true;
    }
    
    return false;
}

// Función para procesar un archivo
function processFile($filePath) {
    echo "🔍 Procesando: $filePath\n";
    
    $modified = false;
    
    // Agregar manejo de buffer al inicio
    if (addBufferHandling($filePath)) {
        $modified = true;
    }
    
    // Agregar manejo de buffer al final
    if (addBufferEndHandling($filePath)) {
        $modified = true;
    }
    
    if (!$modified) {
        echo "ℹ️  $filePath no requiere modificaciones\n";
    }
    
    echo "\n";
}

// Función para escanear directorio recursivamente
function scanDirectory($dir) {
    $files = glob($dir . '*.php');
    
    foreach ($files as $file) {
        if (is_file($file)) {
            processFile($file);
        }
    }
}

// Ejecutar el script
echo "🚀 Iniciando corrección de archivos PDF...\n\n";

if (is_dir($pdfDir)) {
    scanDirectory($pdfDir);
    echo "✅ Proceso completado\n";
} else {
    echo "❌ Error: No se encontró el directorio $pdfDir\n";
}

echo "\n📋 Resumen de cambios:\n";
echo "- Se agregó manejo de buffer al inicio de archivos que no lo tenían\n";
echo "- Se agregó manejo de buffer al final de archivos que no lo tenían\n";
echo "- Se configuraron headers apropiados para PDF\n";
echo "- Se agregó limpieza y salida limpia del buffer\n";
?> 