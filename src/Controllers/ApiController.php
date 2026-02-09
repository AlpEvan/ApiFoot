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
        
        $config = require __DIR__ . '/../../config/api.php';

        $this->apiModel = new ApiModel(
            $config['api_football']['key'],
            $config['api_football']['url']
        );
    }
    
    public function show(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Récupérer la date depuis les paramètres GET ou utiliser aujourd'hui
        $params = $request->getQueryParams();
        $selectedDate = $params['date'] ?? date('Y-m-d');
        
        try {
            // Récupérer les matchs depuis l'API
            $matchesData = $this->apiModel->getMatchesByDate($selectedDate);
            $matches = $matchesData['response'] ?? [];
            
        } catch (\Exception $e) {
            // En cas d'erreur, tableau vide
            $matches = [];
            error_log("Erreur lors de la récupération des matchs: " . $e->getMessage());
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
            // Cherche dans toutes les catégories
            $equipes = $this->apiModel->chercherEquipe($search);
            $joueurs = $this->apiModel->chercherJoueur($search);
            $ligues = $this->apiModel->chercherLigue($search);
            $pays = $this->apiModel->chercherPays($search);

            return $this->view->render($response, "search/results.php", [
                'title' => 'Résultats de recherche',
                'withMenu' => true,
                'search' => $search,
                'equipes' => $equipes['response'] ?? [],
                'joueurs' => $joueurs['response'] ?? [],
                'ligues' => $ligues['response'] ?? [],
                'pays' => $pays['response'] ?? []
            ]);

        } catch (\Exception $e) {
            return $this->view->render($response, "search/results.php", [
                'title' => 'Recherche',
                'withMenu' => true,
                'error' => 'Erreur lors de la recherche : ' . $e->getMessage(),
                'search' => $search
            ]);
        }
    }
}