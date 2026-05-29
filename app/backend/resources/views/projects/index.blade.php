@extends('layouts.app')

@section('title', 'Projects')
@section('heading', 'Projects')

@section('actions')
    <span class="tooltip tooltip-bottom" data-tip="Ctrl+N">
        <a href="{{ route('projects.create') }}"
           class="btn btn-primary btn-sm gap-2 transition duration-200">
            <iconify-icon icon="heroicons:plus" style="font-size: 16px"></iconify-icon>
            New project
        </a>
    </span>
@endsection

@section('content')

@if ($projects->isEmpty())
    <div class="max-w-2xl mx-auto text-center py-16">
        <div class="w-16 h-16 rounded-full bg-teal/15 flex items-center justify-center mx-auto mb-5">
            <iconify-icon icon="heroicons:folder" style="font-size: 32px" class="text-teal"></iconify-icon>
        </div>
        <h2 class="text-lg font-bold text-navy mb-2">No projects yet</h2>
        <p class="text-sm text-warm max-w-md mx-auto mb-6 leading-relaxed">
            Projects are the top-level containers for your work. They group applications, screens, and features together. Create your first one to get started.
        </p>
        <a href="{{ route('projects.create') }}"
           class="btn btn-primary gap-2 transition duration-200">
            <iconify-icon icon="heroicons:plus" style="font-size: 16px"></iconify-icon>
            Create your first project
        </a>
    </div>
@else
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-5">
            <p class="text-sm text-warm">{{ $projects->count() }} {{ Str::plural('project', $projects->count()) }}</p>
        </div>

        @php
        $sectorIcons = [
            'finance'       => 'heroicons:banknotes',
            'healthcare'    => 'heroicons:heart',
            'education'     => 'heroicons:academic-cap',
            'ecommerce'     => 'heroicons:shopping-cart',
            'logistics'     => 'heroicons:truck',
            'government'    => 'heroicons:building-library',
            'entertainment' => 'heroicons:film',
            'manufacturing' => 'heroicons:cog-8-tooth',
            'other'         => 'heroicons:ellipsis-horizontal-circle',
        ];
        $critBadges = [
            'mission_critical'   => 'bg-coral text-white',
            'business_important' => 'bg-peach text-navy',
            'administrative'     => 'bg-cool text-white',
            'experimental'       => 'bg-teal text-white',
        ];
        @endphp

        <div class="space-y-3">
            @foreach ($projects as $project)
            <a href="{{ route('projects.show', $project) }}"
               class="card bg-base-100 border border-cool/40 shadow-sm hover:border-teal/50 hover:shadow-md transition duration-200 block">
                <div class="card-body p-4">
                    <div class="flex items-start gap-4">
                        {{-- Icon --}}
                        <div class="w-11 h-11 rounded-xl bg-navy/5 flex items-center justify-center shrink-0 mt-0.5">
                            <iconify-icon icon="{{ $sectorIcons[$project->business_sector] ?? 'heroicons:folder' }}" style="font-size: 24px" class="text-navy"></iconify-icon>
                        </div>

                        {{-- Main info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-sm font-semibold text-navy truncate">{{ $project->name }}</h3>
                                <span class="badge badge-sm {{ $critBadges[$project->criticality] ?? 'bg-cool text-white' }} shrink-0">
                                    {{ \App\Support\Label::humanize($project->criticality) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-warm flex-wrap">
                                <span class="flex items-center gap-1">
                                    <iconify-icon icon="{{ $sectorIcons[$project->business_sector] ?? 'heroicons:briefcase' }}" style="font-size: 13px" class="text-cool"></iconify-icon>
                                    {{ \App\Support\Label::humanize($project->business_sector) }}
                                </span>
                                <span>&middot;</span>
                                <span>{{ \App\Support\Label::humanize($project->target_audience) }}</span>
                                @if ($project->applications_count ?? false)
                                    <span>&middot;</span>
                                    <span class="flex items-center gap-1">
                                        <iconify-icon icon="heroicons:code-bracket" style="font-size: 13px" class="text-cool"></iconify-icon>
                                        {{ $project->applications_count }} {{ Str::plural('app', $project->applications_count) }}
                                    </span>
                                @endif
                            </div>
                            @if ($project->compliances->isNotEmpty())
                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach ($project->compliances as $c)
                                    <span class="badge badge-sm badge-outline text-xs">{{ \App\Support\Label::humanize($c->compliance) }}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        {{-- Path + actions --}}
                        <div class="text-right shrink-0 hidden sm:block">
                            <p class="text-sm text-navy font-mono truncate max-w-[160px]" title="{{ $project->path }}">{{ $project->path }}</p>
                            <p class="text-xs text-warm mt-1">{{ $project->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="shrink-0 self-center">
                            <iconify-icon icon="heroicons:chevron-right" style="font-size: 16px" class="text-cool/40"></iconify-icon>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
@endif

@endsection
