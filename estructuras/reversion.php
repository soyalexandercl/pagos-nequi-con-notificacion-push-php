<?php

// Token de acceso (JWT) obtenido en el proceso de autenticación
$token = "TOKEN";

// API Key proporcionada por Nequi para identificar la aplicación
$api_key = "API_KEY";

// Fecha actual en formato ISO 8601 con milisegundos (Requerido por Nequi)
$fecha = date('Y-m-d\TH:i:s.v\Z');

// Generar un ID único para identificar este mensaje específico
$id_mensaje = uniqid();

// Datos del pago original a reversar
$telefono = "NUMERO_CLIENTE";
$valor = "VALOR_COBRO";
$id_mensaje_cobro = "ID_MENSAJE"; // MessageID del cobro original

// Construir la estructura completa del cuerpo de la petición (Payload)
$estructura = [
    "RequestMessage" => [
        "RequestHeader" => [
            "Channel" => "PNP04-C001",
            "RequestDate" => $fecha,
            "MessageID" => $id_mensaje,
            "ClientID" => "12345",
            "Destination" => [
                "ServiceName" => "ReverseServices",
                "ServiceOperation" => "reverseTransaction", // Operación: Reversar transacción
                "ServiceRegion" => "C001",
                "ServiceVersion" => "1.0.0"
            ]
        ],
        "RequestBody" => [
            "any" => [
                "reversionRQ" => [
                    "phoneNumber" => $telefono,
                    "value" => $valor,
                    "code" => "NIT_NEGOCIO_CC_NATURAL",
                    "messageId" => $id_mensaje_cobro,
                    "type" => "payment"
                ]
            ]
        ]
    ]
];

// Codificar el array de datos a formato JSON
$json = json_encode($estructura);

// Inicializar la sesión de cURL
$ch = curl_init();

// Configurar la URL del endpoint de Nequi para reversiones
curl_setopt($ch, CURLOPT_URL, "https://api.nequi.com/payments/v2/-services-reverseservices-reversetransaction");

// Configuración estándar de cURL para la API de Nequi
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Bearer ' . $token,
    'x-api-key: ' . $api_key
]);

// Adjuntar el cuerpo JSON
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

// Ejecutar la petición
$respuesta = curl_exec($ch);

// Decodificar la respuesta
$respuesta = json_decode($respuesta, true);

// Imprimir respuesta para depuración
var_dump($respuesta);