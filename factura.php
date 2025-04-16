<?php 

$wsdl = "https://wswhomo.afip.gov.ar/wsfev1/service.asmx?WSDL"; // WSDL de homologación

$client = new SoapClient($wsdl, [
    "trace" => 1,
    "exceptions" => 1,
    "soap_version" => SOAP_1_1,
    "cache_wsdl" => WSDL_CACHE_NONE
]);

$params = [
    "Auth" => [
        "Token" => "PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/Pgo8c3NvIHZlcnNpb249IjIuMCI+CiAgICA8aWQgc3JjPSJDTj13c2FhaG9tbywgTz1BRklQLCBDPUFSLCBTRVJJQUxOVU1CRVI9Q1VJVCAzMzY5MzQ1MDIzOSIgZHN0PSJDTj13c2ZlLCBPPUFGSVAsIEM9QVIiIHVuaXF1ZV9pZD0iMjcxMzg3MTIzMiIgZ2VuX3RpbWU9IjE3NDQ4MjM0NzQiIGV4cF90aW1lPSIxNzQ0ODY2NzM0Ii8+CiAgICA8b3BlcmF0aW9uIHR5cGU9ImxvZ2luIiB2YWx1ZT0iZ3JhbnRlZCI+CiAgICAgICAgPGxvZ2luIGVudGl0eT0iMzM2OTM0NTAyMzkiIHNlcnZpY2U9IndzZmUiIHVpZD0iU0VSSUFMTlVNQkVSPUNVSVQgMjAyNDE1OTEzMTAsIENOPWFidGVzdCIgYXV0aG1ldGhvZD0iY21zIiByZWdtZXRob2Q9IjIyIj4KICAgICAgICAgICAgPHJlbGF0aW9ucz4KICAgICAgICAgICAgICAgIDxyZWxhdGlvbiBrZXk9IjIwMjQxNTkxMzEwIiByZWx0eXBlPSI0Ii8+CiAgICAgICAgICAgIDwvcmVsYXRpb25zPgogICAgICAgIDwvbG9naW4+CiAgICA8L29wZXJhdGlvbj4KPC9zc28+Cg==",
        "Sign" => "NrD/9zmHvh7T37Xgw2ylgR8KlBD1eAxq4FJo5Lz/XKArmP1DZRbWfW5jpVm4ZegTinH+8t+Up3i1ppwMnZVlFqqUVD+Qd+DdgIes97AAAIXNFdxphFWLHhValUIyav2AqWnGeP6jArvfaCfk9YfX5ufkgd0JB8dx9fjrZ9of430=",
        "Cuit" => 20241591310
    ],
    "FeCAEReq" => [
        "FeCabReq" => [
            "CantReg" => 1,
            "PtoVta" => 1,
            "CbteTipo" => 11
        ],
        "FeDetReq" => [
            "FECAEDetRequest" => [
                [
                    "Concepto" => 1,
                    "DocTipo" => 99,
                    "DocNro" => 0,
                    "CbteDesde" => 134,
                    "CbteHasta" => 134,
                    "CbteFch" => "20250415",
                    "ImpTotal" => 100.00,
                    "ImpTotConc" => 0.00,
                    "ImpNeto" => 100.00,
                    "ImpOpEx" => 0.00,
                    "ImpTrib" => 0.00,
                    "ImpIVA" => 0.00,
                    "MonId" => "PES",
                    "MonCotiz" => 1.00,
                    "CondicionIVAReceptorId" => 5 // Consumidor Final
                ]
            ]
        ]
    ]
];

try {
    $response = $client->FECAESolicitar($params);
    echo "<pre>";
    print_r($response);
    echo "</pre>";
} catch (SoapFault $fault) {
    echo "Error: " . $fault->getMessage();
}
