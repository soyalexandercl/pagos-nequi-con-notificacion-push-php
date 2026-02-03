<?php

namespace App\Servicios;

use App\Configuracion\ClienteNequi;

class ServicioPago extends ClienteNequi {

    public function solicitarPagoPush($telefono, $monto, $referencia = "") {
        $cuerpo = [
            "RequestMessage" => [
                "RequestHeader" => $this->generarEncabezado("unregisteredPayment"),
                "RequestBody" => [
                    "any" => [
                        "unregisteredPaymentRQ" => [
                            "phoneNumber" => $telefono,
                            "code" => getenv('NEQUI_NIT_NEGOCIO'),
                            "value" => $monto,
                            "reference1" => $referencia
                        ]
                    ]
                ]
            ]
        ];

        return $this->ejecutarPeticion("-services-paymentservice-unregisteredpayment", $cuerpo);
    }

    public function consultarEstadoPago($idTransaccion) {
        $cuerpo = [
            "RequestMessage" => [
                "RequestHeader" => $this->generarEncabezado("getStatusPayment"),
                "RequestBody" => [
                    "any" => [
                        "getStatusPaymentRQ" => [
                            "codeQR" => $idTransaccion
                        ]
                    ]
                ]
            ]
        ];

        return $this->ejecutarPeticion("-services-paymentservice-getstatuspayment", $cuerpo);
    }
}