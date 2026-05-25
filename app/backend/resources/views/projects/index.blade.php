@extends('layouts.app')

@section('title', 'Projects')
@section('heading', 'Projects')

@section('actions')
    <span class="tooltip tooltip-bottom" data-tip="Ctrl+N">
        <a href="{{ route('projects.create') }}"
           class="btn btn-primary btn-sm gap-2 transition duration-200">
            <iconify-icon icon="heroicons:plus" class="w-4 h-4"></iconify-icon>
            New project
        </a>
    </span>
@endsection

@section('content')

@if ($projects->isEmpty())
    <div class="max-w-2xl mx-auto text-center py-16">
        <div class="w-16 h-16 rounded-full bg-teal/15 flex items-center justify-center mx-auto mb-5">
            <iconify-icon icon="heroicons:folder" class="w-8 h-8 text-teal"></iconify-icon>
        </div>
        <h2 class="text-lg font-bold text-navy mb-2">No projects yet</h2>
        <p class="text-sm text-warm max-w-md mx-auto mb-6 leading-relaxed">
            Projects are the top-level containers for your work. They group applications, screens, and features together. Create your first one to get started.
        </p>
        <a href="{{ route('projects.create') }}"
           class="btn btn-primary gap-2 transition duration-200">
            <iconify-icon icon="heroicons:plus" class="w-4 h-4"></iconify-icon>
            Create your first project
        </a>
    </div>
@else
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-5">
            <p class="text-sm text-warm">{{ $projects->count() }} {{ Str::plural('project', $projects->count()) }}</p>
        </div>

        <div class="bg-white rounded-xl border border-cool/50 shadow-sm">
            <table class="table table-sm table-zebra">
                <thead>
                    <tr class="border-b border-cool/30">
                        <th class="font-semibold text-navy">Name</th>
                        <th class="font-semibold text-navy">Path</th>
                        <th class="font-semibold text-navy">Criticality</th>
                        <th class="font-semibold text-navy">Sector</th>
                        <th class="font-semibold text-navy">Audience</th>
                        <th class="font-semibold text-navy">Compliance</th>
                        <th class="w-0"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $project)
                        @php
                            $criticalityColors = [
                                'mission_critical'   => 'badge-error',
                                'business_important' => 'badge-warning',
                                'administrative'     => 'badge-ghost',
                                'experimental'       => 'badge-info',
                            ];
                        @endphp
                        <tr class="hover">
                            <td class="font-medium text-navy">{{ $project->name }}</td>
                            <td class="text-warm text-xs font-mono truncate max-w-[180px]" title="{{ $project->path }}">{{ $project->path }}</td>
                            <td>
                                <span class="badge badge-sm {{ $criticalityColors[$project->criticality] ?? 'badge-ghost' }}">
                                    {{ \App\Support\Label::humanize($project->criticality) }}
                                </span>
                            </td>
                            <td class="text-warm">{{ \App\Support\Label::humanize($project->business_sector) }}</td>
                            <td class="text-warm">{{ \App\Support\Label::humanize($project->target_audience) }}</td>
                            <td>
                                @if ($project->compliances->isEmpty())
                                    <span class="text-xs text-cool">—</span>
                                @else
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($project->compliances as $c)
                                            <span class="badge badge-sm badge-outline">
                                                {{ \App\Support\Label::humanize($c->compliance) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown dropdown-end">
                                    <button tabindex="0" role="button" class="btn btn-ghost btn-xs btn-square">
                                        <iconify-icon icon="heroicons:ellipsis-vertical" class="w-4 h-4"></iconify-icon>
                                    </button>
                                    <ul tabindex="0" class="dropdown-content z-[1] menu menu-sm p-1 bg-white shadow-lg rounded-lg border border-cool/30 w-36">
                                        <li>
                                            <a href="{{ route('projects.edit', $project) }}" class="text-sm">
                                                <iconify-icon icon="heroicons:pencil" class="w-3.5 h-3.5"></iconify-icon>
                                                Edit
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

@endsection
