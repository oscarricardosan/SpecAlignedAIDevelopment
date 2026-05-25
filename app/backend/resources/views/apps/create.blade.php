@extends('layouts.app')

@section('title', 'New Application')
@section('heading', 'New Application')
@section('subheading', 'Define the tech stack and architecture — your agents will use this to generate consistent code')

@section('content')

<form action="{{ route('apps.store') }}" method="POST" class="max-w-3xl space-y-8"
      x-data="{ submitting: false }" @submit="submitting = true">
    @csrf

    {{-- Identity --}}
    <div class="card bg-base-100 border border-cool/50 shadow-sm">
        <div class="card-body p-0">
            <div class="px-5 py-3 border-b border-cool/30 bg-slate-50/50 rounded-t-xl">
                <h2 class="text-sm font-semibold text-navy flex items-center gap-2">
                    <iconify-icon icon="heroicons:computer-desktop" class="w-4 h-4 text-teal"></iconify-icon>
                    Identity
                </h2>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Application name <span class="text-coral">*</span></label>
                    <input name="name" type="text" value="{{ old('name') }}"
                           class="input input-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200{{ $errors->has('name') ? ' input-error' : '' }}"
                           placeholder="e.g. app-web, mobile-api, admin-panel" required>
                    @error('name')
                        <p class="text-xs text-coral mt-1 flex items-center gap-1">
                            <iconify-icon icon="heroicons:exclamation-circle" class="w-3.5 h-3.5 shrink-0"></iconify-icon>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Tech stack --}}
    <div class="card bg-base-100 border border-cool/50 shadow-sm">
        <div class="card-body p-0">
            <div class="px-5 py-3 border-b border-cool/30 bg-slate-50/50 rounded-t-xl">
                <h2 class="text-sm font-semibold text-navy flex items-center gap-2">
                    <iconify-icon icon="heroicons:code-bracket" class="w-4 h-4 text-teal"></iconify-icon>
                    Technology stack
                </h2>
                <p class="text-xs text-warm mt-0.5 ml-6">The AI will generate code, tests, and configurations following this stack's conventions.</p>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-navy mb-1">Primary technology <span class="text-coral">*</span></label>
                        <select name="technology"
                                class="select select-bordered w-full bg-white text-sm text-navy focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200{{ $errors->has('technology') ? ' select-error' : '' }}" required>
                            <option value="" disabled {{ old('technology') ? '' : 'selected' }}>Select framework / language</option>
                            <optgroup label="PHP">
                                <option value="laravel" {{ old('technology') === 'laravel' ? 'selected' : '' }}>Laravel</option>
                                <option value="symfony" {{ old('technology') === 'symfony' ? 'selected' : '' }}>Symfony</option>
                            </optgroup>
                            <optgroup label="JavaScript / TypeScript">
                                <option value="react" {{ old('technology') === 'react' ? 'selected' : '' }}>React (SPA)</option>
                                <option value="vue" {{ old('technology') === 'vue' ? 'selected' : '' }}>Vue.js (SPA)</option>
                                <option value="node_express" {{ old('technology') === 'node_express' ? 'selected' : '' }}>Node.js — Express</option>
                                <option value="node_nest" {{ old('technology') === 'node_nest' ? 'selected' : '' }}>Node.js — NestJS</option>
                            </optgroup>
                            <optgroup label="Python">
                                <option value="django" {{ old('technology') === 'django' ? 'selected' : '' }}>Python — Django</option>
                                <option value="fastapi" {{ old('technology') === 'fastapi' ? 'selected' : '' }}>Python — FastAPI</option>
                            </optgroup>
                            <optgroup label="Mobile">
                                <option value="flutter" {{ old('technology') === 'flutter' ? 'selected' : '' }}>Flutter</option>
                                <option value="react_native" {{ old('technology') === 'react_native' ? 'selected' : '' }}>React Native</option>
                            </optgroup>
                            <option value="other" {{ old('technology') === 'other' ? 'selected' : '' }}>Other (specify in notes)</option>
                        </select>
                        @error('technology')
                            <p class="text-xs text-coral mt-1 flex items-center gap-1">
                                <iconify-icon icon="heroicons:exclamation-circle" class="w-3.5 h-3.5 shrink-0"></iconify-icon>
                                {{ $message }}
                            </p>
                        @enderror
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
                            <label class="flex items-start gap-3 px-3 py-2 border border-cool/40 rounded-lg cursor-pointer hover:border-teal/50 transition duration-200 has-[:checked]:border-teal/60 has-[:checked]:bg-teal/5">
                                <input type="radio" name="platform" value="{{ $val }}" class="radio radio-sm text-teal focus:ring-teal mt-0.5" {{ old('platform', 'web') === $val ? 'checked' : '' }}>
                                <div>
                                    <span class="text-sm font-medium text-navy">{{ $info[0] }}</span>
                                    <span class="text-xs text-warm ml-2">{{ $info[1] }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('platform')
                            <p class="text-xs text-coral mt-2 flex items-center gap-1">
                                <iconify-icon icon="heroicons:exclamation-circle" class="w-3.5 h-3.5 shrink-0"></iconify-icon>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Architecture --}}
    <div class="card bg-base-100 border border-cool/50 shadow-sm">
        <div class="card-body p-0">
            <div class="px-5 py-3 border-b border-cool/30 bg-slate-50/50 rounded-t-xl">
                <h2 class="text-sm font-semibold text-navy flex items-center gap-2">
                    <iconify-icon icon="heroicons:squares-2x2" class="w-4 h-4 text-teal"></iconify-icon>
                    Architecture &amp; patterns
                </h2>
                <p class="text-xs text-warm mt-0.5 ml-6">These choices determine folder structure, naming conventions, and code organization.</p>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-navy mb-1">Architecture pattern <span class="text-coral">*</span></label>
                        <select name="architecture"
                                class="select select-bordered w-full bg-white text-sm text-navy focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200{{ $errors->has('architecture') ? ' select-error' : '' }}" required>
                            <option value="" disabled {{ old('architecture') ? '' : 'selected' }}>Select pattern</option>
                            <option value="mvc" {{ old('architecture') === 'mvc' ? 'selected' : '' }}>MVC — Model-View-Controller</option>
                            <option value="repository" {{ old('architecture') === 'repository' ? 'selected' : '' }}>Repository Pattern</option>
                            <option value="clean" {{ old('architecture') === 'clean' ? 'selected' : '' }}>Clean Architecture</option>
                            <option value="hexagonal" {{ old('architecture') === 'hexagonal' ? 'selected' : '' }}>Hexagonal / Ports &amp; Adapters</option>
                            <option value="microservices" {{ old('architecture') === 'microservices' ? 'selected' : '' }}>Microservices</option>
                            <option value="monolith" {{ old('architecture') === 'monolith' ? 'selected' : '' }}>Monolith (modular)</option>
                            <option value="serverless" {{ old('architecture') === 'serverless' ? 'selected' : '' }}>Serverless / Lambda</option>
                            <option value="event_driven" {{ old('architecture') === 'event_driven' ? 'selected' : '' }}>Event-Driven</option>
                        </select>
                        @error('architecture')
                            <p class="text-xs text-coral mt-1 flex items-center gap-1">
                                <iconify-icon icon="heroicons:exclamation-circle" class="w-3.5 h-3.5 shrink-0"></iconify-icon>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-cool mt-1">Governs how the AI organizes services, controllers, and dependencies.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-navy mb-1">Database <span class="text-coral">*</span></label>
                        <select name="database"
                                class="select select-bordered w-full bg-white text-sm text-navy focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200{{ $errors->has('database') ? ' select-error' : '' }}" required>
                            <option value="" disabled {{ old('database') ? '' : 'selected' }}>Select database</option>
                            <option value="postgresql" {{ old('database') === 'postgresql' ? 'selected' : '' }}>PostgreSQL</option>
                            <option value="mysql" {{ old('database') === 'mysql' ? 'selected' : '' }}>MySQL / MariaDB</option>
                            <option value="sqlite" {{ old('database') === 'sqlite' ? 'selected' : '' }}>SQLite</option>
                            <option value="mongodb" {{ old('database') === 'mongodb' ? 'selected' : '' }}>MongoDB</option>
                            <option value="none" {{ old('database') === 'none' ? 'selected' : '' }}>None — no database</option>
                        </select>
                        @error('database')
                            <p class="text-xs text-coral mt-1 flex items-center gap-1">
                                <iconify-icon icon="heroicons:exclamation-circle" class="w-3.5 h-3.5 shrink-0"></iconify-icon>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-cool mt-1">Influences ORM choice, migration style, and query patterns.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Code style convention</label>
                    <select name="code_style"
                            class="select select-bordered w-full bg-white text-sm text-navy focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200">
                        <option value="" {{ old('code_style') === '' ? 'selected' : '' }}>Auto-detect from technology</option>
                        <option value="psr12" {{ old('code_style') === 'psr12' ? 'selected' : '' }}>PSR-12 (PHP)</option>
                        <option value="pep8" {{ old('code_style') === 'pep8' ? 'selected' : '' }}>PEP 8 (Python)</option>
                        <option value="airbnb" {{ old('code_style') === 'airbnb' ? 'selected' : '' }}>Airbnb Style (JavaScript / React)</option>
                        <option value="standard" {{ old('code_style') === 'standard' ? 'selected' : '' }}>Standard JS</option>
                        <option value="google" {{ old('code_style') === 'google' ? 'selected' : '' }}>Google Style</option>
                        <option value="custom" {{ old('code_style') === 'custom' ? 'selected' : '' }}>Custom — define later</option>
                    </select>
                    <p class="text-xs text-cool mt-1">The AI will format generated code according to this style guide.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Notes --}}
    <div class="card bg-base-100 border border-cool/50 shadow-sm">
        <div class="card-body p-0">
            <div class="px-5 py-3 border-b border-cool/30 bg-slate-50/50 rounded-t-xl">
                <h2 class="text-sm font-semibold text-navy flex items-center gap-2">
                    <iconify-icon icon="heroicons:pencil" class="w-4 h-4 text-teal"></iconify-icon>
                    Additional notes
                </h2>
            </div>
            <div class="p-5">
                <textarea name="notes" rows="3"
                          class="textarea textarea-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200"
                          placeholder="Any extra context that helps AI agents understand this app — team conventions, existing patterns, constraints... (optional)">{{ old('notes') }}</textarea>
                <p class="text-xs text-cool mt-1">Free-form, but keep it relevant. Bullet points work well.</p>
            </div>
        </div>
    </div>

    {{-- Submit --}}
    <div class="flex items-center gap-3">
        <button type="submit"
                :disabled="submitting"
                class="btn btn-primary disabled:opacity-60 disabled:cursor-not-allowed transition duration-200">
            <span x-show="!submitting">Create application</span>
            <span x-show="submitting" class="flex items-center gap-2">
                <iconify-icon icon="svg-spinners:180-ring" class="w-4 h-4"></iconify-icon>
                Creating&hellip;
            </span>
        </button>
        <a href="{{ route('apps.index') }}" class="btn btn-ghost text-sm text-warm hover:text-navy transition duration-200">Cancel</a>
    </div>
</form>

@endsection
