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

    // function awal create data sebelum ada data sama sekali
    public function create($date)
    {
        // Validasi format tanggal
        try {
            // Cek apakah tanggal memiliki format yang benar
            $parsedDate = \Carbon\Carbon::createFromFormat('Y-m-d', $date);
            $user_id = auth()->id();
            // Konversi ke format yang diinginkan


        } catch (\Exception $e) {
            // Redirect atau tampilkan pesan error jika format tanggal salah
            return redirect()->back()->with('error', 'Format tanggal tidak valid. Harap gunakan format dd-mm-yyyy.');
        }

        // Cek apakah ada cerita dengan tanggal yang sama
        $existingStory = Cerita::where('user_id', $user_id)
            ->whereDate('tanggal', '=', $parsedDate)
            ->first();

        if ($existingStory) {
            // Redirect ke halaman detail atau edit cerita yang sudah ada
            return redirect()->route('cerita.show_data', ['cerita' => $existingStory->id, 'date' => $date])->with('message', 'Cerita sudah ada untuk tanggal ini.');
        }

        // Ambil data kategori dan waktu
        $kategori = Kategori::all();
        $waktu1 = Waktu::where('title', 'Pagi')->first();
        $waktu2 = Waktu::where('title', 'Siang')->first();
        $waktu3 = Waktu::where('title', 'Sore')->first();
        $waktu = [$waktu1, $waktu2, $waktu3];

        // Kirim data ke view
        return view('cerita.add', compact('kategori', 'waktu', 'parsedDate'));
    }
    // end function function awal create data sebelum ada data sama sekali


    // function tambah data yang kosong
    public function create_null_data() {}
    // end function tambah data yang kosong

    // function handle route ke tampilan tombol tambah cerita, tanggal expired, 
    //tanggal belum mulai, dan data sudah ada
    public function handleDate($date = null)
    {
        $date = Carbon::parse($date);
        $today = Carbon::today();
        $user_id = auth()->id();
        $cerita = Cerita::where('user_id', $user_id)
            ->wheredate('tanggal', $today)
            ->first();




        if ($cerita) {
            return redirect()->route('cerita.show_data', ['cerita' => $cerita->id, 'date' => $today]);
            dd($cerita);
        }
        if ($date->lt($today)) {
            return redirect()->route('cerita.date_expired');
        }
        if ($date->gt($today)) {
            // Tanggal yang diklik belum dimulai
            return redirect()->route('cerita.date_belum');
        }

        $formattedDate = $date->format('Y-m-d');

        return redirect()->route('cerita.create_view', ['date' => $formattedDate]);
    }
    // end function

    // function rederect dan tampilan tombol tambah cerita
    public function create_view($date)
    {

        $user_id = auth()->id();

        // Format tanggal yang diklik dari parameter URL
        $parsedDate = \Carbon\Carbon::parse($date)->format('Y-m-d');

        // Cek apakah ada cerita dengan user_id dan tanggal yang diklik
        $cerita = Cerita::where('user_id', $user_id)
            ->whereDate('created_at', $parsedDate)
            ->first();


        if ($cerita) {
            // Jika ada cerita, tampilkan view yang sudah ada ceritanya
            return view('cerita.exists', compact('parsedDate', 'cerita'));
        } else {
            // Jika tidak ada cerita, tampilkan view untuk menambahkan cerita baru
            return view('cerita.create_cerita', compact('parsedDate', 'cerita'));
        }
    }
    // end function

    //function rederect dan tampilkan tanggal expired
    public function show_data_expired_date()
    {
        Alert::Warning('Warning', 'Maaf, Tanggal Sudah Lewat');
        return redirect()->route('cerita.index');
    }
    // end functon

    //function rederect dan tampilkan tanggal belum dimulai
    public function show_data_belum_date()
    {
        Alert::Warning('Warning', 'Maaf, Tanggal Belum Dimulai');
        return redirect()->route('cerita.index');
    }
    // end function

    //function rederect dan tampilan data sudah ada
    public function show_data1()
    {
        $user_id = auth()->id();
        $waktuIds = Waktu::pluck('id')->toArray();
        $currentDate = Carbon::today(); // Ini adalah objek Carbon

        $ceritas = Cerita::with('kategoris', 'waktuCeritas')
            ->where('user_id', $user_id)
            ->whereRaw("DATE_FORMAT(tanggal, '%d-%m-%Y') = ?", [$currentDate->format('d-m-Y')])
            ->get();

        $morningStart = $currentDate->copy()->startOfDay()->addHours(6);
        $morningEnd = $currentDate->copy()->startOfDay()->addHours(12);

        $afternoonStart = $currentDate->copy()->startOfDay()->addHours(12);
        $afternoonEnd = $currentDate->copy()->startOfDay()->addHours(18);

        $eveningStart = $currentDate->copy()->startOfDay()->addHours(18);
        $eveningEnd = $currentDate->copy()->endOfDay();

        // Mapping data dengan default null jika kosong
        $data = [
            'pagi' => $ceritas->filter(function ($cerita) {
                return !empty($cerita->nama_kategori_pagi);
            })->map(function ($cerita) {
                return [
                    'nama_kategori_pagi' => $cerita->nama_kategori_pagi ?? null,
                    'gambar_pagi' => $cerita->gambar_pagi ?? null,
                    'keterangan_pagi' => $cerita->keterangan_pagi ?? null,
                    'text_cerita_pagi' => $cerita->text_cerita_pagi ?? null,
                ];
            })->first() ?? null,

            'siang' => $ceritas->filter(function ($cerita) {
                return !empty($cerita->nama_kategori_siang);
            })->map(function ($cerita) {
                return [
                    'nama_kategori_siang' => $cerita->nama_kategori_siang ?? null,
                    'gambar_siang' => $cerita->gambar_siang ?? null,
                    'keterangan_siang' => $cerita->keterangan_siang ?? null,
                    'text_cerita_siang' => $cerita->text_cerita_siang ?? null,
                ];
            })->first() ?? null,

            'sore' => $ceritas->filter(function ($cerita) {
                return !empty($cerita->nama_kategori_sore);
            })->map(function ($cerita) {
                return [
                    'nama_kategori_sore' => $cerita->nama_kategori_sore ?? null,
                    'gambar_sore' => $cerita->gambar_sore ?? null,
                    'keterangan_sore' => $cerita->keterangan_sore ?? null,
                    'text_cerita_sore' => $cerita->text_cerita_sore ?? null,
                ];
            })->first() ?? null,
        ];

        // Temukan slot kosong
        $emptySlots = array_filter($data, function ($item) {
            return is_null($item);
        });
        // Tentukan apakah ada slot kosong
        $showAddButton = count($emptySlots) > 0;

        // Jika tidak ada slot kosong, cari ID cerita yang ada
        $ceritaId = null;
        if (count($emptySlots) === 0) {
            $ceritaId = $ceritas->first()->id; // Mengambil ID cerita yang ada jika semua slot terisi
        } else if ($showAddButton) {
            foreach ($emptySlots as $key => $slot) {
                $waktuIndex = array_search($key, array_keys($data));
                if ($waktuIndex !== false) {
                    $waktuId = $waktuIds[$waktuIndex];
                    $ceritaForTime = $ceritas->first(function ($cerita) use ($waktuId) {
                        return $cerita->waktuCeritas->contains('id', $waktuId);
                    });

                    if ($ceritaForTime) {
                        $ceritaId = $ceritaForTime->id;
                        break;
                    }
                }
            }
        }

        return view('cerita.exists', compact('data', 'currentDate', 'ceritaId', 'showAddButton'));
    }

    public function show_data_contoh()
    {
        $user_id = auth()->id();
        $waktuIds = Waktu::pluck('id')->toArray();
        $currentDate = Carbon::today(); // Ini adalah objek Carbon

        $ceritas = Cerita::with('kategoris', 'waktuCeritas')
            ->where('user_id', $user_id)
            ->whereRaw("DATE_FORMAT(tanggal, '%d-%m-%Y') = ?", [$currentDate->format('d-m-Y')])
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
        // Temukan slot kosong
        $emptySlots = array_filter($data, function ($items) {
            return $items->isEmpty();
        });

        // Tentukan apakah ada slot kosong
        $showAddButton = count($emptySlots) > 0;

        // Temukan ID cerita untuk slot yang kosong, jika ada
        $ceritaId = null;
        if ($showAddButton) {
            foreach ($emptySlots as $key => $slot) {
                // Temukan ID waktu yang sesuai
                $waktuIndex = array_search($key, array_keys($data));
                if ($waktuIndex !== false) {
                    $waktuId = $waktuIds[$waktuIndex];

                    // Cari cerita yang berisi waktu tersebut
                    $ceritaForTime = $ceritas->first(function ($cerita) use ($waktuId) {
                        return $cerita->waktuCeritas->contains('id', $waktuId);
                    });

                    if ($ceritaForTime) {
                        $ceritaId = $ceritaForTime->id;
                        break;
                    }
                }
            }
        }

        return view('cerita.exists', compact('data', 'currentDate', 'ceritaId', 'showAddButton'));
    }

    // public function show_data()
    // {
    //     $user_id = auth()->id();
    //     $waktuIds = Waktu::pluck('id')->toArray();
    //     $currentDate = Carbon::today(); // Ini adalah objek Carbon

    //     $ceritas = Cerita::with('kategoris', 'waktuCeritas')
    //         ->where('user_id', $user_id)
    //         ->whereRaw("DATE_FORMAT(tanggal, '%d-%m-%Y') = ?", [$currentDate->format('d-m-Y')])
    //         ->get();

    //     // Mapping data dengan default null jika kosong
    //     $data = [
    //         'pagi' => $ceritas->filter(function ($cerita) {
    //             return !empty($cerita->nama_kategori_pagi);
    //         })->map(function ($cerita) {
    //             return [
    //                 'nama_kategori_pagi' => $cerita->nama_kategori_pagi ?? null,
    //                 'gambar_pagi' => $cerita->gambar_pagi ?? null,
    //                 'keterangan_pagi' => $cerita->keterangan_pagi ?? null,
    //                 'text_cerita_pagi' => $cerita->text_cerita_pagi ?? null,
    //             ];
    //         })->first() ?? null,

    //         'siang' => $ceritas->filter(function ($cerita) {
    //             return !empty($cerita->nama_kategori_siang);
    //         })->map(function ($cerita) {
    //             return [
    //                 'nama_kategori_siang' => $cerita->nama_kategori_siang ?? null,
    //                 'gambar_siang' => $cerita->gambar_siang ?? null,
    //                 'keterangan_siang' => $cerita->keterangan_siang ?? null,
    //                 'text_cerita_siang' => $cerita->text_cerita_siang ?? null,
    //             ];
    //         })->first() ?? null,

    //         'sore' => $ceritas->filter(function ($cerita) {
    //             return !empty($cerita->nama_kategori_sore);
    //         })->map(function ($cerita) {
    //             return [
    //                 'nama_kategori_sore' => $cerita->nama_kategori_sore ?? null,
    //                 'gambar_sore' => $cerita->gambar_sore ?? null,
    //                 'keterangan_sore' => $cerita->keterangan_sore ?? null,
    //                 'text_cerita_sore' => $cerita->text_cerita_sore ?? null,
    //             ];
    //         })->first() ?? null,
    //     ];

    //     // Temukan slot kosong
    //     $emptySlots = array_filter($data, function ($item) {
    //         return is_null($item);
    //     });

    //     // Tentukan apakah ada slot kosong
    //     $showAddButton = count($emptySlots) > 0;

    //     // Cari ID cerita yang ada
    //     $ceritaId = $ceritas->first()->id ?? null;

    //     return view('cerita.exists', compact('data', 'currentDate', 'ceritaId', 'showAddButton'));
    // }
    //
    public function show_data()
{
    $user_id = auth()->id();
    $currentDate = Carbon::today();
    
    // Mengambil data waktu dari tabel 'waktus'
    $waktuPagi = Waktu::where('title', 'pagi')->first();
    $waktuSiang = Waktu::where('title', 'siang')->first();
    $waktuSore = Waktu::where('title', 'sore')->first();

    if (!$waktuPagi || !$waktuSiang || !$waktuSore) {
        return response()->json(['error' => 'Data waktu belum diatur.'], 404);
    }

    $now = Carbon::now();

    $ceritas = Cerita::with('kategoris', 'waktuCeritas')
        ->where('user_id', $user_id)
        ->whereRaw("DATE_FORMAT(tanggal, '%d-%m-%Y') = ?", [$currentDate->format('d-m-Y')])
        ->get();

    // Mapping data dengan default null jika kosong
    $data = [
        'pagi' => $ceritas->filter(function ($cerita) {
            return !empty($cerita->nama_kategori_pagi);
        })->map(function ($cerita) {
            return [
                'nama_kategori_pagi' => $cerita->nama_kategori_pagi ?? null,
                'gambar_pagi' => $cerita->gambar_pagi ?? null,
                'keterangan_pagi' => $cerita->keterangan_pagi ?? null,
                'text_cerita_pagi' => $cerita->text_cerita_pagi ?? null,
            ];
        })->first() ?? null,

        'siang' => $ceritas->filter(function ($cerita) {
            return !empty($cerita->nama_kategori_siang);
        })->map(function ($cerita) {
            return [
                'nama_kategori_siang' => $cerita->nama_kategori_siang ?? null,
                'gambar_siang' => $cerita->gambar_siang ?? null,
                'keterangan_siang' => $cerita->keterangan_siang ?? null,
                'text_cerita_siang' => $cerita->text_cerita_siang ?? null,
            ];
        })->first() ?? null,

        'sore' => $ceritas->filter(function ($cerita) {
            return !empty($cerita->nama_kategori_sore);
        })->map(function ($cerita) {
            return [
                'nama_kategori_sore' => $cerita->nama_kategori_sore ?? null,
                'gambar_sore' => $cerita->gambar_sore ?? null,
                'keterangan_sore' => $cerita->keterangan_sore ?? null,
                'text_cerita_sore' => $cerita->text_cerita_sore ?? null,
            ];
        })->first() ?? null,
    ];

    // Tentukan apakah ada slot kosong
    $emptySlots = array_filter($data, function ($item) {
        return is_null($item);
    });
    $showAddButton = count($emptySlots) > 0;

    // Cari ID cerita yang ada
    $ceritaId = $ceritas->first()->id ?? null;

    // Tentukan waktu yang masih bisa diupdate
    $updateAvailable = [
        'pagi' => false,
        'siang' => false,
        'sore' => false,
    ];

    $waktuData = [
        'pagi' => $waktuPagi,
        'siang' => $waktuSiang,
        'sore' => $waktuSore,
    ];

    foreach (['pagi' => $waktuPagi, 'siang' => $waktuSiang, 'sore' => $waktuSore] as $key => $waktu) {
        if ($now->between(Carbon::createFromTimeString($waktu->jam_mulai), Carbon::createFromTimeString($waktu->jam_selesai))) {
            $updateAvailable[$key] = true;
        }
    }

    return view('cerita.exists', compact('data', 'currentDate', 'ceritaId', 'showAddButton', 'updateAvailable', 'waktuData'));
}

    
    /**
     * Store a newly created resource in storage.
     */

    // store tambah data sebelum ada data sama sekali dengan validasi
    //kodingna asli
    // public function store(Request $request)
    // {
    //     $currentTime = Carbon::now()->format('H:i');

    //     // Inisialisasi validator dengan aturan untuk semua waktu (pagi, siang, sore)
    //     $rules = [
    //         'file1' => 'nullable|image|mimes:jpeg,png,gif,bmp,webp',
    //         'nama_kategori_pagi' => 'nullable|exists:kategoris,nama_kategori',
    //         'keterangan_pagi' => 'nullable|string|max:255',
    //         'text_cerita_pagi' => 'nullable|string|max:255',
    //         'waktu_pagi' => 'nullable|exists:waktus,title',
    //         'file2' => 'nullable|image|mimes:jpeg,png,gif,bmp,webp',
    //         'nama_kategori_siang' => 'nullable|exists:kategoris,nama_kategori',
    //         'keterangan_siang' => 'nullable|string|max:255',
    //         'text_cerita_siang' => 'nullable|string|max:255',
    //         'waktu_siang' => 'nullable|exists:waktus,title',
    //         'file3' => 'nullable|image|mimes:jpeg,png,gif,bmp,webp',
    //         'nama_kategori_sore' => 'nullable|exists:kategoris,nama_kategori',
    //         'keterangan_sore' => 'nullable|string|max:255',
    //         'text_cerita_sore' => 'nullable|string|max:255',
    //         'waktu_sore' => 'nullable|exists:waktus,title',
    //     ];
    //     $validator = Validator::make($request->all(), $rules);
    //     $validator->after(function ($validator) use ($request, $currentTime) {
    //         // Validasi tambahan untuk pagi
    //         if ($currentTime >= '06:00' && $currentTime < '12:00') {
    //             if (is_null($request->input('nama_kategori_pagi')) || $request->input('nama_kategori_pagi') === '') {
    //                 $validator->errors()->add('nama_kategori_pagi', 'Kategori pagi tidak boleh kosong.');
    //             }
    //         }
    //         // Validasi tambahan untuk siang
    //         if ($currentTime >= '12:00' && $currentTime < '18:00') {
    //             if (is_null($request->input('nama_kategori_siang')) || $request->input('nama_kategori_siang') === '') {
    //                 $validator->errors()->add('nama_kategori_siang', 'Kategori siang tidak boleh kosong.');
    //             }
    //         }

    //         // Validasi tambahan untuk sore
    //         if ($currentTime >= '18:00' && $currentTime < '24:00') {
    //             if (is_null($request->input('nama_kategori_sore')) || $request->input('nama_kategori_sore') === '') {
    //                 $validator->errors()->add('nama_kategori_sore', 'Kategori sore tidak boleh kosong.');
    //             }
    //         }

    //         // Validasi agar waktu pagi tetap diperiksa meskipun waktu sekarang siang atau sore
    //         if (is_null($request->input('nama_kategori_pagi')) || $request->input('nama_kategori_pagi') === '') {
    //             $validator->errors()->add('nama_kategori_pagi', 'Kategori pagi tidak boleh kosong.');
    //         }
    //     });

    //     if ($validator->fails()) {
    //         return redirect()->back()
    //             ->withErrors($validator)
    //             ->withInput();
    //     }

    //     $validatedData = $validator->validated();
    //     $data = [
    //         'user_id' => Auth::id(),
    //         'tanggal' => Carbon::today(),
    //         'nama_kategori_pagi' => $validatedData['nama_kategori_pagi'] ?? null,
    //         'keterangan_pagi' => $validatedData['keterangan_pagi'] ?? null,
    //         'text_cerita_pagi' => $validatedData['text_cerita_pagi'] ?? null,
    //         'waktu_pagi' => $validatedData['waktu_pagi'] ?? null,

    //         'nama_kategori_siang' => $validatedData['nama_kategori_siang'] ?? null,
    //         'keterangan_siang' => $validatedData['keterangan_siang'] ?? null,
    //         'text_cerita_siang' => $validatedData['text_cerita_siang'] ?? null,
    //         'waktu_siang' => $validatedData['waktu_siang'] ?? null,

    //         'nama_kategori_sore' => $validatedData['nama_kategori_sore'] ?? null,
    //         'keterangan_sore' => $validatedData['keterangan_sore'] ?? null,
    //         'text_cerita_sore' => $validatedData['text_cerita_sore'] ?? null,
    //         'waktu_sore' => $validatedData['waktu_sore'] ?? null,
    //     ];
    //     if ($request->hasFile('file1')) {
    //         $file1 = $request->file('file1');
    //         $filename1 = $file1->getClientOriginalName();
    //         // Simpan file ke storage
    //         $file1->storeAs('public/upload/cerita/pagi/', $filename1);
    //         $thumbnailpath1 = public_path('storage/upload/cerita/pagi/' . $filename1);
    //         // Cek apakah file ada dan bukan kosong
    //         if (file_exists($thumbnailpath1) && filesize($thumbnailpath1) > 0) {
    //             $img1 = Image::make($thumbnailpath1)->resize(300, 300, function ($constraint1) {
    //                 $constraint1->aspectRatio();
    //             });
    //             $img1->save($thumbnailpath1);
    //             $data['gambar_pagi'] = $filename1;
    //         } else {
    //             return back()->withErrors(['file1' => 'File tidak valid atau kosong.']);
    //         }
    //     }
    //     if ($request->hasFile('file2')) {
    //         $file2 = $request->file('file2');
    //         $filename2 = $file2->getClientOriginalName();
    //         // Simpan file ke storage
    //         $file2->storeAs('public/upload/cerita/siang/', $filename2);
    //         $thumbnailpath2 = public_path('storage/upload/cerita/siang/' . $filename2);
    //         // Cek apakah file ada dan bukan kosong
    //         if (file_exists($thumbnailpath2) && filesize($thumbnailpath2) > 0) {
    //             $img1 = Image::make($thumbnailpath2)->resize(300, 300, function ($constraint2) {
    //                 $constraint2->aspectRatio();
    //             });
    //             $img1->save($thumbnailpath2);
    //             $data['gambar_siang'] = $filename2;
    //         } else {
    //             return back()->withErrors(['file2' => 'File tidak valid atau kosong.']);
    //         }
    //     }
    //     if ($request->hasFile('file3')) {
    //         $file3 = $request->file('file3');
    //         $filename3 = $file3->getClientOriginalName();
    //         // Simpan file ke storage
    //         $file3->storeAs('public/upload/cerita/sore/', $filename3);
    //         $thumbnailpath3 = public_path('storage/upload/cerita/sore/' . $filename3);
    //         // Cek apakah file ada dan bukan kosong
    //         if (file_exists($thumbnailpath3) && filesize($thumbnailpath3) > 0) {
    //             $img1 = Image::make($thumbnailpath3)->resize(300, 300, function ($constraint3) {
    //                 $constraint3->aspectRatio();
    //             });
    //             $img1->save($thumbnailpath3);
    //         } else {
    //             return back()->withErrors(['file3' => 'File tidak valid atau kosong.']);
    //         }
    //     }
    //     $cerita = Cerita::create($data);
    //     // Insert data ke tabel pivot
    //     $categories = array_filter([
    //         $validatedData['nama_kategori_pagi'] ?? null,
    //         $validatedData['nama_kategori_siang'] ?? null,
    //         $validatedData['nama_kategori_sore'] ?? null
    //     ]);
    //     $waktu = array_filter([
    //         $validatedData['waktu_pagi'] ?? null,
    //         $validatedData['waktu_siang'] ?? null,
    //         $validatedData['waktu_sore'] ?? null
    //     ]);
    //     if (!empty($categories)) {
    //         $cerita_kategori = Kategori::whereIn('nama_kategori', $categories)->pluck('id')->toArray();
    //         $cerita->kategoris()->attach($cerita_kategori);
    //     }
    //     if (!empty($waktu)) {
    //         $cerita_waktu = Waktu::whereIn('title', $waktu)->pluck('id')->toArray();
    //         $cerita->waktuCeritas()->attach($cerita_waktu);
    //     }
    //     return redirect()->route('cerita.index')->with(['success' => 'Data Berhasil Disimpan!']);
    // }
    // end function store tambah data sebelum ada data sama sekali
    public function store(Request $request)
    {
        $currentTime = Carbon::now()->format('H:i');

        // Daftar waktu
        $times = ['pagi' => ['06:00', '12:00'], 'siang' => ['12:00', '18:00'], 'sore' => ['18:00', '24:00']];

        // Inisialisasi validator dengan aturan dinamis
        $rules = [];
        foreach ($times as $time => $range) {
            $rules["file{$time}"] = 'nullable|image|mimes:jpeg,png,gif,bmp,webp';
            $rules["nama_kategori_{$time}"] = 'nullable|exists:kategoris,nama_kategori';
            $rules["keterangan_{$time}"] = 'nullable|string|max:255';
            $rules["text_cerita_{$time}"] = 'nullable|string|max:255';
            $rules["waktu_{$time}"] = 'nullable|exists:waktus,title';
        }

        $validator = Validator::make($request->all(), $rules);

        $validator->after(function ($validator) use ($request, $currentTime, $times) {
            foreach ($times as $time => $range) {
                // Jika waktu saat ini berada dalam rentang waktu ini, maka lakukan validasi
                if ($currentTime >= $range[0] && $currentTime < $range[1]) {
                    foreach (['nama_kategori', 'keterangan', 'text_cerita'] as $field) {
                        if (is_null($request->input("{$field}_{$time}")) || $request->input("{$field}_{$time}") === '') {
                            $validator->errors()->add("{$field}_{$time}", ucfirst($field) . " $time tidak boleh kosong.");
                        }
                    }
                }
            }

            // Validasi data sebelumnya berdasarkan waktu saat ini
            if ($currentTime >= $times['siang'][0]) { // Jika waktu sekarang siang atau lebih, validasi data pagi
                foreach (['nama_kategori', 'keterangan', 'text_cerita'] as $field) {
                    if (is_null($request->input("{$field}_pagi")) || $request->input("{$field}_pagi") === '') {
                        $validator->errors()->add("{$field}_pagi", ucfirst($field) . " pagi tidak boleh kosong.");
                    }
                }
            }

            if ($currentTime >= $times['sore'][0]) { // Jika waktu sekarang sore, validasi data siang
                foreach (['nama_kategori', 'keterangan', 'text_cerita'] as $field) {
                    if (is_null($request->input("{$field}_siang")) || $request->input("{$field}_siang") === '') {
                        $validator->errors()->add("{$field}_siang", ucfirst($field) . " siang tidak boleh kosong.");
                    }
                }
            }
        });

        if ($validator->fails()) {
            dd($validator->errors());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validatedData = $validator->validated();
        $data = [
            'user_id' => Auth::id(),
            'tanggal' => Carbon::today(),
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


    public function edit(string $id, $time)
    {

        $currentDate = Carbon::now()->format('d-m-Y');
        $cerita = $id > 0 ? Cerita::find($id) : new Cerita();
        $kategori = $cerita ? $cerita->getNamaKategoriByWaktu($time) : null;
        $textCerita = $cerita ? $cerita->getNamaTextCeritaByWaktu($time) : '';
        $keterangan = $cerita ? $cerita->getNamaKeteranganByWaktu($time) : '';
        $gambar = $cerita ? $cerita->getGambarByWaktu($time) : '';
        // Ambil semua kategori untuk select option
        $kategoriList = Kategori::all();

        return view('cerita.edit', compact('currentDate', 'cerita', 'kategori', 'keterangan', 'textCerita', 'gambar', 'time', 'kategoriList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'file1' => 'nullable|image|mimes:jpeg,png,gif,bmp,webp',
            'nama_kategori_pagi' => 'nullable|exists:kategoris,nama_kategori',
            'keterangan_pagi' => 'nullable|string|max:255',
            'text_cerita_pagi' => 'nullable|string|max:255',
            'waktu_pagi' => 'nullable|exists:waktus,title',

            'file2' => 'nullable|image|mimes:jpeg,png,gif,bmp,webp',
            'nama_kategori_siang' => 'nullable|exists:kategoris,nama_kategori',
            'keterangan_siang' => 'nullable|string|max:255',
            'text_cerita_siang' => 'nullable|string|max:255',
            'waktu_siang' => 'nullable|exists:waktus,title',

            'file3' => 'nullable|image|mimes:jpeg,png,gif,bmp,webp',
            'nama_kategori_sore' => 'nullable|exists:kategoris,nama_kategori',
            'keterangan_sore' => 'nullable|string|max:255',
            'text_cerita_sore' => 'nullable|string|max:255',
            'waktu_sore' => 'nullable|exists:waktus,title',
        ]);

        // Ambil data cerita berdasarkan ID
        $cerita = Cerita::find($id);

        if (!$cerita) {
            return redirect()->route('cerita.index')->with('error', 'Cerita tidak ditemukan.');
        }

        // Ambil data lama untuk memelihara data yang tidak berubah
        $oldData = $cerita->toArray();

        // Update atau simpan gambar dan data untuk pagi
        $this->updateDataForWaktu($request, $cerita, 'pagi', $validatedData, $oldData);

        // Update atau simpan gambar dan data untuk siang
        $this->updateDataForWaktu($request, $cerita, 'siang', $validatedData, $oldData);

        // Update atau simpan gambar dan data untuk sore
        $this->updateDataForWaktu($request, $cerita, 'sore', $validatedData, $oldData);

        // Update data cerita
        $cerita->save();

        // Update atau insert data ke tabel pivot
        $this->updatePivotTables($cerita, $validatedData);

        return redirect()->route('cerita.index')->with('success', 'Cerita berhasil diperbarui.');
    }

    private function updateDataForWaktu(Request $request, $cerita, $waktu, $validatedData, $oldData)
    {
        $fileKey = "file" . ($waktu == 'pagi' ? 1 : ($waktu == 'siang' ? 2 : 3));
        $folder = $waktu;

        // Update data untuk waktu tertentu
        $cerita->{"nama_kategori_$waktu"} = $validatedData["nama_kategori_$waktu"] ?? $oldData["nama_kategori_$waktu"];
        $cerita->{"keterangan_$waktu"} = $validatedData["keterangan_$waktu"] ?? $oldData["keterangan_$waktu"];
        $cerita->{"text_cerita_$waktu"} = $validatedData["text_cerita_$waktu"] ?? $oldData["text_cerita_$waktu"];
        $cerita->{"waktu_$waktu"} = $validatedData["waktu_$waktu"] ?? $oldData["waktu_$waktu"];

        // Update gambar jika ada file yang di-upload
        if ($request->hasFile($fileKey)) {
            $file = $request->file($fileKey);
            $filename = $file->getClientOriginalName();
            $file->storeAs("public/upload/cerita/{$folder}/", $filename);
            $thumbnailPath = public_path("storage/upload/cerita/{$folder}/{$filename}");

            if (file_exists($thumbnailPath) && filesize($thumbnailPath) > 0) {
                $img = Image::make($thumbnailPath)->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($thumbnailPath);
                $cerita->{"gambar_$waktu"} = $filename;
            }
        } else {
            $cerita->{"gambar_$waktu"} = $oldData["gambar_$waktu"];
        }
    }

    private function updatePivotTables($cerita, $validatedData)
    {
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
            $cerita->kategoris()->sync($cerita_kategori);
        }

        if (!empty($waktu)) {
            $cerita_waktu = Waktu::whereIn('title', $waktu)->pluck('id')->toArray();
            $cerita->waktuCeritas()->sync($cerita_waktu);
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
