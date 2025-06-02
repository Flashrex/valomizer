<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Map;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index() {
        
        return Inertia::render('admin/Dashboard', [
            'agents' => Agent::all(),
            'maps' => Map::all()
        ]);
    }

    public function login() {
        return Inertia::render('admin/Login');
    }

    public function authenticate(Request $request) {
        
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !password_verify($credentials['password'], $user->password)) {
            return redirect()->back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();
        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }
}
