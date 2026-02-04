<?php

require_once __DIR__ . '/../vendor/autoload.php';

$env = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$env->load();

use App\Servicios\ServicioNequi;

$servicio = new ServicioNequi();

$telefono = "NUMERO_CLIENTE";
$valor = "VALOR";
$referencia = "REFERENCIA_PAGO";

$respuesta = $servicio->registrarPago($telefono, $valor, $referencia);

echo "<pre>";
var_dump($respuesta);
echo "</pre>";