<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Events\UserStatusUpdated;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Notifications\UserStatusUpdatedNotification;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')
                ->where('status', 'approved')
                ->get();

    // Kirim data user ke view
    return view('users.index', compact('users'));
    }

    public function fetch()
    {
        $users = User::with('roles')->where('status', 'pending');

        return DataTables::of($users)
            ->addColumn('roles', function($user) {
                return $user->roles->pluck('name')->implode(', ');
            })
            ->addColumn('status', function($user) {
                return '<span class="badge bg-label-' . ($user->status == 'approved' ? 'success' : 'danger') . ' me-1" 
                        id="status-' . $user->id . '" 
                        data-id="' . $user->id . '" 
                        data-username="' . $user->name . '" 
                        onclick="updateStatus(this)">
                        ' . $user->status . '</span>';
            })
            
            ->addColumn('action', function($user) {
                return '<div class="btn-group" role="group" aria-label="Action buttons">
                            <a href="' . route('users.edit', $user->id) . '" class="btn btn-warning">Edit</a>
                            <button class="btn btn-danger btn-delete" data-name="' . $user->name . '" data-id="' . $user->id . '">
                                <i class="bx bx-trash me-1"></i> Delete
                            </button>
                        </div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function pendingView()
    {
        $notifications = Auth::user()->notifications; 
        $users = User::with('roles')
                ->where('status', 'pending')
                ->get();
                // $notifications1 = DB::table('notifications')
                // ->where('data', 'LIKE', '%"user_id":46%')
                // ->get();
                // dd($notifications1);
    // Kirim data user ke view
    return view('users.pendding', compact('users' , 'notifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all(); // Mengambil semua role
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required',
            'nohp' => 'required|numeric|digits_between:10,13',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|string|exists:roles,name' // Validasi bahwa setiap role yang dipilih valid
        ]);

        // Membuat user
        $user = User::create([
            'name' => $validated['name'],
            'no_hp' => $validated['nohp'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'status' =>  $validated['status'],
        ]);

        // Menambahkan role ke user
        $user->assignRole($validated['roles']);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       

        // Mengembalikan data JSON yang hanya diperlukan
        $user = User::with('roles')->findOrFail($id);

        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); // Mendapatkan semua role
    
        return view('users.edit', compact('user', 'roles'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nohp' => 'required|numeric|digits_between:10,13',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|string|min:8|confirmed', // Validasi password jika ada
            'roles' => 'required|string|exists:roles,name',
            'status' => 'required|string|in:approved,pending' 
        ]);
    
        $user = User::findOrFail($id);
        
        // Update data pengguna
        $user->update([
            'name' => $validated['name'],
            'no_hp' => $validated['nohp'],
            'username' => $validated['username'],
            'status' => $validated['status'],
            // Update password jika diisi
            'password' => isset($validated['password']) ? Hash::make($validated['password']) : $user->password,
        ]);
    
        // Menetapkan role yang baru
        $user->syncRoles($validated['roles']);
    
        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status = $request->status; // Mengubah status jadi approved
        $user->save();
    
        // Kirimkan notifikasi ke admin dan superadmin
        $adminUsers = User::role(['admin', 'superadmin'])->get(); // Ambil user dengan role admin dan superadmin
        foreach ($adminUsers as $admin) {
            $admin->notify(new UserStatusUpdatedNotification($user)); // Kirim notifikasi
        }
    
        // Trigger event untuk broadcast
        event(new UserStatusUpdated($user));
    
        return response()->json(['success' => true, 'message' => 'Status berhasil diubah']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
    
        if ($user) {
            $user->logAndDelete();
            $user->roles()->detach(); 
            $user->delete();
        }
        // Menghapus role yang terkait dengan user
      
    
        // Menghapus user
      
      
    
        return response()->json(['success' => 'Data berhasil dihapus.']);
    }



  

    public function deletedUsers()
    {
        // Ambil isi file log yang menyimpan data pengguna yang dihapus
        $logContent = Storage::get('deleted_users.log');
        
        // Pisahkan tiap baris log ke dalam array
        $deletedUsers = collect(explode("\n", trim($logContent)))->filter(function ($value) {
            return !empty($value);
        })->map(function ($user) {
            $user = json_decode($user);
            // Format tanggal dan waktu dengan zona waktu yang tepat
            $user->deleted_at = Carbon::parse($user->deleted_at)->timezone('Asia/Jakarta')->format('d-m-Y H:i');
            return $user;
        });
    
        // Cek apakah ada data pengguna yang dihapus
        $hasDeletedUsers = $deletedUsers->isNotEmpty();
    
        // Kirim data ke view
        return view('users.deleted_log', compact('deletedUsers', 'hasDeletedUsers'));
       
    }
    
    
    
    

    public function clearDeletedUsersLog()
    {
        // Mengambil ID pengguna yang dihapus dari file log deleted_users.log
        $deletedUsersLog = Storage::get('deleted_users.log');
        $deletedUsers = collect(explode("\n", trim($deletedUsersLog)))
            ->filter(fn($line) => !empty($line))
            ->map(fn($line) => json_decode($line))
            ->pluck('id');
        
        // Membaca isi file laravel.log
        $laravelLogPath = storage_path('logs/laravel.log');
        $laravelLogContent = file_get_contents($laravelLogPath);
    
        // Menghapus entri terkait ID pengguna yang dihapus
        $filteredLogContent = collect(explode("\n", $laravelLogContent))
            ->filter(function ($logLine) use ($deletedUsers) {
                foreach ($deletedUsers as $id) {
                    if (strpos($logLine, (string)$id) !== false) {
                        return false; // Filter out lines containing the user ID
                    }
                }
                return true;
            })
            ->implode("\n");
    
        // Menulis ulang file laravel.log tanpa entri yang dihapus
        file_put_contents($laravelLogPath, $filteredLogContent);
    
        // Mengosongkan file log pengguna yang dihapus
        Storage::put('deleted_users.log', '');
    
        return redirect()->back()->with('success', 'Log pengguna yang dihapus telah dibersihkan.');
    }
    
}
