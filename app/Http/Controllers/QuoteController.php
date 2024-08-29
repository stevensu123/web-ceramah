<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use GuzzleHttp\Client;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\QuotableService;
use App\Services\LibreTranslateService;

class QuoteController extends Controller
{

    protected $quotableService;
    protected $translateService;

    public function __construct(QuotableService $quotableService, LibreTranslateService $translateService)
    {
        $this->quotableService = $quotableService;
        $this->translateService = $translateService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quotes = Quote::all();
        return view('quotes.index', compact('quotes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Kategori::all();
        return view('quotes.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */



    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:kategoris,id',
            'quote_length' => 'required|in:short,medium,long',
        ]);

        // Mengambil kutipan acak dari API Quotable.io
        $response = Http::get('https://api.quotable.io/random', [
            'minLength' => $request->quote_length === 'short' ? 0 : ($request->quote_length === 'medium' ? 50 : 100),
            'maxLength' => $request->quote_length === 'short' ? 50 : ($request->quote_length === 'medium' ? 100 : 200),
        ]);

        $quoteData = $response->json();

        $quote = Quote::create([
            'quote' => $quoteData['content'],
            'author' => $quoteData['author']
        ]);

        // Menerjemahkan kutipan ke Bahasa Indonesia menggunakan Lingva Translate
        $sourceLang = 'en';
        $targetLang = 'id';
        $text = urlencode($quoteData['content']); // Menggunakan urlencode untuk memastikan teks dikodekan dengan benar

        // Menggunakan Http::get untuk panggilan API Lingva Translate
        $translatedResponse = Http::get("https://lingva.ml/api/v1/{$sourceLang}/{$targetLang}/{$text}");

        if ($translatedResponse->successful()) {
            $translatedQuote = $translatedResponse->json('translation');

            // Menghapus tanda plus (+) yang mungkin ada pada hasil terjemahan
            $translatedQuote = str_replace('+', ' ', $translatedQuote);

            $quote->update(['translated_quote' => $translatedQuote]);
        } else {
            return redirect()->route('quotes.index')->with('error', 'Failed to translate the quote.');
        }

        // Menghubungkan kutipan dengan kategori
        $quote->categories()->attach($request->category_id);

        return redirect()->route('quotes.index')->with('success', 'Quote created and translated successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $quote = Quote::with('categories')->findOrFail($id);
        return response()->json([
            'quote' => $quote->quote,
            'quotes_author' => $quote->author,
            'nama_kategori' => $quote->categories->pluck('nama_kategori')->join(', '),
            'quotes_translate' => $quote->translated_quote // atau data yang sesuai
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $quote = Quote::findOrFail($id);
        $categories = Kategori::all();
        return view('quotes.edit', compact('quote', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'category_id' => 'required|exists:kategoris,id',
            'length' => 'required|in:short,medium,long'
        ]);

        $quote = Quote::findOrFail($id);
        $quote->update($request->all());

        return redirect()->route('quotes.index')->with('success', 'Quote berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $quote = Quote::findOrFail($id);

        // Hapus relasi kategori pada tabel pivot
        $quote->categories()->detach();
    
        // Hapus quote
        $quote->delete();

        return response()->json(['success' => 'Data berhasil dihapus.']);
    }
}
