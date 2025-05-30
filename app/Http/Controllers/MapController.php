<?php

namespace App\Http\Controllers;

use App\Models\Map;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index() {
        $maps = Map::all(); // TODO: Return only active maps

        return inertia('Maps', [
            'maps' => $maps,
        ]);
    }
}
