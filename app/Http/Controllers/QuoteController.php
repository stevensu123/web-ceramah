<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class QuoteController extends Controller
{

    public function generateQuote(Request $request)
    {
        $emotion = $request->input('emotion');
        $wordCount = $this->getWordCount($request->input('length'));

        // Ganti dengan URL API yang sesuai dan parameter query jika diperlukan
        $apiUrl = "https://api.quotable.io/quotes?tags={$emotion}&limit=1";
        
        $client = new Client();
        $response = $client->get($apiUrl);
        $data = json_decode($response->getBody(), true);

        // Ambil kutipan dan pastikan panjangnya sesuai
        $quote = $this->filterQuoteByLength($data['results'][0]['content'], $wordCount);
        
        return response()->json(['quote' => $quote]);
    }

    private function getWordCount($length)
    {
        switch ($length) {
            case 'short':
                return 20;
            case 'medium':
                return 40;
            case 'long':
                return 70;
            default:
                return 20;
        }
    }

    private function filterQuoteByLength($quote, $wordCount)
    {
        $words = explode(' ', $quote);
        if (count($words) > $wordCount) {
            $words = array_slice($words, 0, $wordCount);
            return implode(' ', $words) . '...';
        }
        return $quote;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
