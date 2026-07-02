<?php

namespace App\Services;

use Config\Services;
use Exception;

class RajaOngkirService
{
    protected $client;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = Services::curlrequest([
            'timeout'     => 10,
            'http_errors' => false, // Mencegah cURL langsung crash jika HTTP status 4xx atau 5xx
        ]);

        $this->apiKey = 'OovXNo056cc164a8dbc77f26gczTW6Sw';
        $this->baseUrl = 'https://rajaongkir.komerce.id/api/v1/';
    }
    
    /**
     * Mengambil data destinasi / wilayah
     * Sesuai Endpoint: GET destination/domestic-destination
     */
    public function getDestination(string $keyword): array
    {
        try {
            $response = $this->client->get(
                $this->baseUrl . 'destination/domestic-destination',
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'key'    => $this->apiKey,
                    ],
                    'query' => [
                        'search' => trim($keyword),
                        'limit'  => 50
                    ]
                ]
            );

            return json_decode($response->getBody(), true) ?? [];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
    /**
     * Menghitung Biaya Ongkir Komerce JNE
     * Sesuai Endpoint: POST calculate/domestic-cost
     */
    public function getCost($origin, $destination, $weight, string $courier): array 
    {
        try {
            // Memastikan data murni dikonversi sesuai spesifikasi dokumentasi API Komerce
            $cleanOrigin      = trim((string)$origin);
            $cleanDestination = trim((string)$destination);
            $cleanWeight      = (int)$weight; // Wajib bertipe Integer angka murni

            $response = $this->client->post(
                $this->baseUrl . 'calculate/domestic-cost', 
                [
                    'headers' => [
                        'Accept'        => 'application/json',
                        'Content-Type'  => 'application/x-www-form-urlencoded',
                        'key'           => $this->apiKey,
                    ],
                    'form_params' => [
                        'origin'      => $cleanOrigin,
                        'destination' => $cleanDestination,
                        'weight'      => $cleanWeight,
                        'courier'     => trim($courier),
                    ]
                ]
            );

            return json_decode($response->getBody(), true) ?? [];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}