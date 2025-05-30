<?php
namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;

class ValorantApiService
{
    protected $baseUrl = 'https://valorant-api.com/v1';

    public function getAgents()
    {
        $params = [
            'isPlayableCharacter' => 'true',
            'language' => 'en-US'
        ];

        $response = Http::get("{$this->baseUrl}/agents", $params);
        return $response->json()['data'] ?? [];
    }

    public function getMaps()
    {
        $params = [
            'language' => 'en-US'
        ];

        $response = Http::get("{$this->baseUrl}/maps", $params);
        return $response->json()['data'] ?? [];
    }
}