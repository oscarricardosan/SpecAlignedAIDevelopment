@extends('layouts.app')

@section('title', $project->name)
@section('heading', $project->name)
@section('subheading', 'Project overview')

@section('actions')
    <a href="{{ route('projects.edit', $project) }}"
       class="btn btn-outline btn-sm gap-2 transition duration-200">
        <iconify-icon icon="heroicons:pencil" style="font-size: 16px"></iconify-icon>
        Edit project
    </a>
@endsection

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    {{-- Description --}}
    @if ($project->description)
    <div class="card bg-base-100 border border-cool/50 shadow-sm">
        <div class="card-body p-5">
            <p class="text-sm text-navy leading-relaxed">{{ $project->description }}</p>
        </div>
    </div>
    @endif

    {{-- Metadata --}}
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
    $audienceIcons = [
        'internal' => 'heroicons:building-office',
        'b2b'      => 'heroicons:briefcase',
        'b2c'      => 'heroicons:user-group',
        'public'   => 'heroicons:globe-alt',
    ];
    $critColors = [
        'mission_critical'   => ['bg' => 'bg-coral/10', 'icon' => 'text-coral', 'badge' => 'bg-coral text-white'],
        'business_important' => ['bg' => 'bg-peach/20', 'icon' => 'text-peach', 'badge' => 'bg-peach text-navy'],
        'administrative'     => ['bg' => 'bg-cool/10', 'icon' => 'text-cool', 'badge' => 'bg-cool text-white'],
        'experimental'       => ['bg' => 'bg-teal/10', 'icon' => 'text-teal', 'badge' => 'bg-teal text-white'],
    ];
    $critInfo = $critColors[$project->criticality] ?? ['bg' => 'bg-cool/15', 'icon' => 'text-cool', 'badge' => 'bg-cool text-white'];
    @endphp

    <div class="grid grid-cols-2 gap-3">
        {{-- Criticality --}}
        <div class="card bg-base-100 border border-cool/40 shadow-sm">
            <div class="card-body p-4 flex-row items-center gap-3">
                <div class="w-10 h-10 rounded-lg {{ $critInfo['bg'] }} flex items-center justify-center shrink-0">
                    <iconify-icon icon="heroicons:exclamation-triangle" style="font-size: 20px" class="{{ $critInfo['icon'] }}"></iconify-icon>
                </div>
                <div>
                    <p class="text-xs text-warm uppercase tracking-wider">Criticality</p>
                    <span class="badge badge-sm {{ $critInfo['badge'] }} mt-0.5">{{ \App\Support\Label::humanize($project->criticality) }}</span>
                </div>
            </div>
        </div>

        {{-- Business sector --}}
        <div class="card bg-base-100 border border-cool/40 shadow-sm">
            <div class="card-body p-4 flex-row items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-navy/5 flex items-center justify-center shrink-0">
                    <iconify-icon icon="{{ $sectorIcons[$project->business_sector] ?? 'heroicons:briefcase' }}" style="font-size: 20px" class="text-navy"></iconify-icon>
                </div>
                <div>
                    <p class="text-xs text-warm uppercase tracking-wider">Business sector</p>
                    <p class="text-sm font-semibold text-navy">{{ \App\Support\Label::humanize($project->business_sector) }}</p>
                </div>
            </div>
        </div>

        {{-- Target audience --}}
        <div class="card bg-base-100 border border-cool/40 shadow-sm">
            <div class="card-body p-4 flex-row items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-teal/10 flex items-center justify-center shrink-0">
                    <iconify-icon icon="{{ $audienceIcons[$project->target_audience] ?? 'heroicons:users' }}" style="font-size: 20px" class="text-teal"></iconify-icon>
                </div>
                <div>
                    <p class="text-xs text-warm uppercase tracking-wider">Target audience</p>
                    <p class="text-sm font-semibold text-navy">{{ \App\Support\Label::humanize($project->target_audience) }}</p>
                </div>
            </div>
        </div>

        {{-- Path --}}
        <div class="card bg-base-100 border border-cool/40 shadow-sm">
            <div class="card-body p-4 flex-row items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-peach/20 flex items-center justify-center shrink-0">
                    <iconify-icon icon="heroicons:folder" style="font-size: 20px" class="text-peach"></iconify-icon>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-warm uppercase tracking-wider">Path</p>
                    <p class="text-sm font-mono font-medium text-navy truncate" title="{{ $project->path }}">{{ $project->path }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Compliance --}}
    @if ($project->compliances->isNotEmpty())
    @php
    $complianceIcons = [
        'gdpr'      => 'heroicons:globe-europe-africa',
        'hipaa'     => 'heroicons:heart',
        'pci_dss'   => 'heroicons:credit-card',
        'soc2'      => 'heroicons:cloud',
        'iso_27001' => 'heroicons:document-check',
    ];
    @endphp
    <div class="card bg-base-100 border border-cool/40 shadow-sm">
        <div class="card-body p-5">
            <p class="text-xs text-warm uppercase tracking-wider mb-3">Compliance</p>
            <div class="flex flex-wrap gap-3">
                @foreach ($project->compliances as $c)
                    <div class="flex items-center gap-2.5 px-3 py-2 bg-cool/10 border border-cool/30 rounded-lg">
                        <iconify-icon icon="{{ $complianceIcons[$c->compliance] ?? 'heroicons:shield-check' }}" style="font-size: 18px" class="text-teal shrink-0"></iconify-icon>
                        <span class="text-sm font-medium text-navy">{{ \App\Support\Label::humanize($c->compliance) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Applications --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-semibold text-navy">Applications</h3>
            <a href="{{ route('apps.create', $project) }}"
               class="btn btn-primary btn-sm gap-2 transition duration-200">
                <iconify-icon icon="heroicons:plus" style="font-size: 16px"></iconify-icon>
                New application
            </a>
        </div>

        @if ($applications->isNotEmpty())
        @php
        $appLangIcons = [
            'php'         => 'devicon:php',
            'javascript'  => 'devicon:javascript',
            'typescript'  => 'devicon:typescript',
            'python'      => 'devicon:python',
            'csharp'      => 'devicon:csharp',
            'rust'        => 'devicon:rust',
            'go'          => 'devicon:go',
            'dart'        => 'devicon:dart',
            'deno'        => 'devicon:denojs',
        ];
        $appTechIcons = [
            'laravel'          => 'devicon:laravel',
            'symfony'          => 'devicon:symfony',
            'react'            => 'devicon:react',
            'vue'              => 'devicon:vuejs',
            'angular'          => 'devicon:angular',
            'svelte'           => 'devicon:svelte',
            'django'           => 'devicon:django',
            'fastapi'          => 'devicon:fastapi',
            'flask'            => 'devicon:flask',
            'flutter'          => 'devicon:flutter',
            'dotnet'           => 'devicon:dotnetcore',
            'node_express'     => 'devicon:express',
            'node_nest'        => 'devicon:nestjs',
            'react_native'     => 'devicon:react',
            'react_native_ts'  => 'devicon:react',
            'rust_actix'       => 'devicon:rust',
            'rust_axum'        => 'devicon:rust',
            'tauri'            => 'devicon:rust',
            'vanilla_php'      => 'devicon:php',
            'vanilla_js'       => 'devicon:javascript',
            'vanilla_ts'       => 'devicon:typescript',
            'vanilla_py'       => 'devicon:python',
            'vanilla_rust'     => 'devicon:rust',
            'deno'             => 'devicon:denojs',
            'deno_fresh'       => 'devicon:denojs',
            'cakephp'          => 'devicon:cakephp',
            'codeigniter'      => 'devicon-plain:codeigniter',
            'yii'              => 'devicon:yii',
        ];
        @endphp
        <div class="grid grid-cols-3 gap-3">
            @foreach ($applications as $app)
            <a href="{{ route('apps.show', [$project, $app]) }}"
               class="card bg-base-100 border border-cool/40 shadow-sm hover:border-teal/50 hover:shadow-md transition duration-200">
                <div class="card-body p-5 text-center">
                    <div class="w-14 h-14 mx-auto mb-3 flex items-center justify-center">
                        <iconify-icon icon="{{ $appLangIcons[$app->language] ?? 'heroicons:code-bracket' }}" style="font-size: 48px"
                                      class="text-navy"></iconify-icon>
                    </div>
                    <h4 class="text-sm font-semibold text-navy mb-2 truncate">{{ $app->name }}</h4>
                    <div class="flex items-center justify-center gap-1.5 flex-wrap">
                        <span class="badge badge-sm bg-cool/10 text-cool text-xs">
                            {{ \App\Support\Label::humanize($app->platform) }}
                        </span>
                        @if ($app->technology !== 'vanilla_php' && $app->technology !== 'vanilla_js' && $app->technology !== 'vanilla_ts' && $app->technology !== 'vanilla_py' && $app->technology !== 'vanilla_rust' && $app->technology !== 'deno' && $app->technology !== 'go')
                        <span class="text-xs text-warm truncate max-w-[120px]">{{ $app->technology_is_custom ? $app->technology : \App\Support\Label::humanize($app->technology) }}</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="max-w-2xl mx-auto text-center py-12">
            <div class="w-12 h-12 rounded-full bg-teal/15 flex items-center justify-center mx-auto mb-4">
                <iconify-icon icon="heroicons:computer-desktop" style="font-size: 24px" class="text-teal"></iconify-icon>
            </div>
            <p class="text-sm text-warm max-w-md mx-auto">
                Applications define the tech stack and architecture for each deliverable — web frontend, mobile app, API backend, or CLI tool. Create your first one to start writing specs.
            </p>
        </div>
        @endif
    </div>

</div>

@endsection
