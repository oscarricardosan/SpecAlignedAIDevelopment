@extends('layouts.app')

@section('title', 'Applications')
@section('heading', 'Applications')

@section('content')

<div class="max-w-2xl mx-auto text-center py-16">
    <div class="w-16 h-16 rounded-full bg-teal/15 flex items-center justify-center mx-auto mb-5">
        <svg class="w-8 h-8 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
    </div>
    <h2 class="text-lg font-bold text-navy mb-2">No applications yet</h2>
    <p class="text-sm text-warm max-w-md mx-auto mb-6 leading-relaxed">
        Applications represent a deliverable — a web app, mobile app, or API. Create your first project, then add applications to it.
    </p>
    <a href="/apps/create"
       class="inline-flex items-center gap-2 bg-teal text-white rounded-lg px-5 py-2.5 text-sm font-medium hover:bg-teal-dark transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create your first application
    </a>
</div>

@endsection
