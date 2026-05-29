@extends('layouts.app')

@section('title', isset($project) ? 'Edit Project' : 'New Project')
@section('heading', isset($project) ? 'Edit Project' : 'New Project')
@section('subheading', isset($project) ? 'Update project configuration' : 'Set the foundation — this context will guide your AI agents')

@section('content')

<div x-data="folderBrowser()" x-init="selectedPath = '{{ old('path', $project->path ?? '') }}'">

<form action="{{ isset($project) ? route('projects.update', $project) : route('projects.store') }}" method="POST"
      x-data="projectStepper()" @submit="submitting = true">

    @csrf
    @if (isset($project))
        @method('PUT')
    @endif

    {{-- Stepper --}}
    <div class="card bg-base-100 border border-cool/50 shadow-sm mb-6">
        <div class="card-body p-4">
            <div class="flex items-center justify-center gap-1">
                @foreach ([1 => 'Business', 2 => 'Compliance', 3 => 'Identity'] as $i => $label)
                    <button type="button" @click="goToStep({{ $i }})" class="flex items-center gap-1.5 group" :disabled="step < {{ $i }} && !isStepComplete({{ $i - 1 }})">
                        <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold border transition shrink-0"
                              :class="step === {{ $i }}
                                  ? 'bg-teal text-white border-teal'
                                  : (step > {{ $i }}
                                      ? 'bg-teal/10 text-teal border-teal/30 cursor-pointer'
                                      : 'text-cool/40 border-cool/30')">
                            <span x-show="step > {{ $i }}">✓</span>
                            <span x-show="step <= {{ $i }}" x-text="{{ $i }}"></span>
                        </span>
                        <span class="text-xs font-semibold transition"
                              :class="step >= {{ $i }} ? 'text-navy' : 'text-cool/40'">{{ $label }}</span>
                    </button>
                    @if ($i < 3)
                        <span class="w-4 h-px shrink-0 transition" :class="step > {{ $i }} ? 'bg-teal/40' : 'bg-cool/20'"></span>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Step content --}}
    <div class="card bg-base-100 border border-cool/50 shadow-sm mb-6">
        <div class="card-body p-6">
            @include('partials.projects.step1-business')
            @include('partials.projects.step2-compliance')
            @include('partials.projects.step3-identity')
        </div>
    </div>

    {{-- Navigation --}}
    <div class="flex items-center gap-3">
        <button type="button" @click="goToStep(step - 1)"
                x-show="step > 1"
                class="btn btn-ghost btn-sm text-sm text-warm hover:text-navy transition duration-200">
            <iconify-icon icon="heroicons:arrow-left" style="font-size: 16px"></iconify-icon>
            Back
        </button>
        <button type="button" @click="goToStep(step + 1)"
                x-show="step < 3"
                class="btn btn-primary btn-sm ml-auto transition duration-200"
                :disabled="!canProceed()">
            Next
            <iconify-icon icon="heroicons:arrow-right" style="font-size: 16px"></iconify-icon>
        </button>
        <button type="submit"
                x-show="step === 3"
                :disabled="submitting"
                class="btn btn-primary btn-sm ml-auto disabled:opacity-60 disabled:cursor-not-allowed transition duration-200">
            <span x-show="!submitting">{{ isset($project) ? 'Save changes' : 'Create project' }}</span>
            <span x-show="submitting" class="flex items-center gap-2">
                <iconify-icon icon="svg-spinners:180-ring" style="font-size: 16px"></iconify-icon>
                {{ isset($project) ? 'Saving…' : 'Creating…' }}
            </span>
        </button>
        <a href="{{ route('projects.index') }}" class="btn btn-ghost btn-sm text-sm text-warm hover:text-navy transition duration-200">Cancel</a>
    </div>
</form>

{{-- Folder browser modal --}}
<dialog x-ref="folderModal" class="modal" @click.self="open = false; $refs.folderModal?.close()" @close="open = false">
    <div class="modal-box max-w-lg p-0 flex flex-col max-h-[80vh] overflow-hidden" @click.stop @keydown.enter.prevent>
        <div class="flex items-center justify-between px-5 py-3 border-b border-cool/30 bg-cool/10 shrink-0 rounded-t-2xl">
            <h3 class="text-sm font-semibold text-navy flex items-center gap-2">
                <iconify-icon icon="heroicons:folder-open" style="font-size: 16px" class="text-teal"></iconify-icon>
                Browse projects folder
            </h3>
            <button type="button" @click="open = false; $refs.folderModal?.close()" class="btn btn-ghost btn-xs btn-circle transition duration-200">
                <iconify-icon icon="heroicons:x-mark" style="font-size: 16px"></iconify-icon>
            </button>
        </div>

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

        <div class="flex-1 overflow-y-auto p-3 min-h-0">
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
                <template x-if="breadcrumbs.length > 1">
                    <button type="button" @click="goUp()"
                            class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-cool hover:text-teal hover:bg-teal/10 transition duration-200 text-left font-medium">
                        <iconify-icon icon="heroicons:arrow-up" style="font-size: 16px" class="shrink-0"></iconify-icon>
                        ..
                    </button>
                </template>
                <template x-if="items.length === 0">
                    <p class="text-sm text-cool p-4 text-center italic">This folder is empty. Create a subfolder or select it.</p>
                </template>
                <template x-for="item in items" :key="item.path">
                    <button type="button" @click="navigate(item.path)"
                            class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-navy hover:bg-teal/10 transition duration-200 text-left">
                        <iconify-icon icon="heroicons:folder" style="font-size: 16px" class="text-peach shrink-0"></iconify-icon>
                        <span x-text="item.name"></span>
                    </button>
                </template>
            </div>
        </div>

        <div class="border-t border-cool/20 px-4 py-3 flex items-center gap-3 shrink-0 bg-cool/10">
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
                    <iconify-icon icon="heroicons:check" style="font-size: 16px"></iconify-icon>
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
        currentPath: '', currentFullPath: '', selectedPath: '',
        items: [], breadcrumbs: [], loading: false, error: '',
        creating: false, newFolderName: '',
        init() { this.browse(''); },
        async browse(path) {
            this.loading = true; this.error = ''; this.items = [];
            try {
                const res = await fetch(`{{ route('api.filesystem.browse') }}?path=${encodeURIComponent(path)}`);
                if (!res.ok) { const b = await res.json().catch(() => ({})); throw new Error(b.error || 'Could not load folder contents.'); }
                const data = await res.json();
                this.currentPath = data.path; this.currentFullPath = data.full_path;
                this.breadcrumbs = data.breadcrumbs; this.items = data.items;
            } catch (e) { this.error = e.message; }
            finally { this.loading = false; }
        },
        goUp() { if (this.breadcrumbs.length > 1) this.navigate(this.breadcrumbs[this.breadcrumbs.length - 2].path); },
        navigate(path) { this.creating = false; this.newFolderName = ''; this.browse(path); },
        selectCurrent() { this.selectedPath = this.currentPath; },
        async createFolder() {
            const name = this.newFolderName.trim(); if (!name) return;
            this.error = '';
            try {
                const res = await fetch('{{ route('api.filesystem.directory.create') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '', 'Accept': 'application/json' },
                    body: JSON.stringify({ path: this.currentPath, name }),
                });
                if (!res.ok) { const b = await res.json().catch(() => ({})); throw new Error(b.error || 'Could not create folder.'); }
                this.newFolderName = ''; this.creating = false; await this.browse(this.currentPath);
            } catch (e) { this.error = e.message; }
        },
    };
}

function projectStepper() {
    return {
        step: 1,
        submitting: false,
        projectCriticality: '{{ old('criticality', $project->criticality ?? '') }}',
        projectSector: '{{ old('business_sector', $project->business_sector ?? '') }}',
        projectAudience: '{{ old('target_audience', $project->target_audience ?? 'internal') }}',
        projectName: '{{ old('name', $project->name ?? '') }}',
        compliances: {!! json_encode(old('compliance', isset($project) ? $project->compliances->pluck('compliance')->toArray() : [])) !!},

        toggleCompliance(val, checked) {
            if (checked) { if (!this.compliances.includes(val)) this.compliances.push(val); }
            else { this.compliances = this.compliances.filter(c => c !== val); }
        },

        goToStep(n) { if (n < this.step || this.isStepComplete(n - 1)) { this.step = n; window.scrollTo({ top: 0, behavior: 'smooth' }); } },
        isStepComplete(s) {
            switch (s) { case 1: return !!this.projectCriticality && !!this.projectSector; case 2: return !!this.projectAudience; default: return true; }
        },
        canProceed() { return this.isStepComplete(this.step); },
    };
}
</script>

@endsection
