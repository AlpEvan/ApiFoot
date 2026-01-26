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
}
