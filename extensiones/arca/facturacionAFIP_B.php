<?php
require_once __DIR__ . '/wsaa.php';
require_once __DIR__ . '/wsfev1.php';

function generarFacturaAFIP($header, $body) {
    $modoTexto = strtolower($header['modo']);
    $modoAFIP = $modoTexto === 'produccion' ? Wsaa::MODO_PRODUCCION : Wsaa::MODO_HOMOLOGACION;

    $afip = new Wsfev1($header['cuit'], $modoAFIP);

    $ultimo = $afip->consultarUltimoComprobanteAutorizado($header['ptoVta'], $header['tipoCbte']);
    $nroAEmitir = $ultimo + 1;

    $voucher = array_merge([
        "numeroComprobante" => $nroAEmitir,
        "numeroPuntoVenta" => $header['ptoVta'],
        "codigoTipoComprobante" => $header['tipoCbte'],
        "letra" => "B",
        "canmismaMoneda" => "N"
    ], $body);

    $res = $afip->emitirComprobante($voucher);

    return [
        "modo" => $modoTexto,
        "ultimoComprobanteAutorizado" => $ultimo,
        "nroComprobante" => $nroAEmitir,
        "cae" => $res["cae"],
        "fechaVencimientoCAE" => $res["fechaVencimientoCAE"],
        "observaciones" => $res["observaciones"] ?? [],
        "cuit" => $header['cuit']
    ];
}
