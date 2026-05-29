<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AppController extends Controller
{
    public function index(Project $project)
    {
        $applications = $project->applications()->latest()->get();

        return view('apps.index', compact('project', 'applications'));
    }

    public function create(Project $project)
    {
        return view('apps.create', [
            'project'      => $project,
            'projectPath'  => ltrim($project->path, '/'),
        ]);
    }

    public function store(Request $request, Project $project)
    {
        $validated = $this->validateApp($request, $project);

        $data = $this->resolveCustomFields($validated);
        $data['project_id'] = $project->id;
        $data['disk'] = 'mnt-said-projects';
        $data['user_who_created_id'] = Auth::id();

        $application = Application::create($data);

        $this->toast('Application "' . $application->name . '" created successfully.', 'success');

        return redirect()->route('apps.show', [$project, $application]);
    }

    public function show(Project $project, Application $application)
    {
        return view('apps.show', compact('project', 'application'));
    }

    public function edit(Project $project, Application $application)
    {
        session()->flashInput([
            'name'                      => $application->name,
            'description'               => $application->description,
            'path'                      => $application->path,
            'notes'                     => $application->notes,
            'platform'                  => $application->platform,
            'language'                  => $application->language_is_custom ? 'other' : $application->language,
            'language_custom'           => $application->language_is_custom ? $application->language : '',
            'language_version'          => $application->language_version_is_custom ? 'custom' : $application->language_version,
            'language_version_custom'   => $application->language_version_is_custom ? $application->language_version : '',
            'technology'                => $application->technology_is_custom ? 'other' : $application->technology,
            'technology_custom'         => $application->technology_is_custom ? $application->technology : '',
            'framework_version'         => $application->framework_version_is_custom ? 'custom' : $application->framework_version,
            'framework_version_custom'  => $application->framework_version_is_custom ? $application->framework_version : '',
            'package_manager'           => $application->package_manager,
            'paradigm'                  => $application->paradigm,
            'architecture'              => $application->architecture,
            'design_principles'         => $application->design_principles_codes ?? [],
            'databases'                 => $application->databases_codes ?? [],
            'database_access'           => $application->database_access,
            'storage'                   => $application->storage_codes ?? [],
            'code_repository'           => $application->code_repository,
            'code_repository_url'       => $application->code_repository_url,
            'testing_frameworks'        => $application->testing_frameworks_codes,
            'code_style'                => $application->code_style,
            'ci_cd'                     => $application->ci_cd,
            'executor'                  => $application->executor,
            'context_stack'             => $application->context_stack,
            'context_architecture'      => $application->context_architecture,
            'context_guidelines'        => $application->context_guidelines,
        ]);

        return view('apps.edit', [
            'project'      => $project,
            'application'  => $application,
            'projectPath'  => ltrim($project->path, '/'),
        ]);
    }

    public function update(Request $request, Project $project, Application $application)
    {
        $validated = $this->validateApp($request, $project, $application);

        $data = $this->resolveCustomFields($validated);
        $data['user_who_updated_id'] = Auth::id();

        $application->update($data);

        $this->toast('Application "' . $application->name . '" updated successfully.', 'success');

        return redirect()->route('apps.show', [$project, $application]);
    }

    private function validateApp(Request $request, Project $project, ?Application $application = null): array
    {
        return $request->validate([
            'name'                      => ['required', 'string', 'max:255',
                Rule::unique('applications', 'name')->where('project_id', $project->id)->ignore($application?->id)],
            'description'               => ['nullable', 'string'],
            'path'                      => ['required', 'string', 'max:1024'],
            'notes'                     => ['nullable', 'string'],
            'platform'                  => ['required', 'string', Rule::in(['web', 'api', 'mobile', 'desktop', 'cli'])],
            'language'                  => ['required', 'string', 'max:255'],
            'language_custom'           => ['nullable', 'string', 'max:255'],
            'language_is_custom'        => ['nullable'],
            'language_version'          => ['nullable', 'string', 'max:255'],
            'language_version_custom'   => ['nullable', 'string', 'max:255'],
            'language_version_is_custom'=> ['nullable'],
            'technology'                => ['required', 'string', 'max:255'],
            'technology_custom'         => ['nullable', 'string', 'max:255'],
            'technology_is_custom'      => ['nullable'],
            'framework_version'         => ['nullable', 'string', 'max:255'],
            'framework_version_custom'  => ['nullable', 'string', 'max:255'],
            'framework_version_is_custom' => ['nullable'],
            'package_manager'           => ['nullable', 'string', 'max:255'],
            'paradigm'                  => ['required', 'string', 'max:255'],
            'architecture'              => ['required', 'string', 'max:255'],
            'design_principles'         => ['nullable', 'array'],
            'design_principles.*'       => ['string'],
            'databases'                 => ['nullable', 'array'],
            'databases.*'               => ['string'],
            'database_access'           => ['nullable', 'string', 'max:255'],
            'storage'                   => ['nullable', 'array'],
            'storage.*'                 => ['string'],
            'code_repository'           => ['nullable', 'string', 'max:255'],
            'code_repository_url'       => ['nullable', 'url', 'max:2048'],
            'testing_frameworks'        => ['nullable', 'string', 'max:255'],
            'code_style'                => ['nullable', 'string', 'max:255'],
            'ci_cd'                     => ['nullable', 'string', 'max:255'],
            'executor'                  => ['required', 'string', 'max:255'],
            'context_stack'             => ['nullable', 'string'],
            'context_architecture'      => ['nullable', 'string'],
            'context_guidelines'        => ['nullable', 'string'],
        ]);
    }

    private function resolveCustomFields(array $validated): array
    {
        // Language: if "other", use custom text
        if (($validated['language'] ?? '') === 'other' && ! empty($validated['language_custom'] ?? '')) {
            $validated['language'] = $validated['language_custom'];
            $validated['language_is_custom'] = true;
        } else {
            $validated['language_is_custom'] = false;
        }

        // Language version: if "custom", use custom text
        if (($validated['language_version'] ?? '') === 'custom' && ! empty($validated['language_version_custom'] ?? '')) {
            $validated['language_version'] = $validated['language_version_custom'];
            $validated['language_version_is_custom'] = true;
        } else {
            $validated['language_version_is_custom'] = false;
        }

        // Technology: if "other", use custom text
        if (($validated['technology'] ?? '') === 'other' && ! empty($validated['technology_custom'] ?? '')) {
            $validated['technology'] = $validated['technology_custom'];
            $validated['technology_is_custom'] = true;
        } else {
            $validated['technology_is_custom'] = false;
        }

        // Framework version: if "custom", use custom text
        if (($validated['framework_version'] ?? '') === 'custom' && ! empty($validated['framework_version_custom'] ?? '')) {
            $validated['framework_version'] = $validated['framework_version_custom'];
            $validated['framework_version_is_custom'] = true;
        } else {
            $validated['framework_version_is_custom'] = false;
        }

        // Map array fields to _codes columns
        $validated['design_principles_codes']  = $validated['design_principles'] ?? [];
        $validated['databases_codes']          = $validated['databases'] ?? [];
        $validated['storage_codes']            = $validated['storage'] ?? [];
        $validated['testing_frameworks_codes'] = $validated['testing_frameworks'] ?? null;

        // Clean up form-only fields
        $cleanupKeys = [
            'language_custom', 'language_version_custom',
            'technology_custom', 'framework_version_custom',
            'design_principles', 'databases', 'storage', 'testing_frameworks',
        ];
        foreach ($cleanupKeys as $key) {
            unset($validated[$key]);
        }

        return $validated;
    }
}
