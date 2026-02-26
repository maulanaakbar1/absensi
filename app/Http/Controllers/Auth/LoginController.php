<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Coba login sebagai admin
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }

        // Coba login sebagai pembina
        if (Auth::guard('pembina')->attempt($credentials)) {
            return redirect()->route('pembina.dashboard');
        }

        // Coba login sebagai siswa
        if (Auth::guard('siswa')->attempt($credentials)) {
            return redirect()->route('siswa.dashboard');
        }

        return back()->with('error', 'Email atau password salah');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        Auth::guard('pembina')->logout();
        Auth::guard('siswa')->logout();

        return redirect()->route('login');
    }
}