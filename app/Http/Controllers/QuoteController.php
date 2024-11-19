<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use GuzzleHttp\Client;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Services\QuotableService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
        return view('quotes.index'); 
    }

    public function manual_quots()
    {
        $quotes = Quote::where('source', 'manual')->get();
        return view('quotes.manual_index',compact('quotes')); 
    }

    // Metode untuk menampilkan halaman auto
    public function auto_quots()
    {
        $quotes = Quote::where('source', 'auto')->get();
        return view('quotes.auto_index',compact('quotes')); 
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Kategori::all();
        return view('quotes.create', compact('categories'));
    }

    public function manual_create()
    {
        $categories = Kategori::all();
        return view('quotes.manual_create', compact('categories'));
    }

    public function auto_create()
    {
        $categories = Kategori::all();
        return view('quotes.auto_create', compact('categories'));
    }



    /**
     * Store a newly created resource in storage.
     */

     public function store_manual(Request $request)
     {
         
        $request->validate([
            'quotes' => 'required|string',
            'author' => 'nullable|string',
            'category_id' => 'required|exists:kategoris,id',
            
        ]);

        $quote = Quote::create([
            'quote' => $request->quotes,
            'author' => $request->input('author') ?: Auth::user()->name,
            'source' => 'manual',
        ]);


        $quote->categories()->attach($request->category_id);
 
         return redirect()->route('quotes.manual')->with('success', 'Quote created and translated successfully!');
     }

    public function store_auto(Request $request)
    {
        
        $request->validate([
            'category_id' => 'required|exists:kategoris,id',
            'quote_length' => 'required|in:short,medium,long',
        ]);

        // Mengambil kutipan acak dari API Quotable.io
        $response = Http::withOptions([
            'verify' => false,
        ])->get('https://api.quotable.io/random', [
            'minLength' => $request->quote_length === 'short' ? 0 : ($request->quote_length === 'medium' ? 50 : 100),
            'maxLength' => $request->quote_length === 'short' ? 50 : ($request->quote_length === 'medium' ? 100 : 200),
        ]);

        $quoteData = $response->json();

        $quote = Quote::create([
            'quote' => $quoteData['content'],
            'author' => $quoteData['author'],
            'source' => 'auto'
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
            return redirect()->route('quotes.auto')->with('error', 'Failed to translate the quote.');
        }

        // Menghubungkan kutipan dengan kategori
        $quote->categories()->attach($request->category_id);

        return redirect()->route('quotes.auto')->with('success', 'Quote created and translated successfully!');
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
        $quote = Quote::with('categories')->findOrFail($id); // Memuat relasi kategori
        $categories = Kategori::all(); // Mengambil semua kategori
    
        return view('quotes.edit', compact('quote', 'categories'));
    }

    public function manual_edit(string $id)
    {
        $quote = Quote::findOrFail($id);
        $categories = Kategori::all();
        return view('quotes.manual_edit', compact('quote', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function manual_update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:kategoris,id',
            'quotes' => 'required|string',
            'author' => 'required|string',
        ]);

        // Temukan quote berdasarkan ID
        $quote = Quote::findOrFail($id);

        // Update data utama quote
        $quote->update([
            'author' => $validatedData['author'],
            'quote' => $validatedData['quotes'],
        ]);

        // Update relasi kategori di pivot table
        $quote->categories()->sync([$validatedData['category_id']]);

        return redirect()->route('quotes.manual')->with('success', 'Quote berhasil diupdate.');
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
