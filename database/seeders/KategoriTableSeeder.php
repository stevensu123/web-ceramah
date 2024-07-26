<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class KategoriTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['nama_kategori' => 'Marah', 'gambar' => 'Marah.png','keterangan' => 'Emotikon Marah Menandakan Suasana Hatimu Lagi Penuh Dengan Kemarahan'],
            ['nama_kategori' => 'Sedih', 'gambar' => 'Sedih.png','keterangan' => 'Emotikon Sedih Menandakan Suasana Hatimu Lagi Penuh Dengan Kesedihan'],
            ['nama_kategori' => 'Bahagia', 'gambar' => 'Bahagia.png','keterangan' => 'Emotikon Bahagia Menandakan Suasana Hatimu Lagi Penuh Dengan KeBahagiaan'],
        ];

        foreach ($categories as $category) {
            Kategori::create([
                'nama_kategori' => $category['nama_kategori'],
                'gambar' => $category['gambar'],
                'keterangan' => $category['keterangan'],
                'status' => 1, // Status default 1
            ]);

            // Copy the image from storage to the public directory
            Storage::disk('public')->put("upload/categories/emotikon/{$category['gambar']}", file_get_contents(storage_path("app/public/categories/emotikon/{$category['gambar']}")));
        }
    }
}
