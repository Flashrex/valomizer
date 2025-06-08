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
use MongoDB\BSON\UTCDateTime;

class AdminController extends Controller
{
    public function index()
    {

        $startup = Statistics::where('key', 'startup')->first();
        if (! $startup) {
            $startup = Statistics::create([
                'key' => 'startup',
                'value' => now(),
            ]);
        }

        
        $since = now()->subDay();
        $uniqueVisits = Visits::raw(function($collection) use ($since) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'created_at' => ['$gte' => new UTCDateTime($since->getTimestamp() * 1000)],
                    ],
                ],
                [
                    '$sort' => ['created_at' => -1], // get the latest visit per IP
                ],
                [
                    '$group' => [
                        '_id' => '$ip',
                        'doc' => ['$first' => '$$ROOT'],
                    ],
                ],
                [
                    '$replaceRoot' => ['newRoot' => '$doc'],
                ],
            ]);
        });

        return Inertia::render('admin/Dashboard', [
            'agents' => Agent::all(),
            'maps' => Map::all(),
            'visits' => [
                'lastTwentyFourHours' => $uniqueVisits->count(),
                'total' => Visits::count(),
            ],
            'uptime' => $startup->updated_at->diffForHumans(),
            'totalAgents' => Agent::where('active', true)->count(),
            'fetchedAgents' => Statistics::where('key', 'fetched_agents')->first()?->updated_at->format('d-m-Y H:i:s') ?? now()->format('d-m-Y H:i:s'),
            'totalMaps' => Map::where('active', true)->count(),
            'fetchedMaps' => Statistics::where('key', 'fetched_maps')->first()?->updated_at->format('d-m-Y H:i:s') ?? now()->format('d-m-Y H:i:s'),
        ]);
    }

    public function login()
    {
        return Inertia::render('admin/Login');
    }

    public function authenticate(Request $request)
    {

        $credentials = $request->only('email', 'password');

        $user = User::where('email', strtolower($credentials['email']))->first();

        if (! $user || ! password_verify($credentials['password'], $user->password)) {
            return redirect()->back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();
        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }

    public function fetchAgents()
    {
        Artisan::call(FetchAgentsCommand::class);

        return redirect()->back()->with('success', 'Fetched Agents successfully!');
    }

    public function fetchMaps()
    {
        Artisan::call(FetchMapsCommand::class);

        return redirect()->back()->with('success', 'Fetched Agents successfully!');
    }
}
