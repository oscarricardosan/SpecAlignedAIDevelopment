@extends('layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@php
    $recentProjects = $recentProjects ?? \App\Models\Project::orderBy('updated_at', 'desc')->take(3)->get();
@endphp

@section('content')

{{-- Hero --}}
<div class="card card-side bg-base-100 border border-cool/50 shadow-sm mb-8 overflow-hidden">
    <div class="card-body flex-row items-center gap-5 p-5">
        <img src="/assets/pet.png" alt="SAID" class="w-14 h-auto drop-shadow-sm shrink-0">
        <div class="flex-1 min-w-0">
            <h2 class="text-xl font-bold text-navy">
                Hello{{ Auth::user()->name ? ', ' . Auth::user()->name : '' }}!
            </h2>
            <p class="text-sm text-warm mt-0.5">
                Your SAID workspace is ready. Manage projects, configure AI agents, and build with specs.
            </p>
        </div>
        <div class="hidden sm:flex items-center gap-2 shrink-0">
            <span class="tooltip tooltip-bottom" data-tip="Ctrl+N">
                <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm gap-2">
                    <iconify-icon icon="heroicons:plus" class="w-4 h-4"></iconify-icon>
                    Create Project
                </a>
            </span>
            <a href="#" class="btn btn-outline btn-sm gap-2">
                <iconify-icon icon="heroicons:pencil" class="w-4 h-4"></iconify-icon>
                Write Spec
            </a>
        </div>
    </div>
</div>

{{-- How SAID works --}}
<div class="mb-8">
    <h3 class="text-base font-semibold text-navy mb-4">How SAID works</h3>
    <div class="card bg-base-100 border border-cool/50 shadow-sm">
        <div class="card-body p-0">
            <div class="flex items-stretch divide-x divide-cool/20">
                <div class="flex-1 flex items-center gap-3 p-4">
                    <div class="w-8 h-8 rounded-full bg-teal/15 text-teal flex items-center justify-center text-sm font-bold shrink-0">1</div>
                    <div>
                        <p class="text-sm font-semibold text-navy">Define a Specification</p>
                        <p class="text-sm text-warm mt-0.5">Write what the feature does in structured language.</p>
                    </div>
                </div>
                <div class="flex items-center px-2 text-cool/40 shrink-0">
                    <iconify-icon icon="heroicons:chevron-right" class="w-5 h-5"></iconify-icon>
                </div>
                <div class="flex-1 flex items-center gap-3 p-4">
                    <div class="w-8 h-8 rounded-full bg-teal/15 text-teal flex items-center justify-center text-sm font-bold shrink-0">2</div>
                    <div>
                        <p class="text-sm font-semibold text-navy">AI Generates the Code</p>
                        <p class="text-sm text-warm mt-0.5">Your agent reads the spec and implements it.</p>
                    </div>
                </div>
                <div class="flex items-center px-2 text-cool/40 shrink-0">
                    <iconify-icon icon="heroicons:chevron-right" class="w-5 h-5"></iconify-icon>
                </div>
                <div class="flex-1 flex items-center gap-3 p-4">
                    <div class="w-8 h-8 rounded-full bg-teal/15 text-teal flex items-center justify-center text-sm font-bold shrink-0">3</div>
                    <div>
                        <p class="text-sm font-semibold text-navy">Audit &amp; Verify</p>
                        <p class="text-sm text-warm mt-0.5">A second AI checks code against the spec.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Recent projects or Getting started --}}
@if ($recentProjects->isNotEmpty())
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-semibold text-navy">Recent projects</h3>
        <a href="{{ route('projects.index') }}" class="text-sm text-teal hover:underline transition duration-200 font-medium">
            View all &rarr;
        </a>
    </div>
    <div class="grid grid-cols-3 gap-3">
        @foreach ($recentProjects as $project)
        <a href="{{ route('projects.edit', $project) }}"
           class="card bg-base-100 border border-cool/50 hover:border-teal/60 hover:shadow-sm transition duration-200 group">
            <div class="card-body p-4">
                <div class="flex items-center gap-2 mb-2">
                    <iconify-icon icon="heroicons:folder" class="w-4 h-4 text-peach shrink-0"></iconify-icon>
                    <h4 class="text-sm font-semibold text-navy group-hover:text-teal transition duration-200 truncate">{{ $project->name }}</h4>
                </div>
                <p class="text-xs text-warm line-clamp-2 mb-2">{{ $project->description ?: 'No description' }}</p>
                <div class="flex items-center gap-1.5 mt-auto">
                    @php
                        $critBadge = [
                            'mission_critical'   => 'badge-error',
                            'business_important' => 'badge-warning',
                            'administrative'     => 'badge-ghost',
                            'experimental'       => 'badge-info',
                        ][$project->criticality] ?? 'badge-ghost';
                    @endphp
                    <span class="badge badge-xs {{ $critBadge }}">{{ \App\Support\Label::humanize($project->criticality) }}</span>
                    <span class="text-xs text-cool">{{ \App\Support\Label::humanize($project->business_sector) }}</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>

{{-- Getting started — condensed --}}
<div>
    <h3 class="text-base font-semibold text-navy mb-4">Getting started</h3>
    <div class="grid grid-cols-2 gap-3">

        @php
        $cards = [
            ['icon' => 'heroicons:cpu-chip',     'bg' => 'bg-teal/15 text-teal', 'hb' => 'group-hover:bg-teal/25',
             'title' => 'Configure AI Agents',      'desc' => 'Programmer and auditor agents linked to AI providers.'],
            ['icon' => 'heroicons:key',           'bg' => 'bg-teal-light/20 text-teal', 'hb' => 'group-hover:bg-teal-light/30',
             'title' => 'Generate API Tokens',       'desc' => 'Secure tokens so your agents can access the SAID API.'],
        ];
        @endphp

        @foreach ($cards as $card)
        <div class="card bg-base-100 border border-cool/50 hover:border-teal/60 hover:shadow-sm transition duration-200 group cursor-pointer">
            <div class="card-body flex-row items-start gap-3 p-4">
                <div class="w-9 h-9 rounded-lg {{ $card['bg'] }} {{ $card['hb'] }} flex items-center justify-center shrink-0 transition duration-200">
                    <iconify-icon icon="{{ $card['icon'] }}" class="w-4 h-4"></iconify-icon>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-semibold text-navy group-hover:text-teal transition duration-200">{{ $card['title'] }}</h4>
                    <p class="text-sm text-warm mt-0.5">{{ $card['desc'] }}</p>
                    <span class="badge badge-sm badge-ghost mt-2 text-cool">Coming soon</span>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>
@else
{{-- No projects yet — full Getting started grid --}}
<div class="mb-8">
    <h3 class="text-base font-semibold text-navy mb-4">Getting started</h3>
    <div class="grid grid-cols-2 gap-3">

        @php
        $cards = [
            ['icon' => 'heroicons:folder',       'bg' => 'bg-peach/20 text-peach', 'hb' => 'group-hover:bg-peach/30',
             'title' => 'Create your first Project', 'desc' => 'Group applications, modules and features together.'],
            ['icon' => 'heroicons:cpu-chip',     'bg' => 'bg-teal/15 text-teal', 'hb' => 'group-hover:bg-teal/25',
             'title' => 'Configure AI Agents',      'desc' => 'Programmer and auditor agents linked to AI providers.'],
            ['icon' => 'heroicons:key',           'bg' => 'bg-teal-light/20 text-teal', 'hb' => 'group-hover:bg-teal-light/30',
             'title' => 'Generate API Tokens',       'desc' => 'Secure tokens so your agents can access the SAID API.'],
            ['icon' => 'heroicons:document-text', 'bg' => 'bg-navy/10 text-navy', 'hb' => 'group-hover:bg-navy/20',
             'title' => 'Write your first Spec',     'desc' => 'Define inputs, steps and outputs in a .spec.md file.'],
        ];
        @endphp

        @foreach ($cards as $card)
        <div class="card bg-base-100 border border-cool/50 hover:border-teal/60 hover:shadow-sm transition duration-200 group cursor-pointer">
            <div class="card-body flex-row items-start gap-3 p-4">
                <div class="w-9 h-9 rounded-lg {{ $card['bg'] }} {{ $card['hb'] }} flex items-center justify-center shrink-0 transition duration-200">
                    <iconify-icon icon="{{ $card['icon'] }}" class="w-4 h-4"></iconify-icon>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-semibold text-navy group-hover:text-teal transition duration-200">{{ $card['title'] }}</h4>
                    <p class="text-sm text-warm mt-0.5">{{ $card['desc'] }}</p>
                    <span class="badge badge-sm badge-ghost mt-2 text-cool">Coming soon</span>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>
@endif

{{-- Quick tip --}}
<div role="alert" class="alert bg-slate-50 border border-slate-200 rounded-lg">
    <iconify-icon icon="heroicons:light-bulb" class="text-teal w-5 h-5 shrink-0"></iconify-icon>
    <span class="text-sm text-warm">
        <strong class="text-navy">Tip:</strong> SAID works with any programming language. The spec defines <em>what</em> — the AI figures out <em>how</em>.
    </span>
</div>

@endsection
