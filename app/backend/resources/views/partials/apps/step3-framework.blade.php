<div x-show="step === 3">
    <h3 class="text-lg font-bold text-navy mb-1">Choose a framework</h3>
    <p class="text-sm text-warm mb-6">Select the framework or runtime — the first option is always no framework.</p>

    <div class="flex flex-col lg:flex-row gap-8">
        {{-- LEFT: Frameworks --}}
        <div class="flex-1 min-w-0">
            <div class="grid grid-cols-2 xl:grid-cols-3 gap-3">
                <template x-for="fw in frameworkOptions()" :key="fw.value">
                    <label class="flex flex-col items-center gap-3 p-4 border rounded-xl cursor-pointer transition duration-200 text-center"
                           :class="tech === fw.value ? 'border-teal/60 bg-teal/5 shadow-sm' : 'border-cool/40 hover:border-teal/50 hover:shadow-sm'">
                        <input type="radio" name="technology" :value="fw.value" x-model="tech" class="sr-only">
                        <iconify-icon :icon="fw.icon" style="font-size: 40px" class="shrink-0 pointer-events-none"
                                      :class="tech === fw.value ? 'text-teal' : 'text-cool'"></iconify-icon>
                        <div>
                            <p class="text-sm font-bold text-navy" x-text="fw.label"></p>
                            <p class="text-xs text-warm mt-0.5" x-text="fw.desc"></p>
                        </div>
                    </label>
                </template>
            </div>
            @error('technology')
                <p class="text-xs text-coral mt-2 flex items-center gap-1">
                    <iconify-icon icon="heroicons:exclamation-circle" style="font-size: 14px" class="shrink-0"></iconify-icon>
                    {{ $message }}
                </p>
            @enderror

            {{-- Custom framework input --}}
            <div x-show="tech === 'other'" class="mt-4 p-4 bg-cool/10 rounded-xl border border-cool/20">
                <label class="block text-sm font-semibold text-navy mb-2">Specify framework</label>
                <input type="text" name="technology_custom" x-model="techCustom"
                       class="input input-bordered w-full max-w-sm bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200"
                       placeholder="e.g. Slim, Lumen, Swoole...">
            </div>

            {{-- Framework version --}}
            <div x-show="tech && frameworkVersionOptions().length" class="mt-4 p-4 bg-cool/10 rounded-xl border border-cool/20">
                <p class="text-sm font-semibold text-navy mb-1 flex items-center gap-2">
                    <iconify-icon icon="heroicons:tag" style="font-size: 16px" class="text-teal"></iconify-icon>
                    Version
                </p>
                <p class="text-xs text-cool/70 mb-3">Only versions with active security support are listed.</p>
                <div class="flex flex-wrap gap-2">
                    <template x-for="v in frameworkVersionOptions()" :key="v.value">
                        <label class="flex items-center gap-2 px-4 py-2 border rounded-lg cursor-pointer transition duration-200 text-sm"
                               :class="frameworkVersion === v.value ? 'border-teal/60 bg-teal/5 text-navy font-semibold shadow-sm' : 'border-cool/30 text-warm hover:border-teal/50'">
                            <input type="radio" name="framework_version" :value="v.value" x-model="frameworkVersion" class="sr-only">
                            <span x-text="v.label"></span>
                        </label>
                    </template>
                </div>
                <div x-show="frameworkVersion === 'custom'" class="mt-3">
                    <input type="text" name="framework_version_custom" x-model="frameworkVersionCustom"
                           class="input input-bordered w-full max-w-xs bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200"
                           placeholder="e.g. 3.2, 4.0-rc1...">
                </div>
            </div>
        </div>

        {{-- RIGHT: Package manager + Execution environment --}}
        <div class="w-full lg:w-72 shrink-0 space-y-6">
            {{-- Package manager --}}
            <div>
                <label class="block text-sm font-semibold text-navy mb-2 flex items-center gap-1">
                    <iconify-icon icon="heroicons:cube" style="font-size: 16px" class="text-teal"></iconify-icon>
                    Package manager
                    <span class="text-[10px] text-cool/60 font-normal">(optional)</span>
                </label>
                <p class="text-xs text-warm mb-3">Auto-selected based on technology. Change if needed.</p>
                <div class="grid grid-cols-2 gap-2">
                    <template x-for="pm in packageManagerOptions()" :key="pm.value">
                        <label class="flex items-center gap-2 p-2.5 border rounded-lg cursor-pointer transition duration-200 text-sm has-[:checked]:border-teal/60 has-[:checked]:bg-teal/5"
                               :class="packageManager === pm.value ? 'border-teal/60 bg-teal/5 shadow-sm' : 'border-cool/30 hover:border-teal/50'"
                               @click.prevent="packageManager = packageManager === pm.value ? '' : pm.value">
                            <input type="radio" name="package_manager" :value="pm.value"
                                   :checked="packageManager === pm.value" class="sr-only">
                            <iconify-icon :icon="pm.icon" style="font-size: 20px" class="shrink-0 pointer-events-none"
                                          :class="packageManager === pm.value ? 'text-teal' : 'text-cool'"></iconify-icon>
                            <span class="text-sm font-semibold text-navy" x-text="pm.label"></span>
                        </label>
                    </template>
                </div>
                <p x-show="packageManagerOptions().length === 0 && tech" class="text-xs text-cool/70 mt-2 italic">
                    No package manager options for this technology.
                </p>
            </div>

            {{-- Execution environment --}}
            <div>
                <label class="block text-sm font-semibold text-navy mb-2 flex items-center gap-1">
                    <iconify-icon icon="heroicons:play-circle" style="font-size: 16px" class="text-teal"></iconify-icon>
                    Execution environment
                </label>
                <p class="text-xs text-warm mb-3">Where will the AI agent execute commands?</p>
                <div class="space-y-2">
                    <label class="flex items-start gap-3 p-3 border rounded-xl cursor-pointer transition duration-200 has-[:checked]:border-teal/60 has-[:checked]:bg-teal/5"
                           :class="executor === 'local' ? 'border-teal/60 bg-teal/5 shadow-sm' : 'border-cool/40 hover:border-teal/50 hover:shadow-sm'">
                        <input type="radio" name="executor" value="local" x-model="executor" class="sr-only">
                        <iconify-icon icon="heroicons:computer-desktop" style="font-size: 24px" class="shrink-0 pointer-events-none mt-0.5"
                                      :class="executor === 'local' ? 'text-teal' : 'text-cool'"></iconify-icon>
                        <div>
                            <span class="text-sm font-semibold text-navy">Local</span>
                            <p class="text-xs text-warm mt-0.5">Commands run directly on the host machine.</p>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 p-3 border rounded-xl cursor-pointer transition duration-200 has-[:checked]:border-teal/60 has-[:checked]:bg-teal/5"
                           :class="executor === 'docker' ? 'border-teal/60 bg-teal/5 shadow-sm' : 'border-cool/40 hover:border-teal/50 hover:shadow-sm'">
                        <input type="radio" name="executor" value="docker" x-model="executor" class="sr-only">
                        <iconify-icon icon="devicon:docker" style="font-size: 24px" class="shrink-0 pointer-events-none mt-0.5"
                                      :class="executor === 'docker' ? 'text-teal' : 'text-cool'"></iconify-icon>
                        <div>
                            <span class="text-sm font-semibold text-navy">Docker</span>
                            <p class="text-xs text-warm mt-0.5">Commands run inside ephemeral containers.</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
