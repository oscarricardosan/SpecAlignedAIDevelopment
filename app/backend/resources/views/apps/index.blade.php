@extends('layouts.app')

@section('title', 'Applications')
@section('heading', $project->name)
@section('subheading', 'Applications')

@section('actions')
    <a href="{{ route('apps.create', $project) }}"
       class="btn btn-primary btn-sm gap-2 transition duration-200">
        <iconify-icon icon="heroicons:plus" style="font-size: 16px"></iconify-icon>
        New application
    </a>
    <a href="{{ route('projects.show', $project) }}"
       class="btn btn-ghost btn-sm gap-2 transition duration-200">
        <iconify-icon icon="heroicons:arrow-left" style="font-size: 16px"></iconify-icon>
        Project
    </a>
@endsection

@section('content')

@if ($applications->isEmpty())
<div class="max-w-2xl mx-auto text-center py-16">
    <div class="w-16 h-16 rounded-full bg-teal/15 flex items-center justify-center mx-auto mb-5">
        <iconify-icon icon="heroicons:computer-desktop" style="font-size: 32px" class="text-teal"></iconify-icon>
    </div>
    <h2 class="text-lg font-bold text-navy mb-2">No applications yet</h2>
    <p class="text-sm text-warm max-w-md mx-auto mb-6 leading-relaxed">
        Applications represent a deliverable — a web app, mobile app, or API. Create your first one to start writing specs.
    </p>
    <a href="{{ route('apps.create', $project) }}"
       class="btn btn-primary gap-2 transition duration-200">
        <iconify-icon icon="heroicons:plus" style="font-size: 16px"></iconify-icon>
        Create your first application
    </a>
</div>
@else
<div class="space-y-3">
    @foreach ($applications as $app)
    <a href="{{ route('apps.show', [$project, $app]) }}"
       class="card bg-base-100 border border-cool/40 shadow-sm hover:border-teal/50 hover:shadow-md transition duration-200 block">
        <div class="card-body p-4 flex-row items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-teal/10 flex items-center justify-center shrink-0">
                <iconify-icon icon="heroicons:code-bracket" style="font-size: 20px" class="text-teal"></iconify-icon>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-semibold text-navy">{{ $app->name }}</h3>
                <p class="text-xs text-warm truncate">
                    {{ \App\Support\Label::humanize($app->platform) }}
                    &middot; {{ $app->language }}{{ $app->language_version ? ' ' . $app->language_version : '' }}
                    &middot; {{ $app->technology }}
                </p>
            </div>
            <span class="text-xs text-warm">{{ $app->created_at->diffForHumans() }}</span>
            <iconify-icon icon="heroicons:chevron-right" style="font-size: 16px" class="text-cool/40"></iconify-icon>
        </div>
    </a>
    @endforeach
</div>
@endif

@endsection
