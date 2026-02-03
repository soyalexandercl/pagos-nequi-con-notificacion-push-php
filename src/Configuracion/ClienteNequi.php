<?php

namespace App\Configuracion;

class ClienteNequi {
    private $authBasico;
    protected $apiKey;
    protected $urlBase;

    public function __construct() {
        // Usamos getenv con un fallback vacío para evitar errores de concatenación
        $this->authBasico = getenv('NEQUI_AUTH_BASICA') ?: '';
        $this->apiKey = getenv('NEQUI_API_KEY') ?: '';
        $this->urlBase = getenv('URL_API_NEQUI') ?: '';
    }

    protected function obtenerTokenAcceso() {
        $urlAuth = getenv('URL_AUTH_NEQUI');
        $ch = curl_init($urlAuth . "?grant_type=client_credentials");
        
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

    protected function ejecutarPeticion($endpoint, $payload) {
        $token = $this->obtenerTokenAcceso();
        
        // Verificación de URL para evitar el error "Could not resolve host"
        if (empty($this->urlBase)) {
            return ["error" => "La URL base de la API no está definida en el .env"];
        }

        $urlCompleta = $this->urlBase . ltrim($endpoint, '/');

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
        
        if ($respuesta === false) {
            $error = curl_error($ch);
            curl_close($ch);
            return ["error_curl" => $error];
        }

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