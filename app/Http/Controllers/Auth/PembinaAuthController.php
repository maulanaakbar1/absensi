<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembinaAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.pembina-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('pembina')->attempt($credentials)) {
            return redirect()->route('pembina.dashboard');
        }

        return back()->with('error', 'Email atau password salah');
    }

    public function logout()
    {
        Auth::guard('pembina')->logout();
        return redirect()->route('pembina.login');
    }
}