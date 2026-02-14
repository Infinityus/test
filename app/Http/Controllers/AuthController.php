<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show login page
    public function showLogin()
    {
        return view('login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect('/dashboard')->with('success', 'Login successful');
        }

        return back()->with('error', 'Invalid credentials');
    }
}
