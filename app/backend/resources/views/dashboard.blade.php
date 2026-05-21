@extends('layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')

{{-- Hero --}}
<div class="bg-white rounded-xl shadow-sm mb-8 overflow-hidden border border-cool/50">
    <div class="flex items-center gap-5 p-5">
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
            <a href="#" class="inline-flex items-center gap-1.5 bg-teal text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-teal-dark transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Project
            </a>
            <a href="#" class="inline-flex items-center gap-1.5 border border-cool text-navy text-sm font-medium px-4 py-2 rounded-lg hover:border-teal hover:text-teal transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Write Spec
            </a>
        </div>
    </div>
    <div class="h-1 bg-teal"></div>
</div>

{{-- How SAID works — compact horizontal flow --}}
<div class="mb-8">
    <h3 class="text-base font-semibold text-navy mb-4">How SAID works</h3>
    <div class="flex items-stretch gap-0 bg-white rounded-lg border border-cool/50 overflow-hidden shadow-sm">
        <div class="flex-1 flex items-center gap-3 p-4">
            <div class="w-8 h-8 rounded-full bg-teal/15 text-teal flex items-center justify-center text-sm font-bold shrink-0">1</div>
            <div>
                <p class="text-sm font-semibold text-navy">Define a Specification</p>
                <p class="text-sm text-warm mt-0.5">Write what the feature does in structured language.</p>
            </div>
        </div>
        <div class="flex items-center text-cool/40 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
        <div class="flex-1 flex items-center gap-3 p-4">
            <div class="w-8 h-8 rounded-full bg-teal/15 text-teal flex items-center justify-center text-sm font-bold shrink-0">2</div>
            <div>
                <p class="text-sm font-semibold text-navy">AI Generates the Code</p>
                <p class="text-sm text-warm mt-0.5">Your agent reads the spec and implements it.</p>
            </div>
        </div>
        <div class="flex items-center text-cool/40 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
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

{{-- Getting started --}}
<div class="mb-8">
    <h3 class="text-base font-semibold text-navy mb-4">Getting started</h3>
    <div class="grid grid-cols-2 gap-3">

        @php
        $cards = [
            ['icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z',
             'bg' => 'bg-peach/20 text-peach', 'hb' => 'group-hover:bg-peach/30',
             'title' => 'Create your first Project',
             'desc' => 'Group applications, modules and features together.'],
            ['icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
             'bg' => 'bg-teal/15 text-teal', 'hb' => 'group-hover:bg-teal/25',
             'title' => 'Configure AI Agents',
             'desc' => 'Programmer and auditor agents linked to AI providers.'],
            ['icon' => 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z',
             'bg' => 'bg-teal-light/20 text-teal', 'hb' => 'group-hover:bg-teal-light/30',
             'title' => 'Generate API Tokens',
             'desc' => 'Secure tokens so your agents can access the SAID API.'],
            ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
             'bg' => 'bg-navy/10 text-navy', 'hb' => 'group-hover:bg-navy/20',
             'title' => 'Write your first Spec',
             'desc' => 'Define inputs, steps and outputs in a .spec.md file.'],
        ];
        @endphp

        @foreach ($cards as $card)
        <div class="bg-white rounded-lg border border-cool/50 p-4 hover:border-teal/60 hover:shadow-sm transition group cursor-pointer">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-lg {{ $card['bg'] }} {{ $card['hb'] }} flex items-center justify-center shrink-0 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-semibold text-navy group-hover:text-teal transition">{{ $card['title'] }}</h4>
                    <p class="text-sm text-warm mt-0.5">{{ $card['desc'] }}</p>
                    <span class="inline-block mt-2 text-xs font-medium text-cool bg-slate-50 border border-cool/30 rounded-full px-2 py-0.5">Coming soon</span>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>

{{-- Quick tip --}}
<div class="flex items-start gap-3 bg-slate-50 border border-slate-200 rounded-lg p-3">
    <div class="text-teal shrink-0 mt-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <p class="text-sm text-warm leading-relaxed">
        <strong class="text-navy">Tip:</strong> SAID works with any programming language. The spec defines <em>what</em> — the AI figures out <em>how</em>.
    </p>
</div>

@endsection
