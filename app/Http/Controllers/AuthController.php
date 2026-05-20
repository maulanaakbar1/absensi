<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        // Mengarah ke folder views/auth/login.blade.php
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Buat pesan selamat datang kustom
            $pesanSukses = 'Selamat datang kembali, ' . $user->name . '!';

            // Redirect sesuai role sambil membawa flash session 'loginSuccess'
            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard')->with('loginSuccess', $pesanSukses);
            }
            if ($user->role === 'pembina') {
                return redirect()->intended('/pembina/dashboard')->with('loginSuccess', $pesanSukses);
            }
            
            return redirect()->intended('/siswa/dashboard')->with('loginSuccess', $pesanSukses);
        }

        return back()->with('loginError', 'Email atau password salah!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('logoutSuccess', 'Anda telah berhasil keluar dari sistem.');
    }
}