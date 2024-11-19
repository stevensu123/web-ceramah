<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Models\Quote;
use Illuminate\Support\Facades\Storage;
use Image as InterventionImage;

class HeroimageController extends Controller
{

    public function index()
    {
        return view('heroimage.index');
    }

    public function create()
    {
        $author = Quote::distinct()->pluck('author');
        return view('heroimage.create', compact('author'));
    }


    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'judul_gambar' => 'required|string',
            'background_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'object_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Simpan kutipan terlebih dahulu
        $quote = Quote::create([
            'text' => $request->input('quote_text'),
        ]);

        // Upload gambar latar belakang
        $backgroundImage = $request->file('background_image');
        $backgroundImageName = time() . '_background.' . $backgroundImage->getClientOriginalExtension();
        $backgroundImagePath = $backgroundImage->storeAs('public/uploads/images', $backgroundImageName);

        // Upload gambar objek
        $objectImage = $request->file('object_image');
        $objectImageName = time() . '_object.' . $objectImage->getClientOriginalExtension();
        $objectImagePath = $objectImage->storeAs('public/uploads/images', $objectImageName);

        // Simpan informasi gambar ke database dengan quote_id
        Image::create([
            'quote_id' => $quote->id,
            'image_path_background' => $backgroundImageName,
            'image_path_object' => $objectImageName,
        ]);

        return back()->with('success', 'Gambar dan kutipan berhasil diunggah dan disimpan!');
    }

    

}
