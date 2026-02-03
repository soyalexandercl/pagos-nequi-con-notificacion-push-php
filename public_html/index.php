<?php

// Ruta corregida para salir de public_html y buscar vendor
require_once __DIR__ . '/../vendor/autoload.php';

// Carga estricta del .env
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
} catch (\Exception $e) {
    die("Error crítico: No se pudo cargar el archivo .env. Asegúrate de que exista en la raíz.");
}

use App\Servicios\ServicioPago;

$servicio = new ServicioPago();

echo "<h1>Prueba Integración Nequi</h1>";

// Ejecución con var_dump para ver la respuesta real
$respuesta = $servicio->solicitarPagoPush("3235230270", 1000, "Cubitx");

echo "<pre>";
var_dump($respuesta);
echo "</pre>";