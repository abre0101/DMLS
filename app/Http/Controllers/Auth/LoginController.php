<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
    
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

       
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->status === 'blocked') {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Your account has been blocked. Please contact the administrator.',
                ]);
            }

       
            return redirect()->intended(route('dashboard'));
        }

      
        return redirect()->back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
