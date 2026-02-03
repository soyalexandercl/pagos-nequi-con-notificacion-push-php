<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

use App\Servicios\ServicioPago;

$servicio = new ServicioPago();

$respuesta = $servicio->solicitarPagoPush("3235230270", 1000, "Cubitx");

echo "<pre>";
var_dump($respuesta);
echo "</pre>";