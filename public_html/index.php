<?php

require_once 'vendor/autoload.php';

use App\Servicios\ServicioPago;

$servicio = new ServicioPago();
$servicio->solicitarPagoPush("3235230270", 1000, "Cubitx");