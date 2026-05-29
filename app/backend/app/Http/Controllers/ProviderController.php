<?php

namespace App\Http\Controllers;

use App\Models\AiProvider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = AiProvider::orderBy('name')->get();
        return view('providers.index', compact('providers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'provider_code' => 'required|in:openai,anthropic,deepseek,groq,together',
            'api_key'       => 'required|string',
            'api_endpoint'  => 'nullable|url',
        ]);

        $provider = AiProvider::create($validated);

        return response()->json(['ok' => true, 'provider' => $provider]);
    }

    public function destroy(AiProvider $provider)
    {
        $provider->delete();

        AiAgent::where('provider_id_low', $provider->id)
            ->update(['provider_id_low' => null, 'model_low' => null]);
        AiAgent::where('provider_id_medium', $provider->id)
            ->update(['provider_id_medium' => null, 'model_medium' => null]);
        AiAgent::where('provider_id_high', $provider->id)
            ->update(['provider_id_high' => null, 'model_high' => null]);

        return response()->json(['ok' => true]);
    }
}
