<?php

// app/Http/Controllers/VideoController.php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use App\DataTables\VideoDataTable;

class VideoController extends Controller
{

    public function index(VideoDataTable $dataTable)
    {
        return $dataTable->render('videos.index'); // Pastikan Anda memiliki view videos.index
    }
    
    
    // Menampilkan form create video
    public function create(Request $request)
    {
      
    }

    public function backToIndexWithModal()
{
    // Redirect ke halaman index dengan session flash untuk menampilkan modal
    return redirect()->route('videos.index')->with('showModal', true);
}

    public function createFromFile()
    {
        return view('videos.create_from_file');
    }

    // Method untuk menampilkan form tambah video dari link YouTube
    public function createFromYouTube()
    {
        return view('videos.create_from_youtube');
    }

    // Menyimpan video yang diupload atau link YouTube
//     public function store(Request $request)
// {
//     $request->validate([
//         'title' => 'required|string|max:255',
//         'description' => 'nullable|string',
//         'video_file' => 'nullable|mimes:mp4,mkv,avi,mov,wmv|max:512000', // max 500MB
//         'video_link' => 'nullable|url'
//     ]);

//     if ($request->hasFile('video_file')) {
//         $originalFileName = $request->file('video_file')->getClientOriginalName();
//         $extension = $request->file('video_file')->getClientOriginalExtension();
        
//         // Menghilangkan ekstensi dari nama file untuk penomoran
//         $baseName = pathinfo($originalFileName, PATHINFO_FILENAME);
        
//         // Mencari nama file yang unik
//         $videoPath = $this->generateUniqueFileName($baseName, $extension);

//         // Menyimpan file dengan nama baru
//         $filePath = $request->file('video_file')->storeAs('videos', $videoPath, 'public');
//     } elseif ($request->input('video_link')) {
//         $videoPath = $request->input('video_link');
//     } else {
//         return back()->withErrors('Harap upload video atau masukkan link video.');
//     }

//     Video::create([
//         'title' => $request->input('title'),
//         'description' => $request->input('description'),
//         'video_path' => $videoPath
//     ]);

//     return redirect()->route('videos.index')->with('success', 'Video berhasil disimpan!');
// }

// private function generateUniqueFileName($baseName, $extension)
// {
//     $videoPath = $baseName . '.' . $extension;

//     // Mengecek apakah file sudah ada
//     if (Storage::disk('public')->exists('videos/' . $videoPath)) {
//         // Jika ada, maka tambahkan angka sebagai inisial
//         $counter = 1;
//         while (Storage::disk('public')->exists('videos/' . $baseName . " ({$counter})." . $extension)) {
//             $counter++;
//         }
//         // Membuat nama file dengan inisial
//         $videoPath = $baseName . " ({$counter})." . $extension;
//     }

//     return $videoPath;
// }

public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'video_file' => 'nullable|mimes:mp4,mkv,avi,mov,wmv|max:512000', // max 500MB
        'video_link' => 'nullable|url'
    ]);

    // Inisialisasi variabel untuk menyimpan nama file video
    $videoName = null;
    $createBy = null; // Variabel untuk menyimpan informasi tentang sumber

    // Cek apakah ada file video yang di-upload
    if ($request->hasFile('video_file')) {
        $originalFileName = $request->file('video_file')->getClientOriginalName();
        $cleanFileName = $this->sanitizeFileName($originalFileName);

        // Menyimpan file dengan nama yang sudah dibersihkan
        $request->file('video_file')->storeAs('videos', $cleanFileName, 'public');

        // Hanya menyimpan nama file tanpa path
        $videoName = $cleanFileName;
        $createBy = 'file_upload'; // Menandakan video diunggah melalui file
    } elseif ($request->input('video_link')) {
        // Jika video link ada, gunakan sebagai path video
        $videoName = $request->input('video_link');
        $createBy = 'link'; // Menandakan video diunggah melalui link
    } else {
        return back()->withErrors(['video' => 'Harap upload video atau masukkan link video.']);
    }

    // Simpan data video ke database
    Video::create([
        'title' => $request->input('title'),
        'description' => $request->input('description'),
        'video_path' => $videoName, // Simpan hanya nama file
        'create_by' => $createBy // Simpan informasi tentang sumber
    ]);

    return redirect()->route('videos.index')->with('success', 'Video berhasil disimpan!');
}

private function sanitizeFileName($filename)
{
    // Ganti karakter tidak diinginkan dengan underscore
    $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $filename);
    return $filename;
}


    

public function show($id)
{
    $video = Video::findOrFail($id);
    $filePath = storage_path('app/public/videos/' . $video->video_path); // Sesuaikan path saat mengambil file

    if (!file_exists($filePath)) {
        return response()->json(['error' => 'File not found.'], 404);
    }

    // Ambil ukuran dan format file
    $video->size = filesize($filePath);
    $video->format = pathinfo($video->video_path, PATHINFO_EXTENSION);

    return response()->json($video);
}


    // Menampilkan form edit video
    public function edit($id)
    {
        $video = Video::findOrFail($id);
        return view('videos.edit', compact('video'));
    }

      // Method untuk mengedit video dari file
      public function editFromFile($id)
      {
          $video = Video::findOrFail($id);
          return view('videos.edit_from_file', compact('video'));
      }
  
      // Method untuk mengedit video dari YouTube
      public function editFromYoutube($id)
      {
          $video = Video::findOrFail($id);
          return view('videos.edit_from_youtube', compact('video'));
      }

      public function update(Request $request, $id)
      {
          // Validasi input
          $request->validate([
              'title' => 'required|string|max:255',
              'description' => 'nullable|string',
              'video_file' => 'nullable|mimes:mp4,mkv,avi,mov,wmv|max:512000', // max 500MB
              'video_link' => 'nullable|url'
          ]);
      
          // Ambil data video dari database
          $video = Video::findOrFail($id);
      
          // Inisialisasi variabel untuk menyimpan nama file video baru
          $videoName = $video->video_path; // Awalnya set ke file yang lama
          $createBy = $video->create_by; // Awalnya set ke sumber yang lama
      
          // Hapus file video lama jika ada file baru yang diunggah
          if ($request->hasFile('video_file')) {
              // Hapus video lama jika ada di storage
              if ($video->create_by === 'file_upload' && Storage::disk('public')->exists('videos/' . $video->video_path)) {
                  Storage::disk('public')->delete('videos/' . $video->video_path);
              }
      
              // Bersihkan nama file yang di-upload
              $originalFileName = $request->file('video_file')->getClientOriginalName();
              $cleanFileName = $this->sanitizeFileName($originalFileName);
      
              // Simpan file baru dengan nama yang sudah dibersihkan
              $request->file('video_file')->storeAs('videos', $cleanFileName, 'public');
      
              // Hanya menyimpan nama file tanpa path
              $videoName = $cleanFileName;
              $createBy = 'file_upload'; // Menandakan video diunggah melalui file
          } elseif ($request->input('video_link')) {
              // Jika video link ada, gunakan sebagai path video
              // Hapus file video lama jika sebelumnya diunggah melalui file
              if ($video->create_by === 'file_upload' && Storage::disk('public')->exists('videos/' . $video->video_path)) {
                  Storage::disk('public')->delete('videos/' . $video->video_path);
              }
      
              $videoName = $request->input('video_link');
              $createBy = 'link'; // Menandakan video diunggah melalui link
          }
      
          // Update data video ke database
          $video->update([
              'title' => $request->input('title'),
              'description' => $request->input('description'),
              'video_path' => $videoName,
              'create_by' => $createBy
          ]);
      
          return redirect()->route('videos.index')->with('success', 'Video berhasil diupdate!');
      }
  // Menghapus video
public function destroy($id)
{
    $video = Video::findOrFail($id);
    
    // Hapus file video dari storage jika ada
    if ($video->video_path) {
        $fullPath = 'videos/' . $video->video_path; // Menentukan path lengkap

        if (Storage::disk('public')->exists($fullPath)) {
            Storage::disk('public')->delete($fullPath); // Menggunakan disk 'public'
        }
    }

    // Menghapus entri video dari database
    $video->delete();
    
    return response()->json(['success' => 'Video berhasil dihapus!']);
}

}
