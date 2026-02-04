<?php

namespace App\Servicios;

use App\Configuracion\ClienteNequi;

class ServicioNequi extends ClienteNequi {

    public function registrarPago($telefono, $monto, $referencia = "") {
        $estructura = [
            "RequestMessage" => [
                "RequestHeader" => $this->generarEncabezado("unregisteredPayment"),
                "RequestBody" => [
                    "any" => [
                        "unregisteredPaymentRQ" => [
                            "phoneNumber" => $telefono,
                            "code" => $this->nitNegocio,
                            "value" => $monto,
                            "reference1" => $referencia
                        ]
                    ]
                ]
            ]
        ];

        return $this->ejecutarPeticion("-services-paymentservice-unregisteredpayment", $estructura);
    }

    public function consultarPago($idTransaccion) {
        $estructura = [
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

        return $this->ejecutarPeticion("-services-paymentservice-getstatuspayment", $estructura);
    }

    public function cancelarPago($telefono, $idTransaccion) {
        $estructura = [
            "RequestMessage" => [
                "RequestHeader" => $this->generarEncabezado("cancelUnregisteredPayment"),
                "RequestBody" => [
                    "any" => [
                        "cancelUnregisteredPaymentRQ" => [
                            "code" => $this->nitNegocio,
                            "phoneNumber" => $telefono,
                            "transactionId" => $idTransaccion
                        ]
                    ]
                ]
            ]
        ];

        return $this->ejecutarPeticion("-services-paymentservice-cancelunregisteredpayment", $estructura);
    }

    public function revertirPago($telefono, $valor, $idMensaje) {
        $estructura = [
            "RequestMessage" => [
                "RequestHeader" => $this->generarEncabezado("reverseTransaction"),
                "RequestBody" => [
                    "any" => [
                        "reversionRQ" => [
                            "phoneNumber" => $telefono,
                            "value" => $valor,
                            "code" => $this->nitNegocio,
                            "messageId" => $idMensaje,
                            "type" => "payment"
                        ]
                    ]
                ]
            ]
        ];

        return $this->ejecutarPeticion("-services-reverseservices-reversetransaction", $estructura);
    }
}