<?php
// app/Services/QuotableService.php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;

class QuotableService
{
    protected $client;
    protected $baseUrl = 'https://api.quotable.io/quotes';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getRandomQuote($category)
    {
        try {
            $response = $this->client->get($this->baseUrl, [
                'query' => [
                    'tags' => $category,
                    'limit' => 1,
                    'random' => true
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['results'][0])) {
                return [
                    'text' => $data['results'][0]['content'] ?? 'No content',
                ];
            } else {
                return [
                    'text' => 'No quote found',
                ];
            }
        } catch (RequestException $e) {
            Log::error('Quote Request Exception: ' . $e->getMessage());
            return [
                'text' => 'Error fetching quote',
            ];
        } catch (\Exception $e) {
            Log::error('General Exception: ' . $e->getMessage());
            return [
                'text' => 'Error fetching quote',
            ];
        }
    }
}
