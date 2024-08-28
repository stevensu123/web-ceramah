<?php
// app/Services/LibreTranslateService.php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;

class LibreTranslateService
{
    protected $client;
    protected $baseUrl = 'https://libretranslate.com/translate';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function translate($text, $sourceLang = 'en', $targetLang = 'id')
    {
        try {
            $response = $this->client->post($this->baseUrl, [
                'form_params' => [
                    'q' => $text,
                    'source' => $sourceLang,
                    'target' => $targetLang,
                    'format' => 'text'
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            return $data['translatedText'] ?? $text;
        } catch (RequestException $e) {
            Log::error('Translation Request Exception: ' . $e->getMessage());
            return $text;
        } catch (\Exception $e) {
            Log::error('General Exception: ' . $e->getMessage());
            return $text;
        }
    }
}
