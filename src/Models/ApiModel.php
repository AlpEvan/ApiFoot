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
            'timeout' => 30.0,
            'headers' => [
                'X-API-Key' => $apiKey
            ]
        ]);
    }

    /**
     * Récupérer les matchs en direct
     * 
     * @param int $offset Décalage du jour (0=aujourd'hui, -1=hier, 1=demain)
     * @param int $timezone Fuseau horaire (0=UTC, 1=UTC+1, etc.)
     * @return array
     */
    public function getLiveMatches(int $offset = 0, int $timezone = 0): array
    {
        try {
            $response = $this->client->get('/api/flashscore/football/live', [
                'query' => [
                    'offset' => $offset,
                    'tz' => $timezone
                ]
            ]);
            
            $data = json_decode($response->getBody()->getContents(), true);
            
            return [
                'response' => $data ?? [],
                'results' => is_array($data) ? count($data) : 0
            ];

        } catch (GuzzleException $e) {
            error_log("Get Live Matches Error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des matchs en direct");
        }
    }

    /**
     * Récupérer les matchs par date
     * 
     * @param string $date Format: Y-m-d
     * @param int $timezone Fuseau horaire (0=UTC, 1=UTC+1, etc.)
     * @return array
     */
    public function getMatchesByDate(string $date, int $timezone = 0): array
    {
        try {
            // Calculer l'offset par rapport à aujourd'hui
            $today = new \DateTime();
            $targetDate = new \DateTime($date);
            $offset = $today->diff($targetDate)->days;
            
            // Déterminer si c'est dans le passé ou le futur
            if ($targetDate < $today) {
                $offset = -$offset;
            }
            
            $response = $this->client->get('/api/flashscore/football/live', [
                'query' => [
                    'offset' => $offset,
                    'tz' => $timezone
                ]
            ]);
            
            $data = json_decode($response->getBody()->getContents(), true);
            
            return [
                'response' => $data ?? [],
                'results' => is_array($data) ? count($data) : 0
            ];

        } catch (GuzzleException $e) {
            error_log("Get Matches By Date Error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des matchs");
        }
    }

    /**
     * Récupérer les détails d'un match
     * 
     * @param string $matchId
     * @return array
     */
    public function getMatchDetails(string $matchId): array
    {
        try {
            $response = $this->client->get("/api/flashscore/match/{$matchId}/details");
            
            $data = json_decode($response->getBody()->getContents(), true);
            
            return [
                'response' => $data ?? [],
                'results' => 1
            ];

        } catch (GuzzleException $e) {
            error_log("Get Match Details Error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des détails du match");
        }
    }

    /**
     * Récupérer les compositions d'un match
     * 
     * @param string $matchId
     * @return array
     */
    public function getMatchLineups(string $matchId): array
    {
        try {
            $response = $this->client->get("/api/flashscore/match/{$matchId}/lineups");
            
            $data = json_decode($response->getBody()->getContents(), true);
            
            return [
                'response' => $data ?? [],
                'results' => 1
            ];

        } catch (GuzzleException $e) {
            error_log("Get Match Lineups Error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des compositions");
        }
    }

    /**
     * Récupérer les statistiques d'un match
     * 
     * @param string $matchId
     * @return array
     */
    public function getMatchStats(string $matchId): array
    {
        try {
            $response = $this->client->get("/api/flashscore/match/{$matchId}/stats");
            
            $data = json_decode($response->getBody()->getContents(), true);
            
            return [
                'response' => $data ?? [],
                'results' => 1
            ];

        } catch (GuzzleException $e) {
            error_log("Get Match Stats Error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des statistiques");
        }
    }

    /**
     * Récupérer les cotes des matchs
     * 
     * @param int $offset Décalage du jour (0=aujourd'hui, -1=hier, 1=demain)
     * @param int $timezone Fuseau horaire (0=UTC, 1=UTC+1, etc.)
     * @return array
     */
    public function getLiveOdds(int $offset = 0, int $timezone = 0): array
    {
        try {
            $response = $this->client->get('/api/flashscore/football/live/odds', [
                'query' => [
                    'offset' => $offset,
                    'tz' => $timezone
                ]
            ]);
            
            $data = json_decode($response->getBody()->getContents(), true);
            
            return [
                'response' => $data ?? [],
                'results' => is_array($data) ? count($data) : 0
            ];

        } catch (GuzzleException $e) {
            error_log("Get Live Odds Error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des cotes");
        }
    }

    /**
     * Chercher les matchs d'une équipe
     * Note: L'API ne permet pas de chercher directement par équipe
     * On récupère tous les matchs et on filtre
     * 
     * @param string $teamName Nom de l'équipe
     * @param int $daysBack Nombre de jours dans le passé
     * @param int $daysForward Nombre de jours dans le futur
     * @param int $timezone Fuseau horaire
     * @return array
     */
    public function chercherEquipe(string $teamName, int $daysBack = 7, int $daysForward = 7, int $timezone = 0): array
    {
        try {
            $allMatches = [];
            $teamNameLower = strtolower($teamName);
            
            // Récupérer les matchs sur la période demandée
            for ($offset = -$daysBack; $offset <= $daysForward; $offset++) {
                $response = $this->client->get('/api/flashscore/football/live', [
                    'query' => [
                        'offset' => $offset,
                        'tz' => $timezone
                    ]
                ]);
                
                $matches = json_decode($response->getBody()->getContents(), true);
                
                if (!is_array($matches)) continue;
                
                // Filtrer par nom d'équipe
                foreach ($matches as $match) {
                    $homeNameLower = strtolower($match['homeName'] ?? '');
                    $awayNameLower = strtolower($match['awayName'] ?? '');
                    
                    if (strpos($homeNameLower, $teamNameLower) !== false || 
                        strpos($awayNameLower, $teamNameLower) !== false) {
                        $allMatches[] = $match;
                    }
                }
                
                // Pause pour ne pas surcharger l'API
                usleep(200000); // 0.2 seconde
            }
            
            return [
                'response' => $allMatches,
                'results' => count($allMatches)
            ];

        } catch (GuzzleException $e) {
            error_log("Search Team Error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la recherche d'équipe");
        }
    }
}