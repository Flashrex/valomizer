<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        return inertia('Agents', [
            'agents' => Agent::where('active', true)->get(),
        ]);
    }

    public function update(Request $request, Agent $agent)
    {

        $validated = $request->validate([
            'active' => 'sometimes|boolean',
            'displayName' => 'sometimes|string',
            'description' => 'sometimes|nullable|string',
            'developerName' => 'sometimes|nullable|string',
            'releaseDate' => 'sometimes|nullable|string',
            'characterTags' => 'sometimes|nullable|array',
            'displayIcon' => 'sometimes|nullable|string',
            'displayIconSmall' => 'sometimes|nullable|string',
            'bustPortrait' => 'sometimes|nullable|string',
            'fullPortrait' => 'sometimes|nullable|string',
            'fullPortraitV2' => 'sometimes|nullable|string',
            'killfeedPortrait' => 'sometimes|nullable|string',
            'background' => 'sometimes|nullable|string',
            'backgroundGradientColors' => 'sometimes|nullable|array',
            'assetPath' => 'sometimes|nullable|string',
            'isFullPortraitRightFacing' => 'sometimes|boolean',
            'isPlayableCharacter' => 'sometimes|boolean',
            'isAvailableForTest' => 'sometimes|boolean',
            'isBaseContent' => 'sometimes|boolean',
            'role' => 'sometimes|nullable|array',
            'recruitmentData' => 'sometimes|nullable|array',
            'abilities' => 'sometimes|nullable|array',
            'voiceLine' => 'sometimes|nullable|array',
        ]);

        $agent->update($validated);

        return redirect()->back()->with('success', 'Agent updated successfully.');
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();

        return redirect()->back()->with('success', 'Agent deleted successfully.');
    }
}
