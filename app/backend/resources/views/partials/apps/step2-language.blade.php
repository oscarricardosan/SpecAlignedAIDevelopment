<div x-show="step === 2">
    <h3 class="text-lg font-bold text-navy mb-1">Pick a language</h3>
    <p class="text-sm text-warm mb-6">Select the programming language for this application.</p>
    <div class="grid grid-cols-4 gap-4">
        <template x-for="lang in languageOptions()" :key="lang.value">
            <label class="flex flex-col items-center gap-3 p-5 border rounded-xl cursor-pointer transition duration-200 text-center"
                   :class="language === lang.value ? 'border-teal/60 bg-teal/5 shadow-sm' : 'border-cool/40 hover:border-teal/50 hover:shadow-sm'">
                <input type="radio" x-model="language" :value="lang.value" class="sr-only">
                <iconify-icon :icon="lang.icon" style="font-size: 48px" class="shrink-0 pointer-events-none"
                              :class="language === lang.value ? 'text-teal' : 'text-cool'"></iconify-icon>
                <div>
                    <p class="text-sm font-bold text-navy" x-text="lang.label"></p>
                    <p class="text-xs text-warm mt-0.5" x-text="lang.desc"></p>
                </div>
            </label>
        </template>
    </div>

    {{-- Custom language input --}}
    <div x-show="language === 'other'" class="mt-6 p-4 bg-cool/10 rounded-xl border border-cool/20">
        <label class="block text-sm font-semibold text-navy mb-2">Specify language</label>
        <input type="text" name="language_custom" x-model="languageCustom"
               class="input input-bordered w-full max-w-sm bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200"
               placeholder="e.g. Kotlin, Swift, Ruby, Elixir...">
    </div>

    {{-- Language version --}}
    <div x-show="language && language !== 'other' && languageVersionOptions().length" class="mt-6 p-4 bg-cool/10 rounded-xl border border-cool/20">
        <p class="text-sm font-semibold text-navy mb-1 flex items-center gap-2">
            <iconify-icon icon="heroicons:tag" style="font-size: 16px" class="text-teal"></iconify-icon>
            Version
        </p>
        <p class="text-xs text-cool/70 mb-3">Only versions with active security support are listed. Select <strong>custom</strong> to specify a different version.</p>
        <div class="flex flex-wrap gap-2">
            <template x-for="v in languageVersionOptions()" :key="v.value">
                <label class="flex items-center gap-2 px-4 py-2 border rounded-lg cursor-pointer transition duration-200 text-sm"
                       :class="languageVersion === v.value ? 'border-teal/60 bg-teal/5 text-navy font-semibold shadow-sm' : 'border-cool/30 text-warm hover:border-teal/50'">
                    <input type="radio" name="language_version" :value="v.value" x-model="languageVersion" class="sr-only">
                    <span x-text="v.label"></span>
                </label>
            </template>
        </div>
        <div x-show="languageVersion === 'custom'" class="mt-3">
            <input type="text" name="language_version_custom" x-model="languageVersionCustom"
                   class="input input-bordered w-full max-w-xs bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200"
                   placeholder="e.g. 5.6, 7.4, 8.0...">
        </div>
    </div>
</div>
