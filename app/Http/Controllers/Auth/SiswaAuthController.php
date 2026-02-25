<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.siswa-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('siswa')->attempt($credentials)) {
            return redirect()->route('siswa.dashboard');
        }

        return back()->with('error', 'Email atau password salah');
    }

    public function logout()
    {
        Auth::guard('siswa')->logout();
        return redirect()->route('siswa.login');
    }
}