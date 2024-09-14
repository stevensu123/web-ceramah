<?php
namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string','max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:2', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'no_hp' => $data['no_hp'],
            'username' => $data['username'],
            'status' => 'pending',
            'password' => Hash::make($data['password']),
        ]);

     
        if ($user) {
            $user->assignRole("User");
        // Panggil event hanya sekali setelah user berhasil dibuat
        event(new UserRegistered($user));
    }
    
    }

    // Menimpa metode register untuk menonaktifkan auto-login
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        // Daftarkan pengguna baru
        event(new Registered($user = $this->create($request->all())));

        // Redirect ke halaman login dengan pesan
        return redirect()->route('login')->with('status', 'Akun Anda telah didaftarkan. Silakan tunggu persetujuan dari admin.');
    }
}
