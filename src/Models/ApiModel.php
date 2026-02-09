<?php

declare(strict_types=1);

namespace EvanAlpst\ApiFoot\Models;

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
                'x-rapidapi-key' => $apiKey,
                'x-rapidapi-host' => 'v3.football.api-sports.io'
            ]
        ]);
    }

    /**
     * Récupérer les matchs par date
     * 
     * @param string $date Format: Y-m-d
     * @return array
     */
    public function getMatchesByDate(string $date): array
    {
        try {
            $response = $this->client->get('/fixtures', [
                'query' => ['date' => $date]
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (GuzzleException $e) {
            error_log("Get Matches Error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des matchs");
        }
    }

    /**
     * Chercher une équipe
     * 
     * @param string $query
     * @return array
     */
    public function chercherEquipe(string $query): array
    {
        try {
            $response = $this->client->get('/teams', [
                'query' => ['search' => $query]
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (GuzzleException $e) {
            error_log("Search Teams Error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la recherche d'équipes");
        }
    }
    
    /**
     * Chercher un joueur
     * 
     * @param string $query
     * @return array
     */
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
    
    /**
     * Chercher une ligue
     * 
     * @param string $query
     * @return array
     */
    public function chercherLigue(string $query): array
    {
        try {
            $response = $this->client->get('/leagues', [
                'query' => ['search' => $query]
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (GuzzleException $e) {
            error_log("Search Leagues Error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la recherche de ligues");
        }
    }
    
    /**
     * Chercher un pays
     * 
     * @param string $query
     * @return array
     */
    public function chercherPays(string $query): array
    {
        try {
            $response = $this->client->get('/countries', [
                'query' => ['search' => $query]
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (GuzzleException $e) {
            error_log("Search Countries Error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la recherche de pays");
        }
    }
}