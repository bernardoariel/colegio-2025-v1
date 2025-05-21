<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['FCHeader']) || !isset($data['FCBody'])) {
    http_response_code(400);
    echo json_encode(["error" => "Se requiere un objeto con FCHeader y FCBody"]);
    exit;
}

$header = $data['FCHeader'];
if (
    !isset($header['cuit']) ||
    !isset($header['ptoVta']) ||
    !isset($header['tipoCbte']) ||
    !isset($header['modo'])
) {
    http_response_code(400);
    echo json_encode(["error" => "FCHeader debe incluir cuit, ptoVta, tipoCbte y modo"]);
    exit;
}

// Específico para Factura A
require_once __DIR__ . '/../facturacionAFIP_A.php';

try {
    $resultado = generarFacturaAFIP($header, $data['FCBody']);
    echo json_encode(["exito" => true, "resultado" => $resultado]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
