<?php

declare(strict_types=1);

namespace EvanAlpst\ApiFoot\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use EvanAlpst\ApiFoot\Models\ApiModel;

class ApiController extends BaseController
{
    private ApiModel $apiModel;

    public function __construct()
    {
        parent::__construct();
        
        $configPath = __DIR__ . '/../../config/api.php';
        
        if (!file_exists($configPath)) {
            $configPath = dirname(__DIR__, 2) . '/config/api.php';
        }
        
        if (!file_exists($configPath)) {
            throw new \RuntimeException("Configuration file not found at: " . $configPath);
        }
        
        $config = require $configPath;
        
        if (!isset($config['sportdb']['key']) || !isset($config['sportdb']['url'])) {
            throw new \RuntimeException("Invalid SportDB API configuration");
        }

        $this->apiModel = new ApiModel(
            $config['sportdb']['key'],
            $config['sportdb']['url']
        );
    }

    public function show(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();
        $selectedDate = $params['date'] ?? date('Y-m-d');
        $timezone = $params['tz'] ?? 1; // UTC+1 par défaut (Europe)
        
        try {
            error_log("Fetching matches for date: " . $selectedDate);
            
            // Récupérer les matchs depuis l'API SportDB
            $matchesData = $this->apiModel->getMatchesByDate($selectedDate, (int)$timezone);
            
            error_log("API Response: " . json_encode($matchesData));
            
            $matches = $matchesData['response'] ?? [];
            
            error_log("Number of matches found: " . count($matches));
            
        } catch (\Exception $e) {
            // En cas d'erreur, tableau vide
            $matches = [];
            error_log(
                'Erreur lors de la récupération des matchs : ' . $e->getMessage()
            );
        }


        return $this->view->render($response, 'matches/showAll.php', [
            'title' => 'Api Foot - Accueil',
            'withMenu' => true,
            'matches' => $matches,
            'selectedDate' => $selectedDate,
            'data' => [],
            'errors' => [],
        ]);
    }

    public function search(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();
        $search = isset($data['search']) ? trim($data['search']) : '';

        if (empty($search) || strlen($search) < 2) {
            return $this->view->render($response, "search/results.php", [
                'title' => 'Recherche',
                'withMenu' => true,
                'error' => 'Veuillez entrer au moins 2 caractères',
                'search' => $search
            ]);
        }

        try {
            error_log("Searching for team: " . $search);
            
            // Cherche uniquement les équipes (seul endpoint disponible)
            // On recherche sur 7 jours avant et 7 jours après
            $equipes = $this->apiModel->chercherEquipe($search, 7, 7, 1);
            
            error_log("Teams found: " . count($equipes['response'] ?? []));

            return $this->view->render($response, "search/results.php", [
                'title' => 'Résultats de recherche',
                'withMenu' => true,
                'search' => $search,
                'equipes' => $equipes['response'] ?? [],
                'joueurs' => [], // Non disponible dans SportDB
                'ligues' => [],  // Non disponible dans SportDB
                'pays' => [],    // Non disponible dans SportDB
                'info' => 'Note: SportDB ne permet que la recherche d\'équipes via leurs matchs récents/à venir'
            ]);

        } catch (\Exception $e) {
            error_log("Search error: " . $e->getMessage());
            return $this->view->render($response, "search/results.php", [
                'title' => 'Recherche',
                'withMenu' => true,
                'error' => 'Erreur lors de la recherche : ' . $e->getMessage(),
                'search' => $search
            ]);
        }
    }
    
    /**
     * Afficher les détails d'un match
     */
    /**
 * Afficher les détails d'un match
 */
    /**
 * Afficher les détails d'un match
 */
public function showMatchDetails(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
{
    $matchId = $args['id'] ?? null;
    
    if (!$matchId) {
        return $this->view->render($response, 'matches/details.php', [
            'title' => 'Détails du match',
            'withMenu' => true,
            'error' => 'ID de match manquant',
            'match' => [],
            'lineups' => [],
            'stats' => []
        ]);
    }
    
    try {
        error_log("Fetching match for ID: " . $matchId);
        
        // Récupérer tous les matchs des 7 derniers jours et 7 prochains jours
        $allMatches = [];
        for ($offset = -7; $offset <= 7; $offset++) {
            try {
                $matchesData = $this->apiModel->getLiveMatches($offset, 1);
                $matches = $matchesData['response'] ?? [];
                $allMatches = array_merge($allMatches, $matches);
            } catch (\Exception $e) {
                error_log("Error fetching matches for offset $offset: " . $e->getMessage());
            }
        }
        
        // Trouver le match correspondant à l'ID
        $match = null;
        foreach ($allMatches as $m) {
            if (($m['eventId'] ?? '') === $matchId) {
                $match = $m;
                break;
            }
        }
        
        if (!$match) {
            return $this->view->render($response, 'matches/showOne.php', [
                'title' => 'Détails du match',
                'withMenu' => true,
                'error' => 'Match non trouvé',
                'match' => [],
                'lineups' => [],
                'stats' => []
            ]);
        }
        
        error_log("Match found: " . json_encode($match));
        
        // Essayer de récupérer les détails supplémentaires (si disponibles)
        $lineups = [];
        $stats = [];
        
        try {
            $lineupsResponse = $this->apiModel->getMatchLineups($matchId);
            $lineups = $lineupsResponse['response'] ?? [];
        } catch (\Exception $e) {
            error_log("Lineups not available: " . $e->getMessage());
        }
        
        try {
            $statsResponse = $this->apiModel->getMatchStats($matchId);
            $stats = $statsResponse['response'] ?? [];
        } catch (\Exception $e) {
            error_log("Stats not available: " . $e->getMessage());
        }
        
        return $this->view->render($response, 'matches/showOne.php', [
            'title' => 'Détails du match - ' . ($match['homeName'] ?? '') . ' vs ' . ($match['awayName'] ?? ''),
            'withMenu' => true,
            'match' => $match,
            'lineups' => $lineups,
            'stats' => $stats
        ]);
        
    } catch (\Exception $e) {
        error_log("General error in showMatchDetails: " . $e->getMessage());
        return $this->view->render($response, 'matches/details.php', [
            'title' => 'Détails du match',
            'withMenu' => true,
            'error' => 'Erreur lors de la récupération du match: ' . $e->getMessage(),
            'match' => [],
            'lineups' => [],
            'stats' => []
        ]);
    }
}
    
    /**
     * Afficher les matchs en direct uniquement
     */
    public function showLive(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            error_log("Fetching live matches");
            
            // Récupérer uniquement les matchs en direct (offset=0)
            $matchesData = $this->apiModel->getLiveMatches(0, 1);
            
            $matches = $matchesData['response'] ?? [];
            
            // Filtrer uniquement les matchs avec eventStage = "LIVE"
            $liveMatches = array_filter($matches, function($match) {
                return isset($match['eventStage']) && $match['eventStage'] === 'LIVE';
            });
            
            error_log("Live matches found: " . count($liveMatches));
            
            return $this->view->render($response, 'matches/live.php', [
                'title' => 'Matchs en direct',
                'withMenu' => true,
                'matches' => array_values($liveMatches)
            ]);
            
        } catch (\Exception $e) {
            error_log("Error fetching live matches: " . $e->getMessage());
            return $this->view->render($response, 'matches/live.php', [
                'title' => 'Matchs en direct',
                'withMenu' => true,
                'error' => 'Erreur lors de la récupération des matchs en direct',
                'matches' => []
            ]);
        }
    }
}