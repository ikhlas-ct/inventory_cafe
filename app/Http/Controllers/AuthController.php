<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\KaryawanLoginNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login()
    {
        return view('pages.auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $field     => $request->login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Jika yang login adalah karyawan
            if ($user->role === 'karyawan') {
                $karyawan = $user->karyawan; // Ambil relasi Karyawan

                if ($karyawan) { // Cek kalau ada data karyawan
                    // Ambil semua manager
                    $managers = User::where('role', 'manajer')->get();

                    foreach ($managers as $manager) {
                        $manager->notify(new KaryawanLoginNotification($karyawan)); // Pass $karyawan (bukan $user)
                    }
                }
            }

            return match ($user->role) {
                'manajer'  => redirect()->route('dashboard'),
                'karyawan' => redirect()->route('dashboard'),
                default    => redirect()->intended('/dashboard'),
            };
        }

        throw ValidationException::withMessages([
            'login' => 'Username/email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
