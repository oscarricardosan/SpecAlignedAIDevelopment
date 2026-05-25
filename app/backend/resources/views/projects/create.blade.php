@extends('layouts.app')

@section('title', isset($project) ? 'Edit Project' : 'New Project')
@section('heading', isset($project) ? 'Edit Project' : 'New Project')
@section('subheading', isset($project) ? 'Update project configuration' : 'Set the foundation — this context will guide your AI agents')

@section('content')

<div x-data="folderBrowser()" x-init="selectedPath = '{{ old('path', $project->path ?? '') }}'">

<form action="{{ isset($project) ? route('projects.update', $project) : route('projects.store') }}" method="POST" class="max-w-3xl space-y-8"
      x-data="{ submitting: false }" @submit="submitting = true">
    @csrf
    @if (isset($project))
        @method('PUT')
    @endif

    {{-- Identity --}}
    <div class="card bg-base-100 border border-cool/50 shadow-sm">
        <div class="card-body p-0">
            <div class="px-5 py-3 border-b border-cool/30 bg-slate-50/50 rounded-t-xl">
                <h2 class="text-sm font-semibold text-navy flex items-center gap-2">
                    <iconify-icon icon="heroicons:tag" class="w-4 h-4 text-teal"></iconify-icon>
                    Identity
                </h2>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Project name <span class="text-coral">*</span></label>
                    <input name="name" type="text" value="{{ old('name', $project->name ?? '') }}"
                           class="input input-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200{{ $errors->has('name') ? ' input-error' : '' }}"
                           placeholder="e.g. SAID Platform, E-Commerce Backend" required>
                    @error('name')
                        <p class="text-xs text-coral mt-1 flex items-center gap-1">
                            <iconify-icon icon="heroicons:exclamation-circle" class="w-3.5 h-3.5 shrink-0"></iconify-icon>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                @if (isset($project))
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Project path</label>
                    <p class="text-sm text-warm font-mono bg-slate-50 border border-cool/30 rounded-lg px-3 py-2.5">{{ $project->path }}</p>
                    <p class="text-xs text-cool mt-1">Path cannot be changed after project creation.</p>
                </div>
                @else
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Project path <span class="text-coral">*</span></label>
                    <div class="relative">
                        <input name="path" type="text" x-model="selectedPath" required readonly
                               @click="open = true; $nextTick(() => $refs.folderModal?.showModal())"
                               class="input input-bordered w-full pl-10 bg-white text-sm text-navy placeholder:text-cool/60 cursor-pointer focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200{{ $errors->has('path') ? ' input-error' : '' }}"
                               placeholder="Click to browse the projects folder&hellip;">
                        <iconify-icon icon="heroicons:folder" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-cool"></iconify-icon>
                    </div>
                    @error('path')
                        <p class="text-xs text-coral mt-1 flex items-center gap-1">
                            <iconify-icon icon="heroicons:exclamation-circle" class="w-3.5 h-3.5 shrink-0"></iconify-icon>
                            {{ $message }}
                        </p>
                    @else
                        <p class="text-xs text-cool mt-1">Select the folder where this project's source code lives.</p>
                    @enderror
                </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Short description</label>
                    <textarea name="description" rows="2"
                              class="textarea textarea-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200{{ $errors->has('description') ? ' textarea-error' : '' }}"
                              placeholder="What is this project about? This helps the AI understand the domain.">{{ old('description', $project->description ?? '') }}</textarea>
                    @error('description')
                        <p class="text-xs text-coral mt-1 flex items-center gap-1">
                            <iconify-icon icon="heroicons:exclamation-circle" class="w-3.5 h-3.5 shrink-0"></iconify-icon>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Business context --}}
    <div class="card bg-base-100 border border-cool/50 shadow-sm">
        <div class="card-body p-0">
            <div class="px-5 py-3 border-b border-cool/30 bg-slate-50/50 rounded-t-xl">
                <h2 class="text-sm font-semibold text-navy flex items-center gap-2">
                    <iconify-icon icon="heroicons:briefcase" class="w-4 h-4 text-teal"></iconify-icon>
                    Business context
                </h2>
                <p class="text-xs text-warm mt-0.5 ml-6">This information gives AI agents domain awareness when writing specs and code.</p>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">System criticality <span class="text-coral">*</span></label>
                    <select name="criticality"
                            class="select select-bordered w-full bg-white text-sm text-navy focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200{{ $errors->has('criticality') ? ' select-error' : '' }}" required>
                        <option value="" disabled {{ old('criticality', $project->criticality ?? '') === '' ? 'selected' : '' }}>Select how critical this system is</option>
                    @foreach (\App\Support\Label::options('criticality') as $val => $label)
                        <option value="{{ $val }}" {{ old('criticality', $project->criticality ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                    </select>
                    @error('criticality')
                        <p class="text-xs text-coral mt-1 flex items-center gap-1">
                            <iconify-icon icon="heroicons:exclamation-circle" class="w-3.5 h-3.5 shrink-0"></iconify-icon>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="text-xs text-cool mt-1">Helps the AI prioritize reliability, testing depth, and audit strictness.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Business sector <span class="text-coral">*</span></label>
                    <select name="business_sector"
                            class="select select-bordered w-full bg-white text-sm text-navy focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200{{ $errors->has('business_sector') ? ' select-error' : '' }}" required>
                        <option value="" disabled {{ old('business_sector', $project->business_sector ?? '') === '' ? 'selected' : '' }}>Select the industry or domain</option>
                    @foreach (\App\Support\Label::options('business_sector') as $val => $label)
                        <option value="{{ $val }}" {{ old('business_sector', $project->business_sector ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                    </select>
                    @error('business_sector')
                        <p class="text-xs text-coral mt-1 flex items-center gap-1">
                            <iconify-icon icon="heroicons:exclamation-circle" class="w-3.5 h-3.5 shrink-0"></iconify-icon>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="text-xs text-cool mt-1">Sets domain language, entity naming conventions, and common patterns.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Compliance & audience --}}
    <div class="card bg-base-100 border border-cool/50 shadow-sm">
        <div class="card-body p-0">
            <div class="px-5 py-3 border-b border-cool/30 bg-slate-50/50 rounded-t-xl">
                <h2 class="text-sm font-semibold text-navy flex items-center gap-2">
                    <iconify-icon icon="heroicons:shield-check" class="w-4 h-4 text-teal"></iconify-icon>
                    Compliance &amp; audience
                </h2>
            </div>
            <div class="p-5 space-y-5">
                <fieldset>
                    <legend class="text-sm font-medium text-navy mb-2">Compliance requirements</legend>
                    <p class="text-xs text-warm mb-3">Select all that apply. The AI will enforce relevant standards in generated code.</p>
                    <div class="space-y-2">
                        @php
                        $selectedCompliance = old('compliance', isset($project) ? $project->compliances->pluck('compliance')->toArray() : []);
                        @endphp
                        @foreach (\App\Support\Label::options('compliance') as $val => $info)
                        <label class="flex items-start gap-2 px-3 py-2 border border-cool/40 rounded-lg cursor-pointer hover:border-teal/50 transition duration-200 text-sm text-navy has-[:checked]:border-teal/60 has-[:checked]:bg-teal/5">
                            <input type="checkbox" name="compliance[]" value="{{ $val }}" class="checkbox checkbox-sm border-cool text-teal focus:ring-teal mt-0.5"
                                   {{ in_array($val, $selectedCompliance) ? 'checked' : '' }}>
                            <div>
                                <span class="font-medium">{{ \App\Support\Label::humanize($val) }}</span>
                                <span class="text-xs text-warm ml-1">— {{ str_replace(\App\Support\Label::humanize($val) . ' — ', '', $info) }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </fieldset>

                <fieldset>
                    <legend class="text-sm font-medium text-navy mb-2">Target audience <span class="text-coral">*</span></legend>
                    <p class="text-xs text-warm mb-3">Who will use this system? Influences UX tone, auth patterns, and scalability.</p>
                    <div class="grid grid-cols-2 gap-2">
                        @php
                        $selectedAudience = old('target_audience', $project->target_audience ?? 'internal');
                        @endphp
                        @foreach (\App\Support\Label::options('target_audience') as $val => $info)
                        <label class="flex items-start gap-3 px-4 py-3 border border-cool/40 rounded-lg cursor-pointer hover:border-teal/50 transition duration-200 has-[:checked]:border-teal/60 has-[:checked]:bg-teal/5">
                            <input type="radio" name="target_audience" value="{{ $val }}" class="radio radio-sm text-teal focus:ring-teal mt-0.5"
                                   {{ $selectedAudience === $val ? 'checked' : '' }}>
                            <div>
                                <span class="text-sm font-medium text-navy">{{ \App\Support\Label::humanize($val) }}</span>
                                <p class="text-xs text-warm mt-0.5">{{ str_replace(\App\Support\Label::humanize($val) . ' — ', '', $info) }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('target_audience')
                        <p class="text-xs text-coral mt-2 flex items-center gap-1">
                            <iconify-icon icon="heroicons:exclamation-circle" class="w-3.5 h-3.5 shrink-0"></iconify-icon>
                            {{ $message }}
                        </p>
                    @enderror
                </fieldset>
            </div>
        </div>
    </div>

    {{-- Submit --}}
    <div class="flex items-center gap-3">
        <button type="submit"
                :disabled="submitting"
                class="btn btn-primary disabled:opacity-60 disabled:cursor-not-allowed transition duration-200">
            <span x-show="!submitting">{{ isset($project) ? 'Save changes' : 'Create project' }}</span>
            <span x-show="submitting" class="flex items-center gap-2">
                <iconify-icon icon="svg-spinners:180-ring" class="w-4 h-4"></iconify-icon>
                {{ isset($project) ? 'Saving…' : 'Creating…' }}
            </span>
        </button>
        <a href="{{ route('projects.index') }}" class="btn btn-ghost text-sm text-warm hover:text-navy transition duration-200">Cancel</a>
    </div>
</form>

{{-- Folder browser modal — DaisyUI --}}
<dialog x-ref="folderModal" class="modal" @click.self="open = false; $refs.folderModal?.close()" @close="open = false">
    <div class="modal-box max-w-lg p-0 flex flex-col max-h-[80vh] overflow-hidden" @click.stop @keydown.enter.prevent>
        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-3 border-b border-cool/30 bg-slate-50/50 shrink-0 rounded-t-2xl">
            <h3 class="text-sm font-semibold text-navy flex items-center gap-2">
                <iconify-icon icon="heroicons:folder-open" class="w-4 h-4 text-teal"></iconify-icon>
                Browse projects folder
            </h3>
            <button type="button" @click="open = false; $refs.folderModal?.close()" class="btn btn-ghost btn-xs btn-circle transition duration-200">
                <iconify-icon icon="heroicons:x-mark" class="w-4 h-4"></iconify-icon>
            </button>
        </div>

        {{-- Breadcrumbs --}}
        <nav class="flex items-center gap-1 px-5 py-2 text-sm text-warm flex-wrap border-b border-cool/20 shrink-0" aria-label="Breadcrumb">
            <template x-for="(crumb, idx) in breadcrumbs" :key="crumb.path">
                <span class="flex items-center gap-1">
                    <span x-show="idx > 0" class="text-cool/50">/</span>
                    <button type="button" @click="navigate(crumb.path)"
                            :class="idx === breadcrumbs.length - 1 ? 'text-navy font-semibold cursor-default' : 'text-teal hover:underline cursor-pointer'"
                            class="transition duration-200"
                            x-text="crumb.label"></button>
                </span>
            </template>
        </nav>

        {{-- Body --}}
        <div class="flex-1 overflow-y-auto p-3 min-h-0">
            {{-- Skeleton loading --}}
            <div x-show="loading" class="space-y-1 py-1">
                <template x-for="n in 5" :key="n">
                    <div class="flex items-center gap-2.5 px-3 py-2">
                        <div class="w-4 h-4 rounded bg-cool/20 animate-pulse shrink-0"></div>
                        <div class="h-3.5 rounded bg-cool/15 animate-pulse flex-1"></div>
                    </div>
                </template>
            </div>

            <div x-show="error" x-cloak class="text-sm text-coral py-4 text-center" x-text="error"></div>

            <div x-show="!loading && !error" class="space-y-0.5">
                {{-- Parent folder --}}
                <template x-if="breadcrumbs.length > 1">
                    <button type="button" @click="goUp()"
                            class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-cool hover:text-teal hover:bg-teal/10 transition duration-200 text-left font-medium">
                        <iconify-icon icon="heroicons:arrow-up" class="w-4 h-4 shrink-0"></iconify-icon>
                        ..
                    </button>
                </template>

                <template x-if="items.length === 0">
                    <p class="text-sm text-cool p-4 text-center italic">This folder is empty. Create a subfolder or select it.</p>
                </template>
                <template x-for="item in items" :key="item.path">
                    <button type="button" @click="navigate(item.path)"
                            class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-navy hover:bg-teal/10 transition duration-200 text-left">
                        <iconify-icon icon="heroicons:folder" class="w-4 h-4 text-peach shrink-0"></iconify-icon>
                        <span x-text="item.name"></span>
                    </button>
                </template>
            </div>
        </div>

        {{-- Footer --}}
        <div class="border-t border-cool/20 px-4 py-3 flex items-center gap-3 shrink-0 bg-slate-50/30">
            <div class="flex items-center gap-2 flex-1">
                <button type="button" @click="creating = !creating; if(creating) $nextTick(() => $refs.newFolderInput?.focus())"
                        class="btn btn-ghost btn-xs text-teal transition duration-200"
                        x-text="creating ? 'Cancel' : '+ New folder'"></button>
                <template x-if="creating">
                    <div class="flex items-center gap-1.5 flex-1">
                        <input type="text" x-model="newFolderName" x-ref="newFolderInput"
                               @keydown.enter.prevent="createFolder()"
                               placeholder="Folder name"
                               class="input input-bordered input-xs flex-1 bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal transition duration-200">
                        <button type="button" @click="createFolder()"
                                class="btn btn-primary btn-xs transition duration-200">
                            Create
                        </button>
                    </div>
                </template>
            </div>
            <template x-if="currentPath !== ''">
                <button type="button" @click="selectCurrent(); open = false; $refs.folderModal?.close()"
                        class="btn btn-neutral btn-sm gap-1 transition duration-200">
                    <iconify-icon icon="heroicons:check" class="w-4 h-4"></iconify-icon>
                    Select this folder
                </button>
            </template>
            <template x-if="currentPath === ''">
                <span class="text-sm text-warm">Navigate into a folder to select it</span>
            </template>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button @click="open = false">close</button>
    </form>
</dialog>

</div>

<script>
function folderBrowser() {
    return {
        open: false,
        currentPath: '',
        currentFullPath: '',
        selectedPath: '',
        items: [],
        breadcrumbs: [],
        loading: false,
        error: '',
        creating: false,
        newFolderName: '',

        init() {
            this.browse('');
        },

        async browse(path) {
            this.loading = true;
            this.error = '';
            this.items = [];
            try {
                const res = await fetch(`{{ route('api.filesystem.browse') }}?path=${encodeURIComponent(path)}`);
                if (!res.ok) {
                    const body = await res.json().catch(() => ({}));
                    throw new Error(body.error || 'Could not load folder contents.');
                }
                const data = await res.json();
                this.currentPath = data.path;
                this.currentFullPath = data.full_path;
                this.breadcrumbs = data.breadcrumbs;
                this.items = data.items;
            } catch (e) {
                this.error = e.message;
            } finally {
                this.loading = false;
            }
        },

        goUp() {
            if (this.breadcrumbs.length > 1) {
                const parent = this.breadcrumbs[this.breadcrumbs.length - 2];
                this.navigate(parent.path);
            }
        },

        navigate(path) {
            this.creating = false;
            this.newFolderName = '';
            this.browse(path);
        },

        selectCurrent() {
            this.selectedPath = this.currentFullPath;
        },

        async createFolder() {
            const name = this.newFolderName.trim();
            if (!name) return;

            this.error = '';
            try {
                const res = await fetch('{{ route('api.filesystem.directory.create') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ path: this.currentPath, name }),
                });

                if (!res.ok) {
                    const body = await res.json().catch(() => ({}));
                    throw new Error(body.error || 'Could not create folder.');
                }

                this.newFolderName = '';
                this.creating = false;
                await this.browse(this.currentPath);
            } catch (e) {
                this.error = e.message;
            }
        },
    };
}
</script>

@endsection
