<?php

namespace App\Configuracion;

class ClienteNequi {
    protected $authBasico;
    protected $apiKey;
    protected $urlAuth;
    protected $urlApi;
    protected $nitNegocio;

    public function __construct() {
        $this->authBasico = $_ENV['NEQUI_AUTH_BASICA'];
        $this->apiKey = $_ENV['NEQUI_API_KEY'];
        $this->urlAuth = $_ENV['URL_AUTH_NEQUI'];
        $this->urlApi = $_ENV['URL_API_NEQUI'];
        $this->nitNegocio = $_ENV['NEQUI_NIT_NEGOCIO'];
    }

    public function obtenerToken() {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $this->urlAuth . "?grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Accept: application/json",
            "Authorization: Basic " . $this->authBasico,
            "Content-Type: application/x-www-form-urlencoded"
        ]);

        $respuesta = curl_exec($ch);
        $respuesta = json_decode($respuesta, true);

        curl_close($ch);

        return $respuesta;
    }

    protected function generarEncabezado($operacion) {
        return [
            "Channel" => "PNP04-C001",
            "RequestDate" => date('Y-m-d\TH:i:s.v\Z'),
            "MessageID" => uniqid(),
            "ClientID" => uniqid(),
            "Destination" => [
                "ServiceName" => "PaymentsService",
                "ServiceOperation" => $operacion,
                "ServiceRegion" => "C001",
                "ServiceVersion" => "1.2.0"
            ]
        ];
    }

    protected function ejecutarPeticion($endpoint, $estructura) {
        $token = $this->obtenerToken();

        $json = json_encode($estructura);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->urlApi . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $token['access_token'],
            'x-api-key: ' . $this->apiKey
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        $respuesta = curl_exec($ch);
        $respuesta = json_decode($respuesta, true);

        curl_close($ch);

        return $respuesta;
    }
}