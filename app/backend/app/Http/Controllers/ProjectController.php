<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('compliances')->latest()->get();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255', 'unique:projects,name'],
            'path'            => ['required', 'string', 'max:1024'],
            'description'     => ['nullable', 'string'],
            'criticality'     => ['required', Rule::in(['mission_critical', 'business_important', 'administrative', 'experimental'])],
            'business_sector' => ['required', Rule::in(['finance', 'healthcare', 'education', 'ecommerce', 'logistics', 'government', 'entertainment', 'manufacturing', 'other'])],
            'target_audience' => ['required', Rule::in(['internal', 'b2b', 'b2c', 'public'])],
            'compliance'      => ['nullable', 'array'],
            'compliance.*'    => [Rule::in(['none', 'gdpr', 'hipaa', 'pci_dss', 'soc2', 'iso_27001'])],
        ]);

        $project = Project::create([
            'name'                 => $validated['name'],
            'path'                 => $validated['path'],
            'description'          => $validated['description'] ?? null,
            'criticality'          => $validated['criticality'],
            'business_sector'      => $validated['business_sector'],
            'target_audience'      => $validated['target_audience'],
            'user_who_created_id'  => Auth::id(),
        ]);

        if (! empty($validated['compliance'])) {
            foreach ($validated['compliance'] as $compliance) {
                $project->compliances()->create([
                    'compliance'            => $compliance,
                    'user_who_created_id'   => Auth::id(),
                ]);
            }
        }

        $this->toast('Project "' . $project->name . '" created successfully.', 'success');

        return redirect()->route('projects.index');
    }

    public function edit(Project $project)
    {
        $project->load('compliances');

        return view('projects.create', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255', Rule::unique('projects', 'name')->ignore($project->id)],
            'description'     => ['nullable', 'string'],
            'criticality'     => ['required', Rule::in(['mission_critical', 'business_important', 'administrative', 'experimental'])],
            'business_sector' => ['required', Rule::in(['finance', 'healthcare', 'education', 'ecommerce', 'logistics', 'government', 'entertainment', 'manufacturing', 'other'])],
            'target_audience' => ['required', Rule::in(['internal', 'b2b', 'b2c', 'public'])],
            'compliance'      => ['nullable', 'array'],
            'compliance.*'    => [Rule::in(['none', 'gdpr', 'hipaa', 'pci_dss', 'soc2', 'iso_27001'])],
        ]);

        $project->update([
            'name'                 => $validated['name'],
            'description'          => $validated['description'] ?? null,
            'criticality'          => $validated['criticality'],
            'business_sector'      => $validated['business_sector'],
            'target_audience'      => $validated['target_audience'],
            'user_who_updated_id'  => Auth::id(),
        ]);

        $project->compliances()->forceDelete();
        if (! empty($validated['compliance'])) {
            foreach ($validated['compliance'] as $compliance) {
                $project->compliances()->create([
                    'compliance'            => $compliance,
                    'user_who_created_id'   => Auth::id(),
                ]);
            }
        }

        $this->toast('Project "' . $project->name . '" updated successfully.', 'success');

        return redirect()->route('projects.index');
    }
}
