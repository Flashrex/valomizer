<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index(Request $request) {
        $agents = Agent::all();
        
        return inertia('Agents', [
            'agents' => $agents,
        ]);
    }
}
