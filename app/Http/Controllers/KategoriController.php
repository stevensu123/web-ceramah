<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Intervention\Image\ImageManagerStatic as Image;

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

  
        $file = $request->file('image');
            ini_set('memory_limit', '512M');
        
                $filename   = $file->getClientOriginalName();
                //Upload File
                $file->storeAs('public/upload/emotikon/', $filename);
                //Resize image here
                $thumbnailpath = public_path('storage/upload/emotikon/' . $filename);
                $img = Image::make($thumbnailpath)->resize(1280, 720, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($thumbnailpath);
 
    
    
        $blog = Kategori::create([
            'gambar'     => $filename,
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
