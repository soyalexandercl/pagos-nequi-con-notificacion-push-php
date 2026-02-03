<?php

namespace App\Configuracion;

class ClienteNequi {
    private $authBasico;
    protected $apiKey;
    protected $urlBase;

    public function __construct() {
        // En una implementación real, usar una librería como phpdotenv
        $this->authBasico = getenv('NEQUI_AUTH_BASICA');
        $this->apiKey = getenv('NEQUI_API_KEY');
        $this->urlBase = getenv('URL_API_NEQUI');
    }

    /**
     * Obtiene el token JWT mediante Basic Auth
     *
     */
    protected function obtenerTokenAcceso() {
        $url = getenv('URL_AUTH_NEQUI') . "?grant_type=client_credentials";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Basic " . $this->authBasico,
            "Content-Type: application/x-www-form-urlencoded"
        ]);

        $respuesta = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $respuesta['access_token'] ?? null;
    }

    /**
     * Ejecuta peticiones POST estandarizadas hacia Nequi
     */
    protected function ejecutarPeticion($endpoint, $payload) {
        $token = $this->obtenerTokenAcceso();
        $urlCompleta = $this->urlBase . $endpoint;

        $ch = curl_init($urlCompleta);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $token,
            'x-api-key: ' . $this->apiKey
        ]);

        $respuesta = curl_exec($ch);
        curl_close($ch);

        return json_decode($respuesta, true);
    }

    protected function generarEncabezado($operacion) {
        return [
            "Channel" => "PNP04-C001",
            "RequestDate" => date('Y-m-d\TH:i:s.v\Z'),
            "MessageID" => uniqid(),
            "ClientID" => "123",
            "Destination" => [
                "ServiceName" => "PaymentsService",
                "ServiceOperation" => $operacion,
                "ServiceRegion" => "C001",
                "ServiceVersion" => "1.2.0"
            ]
        ];
    }
}