<?php

namespace App\Http\Controllers;

use App\Models\AiAgent;
use App\Models\AiProvider;
use Illuminate\Http\Request;

class AiAgentController extends Controller
{
    public function index()
    {
        $agents = AiAgent::with(['providerLow', 'providerMedium', 'providerHigh'])->get();
        $providers = AiProvider::orderBy('name')->get();

        return view('ai-agents.index', compact('agents', 'providers'));
    }

    public function update(Request $request, AiAgent $agent)
    {
        $validated = $request->validate([
            'name'               => 'nullable|string|max:255',
            'provider_id_low'    => 'nullable|exists:ai_providers,id',
            'model_low'          => 'nullable|string|max:255',
            'provider_id_medium' => 'nullable|exists:ai_providers,id',
            'model_medium'       => 'nullable|string|max:255',
            'provider_id_high'   => 'nullable|exists:ai_providers,id',
            'model_high'         => 'nullable|string|max:255',
        ]);

        $agent->update($validated);
        $agent->load(['providerLow', 'providerMedium', 'providerHigh']);

        return response()->json(['ok' => true, 'agent' => $agent]);
    }
}
