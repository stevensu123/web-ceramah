<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {

        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username    = $request->username;
        $password = $request->password;

      
        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            // Dapatkan user yang telah di-authenticate
            $user = Auth::user();
    
            // Periksa apakah status user adalah 'approved'
            if ($user->status === 'approved') {
                // Jika disetujui, arahkan ke dashboard
                return redirect()->route('dashboard.index');
            } else {
                // Jika status bukan 'approved', logout dan arahkan kembali ke halaman login dengan pesan error
                Auth::logout();
                return redirect('login')->with(['status' => 'Akun Anda belum disetujui oleh admin.']);
            }
        } else {
            // Jika autentikasi gagal, arahkan kembali ke halaman login dengan pesan error
            return redirect('login')->with(['status' => 'Login gagal, silakan periksa username dan password Anda.']);
        }
    }

}
