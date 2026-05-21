@extends('layouts.app')

@section('title', 'New Application')
@section('heading', 'New Application')
@section('subheading', 'Define the tech stack and architecture — your agents will use this to generate consistent code')

@section('content')

<form action="/apps" method="POST" class="max-w-3xl space-y-8">
    @csrf

    {{-- Identity --}}
    <div class="bg-white rounded-xl border border-cool/50 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-cool/30 bg-slate-50/50">
            <h2 class="text-sm font-semibold text-navy flex items-center gap-2">
                <svg class="w-4 h-4 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Identity
            </h2>
        </div>
        <div class="p-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Application name <span class="text-coral">*</span></label>
                <input name="name" type="text"
                       class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition"
                       placeholder="e.g. app-web, mobile-api, admin-panel" required>
            </div>
        </div>
    </div>

    {{-- Tech stack --}}
    <div class="bg-white rounded-xl border border-cool/50 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-cool/30 bg-slate-50/50">
            <h2 class="text-sm font-semibold text-navy flex items-center gap-2">
                <svg class="w-4 h-4 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                Technology stack
            </h2>
            <p class="text-xs text-warm mt-0.5 ml-6">The AI will generate code, tests, and configurations following this stack's conventions.</p>
        </div>
        <div class="p-5 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Primary technology <span class="text-coral">*</span></label>
                    <select name="technology"
                            class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition" required>
                        <option value="" disabled selected>Select framework / language</option>
                        <optgroup label="PHP">
                            <option value="laravel">Laravel</option>
                            <option value="symfony">Symfony</option>
                        </optgroup>
                        <optgroup label="JavaScript / TypeScript">
                            <option value="react">React (SPA)</option>
                            <option value="vue">Vue.js (SPA)</option>
                            <option value="node_express">Node.js — Express</option>
                            <option value="node_nest">Node.js — NestJS</option>
                        </optgroup>
                        <optgroup label="Python">
                            <option value="django">Python — Django</option>
                            <option value="fastapi">Python — FastAPI</option>
                        </optgroup>
                        <optgroup label="Mobile">
                            <option value="flutter">Flutter</option>
                            <option value="react_native">React Native</option>
                        </optgroup>
                        <option value="other">Other (specify in notes)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Platform <span class="text-coral">*</span></label>
                    <div class="space-y-2 mt-1">
                        @php
                        $platforms = [
                            'web'    => ['Web', 'Browser-based application'],
                            'mobile' => ['Mobile', 'iOS / Android app'],
                            'desktop'=> ['Desktop', 'Native desktop application'],
                            'api'    => ['API Backend', 'Headless API consumed by other apps'],
                            'cli'    => ['CLI Tool', 'Command-line interface'],
                        ];
                        @endphp
                        @foreach ($platforms as $val => $info)
                        <label class="flex items-start gap-3 px-3 py-2 border border-cool/40 rounded-lg cursor-pointer hover:border-teal/50 transition">
                            <input type="radio" name="platform" value="{{ $val }}" class="mt-0.5 text-teal focus:ring-teal" {{ $val === 'web' ? 'checked' : '' }}>
                            <div>
                                <span class="text-sm font-medium text-navy">{{ $info[0] }}</span>
                                <span class="text-xs text-warm ml-2">{{ $info[1] }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Architecture --}}
    <div class="bg-white rounded-xl border border-cool/50 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-cool/30 bg-slate-50/50">
            <h2 class="text-sm font-semibold text-navy flex items-center gap-2">
                <svg class="w-4 h-4 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                Architecture &amp; patterns
            </h2>
            <p class="text-xs text-warm mt-0.5 ml-6">These choices determine folder structure, naming conventions, and code organization.</p>
        </div>
        <div class="p-5 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Architecture pattern <span class="text-coral">*</span></label>
                    <select name="architecture"
                            class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition" required>
                        <option value="" disabled selected>Select pattern</option>
                        <option value="mvc">MVC — Model-View-Controller</option>
                        <option value="repository">Repository Pattern</option>
                        <option value="clean">Clean Architecture</option>
                        <option value="hexagonal">Hexagonal / Ports &amp; Adapters</option>
                        <option value="microservices">Microservices</option>
                        <option value="monolith">Monolith (modular)</option>
                        <option value="serverless">Serverless / Lambda</option>
                        <option value="event_driven">Event-Driven</option>
                    </select>
                    <p class="text-xs text-cool mt-1">Governs how the AI organizes services, controllers, and dependencies.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Database <span class="text-coral">*</span></label>
                    <select name="database"
                            class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition" required>
                        <option value="" disabled selected>Select database</option>
                        <option value="postgresql">PostgreSQL</option>
                        <option value="mysql">MySQL / MariaDB</option>
                        <option value="sqlite">SQLite</option>
                        <option value="mongodb">MongoDB</option>
                        <option value="none">None — no database</option>
                    </select>
                    <p class="text-xs text-cool mt-1">Influences ORM choice, migration style, and query patterns.</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-navy mb-1">Code style convention</label>
                <select name="code_style"
                        class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition">
                    <option value="" selected>Auto-detect from technology</option>
                    <option value="psr12">PSR-12 (PHP)</option>
                    <option value="pep8">PEP 8 (Python)</option>
                    <option value="airbnb">Airbnb Style (JavaScript / React)</option>
                    <option value="standard">Standard JS</option>
                    <option value="google">Google Style</option>
                    <option value="custom">Custom — define later</option>
                </select>
                <p class="text-xs text-cool mt-1">The AI will format generated code according to this style guide.</p>
            </div>
        </div>
    </div>

    {{-- Notes --}}
    <div class="bg-white rounded-xl border border-cool/50 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-cool/30 bg-slate-50/50">
            <h2 class="text-sm font-semibold text-navy flex items-center gap-2">
                <svg class="w-4 h-4 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Additional notes
            </h2>
        </div>
        <div class="p-5">
            <textarea name="notes" rows="3"
                      class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition"
                      placeholder="Any extra context that helps AI agents understand this app — team conventions, existing patterns, constraints... (optional)"></textarea>
            <p class="text-xs text-cool mt-1">Free-form, but keep it relevant. Bullet points work well.</p>
        </div>
    </div>

    {{-- Submit --}}
    <div class="flex items-center gap-3">
        <button type="submit"
                class="bg-teal text-white rounded-lg px-6 py-2.5 text-sm font-medium hover:bg-teal-dark transition shadow-sm">
            Create application
        </button>
        <a href="/apps" class="text-sm text-warm hover:text-navy transition">Cancel</a>
    </div>
</form>

@endsection
