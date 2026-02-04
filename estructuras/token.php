<?php

// Cadena de autenticación codificada en Base64 (ClienteID:ClienteSecret)
$auth_basic = "CLIENTE_ID_CLIENTE_SECRET";

// Inicializar la sesión de cURL
$ch = curl_init();

// Configurar la URL del endpoint de Nequi para solicitar el token
curl_setopt($ch, CURLOPT_URL, "https://oauth.nequi.com/oauth2/token?grant_type=client_credentials");

// Indicar que la petición es de tipo POST
curl_setopt($ch, CURLOPT_POST, true);

// Importante: Retorna la respuesta como string en la variable en lugar de imprimirla en pantalla
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Configurar los encabezados (Headers) necesarios para la API
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Accept: application/json", // Indicamos que esperamos una respuesta JSON
    "Authorization: Basic " . $auth_basic, // Enviamos las credenciales Basic Auth
    "Content-Type: application/x-www-form-urlencoded" // Tipo de contenido requerido por OAuth2
]);

// Ejecutar la petición y almacenar la respuesta cruda (string)
$respuesta = curl_exec($ch);

// Decodificar el JSON recibido. 
// Se agrega 'true' como segundo parámetro para convertirlo en un array asociativo.
$respuesta = json_decode($respuesta, true);

// Imprimir la estructura de la respuesta para depuración
var_dump($respuesta);