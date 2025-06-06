<?php

namespace App\Http\Controllers;

use App\Console\Commands\FetchAgentsCommand;
use App\Console\Commands\FetchMapsCommand;
use App\Models\Agent;
use App\Models\Map;
use App\Models\Statistics;
use App\Models\User;
use App\Models\Visits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index() {
        
        return Inertia::render('admin/Dashboard', [
            'agents' => Agent::all(),
            'maps' => Map::all(),
            'visits' => Visits::count(),
            'uptime' => Statistics::where('key', 'boot_time')->first()?->value ?? now()->format('d-m-Y H:i:s'),
            'agentCount' => Agent::count(),
            'fetchedAgents' => Statistics::where('key', 'fetched_agents')->first()?->fetched_agents ?? now()->format('d-m-Y H:i:s'),
            'mapCount' => Map::count(),
            'fetchedMaps' => Statistics::where('key', 'fetched_maps')->first()?->fetched_maps ?? now()->format('d-m-Y H:i:s'),
        ]);
    }

    public function login() {
        return Inertia::render('admin/Login');
    }

    public function authenticate(Request $request) {
        
        $credentials = $request->only('email', 'password');

        $user = User::where('email', strtolower($credentials['email']))->first();

        if (!$user || !password_verify($credentials['password'], $user->password)) {
            return redirect()->back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();
        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }

    public function fetchAgents() {
        Artisan::call(FetchAgentsCommand::class);
        return response()->json(['message' => 'Fetched Agents successfully!']);
    }

    public function fetchMaps() {
        Artisan::call(FetchMapsCommand::class);
        return response()->json(['message' => 'Fetched Maps successfully!']);
    }
}
