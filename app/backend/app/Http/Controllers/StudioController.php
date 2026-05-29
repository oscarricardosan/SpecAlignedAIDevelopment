<?php

namespace App\Http\Controllers;

use App\Models\AiAgent;
use App\Models\Project;

class StudioController extends Controller
{
    public function index()
    {
        $projects = Project::orderBy('name')->get();
        $agentsConfigured = AiAgent::allConfigured();

        return view('studio.index', compact('projects', 'agentsConfigured'));
    }
}
