<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

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
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'no_hp' => $data['no_hp'],
            'username' => $data['username'],
            'status' => 'pending',
            'password' => Hash::make($data['password']),
        ]);
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
