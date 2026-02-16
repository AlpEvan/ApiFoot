<?php

declare(strict_types=1);

namespace EvanAlpst\ApiFoot\Models;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class ApiModel
{
    private Client $client;
    private string $apiKey;
    private string $apiHost;
    private string $baseUrl;

    public function __construct(string $apiKey, string $apiHost, string $baseUrl)
    {
        $this->apiKey = $apiKey;
        $this->apiHost = $apiHost;
        $this->baseUrl = $baseUrl;

        $this->client = new Client([
            'base_uri' => $baseUrl,
            'timeout' => 10.0,
            'headers' => [
                'x-rapidapi-key' => $apiKey,
                'x-rapidapi-host' => $apiHost
            ]
        ]);
    }

    /**
     * Récupérer les matchs par date
     * Format date: YYYYMMDD (ex: 20240210)
     */
    public function getMatchesByDate(string $date): array
    {
        try {
            // Convertir Y-m-d en YYYYMMDD
            $dateFormatted = str_replace('-', '', $date);
            
            $response = $this->client->get('/football-get-matches-by-date', [
                'query' => ['date' => $dateFormatted]
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (GuzzleException $e) {
            error_log("Get Matches Error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des matchs");
        }
    }

    /**
     * Chercher des équipes
     */
    public function chercherEquipe(string $query): array
    {
        try {
            $response = $this->client->get('/football-search-team', [
                'query' => ['team' => $query]
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (GuzzleException $e) {
            error_log("Search Teams Error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la recherche d'équipes");
        }
    }

    /**
     * Chercher des joueurs
     */
    public function chercherJoueur(string $query): array
    {
        try {
            $response = $this->client->get('/football-search-all-players', [
                'query' => ['player' => $query]
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (GuzzleException $e) {
            error_log("Search Players Error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la recherche de joueurs");
        }
    }

    /**
     * Récupérer les ligues populaires
     */
    public function getPopularLeagues(): array
    {
        try {
            $response = $this->client->get('/football-get-popular-leagues');

            return json_decode($response->getBody()->getContents(), true);

        } catch (GuzzleException $e) {
            error_log("Get Leagues Error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des ligues");
        }
    }
}