{{-- Step 3: Identity --}}
<div x-show="step === 3">
    <h3 class="text-lg font-bold text-navy mb-1">Project identity</h3>
    <p class="text-sm text-warm mb-6">Name your project and choose where the source code lives.</p>

    <div class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-navy mb-1">Project name <span class="text-coral">*</span></label>
            <input name="name" type="text" x-model="projectName" value="{{ old('name', $project->name ?? '') }}"
                   class="input input-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200{{ $errors->has('name') ? ' input-error' : '' }}"
                   placeholder="e.g. SAID Platform, E-Commerce Backend" required>
            @error('name')
                <p class="text-xs text-coral mt-1 flex items-center gap-1">
                    <iconify-icon icon="heroicons:exclamation-circle" style="font-size: 14px" class="shrink-0"></iconify-icon>
                    {{ $message }}
                </p>
            @enderror
        </div>

        @if (isset($project))
        <div>
            <label class="block text-sm font-medium text-navy mb-1">Project path</label>
            <p class="text-sm text-warm font-mono bg-cool/10 border border-cool/30 rounded-lg px-3 py-2.5">{{ $project->path }}</p>
            <p class="text-xs text-cool mt-1">Path cannot be changed after project creation.</p>
        </div>
        @else
        <div>
            <label class="block text-sm font-medium text-navy mb-1">Project path <span class="text-coral">*</span></label>
            <div class="relative">
                <input name="path" type="text" x-model="selectedPath" required readonly
                       @click="open = true; $nextTick(() => $refs.folderModal?.showModal())"
                       class="input input-bordered w-full pl-10 bg-white text-sm text-navy placeholder:text-cool/60 cursor-pointer focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200{{ $errors->has('path') ? ' input-error' : '' }}"
                       placeholder="Click to browse the projects folder…">
                <iconify-icon icon="heroicons:folder" style="font-size: 16px" class="absolute left-3 top-1/2 -translate-y-1/2 text-cool"></iconify-icon>
            </div>
            @error('path')
                <p class="text-xs text-coral mt-1 flex items-center gap-1">
                    <iconify-icon icon="heroicons:exclamation-circle" style="font-size: 14px" class="shrink-0"></iconify-icon>
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
                    <iconify-icon icon="heroicons:exclamation-circle" style="font-size: 14px" class="shrink-0"></iconify-icon>
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>
</div>
