<?php

namespace App\Servicios;

use App\Configuracion\ClienteNequi;

class ServicioTransaccion extends ClienteNequi {

    /**
     * Cancela un pago que aún no ha sido procesado por el usuario
     *
     */
    public function cancelarPagoPendiente($telefono, $idTransaccion) {
        $cuerpo = [
            "RequestMessage" => [
                "RequestHeader" => $this->generarEncabezado("cancelUnregisteredPayment"),
                "RequestBody" => [
                    "any" => [
                        "cancelUnregisteredPaymentRQ" => [
                            "code" => getenv('NEQUI_NIT_NEGOCIO'),
                            "phoneNumber" => $telefono,
                            "transactionId" => $idTransaccion
                        ]
                    ]
                ]
            ]
        ];

        return $this->ejecutarPeticion("-services-paymentservice-cancelunregisteredpayment", $cuerpo);
    }

    /**
     * Revierte un pago ya exitoso
     *
     */
    public function revertirPagoExitoso($telefono, $monto, $idMensajeOriginal) {
        $encabezado = $this->generarEncabezado("reverseTransaction");
        $encabezado["Destination"]["ServiceName"] = "ReverseServices"; // Cambio de servicio para reversos

        $cuerpo = [
            "RequestMessage" => [
                "RequestHeader" => $encabezado,
                "RequestBody" => [
                    "any" => [
                        "reversionRQ" => [
                            "phoneNumber" => $telefono,
                            "value" => $monto,
                            "code" => getenv('NEQUI_NIT_NEGOCIO'),
                            "messageId" => $idMensajeOriginal,
                            "type" => "payment"
                        ]
                    ]
                ]
            ]
        ];

        return $this->ejecutarPeticion("-services-reverseservices-reversetransaction", $cuerpo);
    }
}