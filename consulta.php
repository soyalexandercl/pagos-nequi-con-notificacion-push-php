<?php

// Token de acceso (JWT) obtenido en el proceso de autenticación
$token = "TOKEN";

// API Key proporcionada por Nequi para identificar la aplicación
$api_key = "API_KEY";

// Fecha actual en formato ISO 8601 con milisegundos (Requerido por Nequi)
$fecha = date('Y-m-d\TH:i:s.v\Z');

// Generar un ID único para identificar este mensaje específico
$id_mensaje = uniqid();

// ID de la transacción o pago que queremos consultar
// (Este valor se obtuvisto en la respuesta del cobro "unregisteredPayment")
$id_transaccion = "ID_TRANSACCION";

// Construir la estructura completa del cuerpo de la petición (Payload)
$estructura = [
    "RequestMessage" => [
        "RequestHeader" => [
            "Channel" => "PNP04-C001",
            "RequestDate" => $fecha,
            "MessageID" => $id_mensaje, // ID único
            "ClientID" => "12345",
            "Destination" => [
                "ServiceName" => "PaymentsService",
                "ServiceOperation" => "getStatusPayment", // Operación: Consultar estado
                "ServiceRegion" => "C001",
                "ServiceVersion" => "1.0.0"
            ]
        ],
        "RequestBody" => [
            "any" => [
                "getStatusPaymentRQ" => [
                    "codeQR" => $id_transaccion 
                ]
            ]
        ]
    ]
];

// Codificar el array de datos a formato JSON para enviarlo
$json = json_encode($estructura);

// Inicializar la sesión de cURL
$ch = curl_init();

// Configurar la URL del endpoint de Nequi para consulta de estado
curl_setopt($ch, CURLOPT_URL, "https://api.nequi.com/payments/v2/-services-paymentservice-getstatuspayment");

// Importante: Retorna la respuesta como string en la variable en lugar de imprimirla en pantalla
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Indicar que la petición es de tipo POST
curl_setopt($ch, CURLOPT_POST, true);

// Configurar los encabezados (Headers) necesarios para la API
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json', // Indicamos que el cuerpo es JSON
    'Accept: application/json', // Indicamos que esperamos respuesta JSON
    'Authorization: Bearer ' . $token, // Enviamos el Token de acceso
    'x-api-key: ' . $api_key // Enviamos la API Key
]);

// Adjuntar el cuerpo JSON a la petición POST
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

// Ejecutar la petición y almacenar la respuesta cruda (string)
$respuesta = curl_exec($ch);

// Decodificar el JSON recibido. 
// Se agrega 'true' como segundo parámetro para convertirlo en un array asociativo.
$respuesta = json_decode($respuesta, true);

// Imprimir la estructura de la respuesta para depuración
var_dump($respuesta);