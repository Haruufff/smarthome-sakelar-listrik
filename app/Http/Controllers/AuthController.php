<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginAuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index() {
        return view('pages.login');
    }

    public function login(LoginAuthRequest $request) {
        if (Auth::attempt($request->validated())) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }
        return back()->with('loginError', 'Incorrect Username or Password');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if($request->expectsJson()) {
            return response()->json(['message' => 'Logged out successfully'], 200);
        }

        return redirect('/');
    }
}