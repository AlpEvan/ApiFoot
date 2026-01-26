<?php

declare(strict_types=1);

namespace EvanAlpst\ApiFoot\Models;

use EvanAlpst\ApiFoot\Controller\ApiController;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class ApiModel
{
    private Client $client;
    private string $apiKey;
    private string $baseUrl;

    public function __construct(string $apiKey, string $baseUrl)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;

        $this->client = new Client([
            'base_uri' => $baseUrl,
            'timeout' => 10.0,
            'headers' => [
                'X-API-Key' => $apiKey,
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    public function chercherEquipe(string $query): array
    {
        try {
            $response = $this->client->get('/teams', [
                'query' => ['search' => $query]
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (GuzzleException $e) {
            error_log("Search Error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la recherche d'équipes");
        }
    }
    public function chercherJoueur(string $query): array
    {
        try {
            $response = $this->client->get('/players', [
                'query' => ['search' => $query]
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (GuzzleException $e) {
            error_log("Search Players Error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la recherche de joueurs");
        }
    }
    public function chercherLigue(string $query): array
    {
        try {
            $response = $this->client->get('/ligue', [
                'query' => ['search' => $query]
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (GuzzleException $e) {
            error_log("Search Players Error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la recherche de joueurs");
        }
    }
    public function chercherPays(string $query): array
    {
        try {
            $response = $this->client->get('/pays', [
                'query' => ['search' => $query]
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (GuzzleException $e) {
            error_log("Search Players Error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la recherche de joueurs");
        }
    }
}
