<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $kategori = Kategori::get();
        return view('kategori.index', compact('kategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategori.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

   

      
        $image = $request->file('image');
    // Nama file unik
    $imageName = time() . '-' . $image->getClientOriginalName();

    // Folder tujuan
    $folderPath = 'upload/emotikon/';
    $thumbnailFolderPath = 'upload/emotikon/thumbnails/';

    // Buat folder jika belum ada
    if (!Storage::exists($folderPath)) {
        Storage::makeDirectory($folderPath);
    }

    if (!Storage::exists($thumbnailFolderPath)) {
        Storage::makeDirectory($thumbnailFolderPath);
    }

    // Simpan gambar asli
    $filePath = $image->storeAs($folderPath, $imageName);

    // Inisialisasi ImageManager
    $imgManager = new ImageManager(new Driver());

    // Baca gambar yang diupload
    $thumbnails = $imgManager->read(Storage::path($filePath));

    // Resize gambar
    $thumbnails->resize(1270, 1270);

    // Simpan thumbnail
    $thumbnailPath = $thumbnailFolderPath . $imageName;
    $thumbnails->save(Storage::path($thumbnailPath));
      

   
    
      
        $blog = Kategori::create([
            'gambar'     => $imageName,
            'keterangan'     => $request->keterangan,
            'nama_kategori'     => $request->nama_kategori,
            'status'   => $request->status
        ]);
        // Store the data in your database or perform any necessary actions
   

        // Optionally, redirect back or return a success response
        return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    
        $kategori = Kategori::find($id);
        return response()->json($kategori);
    
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
        $data = Kategori::findOrFail($id);
        // Hapus gambar dari storage
        if ($data->gambar && Storage::exists('public/upload/emotikon/' . $data->gambar)) {
            Storage::delete('public/upload/emotikon/' . $data->gambar);
        }
        $data->delete();


        return response()->json(['success' => 'Data berhasil dihapus.']);
    }
}
