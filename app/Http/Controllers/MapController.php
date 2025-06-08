<?php

namespace App\Http\Controllers;

use App\Models\Map;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $maps = Map::where('active', true)->get();

        return inertia('Maps', [
            'maps' => $maps,
        ]);
    }

    public function update(Request $request, Map $map)
    {
        $validated = $request->validate([
            'uuid' => 'sometimes|string',
            'displayName' => 'sometimes|string',
            'narrativeDescription' => 'sometimes|nullable|string',
            'tacticalDescription' => 'sometimes|nullable|string',
            'coordinates' => 'sometimes|nullable|string',
            'displayIcon' => 'sometimes|nullable|string',
            'listViewIcon' => 'sometimes|nullable|string',
            'listViewIconTall' => 'sometimes|nullable|string',
            'splash' => 'sometimes|nullable|string',
            'stylizedBackgroundImage' => 'sometimes|nullable|string',
            'premierBackgroundImage' => 'sometimes|nullable|string',
            'assetPath' => 'sometimes|nullable|string',
            'mapUrl' => 'sometimes|nullable|string',
            'xMultiplier' => 'sometimes|nullable|numeric',
            'yMultiplier' => 'sometimes|nullable|numeric',
            'xScalarToAdd' => 'sometimes|nullable|numeric',
            'yScalarToAdd' => 'sometimes|nullable|numeric',
            'callouts' => 'sometimes|nullable|array',
            'gamemode' => 'sometimes|nullable|string',
            'active' => 'sometimes|boolean',
        ]);

        $map->update($validated);

        return redirect()->back()->with('success', 'Map updated successfully.');
    }

    public function destroy(Map $map)
    {
        $map->delete();

        return redirect()->back()->with('success', 'Map deleted successfully.');
    }
}
