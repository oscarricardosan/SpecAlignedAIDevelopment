@extends('layouts.app')

@section('title', 'Projects')
@section('heading', 'Projects')

@section('content')

<div class="max-w-2xl mx-auto text-center py-16">
    <div class="w-16 h-16 rounded-full bg-teal/15 flex items-center justify-center mx-auto mb-5">
        <svg class="w-8 h-8 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
        </svg>
    </div>
    <h2 class="text-lg font-bold text-navy mb-2">No projects yet</h2>
    <p class="text-sm text-warm max-w-md mx-auto mb-6 leading-relaxed">
        Projects are the top-level containers for your work. They group applications, screens, and features together. Create your first one to get started.
    </p>
    <a href="/projects/create"
       class="inline-flex items-center gap-2 bg-teal text-white rounded-lg px-5 py-2.5 text-sm font-medium hover:bg-teal-dark transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create your first project
    </a>
</div>

@endsection
