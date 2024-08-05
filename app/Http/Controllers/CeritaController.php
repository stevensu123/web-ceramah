<?php

namespace App\Http\Controllers;

use App\Models\Cerita;
use App\Models\Kategori;
use App\Models\Waktu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Intervention\Image\ImageManagerStatic as Image;

class CeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('cerita.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::get();
        $waktu1 = Waktu::where('title', 'Pagi')->first();
        $waktu2 = Waktu::where('title', 'Siang')->first();
        $waktu3 = Waktu::where('title', 'Sore')->first();

        // Simpan data dalam array atau koleksi
        $waktu = [$waktu1, $waktu2, $waktu3];
        return view('cerita.add', compact('kategori', 'waktu'));
    }

    public function handleDate($date)
    {

        $date = Carbon::parse($date);
        $today = Carbon::today();
        $user = Auth::user();
        $cerita = Cerita::whereDate('created_at', $date)
            ->where('user_id', $user->id)
            ->first();

        if ($cerita) {
            return redirect()->route('cerita.show_data', ['cerita' => $cerita->id]);
        }
        if ($date->lt($today)) {
            return redirect()->route('cerita.date_expired');
        }
        if ($date->gt($today)) {
            // Tanggal yang diklik belum dimulai
            return redirect()->route('cerita.date_belum');
        }

        return redirect()->route('cerita.create_view');
    }

    public function create_view()
    {

        return view('cerita.create_cerita');
    }

    public function show_data_expired_date()
    {
        Alert::Warning('Warning', 'Maaf, Tanggal Sudah Lewat');
        return redirect()->route('cerita.index');
    }

    public function show_data_belum_date()
    {
        Alert::Warning('Warning', 'Maaf, Tanggal Belum Dimulai');
        return redirect()->route('cerita.index');
    }

    public function show_data(Cerita $cerita)
    {
        $user_id = auth()->id(); // Ambil user ID
        $waktuIds = Waktu::pluck('id')->toArray(); // Ambil semua ID waktu (pagi, siang, sore)

        $ceritas = Cerita::with('kategoris', 'waktuCeritas')
            ->where('user_id', $user_id)
            ->get();

        $data = [
            'pagi' => $ceritas->filter(function ($cerita) use ($waktuIds) {
                return $cerita->waktuCeritas->contains('id', $waktuIds[0]); // ID pagi
            }),
            'siang' => $ceritas->filter(function ($cerita) use ($waktuIds) {
                return $cerita->waktuCeritas->contains('id', $waktuIds[1]); // ID siang
            }),
            'sore' => $ceritas->filter(function ($cerita) use ($waktuIds) {
                return $cerita->waktuCeritas->contains('id', $waktuIds[2]); // ID sore
            }),
        ];

        return view('cerita.exists', compact('data'));
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'file1' => 'nullable|image|mimes:jpeg,png,gif,bmp,webp|', // Menyesuaikan dengan format gambar yang didukung
            'nama_kategori_pagi' => 'nullable|exists:kategoris,nama_kategori',
            'keterangan_pagi' => 'nullable|string|max:255',
            'text_cerita_pagi' => 'nullable|string|max:255',
            'waktu_pagi' => 'nullable|exists:waktus,title',

            'file2' => 'nullable|image|mimes:jpeg,png,gif,bmp,webp|', // Menyesuaikan dengan format gambar yang dnama_kategoriukung
            'nama_kategori_siang' => 'nullable|exists:kategoris,nama_kategori',
            'keterangan_siang' => 'nullable|string|max:255',
            'text_cerita_siang' => 'nullable|string|max:255',
            'waktu_siang' => 'nullable|exists:waktus,title',

            'file3' => 'nullable|image|mimes:jpeg,png,gif,bmp,webp|', // Menyesuaikan dengan format gambar yang dnama_kategoriukung
            'nama_kategori_sore' => 'nullable|exists:kategoris,nama_kategori',
            'keterangan_sore' => 'nullable|string|max:255',
            'text_cerita_sore' => 'nullable|string|max:255',
            'waktu_sore' => 'nullable|exists:waktus,title',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'tanggal' => Carbon::now(),

            'nama_kategori_pagi' => $validatedData['nama_kategori_pagi'] ?? null,
            'keterangan_pagi' => $validatedData['keterangan_pagi'] ?? null,
            'text_cerita_pagi' => $validatedData['text_cerita_pagi'] ?? null,
            'waktu_pagi' => $validatedData['waktu_pagi'] ?? null,


            'nama_kategori_siang' => $validatedData['nama_kategori_siang'] ?? null,
            'keterangan_siang' => $validatedData['keterangan_siang'] ?? null,
            'text_cerita_siang' => $validatedData['text_cerita_siang'] ?? null,
            'waktu_siang' => $validatedData['waktu_siang'] ?? null,


            'nama_kategori_sore' => $validatedData['nama_kategori_sore'] ?? null,
            'keterangan_sore' => $validatedData['keterangan_sore'] ?? null,
            'text_cerita_sore' => $validatedData['text_cerita_sore'] ?? null,
            'waktu_sore' => $validatedData['waktu_sore'] ?? null,
        ];


        if ($request->hasFile('file1')) {
            $file1 = $request->file('file1');
            $filename1 = $file1->getClientOriginalName();
            // Simpan file ke storage
            $file1->storeAs('public/upload/cerita/pagi/', $filename1);
            $thumbnailpath1 = public_path('storage/upload/cerita/pagi/' . $filename1);
            // Cek apakah file ada dan bukan kosong
            if (file_exists($thumbnailpath1) && filesize($thumbnailpath1) > 0) {
                $img1 = Image::make($thumbnailpath1)->resize(300, 300, function ($constraint1) {
                    $constraint1->aspectRatio();
                });
                $img1->save($thumbnailpath1);
                $data['gambar_pagi'] = $filename1;
            } else {
                return back()->withErrors(['file1' => 'File tidak valid atau kosong.']);
            }
        }
        if ($request->hasFile('file2')) {
            $file2 = $request->file('file2');
            $filename2 = $file2->getClientOriginalName();
            // Simpan file ke storage
            $file2->storeAs('public/upload/cerita/siang/', $filename2);
            $thumbnailpath2 = public_path('storage/upload/cerita/siang/' . $filename2);
            // Cek apakah file ada dan bukan kosong
            if (file_exists($thumbnailpath2) && filesize($thumbnailpath2) > 0) {
                $img1 = Image::make($thumbnailpath2)->resize(300, 300, function ($constraint2) {
                    $constraint2->aspectRatio();
                });
                $img1->save($thumbnailpath2);
                $data['gambar_siang'] = $filename2;
            } else {
                return back()->withErrors(['file2' => 'File tidak valid atau kosong.']);
            }
        }

        if ($request->hasFile('file3')) {
            $file3 = $request->file('file3');
            $filename3 = $file3->getClientOriginalName();
            // Simpan file ke storage
            $file3->storeAs('public/upload/cerita/sore/', $filename3);
            $thumbnailpath3 = public_path('storage/upload/cerita/sore/' . $filename3);
            // Cek apakah file ada dan bukan kosong
            if (file_exists($thumbnailpath3) && filesize($thumbnailpath3) > 0) {
                $img1 = Image::make($thumbnailpath3)->resize(300, 300, function ($constraint3) {
                    $constraint3->aspectRatio();
                });
                $img1->save($thumbnailpath3);
            } else {
                return back()->withErrors(['file3' => 'File tidak valid atau kosong.']);
            }
        }

        $cerita = Cerita::create($data);

        // Insert data ke tabel pivot
        $categories = array_filter([
            $validatedData['nama_kategori_pagi'] ?? null,
            $validatedData['nama_kategori_siang'] ?? null,
            $validatedData['nama_kategori_sore'] ?? null
        ]);

        $waktu = array_filter([
            $validatedData['waktu_pagi'] ?? null,
            $validatedData['waktu_siang'] ?? null,
            $validatedData['waktu_sore'] ?? null
        ]);

        if (!empty($categories)) {
            $cerita_kategori = Kategori::whereIn('nama_kategori', $categories)->pluck('id')->toArray();
            $cerita->kategoris()->attach($cerita_kategori);
        }

        if (!empty($waktu)) {
            $cerita_waktu = Waktu::whereIn('title', $waktu)->pluck('id')->toArray();
            $cerita->waktuCeritas()->attach($cerita_waktu);
        }

        // $cerita = Cerita::create([
        //     'gambar_pagi'     => $filename1,
        //     'keterangan_pagi'     => $request->keterangan_pagi,
        //     'nama_kategori_pagi'     => $request->nama_kategori_pagi,
        //     'text_cerita_pagi'   => $request->text_cerita_pagi,

        //     'gambar_siang'     => $filename2,
        //     'keterangan_siang'     => $request->keterangan_siang,
        //     'nama_kategori_siang'     => $request->nama_kategori_siang,
        //     'text_cerita_siang'   => $request->text_cerita_siang,

        //     'gambar_sore'     => $filename3,
        //     'keterangan_sore'     => $request->keterangan_sore,
        //     'nama_kategori_sore'     => $request->nama_kategori_sore,
        //     'text_cerita_sore'   => $request->text_cerita_sore,

        //     'status' => $request->status,
        //     'user_id' => auth()->id(),
        // ]);
        // $kategoriIds = Kategori::whereIn('nama_kategori', [
        //     $request->nama_kategori_pagi,
        //     $request->nama_kategori_siang,
        //     $request->nama_kategori_sore
        // ])->pluck('id');
        // $cerita->kategoris()->sync($kategoriIds);

        return redirect()->route('cerita.index')->with(['success' => 'Data Berhasil Disimpan!']);
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
        // $kategori = Kategori::get();
        // $waktu1 = Waktu::where('title', 'Pagi')->first();
        // $waktu2 = Waktu::where('title', 'Siang')->first();
        // $waktu3 = Waktu::where('title', 'Sore')->first();

        // // Simpan data dalam array atau koleksi
        // $waktu = [$waktu1, $waktu2, $waktu3];
        $user_id = auth()->id(); // Ambil user ID
        $waktuIds = Waktu::pluck('id')->toArray(); // Ambil semua ID waktu (pagi, siang, sore)

        $ceritas = Cerita::with('kategoris', 'waktuCeritas')
            ->where('user_id', $user_id)
            ->get();

        $data = [
            'pagi' => $ceritas->filter(function ($cerita) use ($waktuIds) {
                return $cerita->waktuCeritas->contains('id', $waktuIds[0]); // ID pagi
            }),
            'siang' => $ceritas->filter(function ($cerita) use ($waktuIds) {
                return $cerita->waktuCeritas->contains('id', $waktuIds[1]); // ID siang
            }),
            'sore' => $ceritas->filter(function ($cerita) use ($waktuIds) {
                return $cerita->waktuCeritas->contains('id', $waktuIds[2]); // ID sore
            }),
        ];

        return view('cerita.edit', compact('data'));
     
        // return view('cerita.edit', compact('kategori', 'waktu'));
    }

 

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $date = $request->input('date'); // Misal: 2024-07-22

        // Parsing tanggal dan menambahkannya ke created_at
        $parsedDate = Carbon::parse($date);

        $validatedData = $request->validate([
            'file1' => 'nullable|image|mimes:jpeg,png,gif,bmp,webp|', // Menyesuaikan dengan format gambar yang didukung
            'nama_kategori_pagi' => 'nullable|exists:kategoris,nama_kategori',
            'keterangan_pagi' => 'nullable|string|max:255',
            'text_cerita_pagi' => 'nullable|string|max:255',
            'waktu_pagi' => 'nullable|exists:waktus,title',

            'file2' => 'nullable|image|mimes:jpeg,png,gif,bmp,webp|', // Menyesuaikan dengan format gambar yang dnama_kategoriukung
            'nama_kategori_siang' => 'nullable|exists:kategoris,nama_kategori',
            'keterangan_siang' => 'nullable|string|max:255',
            'text_cerita_siang' => 'nullable|string|max:255',
            'waktu_siang' => 'nullable|exists:waktus,title',

            'file3' => 'nullable|image|mimes:jpeg,png,gif,bmp,webp|', // Menyesuaikan dengan format gambar yang dnama_kategoriukung
            'nama_kategori_sore' => 'nullable|exists:kategoris,nama_kategori',
            'keterangan_sore' => 'nullable|string|max:255',
            'text_cerita_sore' => 'nullable|string|max:255',
            'waktu_sore' => 'nullable|exists:waktus,title',
        ]);

        $data = [
            'user_id' => Auth::id(),

            'nama_kategori_pagi' => $validatedData['nama_kategori_pagi'] ?? null,
            'keterangan_pagi' => $validatedData['keterangan_pagi'] ?? null,
            'text_cerita_pagi' => $validatedData['text_cerita_pagi'] ?? null,
            'waktu_pagi' => $validatedData['waktu_pagi'] ?? null,


            'nama_kategori_siang' => $validatedData['nama_kategori_siang'] ?? null,
            'keterangan_siang' => $validatedData['keterangan_siang'] ?? null,
            'text_cerita_siang' => $validatedData['text_cerita_siang'] ?? null,
            'waktu_siang' => $validatedData['waktu_siang'] ?? null,


            'nama_kategori_sore' => $validatedData['nama_kategori_sore'] ?? null,
            'keterangan_sore' => $validatedData['keterangan_sore'] ?? null,
            'text_cerita_sore' => $validatedData['text_cerita_sore'] ?? null,
            'waktu_sore' => $validatedData['waktu_sore'] ?? null,
        ];


        if ($request->hasFile('file1')) {
            $file1 = $request->file('file1');
            $filename1 = $file1->getClientOriginalName();
            // Simpan file ke storage
            $file1->storeAs('public/upload/cerita/pagi/', $filename1);
            $thumbnailpath1 = public_path('storage/upload/cerita/pagi/' . $filename1);
            // Cek apakah file ada dan bukan kosong
            if (file_exists($thumbnailpath1) && filesize($thumbnailpath1) > 0) {
                $img1 = Image::make($thumbnailpath1)->resize(300, 300, function ($constraint1) {
                    $constraint1->aspectRatio();
                });
                $img1->save($thumbnailpath1);
                $data['gambar_pagi'] = $filename1;
            } else {
                return back()->withErrors(['file1' => 'File tidak valid atau kosong.']);
            }
        }
        if ($request->hasFile('file2')) {
            $file2 = $request->file('file2');
            $filename2 = $file2->getClientOriginalName();
            // Simpan file ke storage
            $file2->storeAs('public/upload/cerita/siang/', $filename2);
            $thumbnailpath2 = public_path('storage/upload/cerita/siang/' . $filename2);
            // Cek apakah file ada dan bukan kosong
            if (file_exists($thumbnailpath2) && filesize($thumbnailpath2) > 0) {
                $img1 = Image::make($thumbnailpath2)->resize(300, 300, function ($constraint2) {
                    $constraint2->aspectRatio();
                });
                $img1->save($thumbnailpath2);
                $data['gambar_siang'] = $filename2;
            } else {
                return back()->withErrors(['file2' => 'File tidak valid atau kosong.']);
            }
        }

        if ($request->hasFile('file3')) {
            $file3 = $request->file('file3');
            $filename3 = $file3->getClientOriginalName();
            // Simpan file ke storage
            $file3->storeAs('public/upload/cerita/sore/', $filename3);
            $thumbnailpath3 = public_path('storage/upload/cerita/sore/' . $filename3);
            // Cek apakah file ada dan bukan kosong
            if (file_exists($thumbnailpath3) && filesize($thumbnailpath3) > 0) {
                $img1 = Image::make($thumbnailpath3)->resize(300, 300, function ($constraint3) {
                    $constraint3->aspectRatio();
                });
                $img1->save($thumbnailpath3);
            } else {
                return back()->withErrors(['file3' => 'File tidak valid atau kosong.']);
            }
        }

        $cerita = Cerita::updateOrCreate(
            ['user_id' => Auth::id(), 'tanggal' => now()->format('Y-m-d')],
            $data
        );
        // Insert data ke tabel pivot
        $categories = array_filter([
            $validatedData['nama_kategori_pagi'] ?? null,
            $validatedData['nama_kategori_siang'] ?? null,
            $validatedData['nama_kategori_sore'] ?? null
        ]);

        $waktu = array_filter([
            $validatedData['waktu_pagi'] ?? null,
            $validatedData['waktu_siang'] ?? null,
            $validatedData['waktu_sore'] ?? null
        ]);

        if (!empty($categories)) {
            $cerita_kategori = Kategori::whereIn('nama_kategori', $categories)->pluck('id')->toArray();
            $cerita->kategoris()->attach($cerita_kategori);
        }

        if (!empty($waktu)) {
            $cerita_waktu = Waktu::whereIn('title', $waktu)->pluck('id')->toArray();
            $cerita->waktus()->attach($cerita_waktu);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
